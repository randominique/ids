<?php

namespace DBundle\Controller;

use DBundle\Entity\Missions;
use DBundle\Form\MissionsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use DBundle\Entity\User;
use DBundle\Entity\Service;
use DBundle\Entity\PourInfo;
use DBundle\Entity\Entrant;
use DBundle\Entity\Sortant;
use DBundle\Entity\EntrantObservation;
use DBundle\Entity\MissionObjet;
use DBundle\Entity\MissionObservation;
use DBundle\Form\MissionObservationType;
use DBundle\Entity\SaiSetting;

use DBundle\Form\TacheObjetType;

use Doctrine\Common\Collections\ArrayCollection;

class MissionsController extends Controller
{

  public function newAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $responsableQuery = $em->getRepository(User::class)->findBy(array('service' => $this->getUser()->getService()->getId()));

      $courrier = new Missions;
      $courrier->setStatus('Nouveau');
      $courrier->setAuteur($this->getUser());
      $courrier->setService($this->getUser()->getService());
      $courrier->setUpdatedAt(new \DateTime());
      $courrier->setCreatedAt(new \DateTime());
      $form = $this->createForm(MissionsType::class, $courrier);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

        $gestionnaire = $request->request->get('gestionnaire');
        $responsable = $em->getRepository(User::class)->find($gestionnaire);
        $courrier->setGestionnaire($responsable);
        $em->persist($courrier);

        $observation = new MissionObservation;
        $observation->setMessage($courrier->getObservationContent());
        // $observation->setUser($this->getUser());
        $observation->setUser($this->getUser());
        $observation->setCourrier($courrier);
        $observation->setCreatedAt(new \DateTime);
        $observation->setStatus($courrier->getStatus());
        $em->persist($observation);
        $em->flush();

        //   $userAssigne = $em->getRepository(User::class)->find($gestionnaire);
        //   if($userAssigne)
        //   {
        //       $userAssigne->setNbremission($userAssigne->getNbremission()+1);
        //       $em = $this->getDoctrine()->getManager();
        //       $em->flush();
        //   }

        return $this->redirectToRoute('list_mission');
      }

      return $this->render('DBundle:Missions:new.html.twig', array(
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
    //   $isChefDeService = ($courrier->getService() && $courrier->getService()->getChef() && $courrier->getService()->getChef()->getId() == $user->getId()) ? true : false;
      $isChefDeDirection = ($user->getId() == 4) ? true : false;

      $missionQuery = $em->getRepository('DBundle:Missions')
          ->createQueryBuilder('tac');

      if ($nifFilter) {
          $missionQuery
              ->andWhere('tac.nif LIKE :nifParam')
              ->setParameter('nifParam', '%' . $nifFilter . '%');
      }
      if ($rsFilter) {
          $missionQuery
              ->andWhere('tac.rs LIKE :rsParam')
              ->setParameter('rsParam', '%' . $rsFilter . '%');
      }
      if ($request->query->get('date_du') && $request->query->get('date_au')) {
          $missionQuery
              ->andWhere('tac.createdAt BETWEEN :date_du AND :date_au')
              ->setParameter('date_du', $request->query->get('date_du'))
              ->setParameter('date_au', $request->query->get('date_au'));
      }
      $missionQuery
          ->orderBy('tac.id', 'DESC');
      $missionQuery->getQuery();

      $paginator  = $this->get('knp_paginator');
      $Mission = $paginator->paginate(
          $missionQuery,
          $request->query->getInt('page', 1),
          20
      );

      $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
      if ($user->hasRole('ROLE_SUPER_ADMIN')) {
          $query = $em->getRepository(Missions::class)->findAll();    // Les super_admins peuvent voir toutes les tâches
      } elseif ($user->hasRole('ROLE_ADMIN')) {
          $query = $em->getRepository(Missions::class)->findBy(array(
              'service' => $user->getService()
          ));    // Les admins peuvent voir uniquement toutes les tâches du Service
      } else {
          $query = $em->getRepository(Missions::class)->findBygestionnaire($user->getId());
      }
      return $this->render('DBundle:Missions:list.html.twig', array(
          "missions" => $query,
          'date_du'   => $request->query->get('date_du'),
          'date_au'   => $request->query->get('date_au'),
          'isChefSAI' => $isChefSAI,
          'isChefDeService' => $isChefDeService,
          'isChefDeDirection' => $isChefDeDirection,
          'nifFilter' => $request->query->get('nif'),
          'rsFilter' => $request->query->get('rs'),
      ));
  }

  public function showAction(Missions $courrier, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
      $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
      $isChefDeService = ($courrier->getService() && $courrier->getService()->getChef() && $courrier->getService()->getChef()->getId() == $user->getId()) ? true : false;
      $isChefDeDirection = ($user->getId() == 4) ? true : false;

      $defaultData = ['message' => 'Type your message here'];

      $courrier->setObservationContent('Observations');

      $observation = new MissionObservation;
      $observation->setUser($this->getUser());
      $observation->setCourrier($courrier);
      $observation->setCreatedAt(new \DateTime);
      $formulaire_observation = $this->createForm(MissionObservationType::class, $observation);
      $formulaire_observation->handleRequest($request);

      if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
          $courrier->setUpdatedAt(new \DateTime);
          $em->persist($observation);
          $em->flush();
          return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
      }
      $observations =  $em->getRepository(MissionObservation::class)->findBy(['courrier' => $courrier->getId()]);
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
                      $observation = new MissionObservation;
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
                      $observation = new MissionObservation;
                      $observation->setUser($this->getUser());
                      $observation->setCourrier($courrier);
                      $observation->setCreatedAt(new \DateTime);
                      $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
                      $em->persist($observation);
                      break;

                  case 'Traité':
                      $courrier->setStatus($stat);
                      $courrier->setUpdatedAt(new \DateTime);
                      $observation = new MissionObservation;
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
                      $observation = new MissionObservation;
                      $observation->setUser($this->getUser());
                      $observation->setCourrier($courrier);
                      $observation->setCreatedAt(new \DateTime);
                      $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
                      $em->persist($observation);
                      break;
              }

              $em->flush();
              return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
          }

          return $this->render('DBundle:Missions:show.html.twig', array(
              'courrier' => $courrier,
              'observations' => $observations,
              'assigne_form' => $assigne_form->createView(),
              'isChefDeService' => $isChefDeService,
              'isChefSAI' => $isChefSAI,
          ));
      }
  }

  public function missionUpdateAction(Missions $courrier, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      $courrier->setObservationContent('ids');
      if ($request->getMethod() == 'GET') {
          $stat = $request->query->get('changestatus');
          if ($stat == 'Traité') {
            $courrier->setUpdatedAt(new \DateTime);
            $courrier->setStatus('Traité');
            $observation = new MissionObservation;
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

            return $this->redirectToRoute('list_mission', ['courrier' => $courrier->getId()]);
        }
        if ($stat == 'Clôturé') {
            $courrier->setUpdatedAt(new \DateTime);
            $courrier->setStatus('Clôturé');
            $observation = new MissionObservation;
            $observation->setUser($this->getUser());
            $observation->setCourrier($courrier);
            $observation->setMessage('a changé le status: <span class="badge bg-red">Clôturé</span>');
            $observation->setCreatedAt(new \DateTime);
            $observation->setStatus('Clôturé');
            $em->persist($observation);

            $em->flush();

            return $this->redirectToRoute('list_mission', ['courrier' => $courrier->getId()]);
        }

        }
      return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
  }

  public function showBackupAction(Missions $courrier, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      // $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
      // $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
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

          // if (!$isChefSAI) {
          //     throw $this->createNotFoundException('Seul le chef SAI peut dispatcher les courriers.');
          // }

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

              $observation = new MissionObservation;
              $observation->setUser($this->getUser());
              $observation->setCourrier($courrier);
              $observation->setCreatedAt(new \DateTime);
              $observation->setMessage('<span class="text-green">a dispatché ce courrier au ' . $service->getNom() . '. Status courrier: EN ATTENTE</span>');
              $em->persist($observation);

              $em->flush();

              return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
          } else {
              return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
          }
      }

      $observation = new MissionObservation;
      $observation->setUser($this->getUser());
      $observation->setCourrier($courrier);
      $observation->setCreatedAt(new \DateTime);
      $formulaire_observation = $this->createForm(MissionObservationType::class, $observation);
      $formulaire_observation->handleRequest($request);

      if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
          $courrier->setUpdatedAt(new \DateTime);
          $em->persist($observation);
          $em->flush();
          return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
      }
      $observations =  $em->getRepository(MissionObservation::class)->findBy(['courrier' => $courrier->getId()]);
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

                  $observation = new MissionObservation;
                  $observation->setUser($this->getUser());
                  $observation->setCourrier($courrier);
                  $observation->setCreatedAt(new \DateTime);
                  $observation->setMessage('a assigné cette tâche à ' . $getUser->getNom() . ' ' . $getUser->getPrenom() . '. Status courrier: <span class="badge bg-yellow">En cours</span>');
                  $em->persist($observation);

                  $em->flush();
                  $this->addFlash('success', 'Enregistrer avec succès');
                  return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
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
                      $observation = new MissionObservation;
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
                      $observation = new MissionObservation;
                      $observation->setUser($this->getUser());
                      $observation->setCourrier($courrier);
                      $observation->setCreatedAt(new \DateTime);
                      $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
                      $em->persist($observation);
                      break;

                  case 'Traité':
                      $courrier->setStatus($stat);
                      $courrier->setUpdatedAt(new \DateTime);
                      $observation = new MissionObservation;
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
                      $observation = new MissionObservation;
                      $observation->setUser($this->getUser());
                      $observation->setCourrier($courrier);
                      $observation->setCreatedAt(new \DateTime);
                      $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
                      $em->persist($observation);
                      break;
              }

              $em->flush();
              return $this->redirectToRoute('show_mission', ['courrier' => $courrier->getId()]);
          }
      }
      /*
      */
      return $this->render('DBundle:Mission:show.html.twig', array(
          'courrier' => $courrier,
          'observations' => $observations,
          'form' => $form->createView(),
          'assigne_form' => $assigne_form->createView(),
          'isChefDeService' => $isChefDeService,
          // 'isChefSAI' => $isChefSAI,
      ));
  }

  public function newCatAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $categorie = new MissionObjet;

      $form = $this->createForm(MissionObjetType::class, $categorie);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $em->persist($categorie);
          $em->flush();
          return $this->redirectToRoute('list_mission_cat');
      }

      return $this->render('DBundle:Mission:categorie\new_cat.html.twig', array(
          'form' => $form->createView()
      ));
  }

  public function nxObservationAction(Missions $courrier, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      $sai = $em->getRepository(SaiSetting::class)->findOneBy(
          [],
          [
              'id' => 'desc'
          ]
      );
      $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
      $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
      $isChefDeDirection = ($user->getId() == 4) ? true : false;

      $date_du = $request->query->get('date_du');
      $date_au = $request->query->get('date_au');
      $nifFilter = $request->query->get('nif');
      $rsFilter = $request->query->get('rs');
      $observation = new MissionObservation;
      $form = $this->createForm(MissionObservationType::class, $observation);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $missionId = $courrier->getId();
        $mission = $em->getRepository(Missions::class)->findOneBy(['id' => $missionId]);
        if ($mission){
            $mission->setStatus("En cours");
        }
        $observation->setUser($this->getUser());
        $observation->setCourrier($courrier);
        $observation->setCreatedAt(new \DateTime);
        $observation->setStatus("En cours");
        $em->persist($observation);
        $em->flush();
        $query = $em->getRepository(Missions::class)->findAll();
        return $this->render('DBundle:Missions:list.html.twig', array(
            "missions" => $query,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
            'nifFilter' => $request->query->get('nif'),
            'rsFilter' => $request->query->get('rs'),
            'isChefSAI' => $isChefSAI,
            'isChefDeService' => $isChefDeService,
            'isChefDeDirection' => $isChefDeDirection,
        ));
      }
      return $this->render('DBundle:Missions:categorie\new_obs.html.twig', array(
          'form' => $form->createView(),
      ));
  }

  public function listCatAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $query = $em->getRepository(MissionObjet::class)->createQueryBuilder('c')->getQuery();

      $paginator  = $this->get('knp_paginator');
      $categorie = $paginator->paginate(
          $query,
          $request->query->getInt('page', 1),
          20
      );

      return $this->render('DBundle:Mission:categorie\list_cat.html.twig', array(
          'categories' => $categorie
      ));
  }

  public function editCatAction(Request $request, MissionObjet $nature)
  {
      $em = $this->getDoctrine()->getManager();
      $form = $this->createForm(MissionObjetType::class, $nature);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $em->flush();
          return $this->redirectToRoute('list_mission_cat');
      }

      return $this->render('DBundle:Mission:categorie\edit_cat.html.twig', array(
          'form' => $form->createView()
      ));
  }

  public function autoCompleteNif3Action(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      if (isset($_GET["term"])) {
          $nif = $_GET["term"];
          $missions = $em->getRepository(Missions::class)->getByNif($nif);
          $output = [];
          foreach ($missions as $mission) {
              $createdAt = date_format($mission->getCreatedAt(), 'd-m-Y');
              $temp_array = array();
              $temp_array['thisNif'] = $mission->getNif();
              $temp_array['raisonSoncial'] = $mission->getRs();
              $temp_array['useIt'] = $createdAt . ' - ' . $mission->getNif() . ' - ' . $mission->getRs() . '';
              $output[] = $temp_array;
          }
          return new JsonResponse($output);
      }

      return $this->redirectToRoute('_mes_missions');
  }
  public function autoCompleteRsoc3Action(Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getUser();
      if (isset($_GET["term"])) {
          $rsoc = $_GET["term"];
          $missions = $em->getRepository(Missions::class)->getByRsoc($rsoc);
          $output = [];
          foreach ($missions as $mission) {
              $createdAt = date_format($mission->getCreatedAt(), 'd-m-Y');
              $temp_array = array();
              $temp_array['thisNif'] = $mission->getNif();
              $temp_array['raisonSoncial'] = $mission->getRs();
              $temp_array['useIt'] = $createdAt . ' - ' . $mission->getNif() . ' - ' . $mission->getRaisonSocial() . '';

              $output[] = $temp_array;
          }
          return new JsonResponse($output);
      }

      return $this->redirectToRoute('_mes_missions');
  }

  public function justMissionAction($max = 5, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $entrantAssigne = $em->getRepository(Entrant::class)->findBy(['status' => 'Assigné' || 'Transmis']);
      // $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
      $user = $this->getUser();
      // $isMembreSAI = (($user->getService()->getId() == $sai->getService()->getId())) ? true : false;
      // $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
      $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
      $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
      $entrantQuery = 0;
      $missionQuery = 0;
      $listAssigne = false;
      // if ($isChefDeService || $isChefSAI) {
      if ($isChefDeService) {
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

          $missionQuery = $em->getRepository(Missions::class)->findBy(["gestionnaire" => $user->getId()]);
      } else {
          
          $listAssigne = true;
          $status = 'Clôturé';
          $entrantQuery = $em->getRepository(EntrantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
          $missionQuery = $em->getRepository(Missions::class)->find($user->getId());
      }
      return $this->render(
          'AvanzuAdminThemeBundle:Navbar:justMission.html.twig',
          array(
              'entrantAssigne' => $entrantQuery,
              'missionAssigne' => $missionQuery,
              'listAssigne' => $listAssigne
          )
      );
  }

  public function notifyAction($max = 5, Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $entrantAssigne = $em->getRepository(Entrant::class)->findBy(['status' => 'Assigné' || 'Transmis']);
      // $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
      $user = $this->getUser();
      // $isMembreSAI = (($user->getService()->getId() == $sai->getService()->getId())) ? true : false;
      // $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
      $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
      $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
      $entrantQuery = 0;
      $missionQuery = 0;
      $listAssigne = false;
      $nbreCourrier = $this->getUser()->getNbreCourrier();
      $nbreMission = $this->getUser()->getNbremission();
      // if ($isChefDeService || $isChefSAI) {
      if ($isChefDeService) {
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

          $missionQuery = $em->getRepository(Missions::class)->findBy(["gestionnaire" => $user->getId()]);
      } else {
          $listAssigne = true;
          $status = 'Clôturé';
          $entrantQuery = $em->getRepository(EntrantObservation::class)->findBy(['user'=> $user],['createdAt' => 'DESC']);
          $missionQuery = $em->getRepository(Missions::class)->find($user->getId());
      }
      $entrantQuery = $nbreCourrier;
      $missionQuery = $nbreMission;
      return $this->render(
          'AvanzuAdminThemeBundle:Navbar:justMission.html.twig',
          array(
              'nbreCourrier' => $nbreCourrier,
              'nbreMission' => $nbreMission,
              'entrantAssigne' => $entrantQuery,
              'missionAssigne' => $missionQuery,
              'listAssigne' => $listAssigne
          )
      );
  }

  public function addAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $mission = new Missions();

    $form = $this->createForm(MissionsType::class, $mission);

    $form->handleRequest($request);

    if($form->isSubmitted())
    {
      $em->persist($mission);
      $em->flush();

      return new Response('Mission créée !!!');
    }

    $formView = $form->createView();

    return $this->render('DBundle:Missions:missionAdd.html.twig', array(
      'form' => $formView,
    ));
  //   return $this->render('DBundle:Missions:list.html.twig', array(
  //     "missions" => $query,
  //     'date_du'   => $request->query->get('date_du'),
  //     'date_au'   => $request->query->get('date_au'),
  //     'isChefSAI' => $isChefSAI,
  //     'isChefDeService' => $isChefDeService,
  //     'isChefDeDirection' => $isChefDeDirection,
  //     'nifFilter' => $request->query->get('nif'),
  //     'rsFilter' => $request->query->get('rs'),
  // ));

  }

//   public function NxObservationAction(Missions $courrier, Request $request)
//   {
//       $em = $this->getDoctrine()->getManager();
//       $user = $this->getUser();
//       $sai = $em->getRepository(SaiSetting::class)->findOneBy(
//           [],
//           [
//               'id' => 'desc'
//           ]
//       );
//       $isChefSAI = (($sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
//       $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
//       $isChefDeDirection = ($user->getId() == 4) ? true : false;

//       $date_du = $request->query->get('date_du');
//       $date_au = $request->query->get('date_au');
//       $nifFilter = $request->query->get('nif');
//       $rsFilter = $request->query->get('rs');
//       $observation = new MissionObservation;
//       $form = $this->createForm(MissionObservationType::class, $observation);
//       $form->handleRequest($request);
//       if ($form->isSubmitted() && $form->isValid()) {
//           $observation->setUser($this->getUser());
//           $observation->setCourrier($courrier);
//           $observation->setCreatedAt(new \DateTime);
//           $observation->setStatus("Nouveau");
//           $em->persist($observation);
//           $em->flush();
//           $query = $em->getRepository(Missions::class)->findAll();
//           return $this->render('DBundle:Missions:list.html.twig', array(
//               "missions" => $query,
//               'date_du'   => $request->query->get('date_du'),
//               'date_au'   => $request->query->get('date_au'),
//               'nifFilter' => $request->query->get('nif'),
//               'rsFilter' => $request->query->get('rs'),
//               'isChefSAI' => $isChefSAI,
//               'isChefDeService' => $isChefDeService,
//               'isChefDeDirection' => $isChefDeDirection,
//           ));
//       }
//       return $this->render('DBundle:Missions:categorie\nx_obs.html.twig', array(
//           'form' => $form->createView(),
//       ));
//   }

}