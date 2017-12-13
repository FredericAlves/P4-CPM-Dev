<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingStepTwoType;
use Louvre\TicketingBundle\Form\BookingType;
use Louvre\TicketingBundle\Form\TicketType;
use Louvre\TicketingBundle\LouvreTicketingBundle;
use Louvre\TicketingBundle\Repository\BookingRepository;
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

//            $dayVisit = $booking->getDateOfVisit();
            $dayOfTheWeekVisit = $booking->findDayOfTheWeek();

            if ($dayOfTheWeekVisit == 0 || $dayOfTheWeekVisit == 2 ) {
                $this->addFlash('error', 'Désolé ! Les réservation ne sont pas possibles pour les mardi et dimanche !');
                return $this->redirectToRoute('louvre_ticketing_bookingsteponepage');
            }

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
    public function bookingStepTwoAction( Request $request, Booking $booking)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $booking->getId();
        $dateOfVisit = $booking->getDateOfVisit();
        $numberOfTickets = $booking->getNumberOfTickets();

        $ticketsForTheDay = $em->getRepository('LouvreTicketingBundle:Booking')->findByBookingDateOfVisit($booking->getDateOfVisit());
        $totalTicketsForTheDay = 0;
        foreach($ticketsForTheDay as $ticketForTheDay) {
            $totalTicketsForTheDay += $ticketForTheDay->getNumberOfTickets();
        }

        $form = $this->createForm(BookingStepTwoType::class, $booking, ['method'=>'PUT']);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($tickets[]);
            $em->flush();

            return $this->redirectToRoute("louvre_ticketing_homepage");

        }

        return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingsteptwo.html.twig',[

           "id" => $id,
           "dateOfVisit" => $dateOfVisit,
           "numberOfTickets" => $numberOfTickets,
           "totalTicketsForTheDay" => $totalTicketsForTheDay,
           'form' => $form->createView()
    ]);
    }
}
