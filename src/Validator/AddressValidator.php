<?php
namespace App\Validator;

use App\Service\LocationService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AddressValidator extends ConstraintValidator
{
    private LocationService $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    public function validate($value, Constraint $constraint)
    {
        if($this->locationService->checkAddress($value)){
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
