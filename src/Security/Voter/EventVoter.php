<?php
namespace App\Security\Voter;

use App\Entity\Event;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    const ATTRIBUTE_MODIFY = 'EVENT_MODIFY';
    const ATTRIBUTES = [self::ATTRIBUTE_MODIFY];

    protected function supports(string $attribute, $subject): bool
    {
        if(!in_array($attribute, self::ATTRIBUTES)){
            return false;
        }

        if(!$subject instanceof Event){
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

        switch ($attribute) {
            case self::ATTRIBUTE_MODIFY:
                return $this->allowModify($subject, $user);
        }

        return false;
    }

    private function allowModify(Event $event, UserInterface $user): bool
    {
        if($event->getOwner() === $user){
            return true;
        }

        return false;
    }
}
