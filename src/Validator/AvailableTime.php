<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class AvailableTime extends Constraint
{
    public $message = 'L\'heure est déjà réservée pour ce jour.';
}
