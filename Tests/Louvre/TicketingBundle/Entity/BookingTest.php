<?php

namespace Tests\Louvre\TicketingBundle\Entity;

use Louvre\TicketingBundle\Entity\Booking;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Date;

class BookingTest extends TestCase
{
    public function testFindDayOfTheWeek()
    {
        define('formatDate','Y-m-d');
        $date = '28-01-2018';
        $date1 = new \DateTime($date);;
        $booking = new Booking();
        $booking->setDateOfVisit($date1);
        $dayOfVisit = $booking->findDayOfTheWeek();
        $this->assertSame('0', $dayOfVisit);


    }



}