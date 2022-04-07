<?php


namespace App\Form\Type;

use App\Form\Request\UserRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileEditType
 *
 * @package App\Form\Type
 */
class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', EmailType::class, [
                'label' => 'E-Mail Adresse',
                'attr' => [
                    'placeholder' => 'Ihre E-Mail Adresse...'
                    ]
                ]
            )
            ->add('firstName', TextType::class, [
                    'label' => 'Vorname',
                    'attr' => [
                        'placeholder' => 'Ihr Vorname...'
                    ]
                ]
            )
            ->add('lastName', TextType::class, [
                    'label' => 'Nachname',
                    'attr' => [
                        'placeholder' => 'Ihr Nachname...'
                    ]
                ]
            )
            ->add('password', RepeatedType::class, [
                    'required' => false,
                    'type' => PasswordType::class,
                    'first_name' => 'password',
                    'first_options' => [
                        'label' => 'Passwort',
                        'attr' => [
                            'placeholder' => 'Ihr Passwort...'
                        ]
                    ],
                    'second_name' => 'confirm',
                    'second_options' => [
                        'label' => 'Passwort wiederholen',
                        'attr' => [
                            'placeholder' => 'Passwort wiederholen...'
                        ]
                    ],
                    'invalid_message' => 'Die Eingaben stimmen nicht überein.'
                ]
            )
        ;

        if ($options['withAvatar']) {
            $builder->add('avatarFile', FileType::class, [
                    'label' => 'Profilbild',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Profilbild auswählen'
                    ],
                ]
            )
            ->add('enabledJobNotification', CheckboxType::class, [
                    'label' => 'Benachrichtigungen für Aufgaben aktivieren',
                    'required' => false,
                ]
            )
            ->add('enabledPaymentNotification', CheckboxType::class, [
                    'label' => 'Benachrichtigungen für das Haushaltsbuch aktivieren',
                    'required' => false,
                ]
            );
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserRequest::class,
                'validation_groups' => 'edit',
                'withAvatar' => false,
            ]
        );
    }
}