<?php

namespace Base\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GroupeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('menu', EntityType::class, array(
                'class' => 'BaseMainBundle:Menu',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->andWhere('m.actif=1')
                        ->andWhere('m.parent IS NOT NULL')
                        ->orderBy('m.parent','ASC','m.nom', 'ASC');
                },
                'multiple'  =>  true,
                'group_by' => 'nomParent',
                'required'=> false
            ))
            ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Base\MainBundle\Entity\Groupe'
        ));
    }
}
