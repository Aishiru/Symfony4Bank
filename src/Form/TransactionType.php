<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $person = $options['person'];
        $builder
            ->add('amount')
            ->add('label')
            ->add('creditAccount', EntityType::class, [
                'class' => Account::class,
                'query_builder' => function (AccountRepository $er) use ($person) {
                    return $er->createQueryBuilder('a')
                    ->join('a.people', 'p')
                    ->andWhere('a.owner = :person')
                    ->orWhere('p.id = :person')
                    ->setParameter('person', $person);
                },
                'choice_label' => function ($account) {
                    return $account->getOwner()->getFirstname() . ' ' . $account->getOwner()->getLastname() . ' ' . $account->getType();
                }
            ])
            ->add('debitAccount', EntityType::class, [
                'class' => Account::class,
                'query_builder' => function (AccountRepository $er) use ($person) {
                    return $er->createQueryBuilder('a')
                    ->andWhere('a.owner = :person')
                    ->setParameter('person', $person);
                },
                'choice_label' => function ($account) {
                    return $account->getOwner()->getFirstname() . ' ' . $account->getOwner()->getLastname() . ' ' . $account->getType();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
            'person' => 0
        ]);
    }
}
