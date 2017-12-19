<?php

namespace Louvre\TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="Louvre\TicketingBundle\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @Assert\Range(
     *     min = 1,
     *     max = 10,
     *     minMessage = "Il doit y avoir au moins un visiteur !",
     *     maxMessage = "Réservation possible pour 10 visiteurs maximum par commande !"
     * )
     * @ORM\Column(name="numberOfTickets", type="integer")
     */
    private $numberOfTickets;



    /**
     * @var int
     *
     * @ORM\Column(name="bill", type="integer", nullable=true)
     */
    private $bill;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfPurchase", type="datetime")
     */
    private $dateOfPurchase;

    /**
     * @var \Date
     *
     * @ORM\Column(name="dateOfVisit", type="date")
     * @Assert\GreaterThan("today", message = "La date doit être supérieure ou égale à la date du jour !")
     */
    private $dateOfVisit;


    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=255)
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="reservationcode", type="string", length=255)
     */
    private $reservationCode;

    /**
     * @ORM\OneToMany(targetEntity="Louvre\TicketingBundle\Entity\Ticket", mappedBy="booking", cascade={"persist"})
     */
    private $tickets;



    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->dateOfPurchase = new \DateTime();
        $this->reservationCode = $this->generateRandomReservationCode();

    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set numberOfTickets
     *
     * @param integer $numberOfTickets
     *
     * @return Booking
     */
    public function setNumberOfTickets($numberOfTickets)
    {
        $this->numberOfTickets = $numberOfTickets;

        return $this;
    }

    /**
     * Get numberOfTickets
     *
     * @return int
     */
    public function getNumberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * Set bill
     *
     * @param integer $bill
     *
     * @return Booking
     */
    public function setBill($bill)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get bill
     *
     * @return int
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Set dateOfPurchase
     *
     * @param \DateTime $dateOfPurchase
     *
     * @return Booking
     */
    public function setDateOfPurchase($dateOfPurchase)
    {
        $this->dateOfPurchase = $dateOfPurchase;

        return $this;
    }

    /**
     * Get dateOfPurchase
     *
     * @return \DateTime
     */
    public function getDateOfPurchase()
    {
        return $this->dateOfPurchase;
    }

    /**
     * Set dateOfVisit
     *
     * @param \DateTime $dateOfVisit
     *
     * @return Booking
     */
    public function setDateOfVisit($dateOfVisit)
    {
        $this->dateOfVisit = $dateOfVisit;

        return $this;
    }

    /**
     * Get dateOfVisit
     *
     * @return \Date
     */
    public function getDateOfVisit()
    {
        return $this->dateOfVisit;
    }

    /**
     * Set duration
     *
     * @param string $duration
     *
     * @return Booking
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }


    /**
     * Set email
     *
     * @param string $email
     *
     * @return Booking
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set reservationCode
     *
     * @param string $reservationCode
     *
     * @return Booking
     */
    public function setReservationCode($reservationCode)
    {
        $this->reservationCode = $reservationCode;

        return $this;
    }

    /**
     * Get reservationCode
     *
     * @return string
     */
    public function getReservationCode()
    {
        return $this->reservationCode;
    }

    /**
     * Get tickets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Add ticket
     *
     * @param \Louvre\TicketingBundle\Entity\Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(Ticket $ticket)
    {

        $this->tickets[] = $ticket;
        $ticket->setBooking($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \Louvre\TicketingBundle\Entity\Ticket $ticket
     */
    public function removeTicket(ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    public function clearTickets()
    {
        $this->getTickets()->clear();
    }




    public function generateRandomReservationCode($length = 16, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function findDayOfTheWeek () {
        $dayOfTheWeek = $this->getDateOfVisit()->format("w");
        return $dayOfTheWeek;
    }

    public function addTickets(Booking $booking) {
        $ticketsQty = $this->getNumberOfTickets();
        $tickets = $this->getTickets();
        if ($tickets->isEmpty()) {
            for ($i = 1; $i <= $ticketsQty; $i++)
            {
                $ticket = new Ticket();
                $booking->addTicket($ticket);
            }
        }
        elseif ($ticketsQty != $tickets->count()) {
            $booking->clearTickets();
            for ($i = 1; $i <= $ticketsQty; $i++)
            {
                $ticket = new Ticket();
                $booking->addTicket($ticket);
            }
        }
    }

}

