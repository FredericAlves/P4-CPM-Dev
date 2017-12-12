<?php
namespace Louvre\TicketingBundle\Services;

use Louvre\TicketingBundle\Entity\Booking;

class bookingServices {

    /**
     * @param Booking $booking
     */
    public function totalNumberOfTickets (Booking $booking) {
        $numberTicketsInOrder = $booking->getNumberOfTickets();
        $numberOfTicketsForADate = $this->em->getRepository('LouvreTicketingBundle:Booking')->getNumberOfTicketsForADate($booking->getDateOfVisit());
    }


}