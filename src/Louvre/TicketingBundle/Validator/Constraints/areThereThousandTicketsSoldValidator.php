<?php
namespace Louvre\TicketingBundle\Validator\Constraints;
use Louvre\TicketingBundle\Service\BookingUtilities;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Louvre\TicketingBundle\Entity\Booking;

/**
 * @Annotation
 */
class areThereThousandTicketsSoldValidator extends ConstraintValidator
{
    protected $bookingUtilities;

    public function __construct(BookingUtilities $bookingUtilities) {
        $this->bookingUtilities = $bookingUtilities;
    }



    public function validate($dateOfVisit, Constraint $constraint)
    {

        $daysOff = $this->bookingUtilities->getDaysOff();
        $array = explode( ',', $daysOff );
        var_dump('test');
        foreach ($array as $dayOff)
            var_dump($dayOff);
//        if ($dateOfVisit == $daysOff) {
            if (1 == 1) {

                $this->context->buildViolation($constraint->message)
//                ->setParameter('{{ date(\'d-m-Y\' }}', $dateOfVisit)
                ->addViolation();
        }
    }

}
