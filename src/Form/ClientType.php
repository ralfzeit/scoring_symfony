<?php
/*
 * Форма клиента
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Form;

use App\Entity\Client;
use App\Entity\Education;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Класс формы клиента
 */
class ClientType extends AbstractType
{
    /**
     * Сборка формы
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('phone')
            ->add('email')
            ->add('education_id', EntityType::class, [
                'class' => Education::class,
                'choice_label' => 'title',
                'label' => 'Образование',
            ])
            ->add('agree', null, ['required' => false])
        ;
    }

    /**
     * Конфигурация формы
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
