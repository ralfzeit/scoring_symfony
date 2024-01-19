<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Education;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('phone')
            ->add('email')
            ->add('education_id', EntityType::class, [
                'class' => Education::class,
                'choice_label' => 'id',
            ])
            ->add('agree')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
