<?php

namespace Base\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EvenementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut', DateTimeType::class,array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy HH:mm:ss',
            'html5' => false,// do not render as type="date", to avoid HTML5 date pickers
            'attr' => ['class' => 'js-datetimepicker'],// add a class that can be selected in JavaScript
            ))
            ->add('date_fin', DateTimeType::class,array(
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy HH:mm:ss',
            'html5' => false,// do not render as type="time", to avoid HTML5 time pickers
            'attr' => ['class' => 'js-datetimepicker'],// add a class that can be selected in JavaScript
            'required' => false,
            ))
            ->add('description')
            ->add('lieu')
            ->add('titre')
            ->add('url')
            ->add('journee_entiere')
            ->add('couleur_fond')
            ->add('couleur_texte')
            ->add('date_creation', DateTimeType::class)
            ->add('date_modification', DateTimeType::class)
            ->add('auteur')
            ->add('invite', EntityType::class, array(
                'class' => 'BaseMainBundle:Utilisateur',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->leftJoin('BaseMainBundle:Personne','p', 'WITH', 'u.personne_id = p.id')
                        ->where('p.actif = 1')
                        ->andWhere('u.enabled = 1')
                        ->orderBy('p.nom','ASC','p.prenom', 'ASC');
                },
                'multiple'  =>  true,
                'required'=> false
            ))
            ->add('inactif')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Base\MainBundle\Entity\Evenement',
        ));
    }
}
