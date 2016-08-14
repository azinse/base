<?php

namespace Base\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class SocieteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                        'choices'  => array(
                            'Client' => 'Client',
                            'Fournisseur' => 'Fournisseur',
                            'Structure' => 'Structure',
                            'Sous-traitant' => 'Sous-traitant',
                        ),)
                )
            ->add('nom')
            ->add('numero_identification')
            ->add('email')
            ->add('telephone')
            ->add('fax')
            ->add('site')
            ->add('actif')
            ->add('couleur')
            ->add('date_creation')
            ->add('date_modification')
            ->add('secteur', EntityType::class, array(
                'class' => 'BaseMainBundle:Secteur',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->andWhere('s.parent IS NULL')
                        ->orderBy('s.nom', 'ASC');
                },
                'multiple'  =>  false,
                'required'=> false
            ))
            ->add('auteur')

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Base\MainBundle\Entity\Societe'
        ));
    }
}
