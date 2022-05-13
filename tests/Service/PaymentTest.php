<?php
namespace App\Tests\Service;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PaymentTest extends KernelTestCase
{
    public function testIntentCreation(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $paymentService = $container->get(PaymentService::class);

        $result = $paymentService->createIntent(120);
        
        $this->assertIsString($result);
    }
}
