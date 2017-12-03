<?php

namespace Louvre\TicketingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
                    "id" =>"calendar",
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',
                    'data-date-days-of-week-disabled' => '02',
                    'data-date-language' => 'fr',
                           )
            ))
            ->add('email', EmailType::class)
            ->add('duration', ChoiceType::class, array(
                'label' => 'Journée / demi-journée',
                'choices'  => array(
                    'Journée' => true,
                    'Demi-journée' => false,
                )))
            ->add('tickets', CollectionType::class, array (
                'entry_type' => TicketType::class,
                'label' => 'les personnnes concernées par votre réservation: ',
                'allow_add'    => true,
                'allow_delete' => true,
                "attr" => array(
                    "id" => 'tickets')
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
