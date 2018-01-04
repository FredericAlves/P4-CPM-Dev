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


    public function getDaysOff()
    {

        // return a list of days off for the museum ( bank holidays ) , valid until 2022.


        $bankHolidaysCurrentYear = '';
        $bankHolidaysNextYear = '';

        $currentYear = date('Y');
        $nextYear = date('Y')+1;

        settype($nextYear, "string");


        $bankHolidays = ['01-01-', '01-05-', '08-05-', '14-07-', '15-08-', '01-11-', '11-11-', '25-12-'];

        foreach ($bankHolidays as $bankHoliday){
            $bankHolidaysCurrentYear = $bankHolidaysCurrentYear.trim($bankHoliday).$currentYear.',';
            $bankHolidaysNextYear = $bankHolidaysNextYear.trim($bankHoliday).$nextYear.',';
        }


        $easterMondays = '02-04-2018,22-04-2019,13-04-2020,05-04-2021,18-04-2022';

        $ascensionThursdays = '10-05-2018,30-05-2019,21-05-2020,13-05-2021,26-05-2022';


        $daysOff = $bankHolidaysCurrentYear.$bankHolidaysNextYear.$easterMondays.','.$ascensionThursdays;

        $ticketsAllDays = $this->em->getRepository('LouvreTicketingBundle:Booking')->findTicketsAllDays();

        $daysFull = [];

        foreach ($ticketsAllDays as $ticketAllDay => $value){
            if ($value["ticketsSum"] >= 2){
                $daysFull[] = $value["dateOfVisit"]->format('d-m-Y');
            }

        }

        foreach ($daysFull as $day)
        {
            $daysOff = $daysOff .','. $day;
        }


        return $daysOff;
    }


    public function underTenMinutes(Booking $booking)
    {
        $sessionOver = $booking->getDateOfPurchase();
        $now = new \DateTime();
        date_add($sessionOver, date_interval_create_from_date_string('1 minutes'));
        if ($now < $sessionOver) {
            return true;
        }
    }
}
