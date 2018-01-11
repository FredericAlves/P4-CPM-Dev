<?php

namespace Tests\Louvre\TicketingBundle\Service;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Service\BookingUtilities;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServiceTest extends KernelTestCase
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
            ->get('doctrine');
            //->getManager();
    }

    public function testSessionActive()
    {
        $bookingUtilities = new BookingUtilities($this->em);

        $booking = new Booking();

        $this->assertSame(true, $bookingUtilities->sessionActive($booking));
    }

    public function testSessionInactive()
    {
        $bookingUtilities = new BookingUtilities($this->em);

        $booking = new Booking();

        $dateOfPurchase = $booking->getDateOfPurchase()->format('d-m-Y H:i:s');

        $newDateOfPurchase = new \DateTime(date('d-m-Y H:i:s',strtotime('-11 minutes',strtotime($dateOfPurchase))));

        $booking->setDateOfPurchase($newDateOfPurchase);

        $this->assertSame(null, $bookingUtilities->sessionActive($booking));
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->getManager()->close();
        $this->em = null; // avoid memory leaks
    }




}