<?php
namespace App\Model;

use App\Entity\Event;
use Symfony\Component\Validator\Constraints as Assert;

class CheckoutSession
{
    const STATUS_CART = 1;
    const STATUS_ACCOUNT = 2;
    const STATUS_PAYMENT = 3;
    const STATUS_FINISH = 4;

    private Event $event;
    private int $status = self::STATUS_CART;

    /**
     * @Assert\Positive(message="Vous devez commander au moins une place")
     * @Assert\LessThanOrEqual(value=5, message="Le nombre de place est limité à {{ compared_value }} par commande")
     */
    private int $quantity = 1;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}