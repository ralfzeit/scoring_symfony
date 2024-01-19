<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Education;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя',
            ])
            ->add('surname', TextType::class, [
                'label' => 'Фамилия',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Номер телефона',
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
            ])
            ->add('education_id', EntityType::class, [
                'class' => Education::class,
                'choice_label' => 'title',
                'label' => 'Образование',
            ])
            ->add('agree', CheckboxType::class, [
                'label' => 'Email',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
