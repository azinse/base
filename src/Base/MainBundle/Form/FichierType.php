<?php

namespace Base\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class FichierType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie')
            ->add('entite')
            ->add('entite_id')
            ->add('url')
            ->add('taille')
            ->add('date_debut', DateType::class,array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'html5' => false,// do not render as type="date", to avoid HTML5 date pickers
            'attr' => ['class' => 'js-datepicker'],// add a class that can be selected in JavaScript
            ))
            ->add('date_butoir', DateType::class,array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
            'html5' => false,// do not render as type="date", to avoid HTML5 date pickers
            'attr' => ['class' => 'js-datepicker'],// add a class that can be selected in JavaScript
            ))
            ->add('date_creation')
            ->add('auteur')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Base\MainBundle\Entity\Fichier'
        ));
    }
}
