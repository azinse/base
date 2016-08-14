<?php

namespace Base\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class AdresseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entite')
            ->add('entite_id')
            ->add('adresse_facturation')
            ->add('adresse')
            ->add('numero_rue')
            ->add('rue')
            ->add('localite')
            ->add('region')
            ->add('code_postal')
            ->add('latitude')
            ->add('longitude')
            ->add('departement')
            ->add('pays')
             ->add('code_pays')
            ->add('auteur')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Base\MainBundle\Entity\Adresse'
        ));
    }
}
