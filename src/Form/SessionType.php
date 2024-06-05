<?php

namespace App\Form;

use App\Entity\Former;
use App\Entity\Session;
use App\Entity\Student;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded',
                    'placeholder' => 'Title',
                ]
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('nbPlaces', IntegerType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('programDetails', TextType::class, [
                'attr' => [
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            // ->add('students', EntityType::class, [
            //     'class' => Student::class,
            //     'choice_label' => 'fullName',
            //     'attr' => [
            //         'id' => 'dropdown',
            //         'class' => 'z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700'
            //     ]
            // ])
            ->add('former', EntityType::class, [
                'class' => Former::class,
                'choice_label' => 'fullName',
                'attr' => [
                    'id' => 'select',
                    'class' => 'w-full px-5  py-4 text-gray-700 bg-gray-200 rounded'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'id' => 'select',
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
            'data_class' => Session::class,
        ]);
    }
}
