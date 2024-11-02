<?php

namespace App\Validator;

use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AvailableTimeValidator extends ConstraintValidator
{
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $date = $this->context->getRoot()->get('date')->getData();

        if ($date && $value) {
            $dateTime = \DateTime::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $value->format('H:i'));

            if ($this->bookingRepository->isTimeBooked($dateTime)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $value->format('H:i'))
                    ->addViolation();
            }
        }
    }
}
