<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingType;
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


        $form = $this->createForm(BookingType::class, $booking, ['method'=>'PUT']);


        return $this->get('templating')->renderResponse('LouvreTicketingBundle:HomePage:booking.html.twig', [
            "booking" => $booking,
            "dateOfPurchase" => $dateOfPurchase,
            "reservationCode" => $reservationCode,
            "id" => $id,
            'form' => $form->createView()
        ]);
    }
}
