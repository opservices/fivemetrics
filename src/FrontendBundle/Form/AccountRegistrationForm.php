<?php

namespace FrontendBundle\Form;

use EssentialsBundle\Entity\Account\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountRegistrationForm extends AbstractType
{
    public const COUNTRIES = [
        'United States',
        'Albania',
        'Algeria',
        'Angola',
        'Argentina',
        'Armenia',
        'Australia',
        'Austria',
        'Bahrain',
        'Bangladesh',
        'Belgium',
        'Bolivia',
        'Brazil',
        'Bulgaria',
        'Cambodia',
        'Cameroon',
        'Canada',
        'Chad',
        'Chile',
        'China',
        'Colombia',
        'Costa Rica',
        'Croatia',
        'Czech Republic',
        'Denmark',
        'Dominican Republic',
        'Ecuador',
        'Egypt',
        'El Salvador',
        'Estonia',
        'Ethiopia',
        'Finland',
        'France',
        'Georgia',
        'Germany',
        'Ghana',
        'Greece',
        'Guatemala',
        'Honduras',
        'Hong Kong',
        'Hungary',
        'Iceland',
        'India',
        'Indonesia',
        'Ireland',
        'Israel',
        'Italy',
        'Jamaica',
        'Japan',
        'Jordan',
        'Kenya',
        'Korea, Republic of',
        'Kuwait',
        'Lithuania',
        'Madagascar',
        'Malaysia',
        'Mexico',
        'Monaco',
        'Mongolia',
        'Montenegro',
        'Morocco',
        'Mozambique',
        'Nepal',
        'Netherlands',
        'New Zealand',
        'Nicaragua',
        'Niger',
        'Nigeria',
        'Norway',
        'Pakistan',
        'Paraguay',
        'Peru',
        'Philippines',
        'Poland',
        'Portugal',
        'Puerto Rico',
        'Qatar',
        'Romania',
        'Russian Federation',
        'Rwanda',
        'Saudi Arabia',
        'Senegal',
        'Serbia',
        'Singapore',
        'Slovenia',
        'South Africa',
        'Spain',
        'Sweden',
        'Switzerland',
        'Taiwan, Province of China',
        'Thailand',
        'Tunisia',
        'Turkey',
        'Uganda',
        'Ukraine',
        'United Arab Emirates',
        'United Kingdom',
        'Uruguay',
        'Yugoslavia',
        'Zaire',
        'Zambia',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'by_reference' => false,
                'attr' => [
                    'placeholder' => 'Name *',
                    'class' => 'border-focus-blue form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'by_reference' => false,
                'attr' => [
                    'placeholder' => 'user@youremail.com',
                    'class' => 'border-focus-blue form-control',
                ],
            ])->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'by_reference' => false,
                'first_options'  => [
                    'attr' => [
                        'placeholder' => 'Password *',
                        'class' => 'border-focus-blue form-control js-password-input js-password-first',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Repeat Password *',
                        'class' => 'border-focus-blue form-control js-password-input js-password-second',
                    ],
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
