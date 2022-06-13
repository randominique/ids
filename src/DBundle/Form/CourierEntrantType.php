<?php

namespace DBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CourierEntrantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ref', TextType::class, [
            'label'  => 'Référence'
        ])
        ->add('nif', TextType::class, [
            'label' => 'NIF',
            // 'style' => 
        ])
        ->add('rs', TextType::class, [
            'label' => 'Raison sociale'
        ])
        ->add('titre', TextType::class, [
            'label' => 'Titre'
        ])
        ->add('objet', ChoiceType::class, [
            'choices' => [
                'PLI FERME' => 'PLI FERME',
                'AUTRES' => 'AUTRES',
                'COURRIERS DGI' => 'COURRIERS DGI',
                'DEMANDE ATTESTATION IRSA' => 'DEMANDE ATTESTATION IRSA',
                'DEMANDE ATTESTATION DE RESIDENCE FISCALE' => 'DEMANDE ATTESTATION DE RESIDENCE FISCALE',
                'DEMANDE IMPOSITION SEPAREE' => 'DEMANDE IMPOSITION SEPAREE',
                'DEMANDE VISA CHIFFRE D\'AFFAIRES' => 'DEMANDE VISA CHIFFRE D\'AFFAIRES',
                'DEMANDE DE CERTIFICATION DE DOCUMENTS' => 'DEMANDE DE CERTIFICATION DE DOCUMENTS',
                'DEMANDE DE CNAVTO' => 'DEMANDE DE CNAVTO',
                'DEMANDE DECOMPTE DROIT D\'ACCISES' => 'DEMANDE DECOMPTE DROIT D\'ACCISES',
                'DEMANDE DECOMPTE DROIT D\'ENREGISTREMENT' => 'DEMANDE DECOMPTE DROIT D\'ENREGISTREMENT',
                'DEMANDE ENTREGISTREMENT ACTES' => 'DEMANDE ENTREGISTREMENT ACTES',
                'DEMANDE DE PRECISION SUR LE TEXTE FISCAL' => 'DEMANDE DE PRECISION SUR LE TEXTE FISCAL',
                'DEMANDE REDRESSEMENT DE DECLARATION' => 'DEMANDE SUSPENSION PAIEMENT ACOMPTE',
                'DEMANDE DE DELIVRANCE CARTE FISCALE' => 'DEMANDE DE DELIVRANCE CARTE FISCALE',
                'DEPOT DE DOCUMENTS POUR DFU' => 'DEPOT DE DOCUMENTS POUR DFU',
                'DEMANDE DE MISE A JOUR RENSEIGNEMENT PERMANENT DFU' => 'DEMANDE DE MISE A JOUR RENSEIGNEMENT PERMANENT DFU',
                'DEMANDE DE MOT DE PASSE SUR NIF ONLINE' => 'DEMANDE DE MOT DE PASSE SUR NIF ONLINE',
                'DEMANDE DE COMPTE TELEDECLARATION' => 'DEMANDE DE COMPTE TELEDECLARATION',
                'DEMANDE DE CHANGEMENT DE LA DUREE DE L\' EXERCICE FISCAL' => 'DEMANDE DE CHANGEMENT DE LA DUREE DE L\' EXERCICE FISCAL',
                'DEMANDE RAJOUT OU DE SUPPRESSION DES VEHICULES' => 'DEMANDE RAJOUT OU DE SUPPRESSION DES VEHICULES',
                'DEMANDE DE VIGNETTE' => 'DEMANDE DE VIGNETTE',
                'LETTRE DE REPONSE DES NOTIFICATIONS' => 'LETTRE DE REPONSE DES NOTIFICATIONS',
                'DEMANDE DE DELAI DE REPONSE AUX NOTIFICATIONS' => 'DEMANDE DE DELAI DE REPONSE AUX NOTIFICATIONS',
                'DEPOT ETATS FINANCIERS' => 'DEPOT ETATS FINANCIERS',
                'DEMANDE D\'AUTORISATION DE DESTRUCTION' => 'DEMANDE D\'AUTORISATION DE DESTRUCTION',
                'LETTRE DE REPONSE DES NOTIFICATIONS' => 'LETTRE DE REPONSE DES NOTIFICATIONS',
                'DEMANDE DE DETAILS RELATIFS AUX NOTIFICATIONS' => 'DEMANDE DE DETAILS RELATIFS AUX NOTIFICATIONS',
                'DEMANDE DE REPORT DELAI DE REPONSE AUX NOTIFICATIONS' => 'DEMANDE DE REPORT DELAI DE REPONSE AUX NOTIFICATIONS',
                'DEMANDE DE CALENDRIER DE PAIEMENT' => 'DEMANDE DE CALENDRIER DE PAIEMENT',
                'DEMANDE ATTESTATION DE PAIEMENT D\'IMPOTS' => 'DEMANDE ATTESTATION DE PAIEMENT D\'IMPOTS',
                'DEMANDE DE VALIDATION DE PAIEMENT' => 'DEMANDE DE VALIDATION DE PAIEMENT',
                'RECLAMATION AUX TITRES DE PERCEPTION OU ATD' => 'RECLAMATION AUX TITRES DE PERCEPTION OU ATD',
                'DEMANDE DE RECTIFICATION DE PAIEMENT' => 'DEMANDE DE RECTIFICATION DE PAIEMENT',
                'DEMANDE DE SITUATION DES RESTES A RECOUVRER' => 'DEMANDE DE SITUATION DES RESTES A RECOUVRER',
                'DEMANDE DE RECEPISSE' => 'DEMANDE DE RECEPISSE',
                'DEMANDE DE RETOUR DE FONDS' => 'DEMANDE DE RETOUR DE FONDS',
                'DEMANDE DE MAIN LEVEE ATD' => 'DEMANDE DE MAIN LEVEE ATD',
                'DEMANDE DE RECTIFICATION DE DECLARATION ISI' => 'DEMANDE DE RECTIFICATION DE DECLARATION ISI'
            ],
            'attr' => [ 'class' => 'seeAnotherField' ],
            'multiple' => false,
            'expanded' => false,
        ])
        // ->add('adresse', TextType::class, [
        //     'label' => 'adresse',
        //     'attr' => [ 'class' => 'otherField' ]
        // ])
        ->add('observation', null, ['label' => 'Observations'])
        // ->add('categorie')
        ->add('Enregistrer', SubmitType::class, ['attr' => ['class' => 'save btn btn-primary', 'style'=>'width:120px']]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DBundle\Entity\CourierEntrant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dbundle_courierentrant';
    }


}
