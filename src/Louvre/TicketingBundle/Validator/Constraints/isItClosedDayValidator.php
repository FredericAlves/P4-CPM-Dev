<?php
namespace Louvre\TicketingBundle\Validator\Constraints;
use Louvre\TicketingBundle\Service\BookingUtilities;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Louvre\TicketingBundle\Entity\Booking;

/**
 * @Annotation
 */
class isItClosedDayValidator extends ConstraintValidator
{
    protected $bookingUtilities;

    public function __construct(BookingUtilities $bookingUtilities) {
        $this->bookingUtilities = $bookingUtilities;
    }



    public function validate($dateOfVisit, Constraint $constraint)
    {
        $dateOfVisit = date_format($dateOfVisit, 'd-m-Y');

        $daysOff = $this->bookingUtilities->getDaysOff();

        $daysOff = explode( ',', $daysOff );





        foreach ($daysOff as $dayOff)

                if ($dateOfVisit == $dayOff) {

                        $this->context->buildViolation($constraint->message)
        //                ->setParameter('{{ date(\'d-m-Y\' }}', $dateOfVisit)
                        ->addViolation();
                }
    }

}
