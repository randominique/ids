<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        /* ->add('im',TextType::class, [
            'label' => 'Numéro matricule',
            'disabled' => true]) 
        ->add('nom',TextType::class, [
            'label' => 'Nom',
            'disabled' => true])
        ->add('prenom',TextType::class, [
            'label' => 'Prénoms',
            'disabled' => true])
        /* ->add('corps')
        ->add('service')
        ->add('telephone')*/
        ;}
    
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ChangePasswordFormType';
    }
    
    // /**
    //  * {@inheritdoc}
    //  */
    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults(array(
    //         'data_class' => 'DBundle\Entity\User'
    //     ));
    // }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
