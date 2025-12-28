<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\Offer;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('compatibility')
            ->add('status')
            ->add('timestamp')
            ->add('offer', EntityType::class, [
                'class' => Offer::class,
                'choice_label' => 'id',
            ])
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
            'csrf_protection' => false,

        ]);
    }
}
