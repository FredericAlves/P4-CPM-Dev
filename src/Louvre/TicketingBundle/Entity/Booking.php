<?php

namespace Louvre\TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     *
     * @ORM\Column(name="bill", type="integer")
     */
    private $bill;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfPurchase", type="datetime")
     */
    private $dateOfPurchase;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="reservationNumber", type="string", length=255)
     */
    private $reservationNumber;


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
     * Set reservationNumber
     *
     * @param string $reservationNumber
     *
     * @return Booking
     */
    public function setReservationNumber($reservationNumber)
    {
        $this->reservationNumber = $reservationNumber;

        return $this;
    }

    /**
     * Get reservationNumber
     *
     * @return string
     */
    public function getReservationNumber()
    {
        return $this->reservationNumber;
    }
}

