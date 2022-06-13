<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use DBundle\Entity\User;
use DBundle\Entity\SaiSetting;
use DBundle\Entity\Service;
use DBundle\Entity\Tache;
use DBundle\Entity\TacheObservation;
use DBundle\Form\TacheObservationType;
use DBundle\Form\TacheType;
use DBundle\Entity\TacheObjet;
use DBundle\Entity\PourInfo;
use DBundle\Entity\Entrant;
use DBundle\Entity\Sortant;
use DBundle\Entity\EntrantObservation;

use DBundle\Form\TacheObjetType;

use Doctrine\Common\Collections\ArrayCollection;

class TacheController extends Controller
{
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));

        $courrier = new Tache;
        $courrier->setStatus('Nouveau');
        $courrier->setAuteur($this->getUser());
        $courrier->setService($this->getUser()->getService());
        $courrier->setUpdatedAt(new \DateTime());
        $courrier->setCreatedAt(new \DateTime());
        $form = $this->createForm(TacheType::class, $courrier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $gestionnaire = $request->request->get('gestionnaire');
            $responsable = $em->getRepository(User::class)->find($gestionnaire);
            $courrier->setGestionnaire($responsable);
            $em->persist($courrier);

            $observation = new TacheObservation;
            $observation->setMessage($courrier->getObservationContent());
            // $observation->setUser($this->getUser());
            $observation->setUser($this->getUser());
            $observation->setCourrier($courrier);
            $observation->setCreatedAt(new \DateTime);
            $observation->setStatus($courrier->getStatus());
            $em->persist($observation);
            $em->flush();

            // $userAssigne = $em->getRepository(User::class)->findOneBy(array('id' => $this->getUser()->getId()));
            $userAssigne = $em->getRepository(User::class)->find($gestionnaire);
            if($userAssigne)
            {
                $userAssigne->setNbretache($userAssigne->getNbretache()+1);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }

            return $this->redirectToRoute('list_tache');
        }

        return $this->render('DBundle:Tache:new.html.twig', array(
            'form' => $form->createView(),
            'usersService' => $responsableQuery,
            'request' => $request,
        ));
    }

    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nif_em = $this->getDoctrine()->getManager('nifonline');
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');
        $user = $this->getUser();
        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeDirection = ($user->getId() == 4) ? true : false;

        $tacheQuery = $em->getRepository('DBundle:Tache')
            ->createQueryBuilder('tac');

        if ($nifFilter) {
            $tacheQuery
                ->andWhere('tac.nif LIKE :nifParam')
                ->setParameter('nifParam', '%' . $nifFilter . '%');
        }
        if ($rsFilter) {
            $tacheQuery
                ->andWhere('tac.rs LIKE :rsParam')
                ->setParameter('rsParam', '%' . $rsFilter . '%');
        }
        if ($request->query->get('date_du') && $request->query->get('date_au')) {
            $tacheQuery
                ->andWhere('tac.createdAt BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $request->query->get('date_du'))
                ->setParameter('date_au', $request->query->get('date_au'));
        }
        $tacheQuery
            ->orderBy('tac.id', 'DESC');
        $tacheQuery->getQuery();

        $paginator  = $this->get('knp_paginator');
        $Tache = $paginator->paginate(
            $tacheQuery,
            $request->query->getInt('page', 1),
            20
        );

        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            $query = $em->getRepository(Tache::class)->findAll();    // Les super_admins peuvent voir toutes les tâches
        } elseif ($user->hasRole('ROLE_ADMIN')) {
            $query = $em->getRepository(Tache::class)->findBy(array(
                'service' => $user->getService()
            ));    // Les admins peuvent voir uniquement toutes les tâches du Service
        } else {
            $query = $em->getRepository(Tache::class)->findBygestionnaire($user->getId());
        }

        $paginator  = $this->get('knp_paginator');
        $courriers = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('DBundle:Tache:list.html.twig', array(
            "taches" => $query,
            "courriers" => $courriers,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'isChefSAI' => $isChefSAI,
            'isChefDeService' => $isChefDeService,
            'isChefDeDirection' => $isChefDeDirection,
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
        ));
    }

    public function showAction(Tache $courrier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeService = ($courrier->getService() && $courrier->getService()->getChef() && $courrier->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isChefDeDirection = ($user->getId() == 4) ? true : false;

        $defaultData = ['message' => 'Type your message here'];

        $courrier->setObservationContent('Observations');

        $observation = new TacheObservation;
        $observation->setUser($this->getUser());
        $observation->setCourrier($courrier);
        $observation->setCreatedAt(new \DateTime);
        $formulaire_observation = $this->createForm(TacheObservationType::class, $observation);
        $formulaire_observation->handleRequest($request);

        if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
            $courrier->setUpdatedAt(new \DateTime);
            $em->persist($observation);
            $em->flush();
            return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
        }
        $observations =  $em->getRepository(TacheObservation::class)->findBy(['courrier' => $courrier->getId()]);
        $defaultData = ['message' => 'Type your message here'];

        $courrier->setObservationContent('Observations');
        $assigne_form = $this->createFormBuilder($defaultData)
            ->add('gestionnaire', EntityType::class, [
                'placeholder' => 'Choisissez',
                'class' => User::class,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.service = ' . $this->getUser()->getService()->getId() . '')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => function ($gestionnaire) {
                    $name = $gestionnaire->getNom() . ' ' . $gestionnaire->getPrenom() /*.'('.$gestionnaire->getService()->getNom().')*/;
                    return $name;
                },
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']])
            ->getForm();

        $assigne_form->handleRequest($request);

        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat) {

                switch ($stat) {

                    case 'en_attente':
                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-blue">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'En cours':
                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'Traité':
                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-green">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'Clôturé':

                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }

                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
                        $em->persist($observation);
                        break;
                }

                $em->flush();
                return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
            }

            return $this->render('DBundle:Tache:show.html.twig', array(
                'courrier' => $courrier,
                'observations' => $observations,
                'assigne_form' => $assigne_form->createView(),
                'isChefDeService' => $isChefDeService,
                'isChefSAI' => $isChefSAI,
            ));
        }
    }

    public function TacheUpdateAction(Tache $courrier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        // $courrier->setObservationContent('ids');
        // dump($courrier);
        // die();
        $docTache = $em->getRepository(Tache::class)->findOneBy(array('id' => $courrier->getId()));
        // dump($courrier->getId(), $docTache);
        // die();
        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat == 'Traité') {
                $courrier->setUpdatedAt(new \DateTime);
                $courrier->setStatus('Traité');
                $observation = new TacheObservation;
                $observation->setUser($this->getUser());
                $observation->setCourrier($courrier);
                $observation->setMessage('a changé le status: <span class="badge bg-green">Traité</span>');
                $observation->setCreatedAt(new \DateTime);
                $observation->setStatus('Traité');
                $em->persist($observation);

                $userAssigne = $em->getRepository(User::class)->find($user->getId());
                if($userAssigne)
                {
                    $userAssigne->setNbretache($userAssigne->getNbretache()-1);
                    $em = $this->getDoctrine()->getManager();
                }
                $em->flush();

                return $this->redirectToRoute('list_tache', ['courrier' => $courrier->getId()]);
            }
            if ($stat == 'Clôturé') {
                $courrier->setUpdatedAt(new \DateTime);
                $courrier->setStatus('Clôturé');
                $observation = new TacheObservation;
                $observation->setUser($this->getUser());
                $observation->setCourrier($courrier);
                $observation->setMessage('a changé le status: <span class="badge bg-red">Clôturé</span>');
                $observation->setCreatedAt(new \DateTime);
                $observation->setStatus('Clôturé');
                $em->persist($observation);

                $em->flush();

                return $this->redirectToRoute('list_tache', ['courrier' => $courrier->getId()]);
            }
        }
        return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
    }

    public function showBackupAction(Tache $courrier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);

        $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeService = ($courrier->getService() && $courrier->getService()->getChef() && $courrier->getService()->getChef()->getId() == $user->getId()) ? true : false;

        $defaultData = ['message' => 'Type your message here'];

        $courrier->setObservationContent('ids');
        $form = $this->createFormBuilder($defaultData)
            ->add('service', EntityType::class, [
                'placeholder' => 'Choisissez',
                'class' => Service::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('pourInfo', EntityType::class, [
                'placeholder' => 'Choisissez',
                'class' => Service::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$isChefSAI) {
                throw $this->createNotFoundException('Seul le chef SAI peut dispatcher les courriers.');
            }

            $courrier_service = $courrier->getService();
            if (!$courrier_service) {

                $service = $request->request->get('form')['service'];
                $pourInfoValue = $request->request->get('form')['pourInfo'];

                foreach ($pourInfoValue as $value) {

                    $pourInfoService = $em->getRepository(Service::class)->find($value);

                    if ($pourInfoService) {
                        $pourInfo = new PourInfo;
                        $pourInfo->setCourrier($courrier);
                        $pourInfo->setService($pourInfoService);
                        $em->persist($pourInfo);
                    }
                }

                $service = $em->getRepository(Service::class)->find($service);
                $courrier->setService($service);
                $courrier->setUpdatedAt(new \DateTime);
                $courrier->setStatus('en_attente');

                $observation = new TacheObservation;
                $observation->setUser($this->getUser());
                $observation->setCourrier($courrier);
                $observation->setCreatedAt(new \DateTime);
                $observation->setMessage('<span class="text-green">a dispatché ce courrier au ' . $service->getNom() . '. Status courrier: EN ATTENTE</span>');
                $em->persist($observation);

                $em->flush();

                return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
            } else {
                return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
            }
        }

        $observation = new TacheObservation;
        $observation->setUser($this->getUser());
        $observation->setCourrier($courrier);
        $observation->setCreatedAt(new \DateTime);
        $formulaire_observation = $this->createForm(TacheObservationType::class, $observation);
        $formulaire_observation->handleRequest($request);

        if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
            $courrier->setUpdatedAt(new \DateTime);
            $em->persist($observation);
            $em->flush();
            return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
        }
        $observations =  $em->getRepository(TacheObservation::class)->findBy(['courrier' => $courrier->getId()]);
        $defaultData = ['message' => 'Type your message here'];

        $courrier->setObservationContent('ids');
        $assigne_form = $this->createFormBuilder($defaultData)
            ->add('gestionnaire', EntityType::class, [
                'placeholder' => 'Choisissez',
                'class' => User::class,
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.service = ' . $this->getUser()->getService()->getId() . '')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => function ($gestionnaire) {
                    $name = $gestionnaire->getNom() . ' ' . $gestionnaire->getPrenom()/*.'('.$gestionnaire->getService()->getNom().')*/;
                    return $name;
                },
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer', 'attr' => ['class' => 'btn btn-success']])
            ->getForm();

        $assigne_form->handleRequest($request);

        if ($assigne_form->isSubmitted() && $assigne_form->isValid()) {

            $courrier_gestionnaire = $courrier->getGestionnaire();

            if (!$courrier_gestionnaire) {

                $gestionnaireData = $request->request->get('form')['gestionnaire'];
                $getUser = $em->getRepository(User::class)->find($gestionnaireData);
                if ($getUser) {
                    $courrier->setGestionnaire($getUser);
                    $courrier->setStatus('En cours');
                    $courrier->setUpdatedAt(new \DateTime);

                    $observation = new TacheObservation;
                    $observation->setUser($this->getUser());
                    $observation->setCourrier($courrier);
                    $observation->setCreatedAt(new \DateTime);
                    $observation->setMessage('a assigné cette tâche à ' . $getUser->getNom() . ' ' . $getUser->getPrenom() . '. Status courrier: <span class="badge bg-yellow">En cours</span>');
                    $em->persist($observation);

                    $em->flush();
                    $this->addFlash('success', 'Enregistrer avec succès');
                    return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
                } else {
                    $this->addFlash('error', 'Utilisateur introuvable.');
                }
            } else {
                $this->addFlash('error', 'Une erreur s\'est produite: un gestionnaire est déjà assigné à ce courrier.');
            }
        }

        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat) {

                switch ($stat) {

                    case 'en_attente':
                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-blue">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'En cours':
                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'Traité':
                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-green">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'Clôturé':

                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }

                        $courrier->setStatus($stat);
                        $courrier->setUpdatedAt(new \DateTime);
                        $observation = new TacheObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
                        $em->persist($observation);
                        break;
                }

                $em->flush();
                return $this->redirectToRoute('show_tache', ['courrier' => $courrier->getId()]);
            }
        }
        /*
        */
        return $this->render('DBundle:Tache:show.html.twig', array(
            'courrier' => $courrier,
            'observations' => $observations,
            'form' => $form->createView(),
            'assigne_form' => $assigne_form->createView(),
            'isChefDeService' => $isChefDeService,
            'isChefSAI' => $isChefSAI,
        ));
    }

    public function newCatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = new TacheObjet;

        $form = $this->createForm(TacheObjetType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('list_tache_cat');
        }

        return $this->render('DBundle:Tache:categorie\new_cat.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function NewObservationAction(Tache $courrier, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([],['id' => 'desc']);
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isChefDeDirection = ($user->getId() == 4) ? true : false;

        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');
        $nifFilter = $request->query->get('nif');
        $rsFilter = $request->query->get('rs');
        $observation = new TacheObservation;
        $form = $this->createForm(TacheObservationType::class, $observation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tacheId = $courrier->getId();
            $tache = $em->getRepository(Tache::class)->findOneBy(['id' => $tacheId]);
            if ($tache){
                $tache->setStatus("En cours");
            }
            $observation->setUser($this->getUser());
            $observation->setCourrier($courrier);
            $observation->setCreatedAt(new \DateTime);
            $observation->setStatus("En cours");
            $em->persist($observation);
            $em->flush();
            $query = $em->getRepository(Tache::class)->findAll();
            return $this->render('DBundle:Tache:list.html.twig', array(
                "taches" => $query,
                'date_du'   => $request->query->get('date_du'),
                'date_au'   => $request->query->get('date_au'),
                'nifFilter' => $request->query->get('nif'),
                'rsFilter' => $request->query->get('rs'),
                'isChefSAI' => $isChefSAI,
                'isChefDeService' => $isChefDeService,
                'isChefDeDirection' => $isChefDeDirection,
            ));
        }
        return $this->render('DBundle:Tache:categorie\new_obs.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listCatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository(TacheObjet::class)->createQueryBuilder('c')->getQuery();

        $paginator  = $this->get('knp_paginator');
        $categorie = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('DBundle:Tache:categorie\list_cat.html.twig', array(
            'categories' => $categorie
        ));
    }

    public function editCatAction(Request $request, TacheObjet $nature)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(TacheObjetType::class, $nature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('list_tache_cat');
        }

        return $this->render('DBundle:Tache:categorie\edit_cat.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function autoCompleteNif3Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $nif = $_GET["term"];
            $taches = $em->getRepository(Tache::class)->getByNif($nif);
            $output = [];
            foreach ($taches as $tache) {
                $createdAt = date_format($tache->getCreatedAt(), 'd-m-Y');
                $temp_array = array();
                $temp_array['thisNif'] = $tache->getNif();
                $temp_array['raisonSoncial'] = $tache->getRs();
                $temp_array['useIt'] = $createdAt . ' - ' . $tache->getNif() . ' - ' . $tache->getRs() . '';
                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('_mes_taches');
    }
    public function autoCompleteRsoc3Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $rsoc = $_GET["term"];
            $taches = $em->getRepository(Tache::class)->getByRsoc($rsoc);
            $output = [];
            foreach ($taches as $tache) {
                $createdAt = date_format($tache->getCreatedAt(), 'd-m-Y');
                $temp_array = array();
                $temp_array['thisNif'] = $tache->getNif();
                $temp_array['raisonSoncial'] = $tache->getRs();
                $temp_array['useIt'] = $createdAt . ' - ' . $tache->getNif() . ' - ' . $tache->getRaisonSocial() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('_mes_taches');
    }

    public function justTacheAction($max = 5, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entrantAssigne = $em->getRepository(Entrant::class)->findBy(['status' => 'Assigné' || 'Transmis']);
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        $user = $this->getUser();
        $isMembreSAI = (($user->getService()->getId() == $sai->getService()->getId())) ? true : false;
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
        $entrantQuery = 0;
        $tacheQuery = 0;
        $listAssigne = false;
        if ($isChefDeService || $isChefSAI) {
            $status = 'Clôturé';
            if(($user->getService()->getId()) == 2){
            }else{
                $entrantQuery1 = $em->getRepository(Service::class)->find($user->getService()->getId())->getEntrant();
                $entrantQuery = [];
                foreach($entrantQuery1 as $entrant)
                {   
                    if($entrant->getStatus() == 'Transmis')
                    {
                        array_push($entrantQuery, $entrant);
                    }
                }
            }            

            $tacheQuery = $em->getRepository(Tache::class)->findBy(["gestionnaire" => $user->getId()]);
        } else {
            
            $listAssigne = true;
            $status = 'Clôturé';
            $entrantQuery = $em->getRepository(EntrantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
            $tacheQuery = $em->getRepository(Tache::class)->find($user->getId());
        }
        return $this->render(
            'AvanzuAdminThemeBundle:Navbar:justTache.html.twig',
            array(
                'entrantAssigne' => $entrantQuery,
                'tacheAssigne' => $tacheQuery,
                'listAssigne' => $listAssigne
            )
        );
    }

    public function notifyAction($max = 5, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entrantAssigne = $em->getRepository(Entrant::class)->findBy(['status' => 'Assigné' || 'Transmis']);
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        $user = $this->getUser();
        $isMembreSAI = (($user->getService()->getId() == $sai->getService()->getId())) ? true : false;
        $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
        $entrantQuery = 0;
        $tacheQuery = 0;
        $listAssigne = false;
        $nbreCourrier = $this->getUser()->getNbreCourrier();
        $nbreTache = $this->getUser()->getNbretache();
        if ($isChefDeService || $isChefSAI) {
            $status = 'Clôturé';
            if(($user->getService()->getId()) == 2){
            }else{
                $entrantQuery1 = $em->getRepository(Service::class)->find($user->getService()->getId())->getEntrant();
                $entrantQuery = [];
                foreach($entrantQuery1 as $entrant)
                {   
                    if($entrant->getStatus() == 'Transmis')
                    {
                        array_push($entrantQuery, $entrant);
                    }
                }

            }            

            $tacheQuery = $em->getRepository(Tache::class)->findBy(["gestionnaire" => $user->getId()]);
        } else {
            $listAssigne = true;
            $status = 'Clôturé';
            $entrantQuery = $em->getRepository(EntrantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
            $tacheQuery = $em->getRepository(Tache::class)->find($user->getId());
        }
        $entrantQuery = $nbreCourrier;
        $tacheQuery = $nbreTache;
        return $this->render(
            'AvanzuAdminThemeBundle:Navbar:justTache.html.twig',
            array(
                'nbreCourrier' => $nbreCourrier,
                'nbreTache' => $nbreTache,
                'entrantAssigne' => $entrantQuery,
                'tacheAssigne' => $tacheQuery,
                'listAssigne' => $listAssigne
            )
        );
    }

}
