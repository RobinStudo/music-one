<?php
namespace App\Service;

use Exception;
use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PaymentService
{
    private array $config;
    private StripeClient $client;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->config = $parameterBag->get('payment');
        $this->client = new StripeClient($this->config['privateKey']);
    }

    public function createIntent(float $price): string
    {
        $intent = $this->client->paymentIntents->create([
            'amount' => $this->convertPrice($price),
            'currency' => $this->config['currency'],
        ]);

        return $intent->client_secret;
    }

    public function validateIntent($intentId, $total): bool
    {
        try{
            $intent = $this->client->paymentIntents->retrieve($intentId);

            if($intent->status !== 'succeeded'){
                return false;
            }

            if($intent->amount !== $this->convertPrice($total)){
                return false;
            }

            return true;
        }catch(Exception $e){
            return false;
        }
    }

    private function convertPrice(float $price): int
    {
        return round($price * 100);
    }
}