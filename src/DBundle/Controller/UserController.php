<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPExcel;
use PHPExcel_IOFactory;
use Symfony\Component\DependencyInjection\Dumper\Dumper;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class UserController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $systemUser = 'admin';
        $nomFilter = $request->query->get('nom');
        $query = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->Where('u.username <> :acces')
            ->setParameter('acces', $systemUser)
            ->orderBy('u.service', 'asc')
            ->addOrderBy('u.id', 'asc')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            18
        );

        return $this->render('DBundle:User:index.html.twig', array(
            'users' => $pagination,
            'nomFilter' => $request->query->get('nom'),
        ));
    }
    //pdf
    public function indexpdfAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(User::class)->findAll();
        // $options = new Options();
        // $options->set('isRemoteEnabled', true);
        // $options->set('defaultFont', 'Roboto');
        // $dompdf = new Dompdf($options);
        // $data = array(
        //   'headline' => 'my headline'
        // );
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetAuthor('Informatics Development Software');
        $pdf->SetTitle(('Liste des utilisateurs'));
        $pdf->SetSubject('Our Code World Subject');
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('helvetica', '', 8, '', true);
        //$pdf->SetMargins(20,20,40, true);
        $pdf->AddPage('P', 'A4');

        $filename = 'ourcodeworld_pdf_demo';
        $html = $this->render('DBundle:User:indexpdf.html.twig', array(
            'users' => $query,

        ));
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename . ".pdf", 'I'); // T

        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'portrait');
        // $dompdf->render();
        // $dompdf->stream("ExportPdf.pdf", [
        //     "Attachment" => true
        // ]);
    }
    public function indexExcelAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(User::class)->findAll();
        // foreach ($query as $query){
        //     dump($query->getService()->getnom());
        // }die();
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("Dominique")
            ->setLastModifiedBy("Dominique")
            ->setTitle("Liste des utilisteurs")
            ->setSubject("Personnel de la DGE")
            ->setDescription("Ce fichier contient les codes d'accès du personnel de la DGE")
            ->setKeywords("Personnel")
            ->setCategory("ids.xls");
        $count = 6;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'DIRECTION DES GRANDES ENTREPRISES ');

        $phpExcelObject->setActiveSheetIndex(0)
        ->setCellValue('A3', 'LISTE DES UTILISATEURS ');

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A5', 'Nom ')
            ->setCellValue('B5', 'Prenom ')
            ->setCellValue('C5', 'Email ')
            ->setCellValue('D5', 'Service ')
            ->setCellValue('E5', 'UserName ');
            // ->setCellValue('F5', 'Mot de passe ');

        foreach ($query as $query) {

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A' . $count, $query->getNom())
                ->setCellValue('B' . $count, $query->getPrenom())
                ->setCellValue('C' . $count, $query->getemail())
                ->setCellValue('D' . $count, $query->getService()->getnom())
                ->setCellValue('E' . $count, $query->getUsername());
                // ->setCellValue('F' . $count, $query->getPassword());
            $count++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Liste des utilisateurs.xlsx'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function addRoleAction($id, $role)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserBy(['id' => $id]);
        if ($user) {

            $privilege = '';
            switch ($role) {
                case '1':
                    $privilege = 'Utilisateur';
                    break;
                case '2':
                    $privilege = 'Admin';
                    break;
                case '3':
                    $privilege = 'Super admin';
                    break;
                // case '4':
                //     $privilege = 'System';
                //     break;
                }

            switch ($role) {
                case '1':
                    $role = 'ROLE_USER';
                    break;
                case '2':
                    $role = 'ROLE_ADMIN';
                    break;
                case '3':
                    $role = 'ROLE_SUPER_ADMIN';
                    break;
                // case '4':
                //     $role = 'ROLE_SYSTEM';
                //     break;
            }

            $roles = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_SYSTEM'];
            if (in_array($role, $roles)) {

                if ($role == 'ROLE_SYSTEM') {
                    if (!$user->hasRole('ROLE_SUPER_ADMIN')) {
                        $user->addRole('ROLE_SUPER_ADMIN');
                    }
                }

                if ($role == 'ROLE_SUPER_ADMIN') {
                    if (!$user->hasRole('ROLE_ADMIN')) {
                        $user->addRole('ROLE_ADMIN');
                    }
                }

                // if (!$role == 'ROLE_SYSTEM') {
                    $user->addRole($role);
                    $userManager->updateUser($user);
                // }
            // return new Response("<p><a href='#' type='button' data-privilege='privilege".$user->getId()."' data-priv='priv".$user->getId()."' data-result='result".$user->getId()."' data-link='' class='pull-right addrole'>promouvoir</a>".$privilege."</p>");
                // {{ path('users_remove_role', { 'id': user.id, 'role': '3' }) }}

                if ($role == 'ROLE_ADMIN') {
                    return new JsonResponse(
                        [
                            'result' => "<p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_remove_role', array('id' => $user->getId(), 'role' => '2'), true) . "' class='pull-right text-danger removerole'>rétrograder</a>Admin</p>
                                <p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_add_role', array('id' => $user->getId(), 'role' => '3'), true) . "' class='pull-right addrole'>promouvoir</a>Super admin</p>",
                            'priv' => 'Admin'
                        ]
                    );
                }

                if ($role == 'ROLE_SUPER_ADMIN') {
                    return new JsonResponse(
                        [
                            'result' => "<p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_remove_role', array('id' => $user->getId(), 'role' => '3'), true) . "' class='pull-right text-danger removerole'>rétrograder</a>Super admin</p>",
                            'priv' => 'Super admin'
                        ]
                    );
                }

                if ($role == 'ROLE_SYSTEM') {
                    return new JsonResponse(
                        [
                            'result' => "<p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_remove_role', array('id' => $user->getId(), 'role' => '4'), true) . "' class='pull-right text-danger removerole'>rétrograder</a>System</p>",
                            'priv' => 'System'
                        ]
                    );
                }
            }
        } else {
            return new Response('<p class="alert alert-danger">Utilisateur introuvable</p>', Response::HTTP_BAD_REQUEST);
        }

        return new Response('<p class="alert alert-danger">Erreur</p>', Response::HTTP_BAD_REQUEST);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeRoleAction($id, $role)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->findUserBy(['id' => $id]);
        if ($user) {

            switch ($role) {
                case '1':
                    $role = 'ROLE_USER';
                    break;
                case '2':
                    $role = 'ROLE_ADMIN';
                    break;
                case '3':
                    $role = 'ROLE_SUPER_ADMIN';
                    break;
                case '4':
                    $role = 'ROLE_SYSTEM';
                    break;
            }

            $roles = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_SYSTEM'];
            if (in_array($role, $roles)) {
                $user->removeRole($role);
                $userManager->updateUser($user);
                $privilege = 'Utilisateur';
                if ($role == 'ROLE_SYSTEM') {
                    return new JsonResponse(
                        [
                            'result' => "
                                <p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_remove_role', array('id' => $user->getId(), 'role' => '3'), true) . "' class='pull-right text-danger removerole'>rétrograder</a>Super admin</p>
                                <p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_add_role', array('id' => $user->getId(), 'role' => '4'), true) . "' class='pull-right addrole'>promouvoir</a>Admin</p>
                                ",
                            'priv' => 'Admin'
                        ]
                    );
                } else {
                    if ($role == 'ROLE_SUPER_ADMIN') {
                        return new JsonResponse(
                            [
                                'result' => "
                                    <p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_remove_role', array('id' => $user->getId(), 'role' => '2'), true) . "' class='pull-right text-danger removerole'>rétrograder</a>Admin</p>
                                    <p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_add_role', array('id' => $user->getId(), 'role' => '3'), true) . "' class='pull-right addrole'>promouvoir</a>Super admin</p>
                                    ",
                                'priv' => 'Admin'
                            ]
                        );
                    } else {
                        return new JsonResponse(
                            [
                                'result' => "
                                    <p>Utilisateur</p>
                                    <p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_add_role', array('id' => $user->getId(), 'role' => '2'), true) . "' class='pull-right addrole'>promouvoir</a>Admin</p>
                                    <p><a href='#' type='button' data-privilege='privilege" . $user->getId() . "' data-priv='priv" . $user->getId() . "' data-result='result" . $user->getId() . "' data-link='" . $this->get('router')->generate('users_add_role', array('id' => $user->getId(), 'role' => '3'), true) . "' class='pull-right addrole'>promouvoir</a>Super admin</p>
                                    ",
                                'priv' => 'Utilisateur'
                            ]
                        );
                    }
                }
            }
        } else {
            return new Response('<p class="alert alert-danger">Utilisateur introuvable</p>', Response::HTTP_BAD_REQUEST);
        }

        return new Response('<p class="alert alert-danger">Erreur</p>', Response::HTTP_BAD_REQUEST);
    }

    public function profileAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm('DBundle\Form\UserType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            // return new Response('Modification effectuée !!!');
            return $this->redirectToRoute('d_homepage');
        }
        return $this->render('DBundle:User:profile.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    public function changePasswordAction(Request $request)
    {
        $user = $this->getUser();
        $userManager = $this->get('fos_user.user_manager');
        $form = $this->createForm('FOS\UserBundle\Form\Type\ChangePasswordFormType', $user);
        $form->setData($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $userManager->updateUser($user);
            /*return new Response('Modification effectuée !!!');*/
            return $this->redirectToRoute('d_homepage');
        }
        // return $this->render('@FOSUser/ChangePassword/change_password.html.twig',array(
        return $this->render('DBundle:User:password.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function addAction()
    {
        // on crée l'utilisateur
        $user = new User();
        // on récupère le formulaire
        $form = $this->createForm(UserType::class, $user);
        // on génère le HTML du formulaire créé
        $formView = $form->createView();
        // on rend la vue
        return $this->render('DBundle:add.html.twig', array('form' => $formView));
    }

    public function autoCompleteNomAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (isset($_GET["term"])) {
            $nom = $_GET["term"];
            $users = $em->getRepository(User::class)->getByNom($nom);
            $output = [];
            foreach ($users as $user) {
                $temp_array = array();
                $temp_array['thisPrenom'] = $user->getPrenom();
                $temp_array['nom'] = $user->getNom();
                $temp_array['useIt'] = $user->getPrenom() . ' - ' . $user->getNom() . '';

                $output[] = $temp_array;
            }
            return new JsonResponse($output);
        }

        return $this->redirectToRoute('users_index');
    }

}
