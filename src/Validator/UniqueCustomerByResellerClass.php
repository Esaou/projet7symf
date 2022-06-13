<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueCustomerByResellerClass extends Constraint
{
    public string $message = "L'email est déjà utilisé pour ce revendeur.";
    public string $mode = 'strict'; // If the constraint has configuration options, define them as public properties

    public function validatedBy()
    {
        return static::class.'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}