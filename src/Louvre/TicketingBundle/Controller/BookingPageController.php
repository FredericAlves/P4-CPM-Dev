<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingStepTwoType;
use Louvre\TicketingBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;




class BookingPageController extends Controller
{

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


        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

       // $session = $request->getSession();
       // $session->set('booking', $booking);

        if ($form->isSubmitted() && $form->isValid()) {

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
            "dateOfPurchase" => $dateOfPurchase,
            "reservationCode" => $reservationCode,
            "id" => $id,
            //"session" => $session,
            "booking" => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     *
     *
     */
    public function bookingStepTwoAction(Request $request, Booking $booking)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $booking->getId();
        $booking->addTickets($booking);
        $dateOfVisit = $booking->getDateOfVisit();
        $numberOfTickets = $booking->getNumberOfTickets();


        $ticketsForTheDay = $em->getRepository('LouvreTicketingBundle:Booking')->findByDateOfVisit($dateOfVisit);
        $totalTicketsForTheDay = 0;
        foreach($ticketsForTheDay as $ticketForTheDay) {
            $totalTicketsForTheDay += $ticketForTheDay->getNumberOfTickets();
        }

        $form = $this->createForm(BookingStepTwoType::class,$booking);

        if ($form->handleRequest($request)->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepthree.html.twig',[
                "booking" => $booking,
                            ]);

        }

        return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingsteptwo.html.twig',[
           "booking" => $booking,
           "id" => $id,
           "dateOfVisit" => $dateOfVisit,
           "numberOfTickets" => $numberOfTickets,
           "totalTicketsForTheDay" => $totalTicketsForTheDay,
           'form' => $form->createView()
    ]);
    }
}
