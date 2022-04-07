<?php


namespace App\Form\Type;


use App\Entity\User;
use App\Form\Request\ScheduledHomeJobRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScheduledHomeJobType
 *
 * @package App\Form\Type
 */
class ScheduledHomeJobType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     *
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                    'label' => 'Name der Aufgabe',
                    'required' => true,
                ]
            )
            ->add('description', TextareaType::class, [
                    'label' => 'Beschreibung der Aufgabe',
                    'required' => true,
                    'purify_html' => true,
                ]
            )
            ->add('period', DateIntervalType::class, [
                    'required' => true,
                    'label' => 'Interval',
                    'label_attr' => [
                        'class' => 'text-bold'
                    ],
                    'labels' => [
                        'years' => 'Jahre',
                        'months' => 'Monate',
                        'days' => 'Tage'
                    ],
                ]
            )
            ->add('startDate', DateType::class, [
                    'label' => 'Startdatum',
                    'widget' => 'single_text',
                    'required' => true
                ]
            )
            ->add('editor', EntityType::class, [
                    'label' => 'Bearbeiter',
                    'class' => User::class,
                    'choice_label' => 'fullNameWithMail',
                    'choices' => $options['users']
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ScheduledHomeJobRequest::class,
            'users' => null,
        ]);
    }
}