<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingType;
use Louvre\TicketingBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;




class BookingPageController extends Controller
{


    /**
     * @return Response
     *
     *
     */
    public function indexAction()
    {

        return $this->render('LouvreTicketingBundle:BookingPage:booking.html.twig');
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     *
     */
    public function bookingStepOneAction(Request $request)
    {
        $booking = new Booking();
        $id = $booking->getId();
        $dateOfPurchase = $booking->getDateOfPurchase();
        $reservationCode = $booking->getReservationCode();


        $form = $this->createForm(BookingType::class, $booking, ['method'=>'PUT']);


        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            return $this->redirectToRoute("louvre_ticketing_bookingsteptwopage", ['reservationCode' => $booking->getReservationCode()]);

        }

        return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepone.html.twig', [
            "booking" => $booking,
            "dateOfPurchase" => $dateOfPurchase,
            "reservationCode" => $reservationCode,
            "id" => $id,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     *
     *
     */
    public function bookingStepTwoAction( Booking $booking)
    {
        $id = $booking->getId();
        $dateOfVisit = $booking->getDateOfVisit();
        $numberOfTickets = $booking->getNumberOfTickets();

        return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingsteptwo.html.twig',[

           "id" => $id,
           "dateOfVisit" => $dateOfVisit,
           "numberOfTickets" => $numberOfTickets
    ]);
    }
}
