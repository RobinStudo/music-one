<?php
namespace App\Service;

use App\Entity\Booking;
use App\Entity\Event;
use App\Model\CheckoutSession;
use App\Repository\BookingRepository;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;

class CheckoutService
{
    const SESSION_KEY = 'CHECKOUT_SESSION';
    private RequestStack $requestStack;
    private PaymentService $paymentService;
    private Security $security;
    private BookingRepository $bookingRepository;
    private EventRepository $eventRepository;

    public function __construct(
        RequestStack $requestStack,
        Security $security,
        BookingRepository $bookingRepository,
        EventRepository $eventRepository,
        PaymentService $paymentService
    ){
        $this->requestStack = $requestStack;
        $this->paymentService = $paymentService;
        $this->security = $security;
        $this->bookingRepository = $bookingRepository;
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
        $totalPrice = $this->calculatePrice($session);
        $key = $this->paymentService->createIntent($totalPrice);

        return [
            'key' => $key,
        ];
    }

    public function finalize(CheckoutSession $session, string $paymentId): ?Booking
    {
        if(!$this->checkPayment($session, $paymentId)){
            return null;
        }

        $booking = new Booking();
        $booking->setSeat($session->getQuantity());
        $event = $this->eventRepository->find($session->getEvent()->getId());
        $booking->setEvent($event);
        $booking->setUser($this->security->getUser());
        $booking->setPaymentIdentifier($paymentId);

        return $booking;
    }

    private function checkPayment(CheckoutSession $session, string $paymentId): bool
    {
        $totalPrice = $this->calculatePrice($session);
        if(!$this->paymentService->validateIntent($paymentId, $totalPrice)){
            return false;
        }

        if($this->bookingRepository->count(['paymentIdentifier' => $paymentId]) > 0){
            return false;
        }

        return true;
    }

    private function calculatePrice(CheckoutSession $session)
    {
        return $session->getQuantity() * $session->getEvent()->getPrice();
    }

    private function session(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}