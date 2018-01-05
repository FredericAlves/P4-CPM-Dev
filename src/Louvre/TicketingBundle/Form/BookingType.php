<?php

namespace Louvre\TicketingBundle\Form;



use Louvre\TicketingBundle\Service\BookingUtilities;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;





class BookingType extends AbstractType
{

    protected $bookingUtilities;

    /**
     * @param string $class The User class name
     */
    public function __construct(BookingUtilities $bookingUtilities)
    {

        $this->bookingUtilities = $bookingUtilities;


    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder

            ->add('dateOfVisit', DateType::class, array(
                "label" => "Choisissez la date de votre visite:",
                "widget" => "single_text",
                'format' => 'dd-MM-yyyy',
                "placeholder" => "Choisissez une date",
                "attr" => array(
                    "class" => "form-control input-inline datepicker",
//                    'readonly'=>'readonly',
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',
                    'data-date-days-of-week-disabled' => '02',
                    'data-date-today-highlight' => 'false',
                    'data-date-language' => 'fr',
                    'data-date-start-date' => "0d",
                    'data-date-end-date' => '+364d',
                    'data-date-dates-disabled' => $this->bookingUtilities->getDaysImpossibleToBook(),
                    'data-date-autoclose' => true
                           )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Entrez votre adresse mail :'
            ))
            ->add('duration', ChoiceType::class, array(
                'label' => 'Billet(s) "Journée" ou "demi-journée" :',
                'choices' => array('journée'=>'journée','demi-journée'=>'demi-journée'),
                'multiple' => false,
            ))
            ->add('numberOfTickets', IntegerType::class, array(
                    "label" => "Nombre de visiteur(s) :",
                    "attr" => array(
                        'value'=>'1',
                        'min'=>'1',
                        'max'=>'10'
                    )
            ))
            ->add('Valider', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketingBundle\Entity\Booking'
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketingbundle_booking';
    }

    public function getDaysOff()
    {

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

        return $daysOff;
    }


}
