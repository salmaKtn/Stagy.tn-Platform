<?php

namespace App\Form;

use App\Entity\CV;
use App\Entity\SectionEntry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('title')
            ->add('description')
            ->add('startDate')
            ->add('endDate')
            ->add('cv', EntityType::class, [
                'class' => CV::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SectionEntry::class,
            'csrf_protection' => false,

        ]);
    }
}
