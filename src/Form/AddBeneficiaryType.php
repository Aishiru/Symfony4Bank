<?php

namespace App\Form;

use App\Entity\Person;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBeneficiaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $person = $options['person'];
        $builder
            ->add('beneficiaries', EntityType::class, [
                'class' => Account::class,
                'query_builder' => function(AccountRepository $er) use ($person) {
                    return $er->createQueryBuilder('a')
                    ->andWhere('a.owner != :person')
                    ->setParameter('person', $person);
                },
                'choice_label' => 'iban',
                'multiple' => true
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Person::class,
            'person' => 0
        ]);
    }
}
