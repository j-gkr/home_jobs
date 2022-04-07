<?php


namespace App\Form\Type;


use App\Entity\PaymentCategory;
use App\Form\Request\PaymentRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaymentType
 * @package App\Form\Type
 */
class PaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                    'label' => 'Name',
                    'required' => true,
                ]
            )
            ->add('amount', MoneyType::class, [
                    'label' => 'Betrag',
                    'required' => true,
                ]
            )
            ->add('paymentCategory', EntityType::class, [
                    'label' => 'Kategorie',
                    'required' => true,
                    'class' => PaymentCategory::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Bitte auswählen'
                ]
            )
            ->add('paymentDate', DateType::class, [
                    'label' => 'Buchungsdatum',
                    'required' => true,
                    'widget' => 'single_text',
                ]
            )
            ->add('description', TextareaType::class, [
                    'label' => 'Beschreibung',
                    'required' => false,
                    'purify_html' => true,
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
                'data_class' => PaymentRequest::class,
            ]
        );
    }

}