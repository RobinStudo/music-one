<?php

namespace App\Security\Voter;

use App\Model\CheckoutSession;
use App\Repository\BookingRepository;
use App\Service\CheckoutService;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingVoter extends Voter
{
    const ATTRIBUTE_CHECKOUT = 'BOOKING_CHECKOUT';
    const ATTRIBUTES = [self::ATTRIBUTE_CHECKOUT];

    private CheckoutService $checkoutService;
    private BookingRepository $bookingRepository;

    public function __construct(CheckoutService $checkoutService, BookingRepository $bookingRepository)
    {
        $this->checkoutService = $checkoutService;
        $this->bookingRepository = $bookingRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if(!in_array($attribute, self::ATTRIBUTES)){
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if(!$user instanceof UserInterface){
            return false;
        }

        if($subject){
            $counter = $this->bookingRepository->count([
                'event' => $subject,
                'user' => $user
            ]);

            if($counter !== 0){
                return false;
            }

            if($user === $subject->getOwner()){
                return false;
            }
        }

        $session = $this->checkoutService->retrieveSession();
        if(!$session){
            return true;
        }

        $counter = $this->bookingRepository->count([
            'event' => $session->getEvent(),
            'user' => $user
        ]);
        if($counter === 0 || $session->getStatus() === CheckoutSession::STATUS_FINISH){
            return true;
        }
        
        return false;
    }
}
