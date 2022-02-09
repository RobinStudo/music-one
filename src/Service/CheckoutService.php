<?php
namespace App\Service;

use App\Entity\Event;
use App\Model\CheckoutSession;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CheckoutService
{
    const SESSION_KEY = 'CHECKOUT_SESSION';
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function initSession(Event $event): CheckoutSession
    {
        $session = new CheckoutSession($event);
        $this->updateSession($session);
        return $session;
    }

    public function retrieveSession(): ?CheckoutSession
    {
        return $this->session()->get(self::SESSION_KEY);
    }

    public function updateSession(CheckoutSession $session): void
    {
        $this->session()->set(self::SESSION_KEY, $session);
    }

    private function session(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}