<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use DBundle\Form\CourierEntrantType;
use DBundle\Form\CategorieCourierEntrantType;

use DBundle\Entity\User;
use DBundle\Entity\CourierEntrant;
use DBundle\Entity\CategorieCourierEntrant;

use DBundle\Entity\CourrierDispatching;
use DBundle\Entity\Service;
use DBundle\Entity\Tache;
use SIGTASBundle\Entity\DocCourrier;
use NIFBundle\Entity\Clients as NIFOnlineClients;


class CourierEntrantController extends Controller
{
    public function NewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $maxId = $em->getRepository(CourierEntrant::class)
            ->createQueryBuilder('e')->select('MAX(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $maxId = $maxId + 1;
        $CourierEntrant = new CourierEntrant;
        $CourierEntrant->setAdresse("Autres");

        $CourierEntrant->setRef("E-" . $maxId);
        $CourierEntrant->setCreatedAt(new \Datetime());

        $CourierEntrant->setGestionaire($user);
        $CourierEntrant->setEtat('Ouvert');

        $form = $this->createForm(CourierEntrantType::class, $CourierEntrant);
        //form with auto complete function, 
        //form with conditions :  if object is autre : creez un champ libre pour entrer l'objet
        //form with ajax function : search for nif or rs in database.

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($CourierEntrant->getObjet() == 'AUTRES') {
                $CourierEntrant->setObjet($CourierEntrant->getAdresse());
            }
            //nif valid
            // populate CourierEntrant
            $CourierEntrant->setAdresse("Adresse");
            // dump($CourierEntrant);
            // dump($request);
            // die();
            $em->persist($CourierEntrant);
            $em->flush();

            return $this->redirectToRoute('_list_entrant');
        }

        return $this->render('DBundle:CourierEntrant:new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function ListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $services = $em->getRepository(Service::class)->findAll();

        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        if ($date_du && $date_au) {

            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));

            $query = $em->getRepository(CourierEntrant::class)
                ->createQueryBuilder('c')
                ->where('c.date BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au)
                ->orderBy('c.id', 'DESC')
                ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $couriers = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        } else {
            // $sigtas_em = $this->getDoctrine()->getManager('sigtas');
            $query = $em->getRepository(CourierEntrant::class)
                ->createQueryBuilder('c')
                ->orderBy('c.id', 'desc')
                ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $couriers = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        }
        foreach ($couriers as $key => $courrier) {

            $courrier->forInfo = [];
            $forInfo = $em->getRepository(CourrierDispatching::class)->findBy([
                'docNo' => $courrier->getId(),
                'informative' => true,
            ]);
            $dispatching = $em->getRepository(CourrierDispatching::class)->findOneBy([
                'docNo' => $courrier->getId(),
                'informative' => false,
            ]);
            if ($dispatching != null) {
                $courrier->dispatching = $dispatching->getService();
            } else {
                $courrier->dispatching = null;
            }
            $courrier->forInfo = array_map(function ($e) {
                return is_object($e) ? $e->getService() : $e['service'];
            }, $forInfo);
        }

        return $this->render('DBundle:CourierEntrant:list.html.twig', [
            'courriers'  => $couriers,
            'services'  => $services,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
        ]);
    }

    public function ListPdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $services = $em->getRepository(Service::class)->findAll();

        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        if ($date_du && $date_au) {

            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));

            $query = $em->getRepository(CourierEntrant::class)
                ->createQueryBuilder('c')
                ->where('c.date BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au)
                ->orderBy('c.date', 'asc')
                ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $couriers = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        } else {
            // $sigtas_em = $this->getDoctrine()->getManager('sigtas');
            $query = $em->getRepository(CourierEntrant::class)
                ->createQueryBuilder('c')
                ->orderBy('c.date', 'desc')
                ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $couriers = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        }
        foreach ($couriers as $key => $courrier) {

            $courrier->forInfo = [];
            $forInfo = $em->getRepository(CourrierDispatching::class)->findBy([
                'docNo' => $courrier->getId(),
                'informative' => true,
            ]);
            $dispatching = $em->getRepository(CourrierDispatching::class)->findOneBy([
                'docNo' => $courrier->getId(),
                'informative' => false,
            ]);
            if ($dispatching != null) {
                $courrier->dispatching = $dispatching->getService();
            } else {
                $courrier->dispatching = null;
            }
            $courrier->forInfo = array_map(function ($e) {
                return is_object($e) ? $e->getService() : $e['service'];
            }, $forInfo);
        }

        return $this->render('DBundle:CourierEntrant:listpdf.html.twig', [
            'courriers'  => $couriers,
            'services'  => $services,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
        ]);
    }

    public function dispatchToServiceAction($courrier, Service $service)
    {

        $em = $this->getDoctrine()->getManager();
        // $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $courier = $em->getRepository(CourierEntrant::class)->find($courrier);
        if (!$courier) {
            return new Response("Courrier introuvable");
        }

        $dispacth = $em->getRepository(CourrierDispatching::class)->findOneBy([
            'docNo' => $courier->getId(),
            'informative' => false // the main courrier dispatching
        ]);
        if (!$dispacth) {
            $dispacth = new CourrierDispatching();
            $dispacth->setTraite(false);
            $dispacth->setInformative(false);
            $dispacth->setCloturer(false);
        }
        $dispacth->setGestionnaire($service->getChef());
        $dispacth->setDocNo($courier->getId());
        $dispacth->setService($service);

        $em->persist($dispacth);
        $em->flush();

        return new Response('Success');
    }

    public function forInfoToServiceAction($courrier, Service $service)
    {

        $em = $this->getDoctrine()->getManager();
        // $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $courier = $em->getRepository(CourierEntrant::class)->find($courrier);
        if (!$courier) {
            return new Response("Courrier introuvable");
        }

        $dispacth = $em->getRepository(CourrierDispatching::class)->findOneBy([
            'docNo' => $courier->getId(),
            'service' => $service,
            'informative' => true
        ]);
        if (!$dispacth) {
            $dispacth = new CourrierDispatching();
            $dispacth->setTraite(false);
            $dispacth->setCloturer(false);
            $dispacth->setInformative(true);
            $dispacth->setGestionnaire($service->getChef());
            $dispacth->setDocNo($courier->getId());
            $dispacth->setService($service);
            $em->persist($dispacth);
        } else { //delete for  info courriers
            $em->remove($dispacth);
        }

        $em->flush();

        return new Response('Success');
    }

    public function tachesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->getService()->getChef()->getId() == $user->getId()) {
            $query = $em->getRepository(CourrierDispatching::class)
                ->createQueryBuilder('c')
                ->where('c.service = :service')
                ->setParameter('service', $user->getService()->getId())
                ->orderBy('c.date', 'desc')
                ->getQuery();
        } else {
            $query = $em->getRepository(CourrierDispatching::class)
                ->createQueryBuilder('c')
                ->where('c.gestionnaire = :gestionnaire')
                ->setParameter('gestionnaire', $user)
                ->orderBy('c.date', 'desc')
                ->getQuery();
        }



        $paginator  = $this->get('knp_paginator');
        $courriers = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );
        //foreach ($courriers as $key => $courrier){
        //  $courrier->doc = $em->getRepository(CourierEntrant::class)->findOneBy([
        //    'id' => $courrier->getDocNo()
        //]);
        //}

        return $this->render('DBundle:GestionDesActivites:MesTaches.html.twig', array(
            'courriers' => $courriers,
        ));
    }

    public function dispatchToGestionnaireAction($courrier, User $gestionnaire)
    {
        $em = $this->getDoctrine()->getManager();

        $courier = $em->getRepository(CourrierDispatching::class)->findOneBy([
            'docNo' => $courrier
        ]);

        if (!$courier) {
            return new Response('error', Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getUser();

        if ($courier->getService()->getId() != $user->getService()->getId()) {
            return new Response('error', Response::HTTP_BAD_REQUEST);
        }

        if ($user->getService()->getChef()->getId() != $user->getId()) {
            return new Response('error', Response::HTTP_BAD_REQUEST);
        }

        $courier->setGestionnaire($gestionnaire);
        $em->flush();
        return new Response('Success');
    }

    public function cloturerTicketAction(CourrierDispatching $courrier)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if ($courrier->getService()->getId() != $user->getService()->getId()) {
            // return new Response('<p class="alert alert-danger">Erreur</p>');
            return $this->redirectToRoute('taches');
        }

        // if ($user->getService()->getChef()->getId() != $user->getId()) {
        //     return new Response('<p class="alert alert-danger">Vous n\'avez pas le droit de cloturer cet courrier</p>');
        // }

        // if (!$courrier->getTraite()) {
        //     return new Response('<p class="alert alert-danger">Erreur: cet courrier n\'est pas encore traité</p>');
        // }

        $courrier->setCloturer(!$courrier->getCloturer());
        $em->flush();
        //return new Response('<p class="alert alert-success">Success</p>');
        return $this->redirectToRoute('taches');
    }

    public function marquerCommeTraiterAction(CourrierDispatching $courrier)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if ($courrier->getService()->getId() != $user->getService()->getId()) {
            return new Response('<p class="alert alert-danger">Erreur</p>');
        }

        $courrier->setTraite(true);
        $em->flush();
        return new Response('<p class="alert alert-success">Success</p>');
    }

    public function getCourrierAction($courrier)
    {
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $courier = $em->getRepository(CourierEntrant::class)->find($courrier);
        if (!$courier) {
            return new Response("Courrier introuvable");
        }

        $response = '';
        $response .= '<table class="table table-striped table-hover"><tbody>';
        $response .= '<tr><th>Référence</th><td>' . $courier->getId() . '</td></tr>';
        $response .= '<tr><th>NIF</th><td>' . $courier->getNif() . '</td></tr>';
        $response .= '<tr><th>Nom ou raison social</th><td>' . $courier->getRs() . '</td></tr>';
        $response .= '<tr><th>Receptioné par</th><td>' . $courier->getGestionaire()->getNom() . '</td></tr>';
        $response .= '<tr><th>Date</th><td>' . $courier->getCreatedAt()->format('d/m/Y à H:i:s') . '</td></tr>';

        $dispacth = $em->getRepository(CourrierDispatching::class)->findOneBy([
            'docNo' => $courier->getId()
        ]);

        $user = $this->getUser();
        $chefDeService = false;
        if ($user->getService()->getChef()->getId() == $user->getId()) {
            $chefDeService = true;
            $gestionnaires = $user->getService()->getUsers();

            // <th>Dispatcher au</th>
            // <td>
            //     <select class="chooseService form-control" data-rslt = "status{{ courrier.id }}">
            //         <option value="" disabled selected>Choisissez un service</option>
            //         {% for service in services %}
            //             <option value="{{ path('_courrier_dispatching_to_service', {'courrier': courrier.id, 'service': service.id}) }}">{{ service.nom }}</option>
            //         {% endfor %}
            //     </select>
            //     <p id="status{{ courrier.id }}"></p>
            // </td>
            $services = $em->getRepository(Service::class)->findAll();
            $response .= '<tr>';
            $response .= '<th>Service du</th><td><select class="chooseService form-control" data-rslt ="status{{ courrier.id }}" data-status="#status' . $courrier->getId() . '" data-box="#box' . $courrier->getId() . '">';
            $response .= '<option value="" disabled selected>Choisissez un service</option>';
            foreach ($services as $key => $service) {
                $url = $this->generateUrl('_courrier_dispatching_to_service', [
                    'courrier' => $courier->getId(),
                    'service' => $service->getId()
                ]);

                $selected = "";
                if ($user->getService()->getId() == $service->getId()) {
                    $selected = "selected";
                }
                $response .= '<option value="' . $url . '" ' . $selected . '>' . $service->getNom() . '</option>';
            }

            $response .= '</select></td></tr>';

            $response .= '<tr><th>Gestionnaire</th><td><select class="chooseGestionnaire form-control" data-status="#status' . $courrier->getId() . '" data-box="#box' . $courrier->getId() . '">
            <option value="" disabled>Choisissez un gestionnaire</option>';
            foreach ($gestionnaires as $key => $gestionnaire) {
                $url = $this->generateUrl('_courrier_dispatching_to_gestionnaire', [
                    'courrier' => $courier->getId(),
                    'gestionnaire' => $gestionnaire->getId()
                ]);

                $selected = "";

                if ($dispacth && $dispacth->getGestionnaire() != null) {
                    if ($dispacth->getGestionnaire()->getId() == $gestionnaire->getId()) {
                        $selected = "selected";
                    }
                }

                $response .= '<option value="' . $url . '" ' . $selected . '>' . $gestionnaire->getNom() . '</option>';
            }
            $response .= '</select>';
            $response .= '<div id="status' . $courier->getId() . '"></div></tr></s>';
        } else {
            $chefDeService = false;
            $chef = $user->getService()->getChef();
            $response .= '<tr><th>Gestionnaire</th><td><select class="chooseGestionnaire form-control" data-status="#status' . $courrier->getId() . '" data-box="#box' . $courrier->getId() . '">
            <option value="" disabled>Choisissez un gestionnaire</option>';

            $url = $this->generateUrl('_courrier_dispatching_to_gestionnaire', [
                'courrier' => $courier->getId(),
                'gestionnaire' => $chef->getId()
            ]);

            $selected = "";
            $response .= '<option value="' . $url . '" selected>' . $user->getNom() . '</option>';
            $response .= '<option value="' . $url . '" ' . $selected . '>' . $chef->getNom() . '</option>';
            $response .= '</select>';
            $response .= '<div id="status' . $courier->getId() . '"></div></td>';
        }

        $response .= '</tbody></table>';
        $response .= '<div class="row">';
        // $response .= '<p class="col-md-6">';
        // $href = $this->generateUrl('_courrier_marquer_comme_traiter', ['courrier' => $dispacth->getId()]);
        // $response .='<a href="'.$href.'" class="btn btn-primary" style="width: 100%;">Marquer comme traite</a></p>';
        // $response .= '<p class="col-md-6">';

        $disabled = "";
        $href = $this->generateUrl('_courrier_close', ['courrier' => $dispacth->getId()]);
        // if (!$chefDeService) {
        //     $disabled = "disabled";
        //  $href = "#";
        // }
        if ($dispacth->getCloturer()) {
            $response .= '<a href="' . $href . '" class="closeTicket btn btn-danger ' . $disabled . '" style="width: 100%;">Reouvrir le ticket</a></p>';
        } else {
            $response .= '<a href="' . $href . '" class="closeTicket btn btn-danger ' . $disabled . '" style="width: 100%;">Cloturer le ticket</a></p>';
        }

        $response .= '<p style="display: block;"> </p>';
        $response .= '<div id="closedstatus' . $courier->getId() . '"></div>';
        $response .= '</div>';

        return new Response($response);
    }

    public function NewCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $CourierEntrant = new CategorieCourierEntrant();

        $form = $this->createForm(CategorieCourierEntrantType::class, $CourierEntrant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($CourierEntrant);
            $em->flush();

            return $this->redirectToRoute('_list_categorie_entrant');
        }
        return $this->render('DBundle:CourierEntrant:new-categorie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function ListCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $date_du = $request->query->get('date_du');
        $date_au = $request->query->get('date_au');

        if ($date_du && $date_au) {

            $date_du = new \DateTime($request->query->get('date_du'));
            $date_au = new \DateTime($request->query->get('date_au'));

            $query = $em->getRepository(CategorieCourierEntrant::class)
                ->createQueryBuilder('n')
                ->innerJoin('n.couriers', 'c')
                ->where('c.date BETWEEN :date_du AND :date_au')
                ->setParameter('date_du', $date_du)
                ->setParameter('date_au', $date_au)
                ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $categories = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );

            foreach ($categories as $key => $categorie) {
                $req = $em->getRepository(CourierEntrant::class)
                    ->createQueryBuilder('c')
                    ->select('COUNT(c.id)')
                    ->where('c.date BETWEEN :date_du AND :date_au')
                    ->andWhere('c.categorie = :cateogorie')
                    ->setParameter('date_du', $date_du)
                    ->setParameter('date_au', $date_au)
                    ->setParameter('cateogorie', $categorie->getId())
                    ->getQuery()->getSingleScalarResult();
                $categorie->setNbr($req);
            }
        } else {
            $query = $em->getRepository(CategorieCourierEntrant::class)
                ->createQueryBuilder('c')
                ->getQuery();

            $paginator  = $this->get('knp_paginator');
            $categories = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                20
            );
        }

        return $this->render('DBundle:CourierEntrant:list-categorie.html.twig', [
            'categories' => $categories,
            'date_du'   => $request->query->get('date_du'),
            'date_au'   => $request->query->get('date_au'),
        ]);
    }

    public function showAction(CourierEntrant $courrier, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $sai = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $courrier->setObservationContent('ids');
        //$courrierDocNo = $courrier->getNumeroCourrier();
        $courrierDocNo = $courrier->getId();

        $docCourrier = $this->getCourrier($courrierDocNo);
        $docCourrierEntrant = $em->getRepository(CourierEntrant::class)->findOneBy(array('id' => $courrierDocNo));
        if ($sai) {
            $isChefSAI = (($sai->getService() && $sai->getService()->getChef()->getId() == $user->getId())) ? true : false;
        }
        $isChefDeService = ($user->getService()->getChef() && $user->getService()->getChef()->getId() == $user->getId()) ? true : false;
        $isMembreDirection = ($user->getService()->getId() == 4) ? true : false;
        $isUserConcerned = ($docCourrierEntrant->getService() == $user->getService()) ? true : false;

        $defaultData = ['message' => 'Type your message here'];
        $defaultStatus = "Nouveau";
        $defaultPriority = "Normal";

        $doc = $em->getRepository('DBundle:EntrantObservation');
        $docCourrierObseration = $doc->findByCourrier($courrierDocNo);
        if (!$docCourrierObseration) {
            $delay = 0;
            header("Refresh: $delay;");

            $observation = new EntrantObservation;
            $observation->setStatus('Nouveau');
            $observation->setService($user->getService());
            $observation->setMessage('En attente de dispatch');
            $observation->setUser($this->getUser());
            $observation->setCourrier($courrierDocNo);
            $observation->setCreatedAt(new \DateTime);
            $em->persist($observation);
            $em->flush();

            if ($request->getMethod() == 'GET') {

                $dispatchingService = $request->query->get('dispatch');
                $entrantService = $em->getRepository(Service::class)->findOneBy(array('nom' =>   $dispatchingService));
                if ($entrantService && $isChefSAI) {
                    $docCourrierEntrant->setUpdatedAt(new \DateTime);
                    $docCourrierEntrant->setDelegationDate(new \DateTime);
                    $docCourrierEntrant->setTraitementDate(null);
                    $observation = new EntrantObservation;
                    $observation->setStatus('Transmis');
                    $observation->setService($user->getService());
                    $observation->setUser($this->getUser());
                    $observation->setCourrier($courrierDocNo);
                    $observation->setCreatedAt(new \DateTime);
                    $observation->setMessage('<span class="text-green">a dispatché ce courrier au ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                    $em->persist($observation);
                    $em->flush();

                    $docCourrierEntrant->setService($entrantService);
                    $docCourrierEntrant->setStatus('Transmis');
                    $observation = new EntrantObservation;
                    $observation->setStatus('Transmis');
                    $observation->setService($entrantService);
                    $observation->setUser($entrantService->getChef());
                    $observation->setCourrier($courrierDocNo);
                    $observation->setCreatedAt(new \DateTime);
                    $observation->setMessage('<span class="text-green">courrier en attente de réception ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                    $em->persist($docCourrierEntrant);
                    $em->persist($observation);
                    $em->flush();
                }
                $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                    'docNo' => $courrierDocNo,
                    'informative' => false
                ]);

                if (!$dispatch) {
                    $dispatch = new CourrierDispatching();
                    $dispatch->setTraite(false);
                    $dispatch->setInformative(false);
                    $dispatch->setCloturer(false);
                    if (($entrantService->getChef())) {
                    } else {
                        $delay = 0;
                        header("Refresh: $delay;");
                        $dispatch->setGestionnaire($entrantService->getChef());
                        $delay = 0;
                        header("Refresh: $delay;");
                    }

                    $dispatch->setDocNo($docCourrierEntrant->getCourrierId());
                    $dispatch->setService($entrantService);

                    $em->persist($dispatch);
                }
                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                return $this->render('DBundle:CourierEntrant:show.html.twig', array(
                    'request' => $request,
                    'courrier' => $courrier,
                    'entrant' => $docCourrierEntrant,
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isMembreDirection' => $isMembreDirection,
                    'observations' => $observations,
                ));
            }
        }

        if ($docCourrierObseration) {
            if (!$isChefDeService && !$isChefSAI && !$isMembreDirection) // Inspecteur ou secretariat
            {
                if ($request->getMethod() == 'GET') {
                    $stat = $request->query->get('changestatus');

                    if ($stat == 'Traité') {
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $docCourrierEntrant->setTraitementDate(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Traité');
                        $observation->setService($docCourrierEntrant->getService());
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-green">' . $stat . '</span>');
                        $em->persist($observation);

                        /// nouvel ajout
                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'traite' => 0
                        ]);
                        if ($dispatch) {
                            $dispatch->setTraite(true);
                        }

                        $em->flush();

                        return $this->redirectToRoute('list_entrant');
                    }
                }
                $services = $em->getRepository(Service::class)->findAll();

                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                return $this->render('DBundle:CourierEntrant:show.html.twig', array(
                    'courrier' => $courrier,
                    'entrant' => $docCourrierEntrant,
                    'observations' => $observations,
                    'services' => $services,
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isMembreDirection' => $isMembreDirection,
                ));
            }

            //Chef de SAI
            if ($isChefSAI && $request->getMethod() == 'GET') {
                $form = $this->createFormBuilder($defaultData)
                    ->add('service', EntityType::class, [
                        'placeholder' => 'Choisissez',
                        'class' => Service::class,
                        'choice_label' => 'nom',
                        'multiple' => false,
                        'expanded' => false,
                    ])/*
                    ->add('pourInfo', EntityType::class, [
                        'placeholder' => 'Choisissez', 
                        'class' => Service::class,
                        'choice_label' => 'nom',
                        'multiple' => true,
                        'expanded' => false,
                    ])*/
                    ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
                    ->getForm();

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                    if (!$isChefSAI && !$isMembreDirection) {
                        throw $this->createNotFoundException('Seul le chef du Service Accueil et Information où la direction peut dispatcher les courriers.');
                    }

                    $courrier_service = $courrier->getService();
                    if (!$courrier_service || $courrier_service && $isChefSAI) {

                        $serviceName = $request->request->get('form')['service'];
                        $service = $em->getRepository(Service::class)->find($serviceName);
                        /*
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
                        */

                        // nouvel ajout
                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'informative' => false // the main courrier dispatching
                        ]);
                        if (!$dispatch) {
                            $dispatch = new CourrierDispatching();
                            $dispatch->setTraite(false);
                            $dispatch->setInformative(false);
                            $dispatch->setCloturer(false);
                            $dispatch->setGestionnaire($service->getChef());
                            $dispatch->setDocNo($docCourrierEntrant->getDocNo());
                            //$dispatch->setDocNo($courrier->getDocNo());
                            $dispatch->setService($service);

                            $em->persist($dispatch);
                        }

                        // fin nouvel ajout

                        // $service = $em->getRepository(Service::class)->find($service);
                        /*$entrant = $em->getRepository(Entrant::class)->findOneBy(
                            array('courrierId' => $courrierDocNo )
                        );*/
                        $courrier->setService($courrier_service);
                        //$entrant->setService($service);
                        // $courrier->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $courrier->setStatus('Transmis');
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($user->getService());
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">a dispatché ce courrier au ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                        $em->persist($observation);
                        $em->flush();

                        $courrier->setService($service);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($service);
                        $observation->setUser($service->getChef());
                        $observation->setCourrier($courrierDocNo);
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('<span class="text-green">courrier en attente de réception ' . $service->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                        $em->persist($observation);
                        $em->flush();

                        // return $this->redirectToRoute('list_entrant');
                        return $this->redirectToRoute('_show_entrant', ['courrier' => $courrier->getDocNo()]);
                    } else {
                        //return $this->redirectToRoute('list_entrant');
                        return $this->redirectToRoute('_show_entrant', ['courrier' => $courrier->getDocNo()]);
                    }
                }

                if ($request->getMethod() == 'GET') {
                    $dispatchingService = $request->query->get('dispatch');
                    $entrantService = $em->getRepository(Service::class)->findOneBy(array('nom' =>   $dispatchingService));

                    if ($entrantService) {
                        //Création des observations pour le dispatch
                        $courrier_service = $courrier->getService();

                        if (!$courrier_service || $courrier_service && $isChefSAI) {
                            $courrier->setService($courrier_service);
                            //$courrier->setService($service);
                            //$courrier->dispatch = $entrantService;
                            //$courrier->setUpdatedAt(new \DateTime);
                            $courrier->setDelegationDate(new \DateTime);
                            $docCourrierEntrant->setTraitementDate(null);
                            $courrier->setStatus('Transmis');
                            $observation = new EntrantObservation;
                            $observation->setStatus('Transmis');
                            $observation->setService($user->getService());
                            $observation->setUser($this->getUser());
                            $observation->setCourrier($courrierDocNo);
                            $observation->setCreatedAt(new \DateTime);
                            $observation->setMessage('<span class="text-green">a dispatché ce courrier au ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');

                            $em->persist($observation);
                            $em->flush();

                            $docCourrierEntrant->setService($entrantService);
                            $docCourrierEntrant->setStatus('Transmis');
                            $observation = new EntrantObservation;
                            $observation->setStatus('Transmis');
                            $observation->setService($entrantService);
                            $observation->setUser($entrantService->getChef());
                            $observation->setCourrier($courrierDocNo);
                            $observation->setCreatedAt(new \DateTime);
                            $observation->setMessage('<span class="text-green">courrier en attente de réception ' . $entrantService->getNom() /*.'. Status courrier: Transmis*/ . '</span>');
                            $em->persist($docCourrierEntrant);
                            $em->persist($observation);
                            $em->flush();
                        }
                    } else {
                        $entrantService = $user->getService();
                    }

                    $stat = $request->query->get('changestatus');
                    if ($stat != 'Ferme') {
                        // EntrantObservationType
                        // Observation formulaire
                        // Liste observations
                        $observation = new EntrantObservation;
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier);
                        $observation->setCreatedAt(new \DateTime);
                        $formulaire_observation = $this->createForm(EntrantObservationType::class, $observation);
                        $formulaire_observation->handleRequest($request);

                        if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                            $courrier->setUpdatedAt(new \DateTime);
                            $em->persist($observation);
                            $em->flush();
                            return $this->redirectToRoute('_show_entrant', ['courrier' => $courrier->getDocNo()]);
                        }
                    }
                }

                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                // Services liste
                $services = $em->getRepository(Service::class)->findAll();
                return $this->render('DBundle:CourierEntrant:show.html.twig', array(
                    'courrier' => $courrier,
                    'observations' => $observations,
                    'entrant' => $docCourrierEntrant,
                    'services' => $services,
                    'form' => $form->createView(),
                    //'formulaire_observation' => $formulaire_observation->createView(),
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isMembreDirection' => $isMembreDirection,
                    // 'form_direction' => $form_direction->createView()
                ));
            }

            // Assigner ce courrier à un gestionnaire
            //Si assignation précedente est inspecteur

            if ($isChefDeService && !$docCourrierEntrant->getGestionnaire()) {
                if ($request->getMethod() == 'POST') {
                    $priorityGet = $request->request->get('priority');

                    if ($request->request->get('gestionnaire')) {

                        if (!$isChefDeService) {
                            $this->addFlash('error', 'Seul le chef de service "' . $courrier->getService()->getNom() . '" peut attribuer ce courrier à un gestionnaire.');
                        }

                        $courrier_gestionnaire = $docCourrierEntrant->getGestionnaire();

                        if (!$courrier_gestionnaire) {

                            $gestionnaireData = $request->request->get('gestionnaire');
                            $getUser = $em->getRepository(User::class)->find($gestionnaireData);
                            $attributionDesc = $request->request->get('attribution');

                            if ($getUser) {
                                $docCourrierEntrant->setAttribution($attributionDesc);
                                $docCourrierEntrant->setGestionnaire($getUser);
                                $docCourrierEntrant->setStatus('Assigné');
                                $docCourrierEntrant->setUpdatedAt(new \DateTime);
                                $docCourrierEntrant->setService($getUser->getService());
                                $docCourrierEntrant->setPriority($priorityGet);
                                $observation = new EntrantObservation;
                                //$observation->setAttribution($request->request->get('attribution'));
                                $observation->setService($docCourrierEntrant->getService());
                                $observation->setStatus('Assigné');
                                $observation->setUser($this->getUser());
                                $observation->setCourrier($courrier->getDocNo());
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('a assigné ce courrier à ' . $getUser->getNom() . ' ' . $getUser->getPrenom()/*.'. Status courrier: <span class="badge bg-yellow">Assigné*/ . '</span>');
                                $em->persist($observation);

                                $observation = new EntrantObservation;
                                $observation->setService($docCourrierEntrant->getService());
                                $observation->setStatus('En cours');
                                $observation->setUser($getUser);
                                $observation->setCourrier($courrier->getDocNo());
                                $observation->setCreatedAt(new \DateTime);
                                $observation->setMessage('Traitement du courrier en cours');
                                //$observation->setAttribution($request->request->get('attribution'));
                                $em->persist($observation);
                                $em->flush();
                                /// nouvel ajout

                                $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                                    'docNo' => $courrier->getDocNo(),
                                    'gestionnaire' => $getUser // the main courrier dispatching
                                ]);
                                if (!$dispatch) {
                                    $dispatch = new CourrierDispatching();
                                    $dispatch->setTraite(false);
                                    $dispatch->setInformative(false);
                                    $dispatch->setCloturer(false);
                                    $dispatch->setGestionnaire($getUser);
                                    $dispatch->setDocNo($docCourrierEntrant->getCourrierId());
                                    $dispatch->setService($getUser->getService());
                                    $em->persist($dispatch);
                                    $em->flush();
                                }

                                $currentDispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                                    'docNo' => $courrier->getDocNo(),
                                    'traite' => 0
                                ]);
                                if ($currentDispatch) {
                                    $currentDispatch->setTraite(true);
                                    $em->flush();
                                }
                                // fin nouvel ajout

                                $this->addFlash('success', 'Enregistrer avec succès');
                                return $this->redirectToRoute('list_entrant', ['courrier' => $courrier->getDocNo()]);
                            } else {
                                $this->addFlash('error', 'Utilisateur introuvable.');
                            }
                        } else {
                            $this->addFlash('error', 'Une erreur s\'est produite: un gestionnaire est déjà assigné à ce courrier.');
                        }
                    }
                }

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

                    if (!$isChefDeService) {
                        $this->addFlash('error', 'Seul le chef de service "' . $courrier->getService()->getNom() . '" peut attribuer ce courrier à un gestionnaire.');
                    }

                    $courrier_gestionnaire = $docCourrierEntrant->getGestionnaire();

                    if (!$courrier_gestionnaire) {
                        $gestionnaireData = $request->request->get('form')['gestionnaire'];
                        $getUser = $em->getRepository(User::class)->find($gestionnaireData);
                        if ($getUser) {
                            $docCourrierEntrant->setGestionnaire($getUser);
                            $docCourrierEntrant->setStatus('Assigné');
                            $docCourrierEntrant->setUpdatedAt(new \DateTime);
                            $docCourrierEntrant->setService($getUser->getService());
                            $docCourrierEntrant->setAttribution($request->request->get('attribution'));
                            $observation = new EntrantObservation;
                            $observation->setService($docCourrierEntrant->getService());
                            $observation->setStatus('Assigné');
                            $observation->setUser($this->getUser());
                            $observation->setCourrier($courrier->getDocNo());
                            $observation->setCreatedAt(new \DateTime);
                            $observation->setMessage('a assigné ce courrier à ' . $getUser->getNom() . ' ' . $getUser->getPrenom()/*.'. Status courrier: <span class="badge bg-yellow">Assigné*/ . '</span>');
                            $em->persist($observation);

                            $observation = new EntrantObservation;
                            $observation->setService($docCourrierEntrant->getService());
                            $observation->setStatus('Assigné');
                            $observation->setUser($getUser);
                            $observation->setCourrier($courrier->getDocNo());
                            $observation->setCreatedAt(new \DateTime);
                            $observation->setMessage('Traitement du courrier Assigné');
                            $em->persist($observation);
                            // nouvel ajout

                            $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                                'docNo' => $courrier->getDocNo(),
                                'gestionnaire' => $this->getUser() // the main courrier dispatching
                            ]);
                            if (!$dispatch) {
                                $dispatch = new CourrierDispatching();
                                $dispatch->setTraite(false);
                                $dispatch->setInformative(false);
                                $dispatch->setCloturer(false);
                            }
                            $dispatch->setGestionnaire($getUser);
                            $dispatch->setDocNo($docCourrierEntrant->getCourrierId());
                            $dispatch->setService($docCourrierEntrant->getService());

                            $em->persist($dispatch);

                            // fin nouvel ajout

                            $em->flush();

                            $this->addFlash('success', 'Enregistré avec succès');
                            return $this->redirectToRoute('_show_entrant', ['courrier' => $courrier->getDocNo()]);
                        } else {
                            $this->addFlash('error', 'Utilisateur introuvable.');
                        }
                    } else {
                        $this->addFlash('error', 'Une erreur s\'est produite: un gestionnaire est déjà assigné à ce courrier.');
                    }
                }
                $services = $em->getRepository(Service::class)->findAll();
                $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);

                return $this->render('DBundle:CourierEntrant:show.html.twig', array(
                    'courrier' => $courrier,
                    'entrant' => $docCourrierEntrant,
                    'observations' => $observations,
                    'services' => $services,
                    //'assigne_form' => $assigne_form->createView(),
                    'isChefDeService' => $isChefDeService,
                    'isChefSAI' => $isChefSAI,
                    'isMembreDirection' => $isMembreDirection,
                    'isUserConcerned' => $isUserConcerned,
                ));
            }
        }
        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            //$stat = $request->request->get('changestatus');

            if ($stat) {
                switch ($stat) {
                    case 'Transmis':
                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $docCourrierEntrant->setDelegationDate(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Transmis');
                        $observation->setService($docCourrierEntrant->getService());
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-blue">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'Assigné':
                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Assigné');
                        $observation->setService($docCourrierEntrant->getService());
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-yellow">' . $stat . '</span>');
                        $em->persist($observation);
                        break;

                    case 'Traité':
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Traité');
                        $observation->setService($docCourrierEntrant->getService());
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-green">' . $stat . '</span>');
                        $em->persist($observation);

                        // nouvel ajout
                        $dispatch = $em->getRepository(CourrierDispatching::class)->findOneBy([
                            'docNo' => $courrier->getDocNo(),
                            'traite' => 0
                        ]);
                        if ($dispatch) {
                            $dispatch->setTraite(true);
                        }

                        $em->flush();

                        // fin nouvel ajout

                        break;

                    case 'Ferme':

                        if (!$isChefDeService) {
                            throw $this->createNotFoundException('Page introuvable.');
                        }
                        $docCourrierEntrant->setStatus($stat);
                        $docCourrierEntrant->setUpdatedAt(new \DateTime);
                        $observation = new EntrantObservation;
                        $observation->setStatus('Ferme');
                        $observation->setService($docCourrierEntrant->getService());
                        $observation->setUser($this->getUser());
                        $observation->setCourrier($courrier->getDocNo());
                        $observation->setCreatedAt(new \DateTime);
                        $observation->setMessage('a changé le status: <span class="badge bg-red">' . $stat . '</span>');
                        $em->persist($observation);

                        return $this->redirectToRoute('new_tache', []);

                        break;
                }
                $em->flush();

                //return $this->redirectToRoute('_show_entrant', [ 'courrier' => $courrier->getDocNo() ]);

                return $this->redirectToRoute('_list_entrant', []);
            }
        }
        if ($request->getMethod() == 'GET') {
            $stat = $request->query->get('changestatus');
            if ($stat != 'Ferme') {
                // EntrantObservationType
                // Observation formulaire
                // Liste observations
                $observation = new EntrantObservation;
                $observation->setUser($this->getUser());
                $observation->setCourrier($courrier->getDocNo());
                $observation->setCreatedAt(new \DateTime);
                $formulaire_observation = $this->createForm(EntrantObservationType::class, $observation);
                $formulaire_observation->handleRequest($request);

                if ($formulaire_observation->isSubmitted() && $formulaire_observation->isValid()) {
                    $courrier->setUpdatedAt(new \DateTime);
                    $em->persist($observation);
                    $em->flush();
                    return $this->redirectToRoute('_show_entrant', ['courrier' => $courrier->getDocNo()]);
                }
            }
        }
        $services = $em->getRepository(Service::class)->findAll();
        $observations =  $em->getRepository(EntrantObservation::class)->findBy(['courrier' => $courrier->getDocNo()]);


        return $this->render('DBundle:CourierEntrant:show.html.twig', array(
            'courrier' => $courrier,
            'entrant' => $docCourrierEntrant,
            'observations' => $observations,
            'services' => $services,
            //'assigne_form' => $assigne_form->createView(),
            'isChefDeService' => $isChefDeService,
            'isChefSAI' => $isChefSAI,
            'isMembreDirection' => $isMembreDirection,
            'isUserConcerned' => $isUserConcerned,
        ));
    }

    public function TruncatetableAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $userSystem = 'SuperAdmin';
        $user = $this->getUser()->getUsername();
        if($user == $userSystem)
        {
            $services = $em->getRepository(Tache::class)->toTruncate();
        }
        return $this->redirectToRoute('list_entrant');
    }
}
