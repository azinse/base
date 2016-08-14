<?php

namespace Base\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UtilisateurType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societe', EntityType::class, array(
                'class' => 'BaseMainBundle:Societe',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
//                        ->andWhere('s.parent IS NOT NULL')
                        ->orderBy('s.nom', 'ASC');
                },
                'empty_data' => null,
                'multiple'  =>  true,
//                'expanded'  => false,
                'required' => false,
            ))
            ->add('groupe_principal')
            ->add('groupe')
            ->add('personne_id')
            ->add('enabled')
            ->add('plainPassword',RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.new_password'),
                'second_options' => array('label' => 'form.new_password_confirmation'),
                'required' => false,
                ))
        ;
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Base\MainBundle\Entity\Utilisateur'
        ));
    }
}
