<?php
namespace Louvre\TicketingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class isItClosedDay extends Constraint
{
    public $message = 'Vous ne pouvez pas réserver de billets pour cette date !';
}
