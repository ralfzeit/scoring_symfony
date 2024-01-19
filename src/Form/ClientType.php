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
            ->add('Имя')
            ->add('Фамилия')
            ->add('Номер телефона')
            ->add('Email')
            ->add('education_id', EntityType::class, [
                'class' => Education::class,
                'choice_label' => 'id',
            ])
            ->add('Я даю согласие на обработку моих личных данных')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
