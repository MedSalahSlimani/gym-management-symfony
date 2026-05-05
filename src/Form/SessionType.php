<?php

namespace App\Form;

use App\Entity\Coach;
use App\Entity\Member;
use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('sessionDate')
            ->add('duration')
            ->add('capacity')
            ->add('coaches', EntityType::class, [
                'class' => Coach::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('members', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
