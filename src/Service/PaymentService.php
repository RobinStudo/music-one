<?php
namespace App\Service;

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

    private function convertPrice(float $price): int
    {
        return round($price * 100);
    }
}