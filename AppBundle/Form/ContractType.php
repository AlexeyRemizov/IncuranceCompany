<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 13.05.2017
 * Time: 20:33
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class LettersRequestType
 * @package AppBundle\Form
 */
class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateIN', TextType::class)
            ->add('dateOFF', TextType::class)
            ->add('subject', TextType::class)
            ->add('vinID', NumberType::class)
            ->add('registrationID', NumberType::class)
            ->add('datePay', TextType::class)
            ->add('sumpay', NumberType::class)
            ->add('type', TextType::class);

    }
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}