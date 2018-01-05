<?php
namespace Louvre\TicketingBundle\Validator\Constraints;
use Louvre\TicketingBundle\Service\BookingUtilities;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Louvre\TicketingBundle\Entity\Booking;

/**
 * @Annotation
 */
class daysImpossibleToBookValidator extends ConstraintValidator
{
    protected $bookingUtilities;

    public function __construct(BookingUtilities $bookingUtilities) {
        $this->bookingUtilities = $bookingUtilities;
    }



    public function validate($dateOfVisit, Constraint $constraint)
    {
        $dateOfVisit = date_format($dateOfVisit, 'd-m-Y');

        $daysImpossibleToBook = $this->bookingUtilities->getDaysImpossibleToBook();

        $daysImpossibleToBook = explode( ',', $daysImpossibleToBook );





        foreach ($daysImpossibleToBook as $dayImpossibleToBook)

                if ($dateOfVisit == $dayImpossibleToBook) {

                        $this->context->buildViolation($constraint->message)
        //                ->setParameter('{{ date(\'d-m-Y\' }}', $dateOfVisit)
                        ->addViolation();
                }
    }

}
