<?php

namespace Louvre\TicketingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, ['label' => 'Nom'])
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('dateOfBirth', BirthdayType::class, array(
                "label" => "Date de naissance",
                "widget" => "single_text",
                'format' => 'dd-MM-yyyy',
                "attr" => array(
                    "class" => "datepicker form-control",
                    'data-provide' => 'datepicker',
                    'data-date-format' => 'dd-mm-yyyy',
                    'data-date-language' => 'fr',
                )))
            ->add('isReduced', CheckboxType::class, [
                'label' => 'Tarif réduit',
                'required' => false
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Louvre\TicketingBundle\Entity\Ticket'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'louvre_ticketingbundle_ticket';
    }

}