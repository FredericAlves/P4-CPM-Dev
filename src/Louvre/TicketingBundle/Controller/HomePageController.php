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


}
