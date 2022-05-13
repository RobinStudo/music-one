<?php
namespace App\Tests\Model;

use App\Entity\Event;
use App\Model\CheckoutSession;
use PHPUnit\Framework\TestCase;

class CheckoutSessionTest extends TestCase
{
    public function testInitialStatus(): void
    {
        $checkoutSession = $this->getFreshModel();

        $this->assertEquals(CheckoutSession::STATUS_CART, $checkoutSession->getStatus());
    }

    public function testQuantityUpdate(): void
    {
        $checkoutSession = $this->getFreshModel();
        $updatedQuantity = rand(2, 5);

        $checkoutSession->setQuantity($updatedQuantity);

        $this->assertEquals($updatedQuantity, $checkoutSession->getQuantity());
    }

    private function getFreshModel(): CheckoutSession
    {
        $event = new Event();
        return new CheckoutSession($event);
    }
}
