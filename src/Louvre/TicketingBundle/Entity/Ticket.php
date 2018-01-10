<?php

namespace Louvre\TicketingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="Louvre\TicketingBundle\Repository\TicketRepository")
 */
class Ticket
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
     * @ORM\ManyToOne(targetEntity="Louvre\TicketingBundle\Entity\Booking", inversedBy="tickets"))
     * @ORM\JoinColumn(nullable=false)
     */
    private $booking;


    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="une catégorie doit être choisie.")
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false)
     * @Assert\NotNull(message="un prix doit être donné.")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="Veuillez indiquer votre nom de famille.")
     * @Assert\Length(
     *      min = 2,
     *      max = 40,
     *      minMessage = "Votre nom doit contenir au minimum {{ limit }} caractères",
     *      maxMessage = "Votre nom doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=false)
     * @Assert\NotNull(message="Veuillez indiquer votre prénom.")
     * @Assert\Length(
     *      min = 2,
     *      max = 40,
     *      minMessage = "Votre prénom doit contenir au minimum {{ limit }} caractères",
     *      maxMessage = "Votre prénom doit contenir au maximum {{ limit }} caractères"
     * )
     */
    private $firstName;

    /**
     * @var \Date
     *
     * @ORM\Column(name="birthDate", type="date", nullable=false)
     * @Assert\NotNull(message="Veuillez indiquer votre date de naissance.")
     */
    private $birthDate;




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
     * Set category
     *
     * @param string $category
     *
     * @return Ticket
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * set firstName
     *
     * @param string $firstName
     *
     * @return Ticket
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * set lastName
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * get birthDate
     *
     * @return \Date
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * Set booking
     *
     * @param \Louvre\TicketingBundle\Entity\Booking $booking
     *
     * @return Ticket
     */
    public function setBooking(\Louvre\TicketingBundle\Entity\Booking $booking)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \Louvre\TicketingBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }
}
