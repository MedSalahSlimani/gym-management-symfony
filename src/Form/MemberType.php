<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\Session;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'Enter first name']
            ])
            ->add('surname', TextType::class, [
                'attr' => ['placeholder' => 'Enter last name']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'email@example.com']
            ])
            ->add('phone', TelType::class, [
                'attr' => ['placeholder' => '+216 XX XXX XXX']
            ])
            ->add('joinDate', DateType::class, [
                'widget' => 'single_text',
            ])
            // 🔥 AJOUTER LA SÉLECTION DES SESSIONS
            ->add('sessions', EntityType::class, [
                'class' => Session::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,  // Affiche des checkboxes (ou false pour select multiple)
                'required' => false,
                'attr' => [
                    'class' => 'session-checkboxes'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}