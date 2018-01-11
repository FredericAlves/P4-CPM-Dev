<?php

namespace Tests\Louvre\TicketingBundle\Repository;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookingRepositoryTest extends KernelTestCase
{
/**
* @var \Doctrine\ORM\EntityManager
*/
private $em;

/**
* {@inheritDoc}
*/
protected function setUp()
{
$kernel = self::bootKernel();

$this->em = $kernel->getContainer()
->get('doctrine')
->getManager();
}

public function testfindTicketsAllDays()
{
    $ticketsAllDays = $this->em->getRepository('LouvreTicketingBundle:Booking')->findTicketsAllDays();

    $daysFull = [];

    foreach ($ticketsAllDays as $ticketAllDay => $value){
        if ($value["ticketsSum"] >= 3){
            $daysFull[] = $value["dateOfVisit"]->format('d-m-Y');
        }

    }


$this->assertNotEmpty( $daysFull);
}

/**
* {@inheritDoc}
*/
protected function tearDown()
{
parent::tearDown();

$this->em->close();
$this->em = null; // avoid memory leaks
}
}