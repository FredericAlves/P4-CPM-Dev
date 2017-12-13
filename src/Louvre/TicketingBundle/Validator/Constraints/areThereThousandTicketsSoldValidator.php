<?php
namespace Louvre\TicketingBundle\Validator\constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Louvre\TicketingBundle\Services\bookingServices;
use Louvre\TicketingBundle\Entity\Booking;

/**
 * @Annotation
 */
class areThereThousandTicketsSoldValidator extends ConstraintValidator
{
    public function __construct(BookingServices $bookingServices) {
        $this->bookingServices = $bookingServices;
    }



    public function validate($value, Constraint $constraint)
    {
        $date = $value->get
        $ticketsSum = $this->bookingServices->totalNumberOfTickets($date);
        if () {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

}
