<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\Plan;
use App\Entity\Subscription;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate')
            ->add('endDate')
            ->add('isPaid')
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'id',
            ])
            ->add('plan', EntityType::class, [
                'class' => Plan::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}
