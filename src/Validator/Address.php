<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Address extends Constraint
{
    public $message = 'La ville, le code postal et le pays ne correspondent pas.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
