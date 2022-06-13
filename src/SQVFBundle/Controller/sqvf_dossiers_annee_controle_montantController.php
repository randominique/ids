<?php

namespace SQVFBundle\Controller;

use DBundle\Entity\DossiersAnneeControleMontant;
use SQVFBundle\Entity\sqvf_dossiers;
use SQVFBundle\Entity\sqvf_dossiers_annee_controle_montant;
use SQVFBundle\Entity\sqvf_nif;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class sqvf_dossiers_annee_controle_montantController extends Controller
{
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');
        $idDossierFilter = $request->query->get('idDossier');
        // $rsFilter = $request->query->get('rs');

        // $qb = $sqvf_em->createQueryBuilder()
        //          ->select('count(e.id)')
        //          ->from(sqvf_dossiers_annee_controle_montant::class, 'e');
        // $countdossier = $qb->getQuery()->getSingleScalarResult();
        
        // $dossierQuery = $em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findAll();
        $dossierQuery = $sqvf_em->createQueryBuilder()
            ->select('e')
            ->from(sqvf_dossiers_annee_controle_montant::class, 'e')
            ->where('e.idDossier = :idDossier')
            ->setparameter('idDossier', 2719)
            ->orWhere('e.idDossier = :idDossier2')
            ->setparameter('idDossier2', 5421)
            ->getQuery()
            ->getResult();


        if ($idDossierFilter) {
            $dossierQuery
                ->andWhere('e.idDossier LIKE :nifParam')
                ->setParameter('nifParam', '%' . $idDossierFilter . '%');
        }

        $paginator  = $this->get('knp_paginator');
        $dossiers = $paginator->paginate(
            $dossierQuery,
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle_montant:list.html.twig', array(
            'dossiers' => $dossiers,
            // 'nbreContrib' => $countdossier,
            'idDossierFilter' => $request->query->get('idDossier'),
            // 'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function showAction()
    {
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle_montant:show.html.twig', array(
            // ...
        ));
    }

    public function editAction()
    {
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle_montant:edit.html.twig', array(
            // ...
        ));
    }

    public function deleteAction()
    {
        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle_montant:delete.html.twig', array(
            // ...
        ));
    }

    public function addDosAnCtrlMontAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sqvf_em = $this->getDoctrine()->getManager('sqvf');

        $nomBynumCourier = [];
        $attributionList = [];
        $dossiersCheck = $em->createQueryBuilder();
        $dossiersCheck->select('count(c.id)');
        $dossiersCheck->from(DossiersAnneeControleMontant::class, 'c');
        $dossiersCount = $dossiersCheck->getQuery()->getSingleScalarResult();

        for ($i = 0; $i < $dossiersCount; $i++) {
            array_push($nomBynumCourier, " ");
            array_push($attributionList, " ");
        }
        // if ($dossiersCount > 0) {
            $dossiersLast = $em->createQueryBuilder()
                ->select('MAX(dos.id)')
                ->from(DossiersAnneeControleMontant::class, 'dos')
                ->getQuery()
                ->getSingleScalarResult();
            $newDossiers = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->createQueryBuilder('nd')
                ->where('nd.id > :lastNumero')
                ->setParameter('lastNumero', $dossiersLast)
                ->orderBy('nd.id', 'ASC')
                // ->distinct('nd.id')
                ->getQuery()
                ->getResult();
                // ->getScalarResult();
        // }else{
        //     $newDossiers = $sqvf_em->getRepository(sqvf_dossiers_annee_controle_montant::class)->findAll();
        // }
            if($newDossiers) {
                foreach ($newDossiers as $key => $dossier) {
                    $nif = $sqvf_em->getRepository(sqvf_dossiers::class)->findOneBy(array(
                        'id' => $dossier->getIdDossier()
                    ));
                    $typeEtape = $sqvf_em->getRepository(sqvf_type_etapes::class)->findOneBy(array(
                        'id' => $dossier->getIdEtape()
                    ));
                    if($typeEtape)
                    {
                        $cb = new DossiersAnneeControleMontant;
                        $cb->setIdDossier($dossier->getIdDossier());
                        if($nif)
                        {
                            $cb->setNif($nif);
                            $rs = $sqvf_em->getRepository(sqvf_nif::class)->findOneBy(array(
                                'nif' => $dossier->getNif()
                            ));
                            if($rs){
                                $cb->setRaisonSociale($rs->getRaisonSociale());
                            }
                        }
                        $cb->setIdEtape($dossier->getIdEtape());
                        $cb->setEtape($typeEtape->getNom());
                        $cb->setMontantPrincipal($dossier->getMontantPrincipal());
                        $cb->setMontantAmende($dossier->getMontantAmende());
                        $cb->setMontantTotal($dossier->getMontantTotal());
                        $em->persist($cb);
                        $em->flush();
                    }
                }
            }

        $qb = $em->createQueryBuilder()
                 ->select('count(e.id)')
                 ->from(DossiersAnneeControleMontant::class, 'e');
        $countdossier = $qb->getQuery()
                                ->getSingleScalarResult();
        
        $dossiers = $em->getRepository(DossiersAnneeControleMontant::class)->findAll();

        return $this->render('SQVFBundle:sqvf_dossiers_annee_controle_montant:list.html.twig', array(
            "dossiers" => $dossiers,
            "nbreContrib" => $countdossier,
        ));
    }

}
