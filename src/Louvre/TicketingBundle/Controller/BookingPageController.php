<?php

namespace Louvre\TicketingBundle\Controller;

use Louvre\TicketingBundle\Entity\Booking;
use Louvre\TicketingBundle\Form\BookingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;


class BookingPageController extends Controller
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     *
     */
    public function bookingAction()
    {


        return $this->render('LouvreTicketingBundle:HomePage:booking.html.twig');
    }
}
