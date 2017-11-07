<?php

namespace Louvre\TicketingBundle\Controller;

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
        return $this->render('LouvreTicketingBundle:HomePage:booking.html.twig');
    }
}
