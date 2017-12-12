<?php
namespace Louvre\TicketingBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class areThereThousandTicketsSold extends Constraint
{
    public $message = 'Plus de 1000 billets ont été vendu à cette date, vous ne pouvez réserver pour celle-ci!';
}
