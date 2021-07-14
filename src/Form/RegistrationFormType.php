<?php

namespace App\Form;

use App\Dictionaries\CityDictionary;
use App\Dictionaries\SexDictionary;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
            ])
            ->add('secondName', TextType::class, [
                'required' => true,
            ])
            ->add('birthdate', BirthdayType::class, [
                'required' => true,
                'placeholder' => [
                    'year' => 'Год', 'month' => 'Месяц', 'day' => 'День',
                ]
            ])
            ->add('sex', ChoiceType::class, [
                'required' => true,
                'choices' => array_flip(SexDictionary::get()),
            ])
            ->add('city', ChoiceType::class, [
                'required' => true,
                'choices' => array_flip(CityDictionary::get()),
            ])
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('about', TextareaType::class, [
                'required' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'invalid_message' => 'Поля паролей должны совпадать.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Введите пароль: '],
                'second_options' => ['label' => 'Повторите пароль: '],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
