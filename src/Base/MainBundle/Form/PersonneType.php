<?php

namespace Base\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PersonneType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('sexe', ChoiceType::class, array(
                'choices'  => array(
                    'monsieur' => 'Monsieur',
                    'madame' => 'Madame',
                ),
                "required" => true,
            ))
            ->add('telephone')
            ->add('mobile')
            ->add('actif')
            ->add('date_naissance', DateType::class,array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'html5' => false,
            'required' => false,
            'attr' => ['class' => 'js-datepicker'],// add a class that can be selected in JavaScript
            ))
            ->add('email')
            ->add('contact')
            ->add('fonction')
            ->add('presentation')
            ->add('date_creation')
            ->add('date_modification')
            ->add('auteur')
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Base\MainBundle\Entity\Personne',
        ));
    }
}
