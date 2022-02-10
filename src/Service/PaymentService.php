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

    public function createIntent(float $price){
        $intent = $this->client->paymentIntents->create([
                'amount' => $this->convertPrice($price),
                'currency' => $this->config['currency'],
            ]);

        dd($intent);
    }

    public function convertPrice(float $price)
    {
        return round($price * 100);
    }
}