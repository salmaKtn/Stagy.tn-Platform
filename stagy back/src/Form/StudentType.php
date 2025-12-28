<?php

namespace App\Form;

use App\Entity\CV;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('password')             
            ->add('firstName')
            ->add('lastName')
            // ->add(child: 'level')
            ->add('phoneNumber')
            // ->add('fieldOfStudy')
            ->add('gender')
            ->add('email')
            // ->add('URLs')
            // ->add('cvFile')
            // ->add('picture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
            'csrf_protection' => false,
        ]);
    }
}
