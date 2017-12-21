<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingStepTwoType;
use Louvre\TicketingBundle\Form\BookingType;
use Louvre\TicketingBundle\Services\BookingServices;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;





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


        $session = $request->getSession();

        $booking = new Booking();

        if ($this->getBookingSession() != false) {
            $booking = $this->getBookingSession();
        }


        $id = $booking->getId();
        $dateOfPurchase = $booking->getDateOfPurchase();
        $reservationCode = $booking->getReservationCode();

        $session->set('code', $reservationCode);


        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $dayOfTheWeekVisit = $booking->findDayOfTheWeek();

            if ($dayOfTheWeekVisit == 0 || $dayOfTheWeekVisit == 2) {
                $this->addFlash('error', 'Désolé ! Les réservation ne sont pas possibles pour les mardi et dimanche !');
                return $this->redirectToRoute('louvre_ticketing_bookingsteponepage');
            }



            //$this->addFlash('error', 'test message !');

            $this->setBookingSession($booking);

            return $this->redirectToRoute("louvre_ticketing_bookingsteptwopage", ['reservationCode' => $booking->getReservationCode()]);

        }

        return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepone.html.twig', [
            "dateOfPurchase" => $dateOfPurchase,
            "reservationCode" => $reservationCode,
            "id" => $id,
            "session" => $session,
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

        $session = $request->getSession();

        $bookingServices = $this->container->get('Louvre_Ticketing_Bundle.bookingServices');

        if ($session->has('booking')) {


            $booking = $session->get('booking');

            if ($bookingServices->beyondThirtyMinutes($booking) == false) {


                $em = $this->getDoctrine()->getManager();
                $id = $booking->getId();
                $booking->addTickets($booking);
                $dateOfVisit = $booking->getDateOfVisit();
                $numberOfTickets = $booking->getNumberOfTickets();


                $ticketsForTheDay = $em->getRepository('LouvreTicketingBundle:Booking')->findByDateOfVisit($dateOfVisit);
                $totalTicketsForTheDay = 0;
                foreach ($ticketsForTheDay as $ticketForTheDay) {
                    $totalTicketsForTheDay += $ticketForTheDay->getNumberOfTickets();
                }

                $form = $this->createForm(BookingStepTwoType::class, $booking);

                if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {


                    $tickets = $booking->getTickets();

                    foreach ($tickets as $ticket) {
                        $birth = $ticket->getBirthDate();
                        $now = new \DateTime('today');
                        $age = $birth->diff($now)->y;

                        if ($ticket->getCategory() == 1) {
                            if ($age < 4) {
                                $ticket->setPrice('0');
                            } elseif ($age >= 4 && $age < 12) {
                                $ticket->setPrice('8');
                            } else {
                                $ticket->setPrice('10');
                            }
                        } elseif ($age < 4) {
                            $ticket->setPrice('0');
                        } elseif ($age >= 4 && $age < 12) {
                            $ticket->setPrice('8');
                        } elseif ($age >= 60) {
                            $ticket->setPrice('12');
                        } else {
                            $ticket->setPrice('16');
                        }
                    }




                    return $this->redirectToRoute("louvre_ticketing_bookingstepthreepage", ['reservationCode' => $booking->getReservationCode()]);

                }
                return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingsteptwo.html.twig', [
                    "booking" => $booking,
                    "id" => $id,
                    "dateOfVisit" => $dateOfVisit,
                    "numberOfTickets" => $numberOfTickets,
                    "totalTicketsForTheDay" => $totalTicketsForTheDay,
                    "session" => $session,
                    'form' => $form->createView()
                ]);

            }
            else {
                $this->addFlash('error', 'La session a expiré, veuillez recommencer.');
                return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
            }
        } else {
            return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
        }

    }

    public function bookingStepThreeAction(Request $request, Booking $booking)
    {

        $session = $request->getSession();

        $bookingServices = $this->container->get('Louvre_Ticketing_Bundle.bookingServices');

        if ($session->has('booking')) {


            $booking = $session->get('booking');

            if ($bookingServices->beyondThirtyMinutes($booking) == false) {

                $tickets = $booking->getTickets();

                $bill = 0;

                foreach ($tickets as $ticket) {
                    $bill = $bill + $ticket->getPrice();
                }

                $booking->setBill($bill);

                return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepthree.html.twig', [
                    "booking" => $booking,
                    "tickets" => $tickets,
                    "session" => $session
                ]);
            } else {
                $this->addFlash('error', 'La session a expiré, veuillez recommencer.');
                return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
            }
        } else {
            return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
        }
    }

    public function getBookingSession()
    {

        $request = $this->container->get('request_stack')->getCurrentRequest();

        $bookingServices = $this->container->get('Louvre_Ticketing_Bundle.bookingServices');

        $session = $request->getSession();

        if ($session->has('booking')) {
            $bookingSession = $session->get('booking');
            if ($bookingServices->beyondThirtyMinutes($bookingSession)) {
                $session->clear();
                return false;

            } else {

                return $bookingSession;
            }
        }
    }


    public function setBookingSession($booking) {

        $request = $this->container->get('request_stack')->getCurrentRequest();

        $session = $request->getSession();

        $session->set('booking', $booking);
    }
}
