<?php

namespace SQVFBundle\Controller;

use DBundle\Entity\DossiersAnneeControle;
use DBundle\Entity\ExercicesVerifies;
use SQVFBundle\Entity\sqvf_dossiers;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle_montant;
use SQVFBundle\Entity\sqvf_dossiers_etapes;
use SQVFBundle\Entity\sqvf_etapes;
use SQVFBundle\Entity\sqvf_type_impots;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class sqvf_dossiers_annee_controleController extends Controller
{
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
                 ->select('count(e.id)')
                 ->from(ExercicesVerifies::class, 'e');
        $countFolder = $qb->getQuery()->getSingleScalarResult();
        $dossiersAll = $em->getRepository(ExercicesVerifies::class)->findAll();
        $paginator  = $this->get('knp_paginator');
        $dossiers = $paginator->paginate(
            $dossiersAll,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle:list.html.twig', array(
            'dossiers' => $dossiers,
            'nbreContrib' => $countFolder,
        ));
    }

    public function showAction()
    {
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle:show.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle:delete.html.twig', array(
            // ...
        ));
    }

    public function addExercicesVerifiesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $nomBynumCourier = [];
        $attributionList = [];
        $dossiersCheck = $em->createQueryBuilder();
        $dossiersCheck->select('count(contribuable.id)');
        $dossiersCheck->from(ExercicesVerifies::class, 'contribuable');
        $dossiersCount = $dossiersCheck->getQuery()->getSingleScalarResult();

        // for ($i = 0; $i < $dossiersCount; $i++) {
        //     array_push($nomBynumCourier, " ");
        //     array_push($attributionList, " ");
        // }
        // if ($dossiersCount > 0) {
            $dossiersLast = $em->createQueryBuilder()
                            ->select('MAX(dos.idDossierAnneeControle)')
                            ->from(ExercicesVerifies::class, 'dos')
                            ->getQuery()
                            ->getSingleScalarResult();
            $newDossiers = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->createQueryBuilder('nc')
                        ->where('nc.id > :lastNumero')
                        ->setParameter('lastNumero', $dossiersLast)
                        ->orderBy('nc.id', 'ASC')
                        // ->distinct('nc.id')
                        ->getQuery()
                        ->getResult();
                        // ->getScalarResult();
        // }else{
        //     $newDossiers = $sqvf_em->getRepository(sqvf_dossiers_annee_controle::class)->findAll();
        // }
            // if($newDossiers) {
                    foreach ($newDossiers as $dossier)
                    {
                        $synchros = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findBy(array(
                            'idDossier' => $dossier->getIdDossier(),
                            'idDossierAnneeControle' => $dossier->getId()
                        ));
                        if($synchros)
                        {
                            foreach ($synchros as $synchro)
                            {
                                $ev = new ExercicesVerifies;
                                $ev->setIdDossier($dossier->getIdDossier());
                                $ev->setIdDossierAnneeControle($dossier->getId());
                                $ev->setAnneeControle($dossier->getAnneeControle());
                                $typecontrole = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                                    'id' => $dossier->getIdDossier()
                                ));
                                if($typecontrole)
                                {
                                    $ev->setTypeControle($typecontrole->getTypeControle());
                                }
                                $syncros = $sqvf_em->getRepository(sqvf_dossiers_etapes::class)->findOneBy(array(
                                    'idDossier' => $dossier->getIdDossier()
                                ));
                                if($syncros)
                                {
                                    $ev->setDateNotificationDefinitive($syncros->getDateNotificationDefinitive());
                                }
                                $typeImpot = $sqvf_em->getRepository(sqvf_type_impots::class)->findOneBy(array(
                                    'id' => $dossier->getIdTypeImpot()
                                ));
                                if($typeImpot)
                                {
                                    $ev->setTypeImpot($typeImpot->getTitre());
                                }

                                $typeEtape = $sqvf_em->getRepository(sqvf_etapes::class)->findOneBy(array(
                                    'id' => $synchro->getIdEtape()
                                ));
                                if($typeEtape)
                                {
                                    $ev->setEtapeCourante($typeEtape->getNom());
                                }
                                $ev->setMontantPrincipal($synchro->getMontantPrincipal());
                                $ev->setMontantAmende($synchro->getMontantAmende());
                                $ev->setMontantTotal($synchro->getMontantTotal());
                                if($synchro->getMontantPrincipal())
                                {
                                    $em->persist($ev);
                                    $em->flush();
                                }
                            }
                        }
                    }
            // }

        $qb = $em->createQueryBuilder()
                 ->select('count(e.id)')
                 ->from(ExercicesVerifies::class, 'e');
        $countFolder = $qb->getQuery()
                                ->getSingleScalarResult();
        
        $contrib = $em->getRepository(ExercicesVerifies::class)->findAll();

        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle:list.html.twig', array(
            "dossiers" => $contrib,
            "nbreContrib" => $countFolder,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

}
