<?php
namespace App\Service;

use App\Entity\Booking;
use App\Entity\Event;
use App\Model\CheckoutSession;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class CheckoutService
{
    const SESSION_KEY = 'CHECKOUT_SESSION';
    private RequestStack $requestStack;
    private PaymentService $paymentService;
    private Security $security;
    private EventRepository $eventRepository;

    public function __construct(
        RequestStack $requestStack,
        Security $security,
        EventRepository $eventRepository,
        PaymentService $paymentService
    ){
        $this->requestStack = $requestStack;
        $this->paymentService = $paymentService;
        $this->security = $security;
        $this->eventRepository = $eventRepository;
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

    public function preparePayment(CheckoutSession $session): array
    {
        $totalPrice = $session->getQuantity() * $session->getEvent()->getPrice();
        $key = $this->paymentService->createIntent($totalPrice);

        return [
            'key' => $key,
        ];
    }

    public function finalize(CheckoutSession $session, string $paymentId): ?Booking
    {
        if(!$this->paymentService->validateIntent($paymentId)){
            return null;
        }

        $booking = new Booking();
        $booking->setSeat($session->getQuantity());
        $event = $this->eventRepository->find($session->getEvent()->getId());
        $booking->setEvent($event);
        $booking->setUser($this->security->getUser());

        return $booking;
    }

    private function session(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}