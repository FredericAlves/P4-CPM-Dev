<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingStepTwoType;
use Louvre\TicketingBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;






class BookingPageController extends Controller
{

    /**
     * first step of the ordering process
     *
     * @param Request $request
     * @return Response
     *
     *
     */
    public function bookingStepOneAction(Request $request)
    {



        $booking = new Booking();

        // we check if there is active session data
        if ($this->getBookingSession() != false) {
            $booking = $this->getBookingSession();
        }



        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // we get the day of the week
            $dayOfTheWeekVisit = $booking->findDayOfTheWeek();

            // if it's a Tuesday or a Sunday
            if ($dayOfTheWeekVisit == 0 || $dayOfTheWeekVisit == 2) {
                $this->addFlash('error', 'Désolé ! Les réservation ne sont pas possibles pour les mardi et dimanche !');
                return $this->redirectToRoute('louvre_ticketing_bookingsteponepage');
            }

            // we update the session data
            $this->setBookingSession($booking);

            return $this->redirectToRoute("louvre_ticketing_bookingsteptwopage", ['reservationCode' => $booking->getReservationCode()]);

        }

        return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepone.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }



    /**
     * second step of the ordering process
     *
     * @param Request $request
     * @return Response
     *
     */
    public function bookingStepTwoAction(Request $request)
    {



        // if the session has order data and is active
        if ($this->getBookingSession() != false) {

            $booking = $this->getBookingSession();



                $booking->addTickets($booking);

                $form = $this->createForm(BookingStepTwoType::class, $booking);

                if ($form->handleRequest($request)->isSubmitted() && $form->isValid()) {


                    $tickets = $booking->getTickets();

                    // we calculate the price according to the age
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
                    'booking' => $booking,
                    'form' => $form->createView()
                ]);

            }
            else {
                $this->addFlash('error', 'La session a expiré, veuillez recommencer.');
                return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
            }

    }



    /**
     * third step of the ordering process
     *
     * @param Request $request
     * @return Response
     *
     */
    public function bookingStepThreeAction(Request $request)
    {



        // if the session has order data abd is active
        if ($this->getBookingSession() != false) {

            $booking = $this->getBookingSession();



                $tickets = $booking->getTickets();

                // we calculate the total price by adding the price of each ticket
                $bill = 0;

                foreach ($tickets as $ticket) {
                    $bill = $bill + $ticket->getPrice();
                }

                $booking->setBill($bill);

                return $this->get('templating')->renderResponse('LouvreTicketingBundle:BookingPage:bookingstepthree.html.twig', [
                    'booking' => $booking,
                    'tickets' => $tickets,
                ]);
            } else {
                $this->addFlash('error', 'La session a expiré, veuillez recommencer.');
                return $this->redirectToRoute("louvre_ticketing_bookingsteponepage");
            }
    }



    /**
     * fourth step of the ordering process
     *
     * @param Request $request
     * @return Response
     *
     */
    public function bookingStepForAction(Request $request)
    {

        // if the session has order data and is active
        if ($this->getBookingSession() != false) {

            $booking = $this->getBookingSession();

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

                // we enter the data in the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($booking);
                $em->flush();

                // we clear the session
                $session = $request->getSession();
                $session->clear();


                //we compose the email message
                $message = \Swift_Message::newInstance()
                    ->setSubject("Votre commande de billet(s) pour le Musée du Louvre")
                    ->setFrom('contact@le-louvre.fr')
                    ->setTo($booking->getEmail())
                    ->setCharset('utf-8')
                    ->setContentType('text/html');
                $logo = $message->embed(\Swift_Image::fromPath('bundles/louvreticketing/images/louvre_logo.png'));

                $message
                    ->setBody($this->renderView('LouvreTicketingBundle:EmailPage:email.html.twig', [
                        'booking' => $booking,
                        'tickets' => $tickets,
                        'logo' => $logo,
                    ]));

                // we send the email message
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
            $this->addFlash('error', 'La session a expiré, le paiement est annulé, veuillez recommencer.');
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
            if ($bookingUtilities->sessionActive($bookingSession)) {
                return $bookingSession;
            } else {
                $session->clear();
                return false;
            }
        } else {
            return false;
        }
    }


    public function setBookingSession($booking) {

        $request = $this->container->get('request_stack')->getCurrentRequest();

        $session = $request->getSession();

        $session->set('booking', $booking);
    }
}
