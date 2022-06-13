<?php

namespace SQVFBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DossiersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('idUser')->add('idCentreFiscal')->add('idTypeNotification')->add('idNotificationRedressement')->add('idNotificationDefinitive')->add('nif')->add('uniqid')->add('typeControle')->add('dateDebutOperation')->add('dateCreation')->add('dateDebutIntervention')->add('dateFinIntervention')->add('etapeCourante')->add('archive')->add('newUniqid')->add('createTime')->add('updateTime');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SQVFBundle\Entity\Dossiers'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sqvfbundle_dossiers';
    }


}
