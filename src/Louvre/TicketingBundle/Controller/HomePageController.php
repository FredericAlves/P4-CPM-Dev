<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;


class HomePageController extends Controller
{
    /**
     * @return Response
     *
     *
     */
    public function indexAction()
    {
        return $this->render('LouvreTicketingBundle:HomePage:index.html.twig');
    }

    /**
     * @return Response
     *
     *
     */
    public function bookingAction()
    {
        $booking = new Booking();
        $id = $booking->getId();
        $dateOfPurchase = $booking->getDateOfPurchase();
        $reservationCode = $booking->getReservationCode();
        return $this->get('templating')->renderResponse('LouvreTicketingBundle:HomePage:booking.html.twig', [
            "booking" => $booking,
            "dateOfPurchase" => $dateOfPurchase,
            "reservationCode" => $reservationCode,
            "id" => $id
        ]);
    }
}
