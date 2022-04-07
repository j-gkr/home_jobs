<?php


namespace App\Form\Type;


use App\Form\Request\GroupRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GroupType
 *
 * @package App\Form\Type
 */
class GroupType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
                    'label' => 'Gruppenname',
                    'required' => true,
                ]
            )
            ->add('budget', NumberType::class, [
                    'label' => 'Monatliches Budget',
                    'required' => false,
                ]
            )
            ->add('street', TextType::class, [
                    'label' => 'Straße',
                    'required' => false,
                ]
            )
            ->add('housenumber', TextType::class, [
                    'label' => 'Hausnummer',
                    'required' => false,
                ]
            )
            ->add('city', TextType::class, [
                    'label' => 'Ort',
                    'required' => false,
                ]
            )
            ->add('zip', TextType::class, [
                    'label' => 'PLZ',
                    'required' => false,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupRequest::class,
        ]);
    }
}