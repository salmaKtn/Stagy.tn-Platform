<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Offer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('Roles')
            ->add('image')            
            ->add('processStep')            
            ->add('workingHours')
            ->add('payment')
            ->add('description')
            ->add('location')
            ->add('type')
            ->add('startDate')
            ->add('endDate')
            ->add('duration')
            ->add('category')
            ->add('governorate')
            ->add('languages')
            ->add('fieldsOfInterest')
            ->add('skills')
            ->add('company')
           
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
            'csrf_protection' => false,

        ]);
    }
}
