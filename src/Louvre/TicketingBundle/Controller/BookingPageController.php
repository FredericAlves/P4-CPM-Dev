<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingStepTwoType;
use Louvre\TicketingBundle\Form\BookingType;
use Louvre\TicketingBundle\Services\BookingUtilities;
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


        $booking = new Booking();

           if ($this->getBookingSession() != false) {
                $booking = $this->getBookingSession();
           }



        $id = $booking->getId();
        $dateOfPurchase = $booking->getDateOfPurchase();


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
            "id" => $id,
            "booking" => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return Response
     *
     *
     */
    public function bookingStepTwoAction(Request $request)
    {

        $session = $request->getSession();

        $bookingUtilities = $this->container->get('Louvre_Ticketing_Bundle.bookingUtilities');

        if ($session->has('booking')) {


            $booking = $session->get('booking');

            if ($bookingUtilities->underTenMinutes($booking) == true) {


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

    public function bookingStepThreeAction(Request $request)
{

    $session = $request->getSession();

    $bookingUtilities = $this->container->get('Louvre_Ticketing_Bundle.bookingUtilities');

    if ($session->has('booking')) {


        $booking = $session->get('booking');

        if ($bookingUtilities->underTenMinutes($booking) == true) {

            $tickets = $booking->getTickets();

            $bill = 0;

            foreach ($tickets as $ticket) {
                $bill = $bill + $ticket->getPrice();
            }

            $booking->setBill($bill);

            return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepthree.html.twig', [
                "booking" => $booking,
                "tickets" => $tickets,
            ]);
        } else {
            $this->addFlash('error', 'La session a expiré, veuillez recommencer.');
            return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
        }
    } else {
        return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
    }
}

    public function bookingStepForAction(Request $request)
    {

        $session = $request->getSession();

        $bookingUtilities = $this->container->get('Louvre_Ticketing_Bundle.bookingUtilities');

        if ($session->has('booking')) {


            $booking = $session->get('booking');
            $tickets = $booking->getTickets();

            \Stripe\Stripe::setApiKey("sk_test_Ui8k9Iq57Y14p3TRj3cV6sSq");

            // Get the credit card details submitted by the form
            $token = $_POST['stripeToken'];

            // Create a charge: this will charge the user's card
            try {
                $charge = \Stripe\Charge::create(array(
                    "amount" => ($booking->getBill())*100, // Amount in cents
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Musée du Louvre : ".$booking->getReservationCode(),
                ));
                $this->addFlash("success","Le paiement a bien été effectué !");

                $em = $this->getDoctrine()->getManager();
                $em->persist($booking);
                $em->flush();
                $session->clear();

                $message = \Swift_Message::newInstance()
                    ->setSubject("Votre commande de billet(s) pour le Musée du Louvre")
                    ->setFrom('contact@le-louvre.fr')
                    ->setTo($booking->getEmail())
                    ->setCharset('utf-8')
                    ->setContentType('text/html');
                $logo = $message->embed(\Swift_Image::fromPath('images/louvre_logo.jpg'));

                $message
                    ->setBody($this->renderView('LouvreTicketingBundle:EmailPage:email.html.twig', [
                        'booking' => $booking,
                        'tickets' => $tickets,
                        'logo' => $logo,
                    ]));

                $this->get('mailer')->send($message);





                return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepfor.html.twig', [
                    "booking" => $booking,
                    "tickets" => $tickets,
                ]);
            } catch(\Stripe\Error\Card $e) {

                $this->addFlash("error","Un problème est survenu, veuillez rééssayer !");
                return $this->redirectToRoute("louvre_ticketing_bookingstethreepage");
                // The card has been declined
            }





            } else {
                $this->addFlash('error', 'La session a expiré, veuillez recommencer.');
                return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
            }

    }




    public function getBookingSession()
    {

        $request = $this->container->get('request_stack')->getCurrentRequest();

        $bookingUtilities = $this->container->get('Louvre_Ticketing_Bundle.bookingUtilities');

        $session = $request->getSession();

        if ($session->has('booking')) {
            $bookingSession = $session->get('booking');
            if ($bookingUtilities->underTenMinutes($bookingSession)) {
                return $bookingSession;
            } else {
                $session->clear();
                return false;
            }
        }
    }


    public function setBookingSession($booking) {

        $request = $this->container->get('request_stack')->getCurrentRequest();

        $session = $request->getSession();

        $session->set('booking', $booking);
    }
}
