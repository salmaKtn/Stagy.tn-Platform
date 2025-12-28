<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('username')
            ->add('password')
            ->add('name')
            ->add('location')
            ->add('logoURL')
            ->add('coverURL')
            ->add('slogan')
            ->add('about')
            ->add('taxNumber')
            ->add('field')
            ->add('socialMediaURLs')
            ->add('agentName')
            ->add('agentLastname')
            ->add('agentEmail')
            ->add('validation')
        ;
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            'csrf_protection' => false,
        ]);
    }
}
