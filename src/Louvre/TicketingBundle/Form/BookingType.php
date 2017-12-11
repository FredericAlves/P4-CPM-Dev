<?php

namespace Louvre\TicketingBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('dateOfVisit', DateType::class, array(
                "label" => "Date de votre visite:",
                "widget" => "single_text",
                'format' => 'dd-MM-yyyy',
                "placeholder" => "Choisissez une date",
                "attr" => array(
                    "class" => "form-control input-inline datepicker",
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',
                    'data-date-days-of-week-disabled' => '02',
                    'data-date-today-highlight' => 'true',
                    'data-date-language' => 'fr',
                    'data-date-start-date' => "0d",
                    'data-date-end-date' => '+364d'
                           )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Votre adresse mail :'
            ))
            ->add('duration', ChoiceType::class, array(
                'label' => 'Billet(s) "Journée" ou "demi-journée" :',
                'choices'  => array(
                    'Journée' => true,
                    'Demi-journée' => false,
                )))
            ->add('numberOfTickets', IntegerType::class, array(
                    "label" => "Nombre de visiteur(s) :",
                    'attr' => array('min' =>1, 'max' =>10)
            ))
            ->add('Commander', SubmitType::class);
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


}
