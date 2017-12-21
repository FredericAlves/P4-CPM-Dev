<?php
namespace Louvre\TicketingBundle\Services;

use Louvre\TicketingBundle\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BookingServices
{


    /**
     * @param Booking $booking
     */
    public function totalNumberOfTickets(Booking $booking)
    {
        $numberTicketsInOrder = $booking->getNumberOfTickets();
        $numberOfTicketsForADate = $this->em->getRepository('LouvreTicketingBundle:Booking')->getNumberOfTicketsForADate($booking->getDateOfVisit());
    }


    /**
     * @param Booking $booking
     */
    public function beyondThirtyMinutes(Booking $booking)
    {
        $sessionOver = $booking->getDateOfPurchase();
        $now = new \DateTime();
        date_add($sessionOver, date_interval_create_from_date_string('1 minutes'));
        if ($now > $sessionOver) {
            return true;
        }
    }
}
