<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('surname', TextType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('sex', TextType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('town', TextType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('mail', TextType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('phone', TelType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('add', SubmitType::class, [
                'attr' => [
                    'class' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'task_item',
        ]);
    }
}
