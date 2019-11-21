<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('iban')
            ->add('balance')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Livret A' => 'Livret A',
                    'Livret Jeune' => 'Livret Jeune',
                    'Compte Courant' => 'Compte Courant',
                    'Compte Joint' => 'Compte Joint',
                ]
            ])
            ->add('owner', EntityType::class, [
                'class' => Person::class,
                'choice_label' => 'lastname'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
