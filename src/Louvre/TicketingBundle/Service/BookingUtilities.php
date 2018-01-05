<?php
namespace Louvre\TicketingBundle\Service;

use Louvre\TicketingBundle\Entity\Booking;





class BookingUtilities
{

   private $em;

    public function __construct ($doctrine) {
        $this->em = $doctrine->getManager();

    }

    public function totalNumberOfTickets(Booking $booking)
    {
        $dateOfVisit = $booking->getDateOfVisit();
        $dateOfVisit = date_format($dateOfVisit, 'Y-m-d');

        $numberOfTicketsForADate = $this->em->getRepository('LouvreTicketingBundle:Booking')->getNumberOfTicketsForADate($dateOfVisit);

        return $numberOfTicketsForADate;
    }


    public function getDaysImpossibleToBook()
    {

        // return a list of days off for the museum ( bank holidays, museum full, others ... ) , valid until 2022.


        $bankHolidaysCurrentYear = '';
        $bankHolidaysNextYear = '';

        $currentYear = date('Y');
        $nextYear = date('Y')+1;

        settype($nextYear, "string");

        // first all fixed bank holidays from one year to another

            $bankHolidays = ['01-01-', '01-05-', '08-05-', '14-07-', '15-08-', '01-11-', '11-11-', '25-12-'];

                foreach ($bankHolidays as $bankHoliday){
                    $bankHolidaysCurrentYear = $bankHolidaysCurrentYear.trim($bankHoliday).$currentYear.',';
                    $bankHolidaysNextYear = $bankHolidaysNextYear.trim($bankHoliday).$nextYear.',';
                }

        // and then the case of Easter Monday and Thursday of Ascension, different each year

            $easterMondays = '02-04-2018,22-04-2019,13-04-2020,05-04-2021,18-04-2022';

            $ascensionThursdays = '10-05-2018,30-05-2019,21-05-2020,13-05-2021,26-05-2022';


            $daysImpossibleToBook = $bankHolidaysCurrentYear.$bankHolidaysNextYear.$easterMondays.','.$ascensionThursdays;

        // days when at least 1000 tickets were sold (2 for testing)

            $ticketsAllDays = $this->em->getRepository('LouvreTicketingBundle:Booking')->findTicketsAllDays();

            $daysFull = [];

                foreach ($ticketsAllDays as $ticketAllDay => $value){
                    if ($value["ticketsSum"] >= 2){
                        $daysFull[] = $value["dateOfVisit"]->format('d-m-Y');
                    }

                }

                foreach ($daysFull as $day)
                {
                    $daysImpossibleToBook = $daysImpossibleToBook .','. $day;
                }
        // remove the actual day if more than 20h (17h for the tests)

        $now = new \DateTime();
        $hour = $now ->format('H');
        $now = $now -> format('d-m-Y');

        if ($hour >= 16) {
            $daysImpossibleToBook = $daysImpossibleToBook .','. $now;
        }



        return $daysImpossibleToBook;
    }


    public function sessionActive(Booking $booking)
    {

        // we take the start time of the order and see if 10 minutes have elapsed ( 2 min for the tests )

        $sessionStarted = $booking->getDateOfPurchase();

        $sessionStarted = $sessionStarted->format('d-m-Y H:i:s');

        $sessionEnded = new \DateTime(date('d-m-Y H:i:s',strtotime('+5 minutes',strtotime($sessionStarted))));

        $now = new \DateTime();

        if ($now < $sessionEnded)
        {
            return true;
        }

    }

}
