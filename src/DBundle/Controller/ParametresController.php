<?php

namespace DBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DBundle\Entity\Service;
use DBundle\Form\ServiceType;
use DBundle\Entity\RelanceSetting;
use DBundle\Form\RelanceSettingType;
use DBundle\Entity\Dge;
use DBundle\Form\DgeType;
use DBundle\Entity\SaiSetting;
use DBundle\Form\SaiSettingType;
use DBundle\Entity\tax_famille;
use DBundle\Form\tax_familleType;
use DBundle\Entity\tax_famille_child;
use DBundle\Form\tax_famille_childType;
use SIGTASBundle\Entity\tax_type;
use DBundle\Entity\Attribution;
use DBundle\Form\AttributionType;

class ParametresController extends Controller
{
    
    
    public function parametreRelanceAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $RelanceSetting = $em->getRepository(RelanceSetting::class)->findOneBy([], ['id' => 'desc']);
        if (!$RelanceSetting) {
            $RelanceSetting = new RelanceSetting();
        }
        $form = $this->createForm(RelanceSettingType::class, $RelanceSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($RelanceSetting);
            $em->flush();
            $this->addFlash("succes", "Success");
            return $this->redirectToRoute('relance_setting');
        }

        return $this->render('DBundle:Parametres:relance_setting.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    public function parametreDgeAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $Dge = $em->getRepository(Dge::class)->findOneBy([], ['id' => 'desc']);
        if (!$Dge) {
            $Dge = new Dge();
        }
        $form = $this->createForm(DgeType::class, $Dge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($Dge);
            $em->flush();
            $this->addFlash("succes", "Success");
            return $this->redirectToRoute('dge_setting');
        }

        return $this->render('DBundle:Parametres:dge_setting.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    public function SaiSettingAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $SaiSetting = $em->getRepository(SaiSetting::class)->findOneBy([], ['id' => 'desc']);
        if (!$SaiSetting) {
            $SaiSetting = new SaiSetting();
        }
        $form = $this->createForm(SaiSettingType::class, $SaiSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($SaiSetting);
            $em->flush();
            $this->addFlash("succes", "Success");
            return $this->redirectToRoute('sai_setting');
        }

        return $this->render('DBundle:Parametres:sai_setting.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function newAssujettissementAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $tax_famille = new tax_famille();

        $form = $this->createForm(tax_familleType::class, $tax_famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tax_famille);
            $em->flush();
            $this->addFlash("succes", "Success");
            return $this->redirectToRoute('assujettissement_setting');
        }

        return $this->render('DBundle:Parametres:newAssujettissement.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function AssujettissementSettingAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $tax_famille = $em->getRepository(tax_famille::class)->findAll([], ['id' => 'desc']);

        return $this->render('DBundle:Parametres:AssujettissementSetting.html.twig',[
            'tax_familles' => $tax_famille
        ]);
    }

    public function ShowAssujettissementSettingAction(tax_famille $taxe){
        
        $em = $this->getDoctrine()->getManager();
        $sigtas_em = $this->getDoctrine()->getManager('sigtas');

        $membre = $em->getRepository(tax_famille_child::class)->findBy([
            'famille' => $taxe->getId()
        ]);
        
        foreach ($membre as $assuj) {
            $tx = $sigtas_em->getRepository(tax_type::class)->findOneBy([
                'id' => $assuj->getTaxTypeNo()
            ]);
            $assuj->setTaxeName($tx);
        }

        return $this->render('DBundle:Parametres:ShowAssujettissementSetting.html.twig',[
            'taxe' => $taxe,
            'membres' => $membre
        ]);
    }
    
    public function NewMembreAssujettissementAction(Request $request, tax_famille $taxe){
        $em = $this->getDoctrine()->getManager();
        $tax_famille_child = new tax_famille_child();
        $tax_famille_child->setFamille($taxe);

        $form = $this->createForm(tax_famille_childType::class, $tax_famille_child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tax_famille_child);
            $em->flush();
            $this->addFlash("succes", "Success");
            return $this->redirectToRoute('show_assujettissement_setting', [
                'taxe' => $taxe->getId()
            ]);
        }

        return $this->render('DBundle:Parametres:NewMembreAssujettissement.html.twig',[
            'form' => $form->createView(),
            'taxe' => $taxe
        ]);
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $services = $em->getRepository(Service::class)->findAll();

        return $this->render('DBundle:Parametres:index.html.twig', array(
            'services' => $services
        ));
    }

    public function newAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chef = $request->request->get('dbundle_service')['chef'];
            $checkChef = $em->getRepository(Service::class)->findOneByChef($chef);
            if ($checkChef) {
                $this->addFlash("error", "Cet utilisateur est déjà un chef de service");
            }
            else{
                $em->persist($service);
                $em->flush();
                return $this->redirectToRoute('d_parametres_service');
            }
        }

        return $this->render('DBundle:Parametres:new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function updateAction(Service $service, Request $request){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $chef = $request->request->get('dbundle_service')['chef'];
            $checkChef = $em->getRepository(Service::class)->findOneByChef($chef);
            if ($checkChef) {
                $this->addFlash("error", "Cet utilisateur est déjà un chef de service");
            }
            else{
                $em->flush();
                return $this->redirectToRoute('d_parametres_service');
            }
            
        }

        return $this->render('DBundle:Parametres:update.html.twig',[
            'form' => $form->createView()
        ]);
    }
    public function AttributionIndexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $attributions = $em->getRepository(Attribution::class)->findAll();

        return $this->render('DBundle:Parametres:attribution_index.html.twig', array(
            'attributions' => $attributions
        ));
    }

    public function AttributionNewAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $attribution = new Attribution();
        $form = $this->createForm(AttributionType::class, $attribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $em->persist($attribution);
            $em->flush();
            return $this->redirectToRoute('d_parametres_attribution');
        
        }

        return $this->render('DBundle:Parametres:attribution_new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function AttributionUpdateAction(Attribution $attribution, Request $request){
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AttributionType::class, $attribution);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($attribution);
            
            $em->flush();
            return $this->redirectToRoute('d_parametres_attribution');
            
        }

        return $this->render('DBundle:Parametres:attribution_update.html.twig',[
            'form' => $form->createView()
        ]);
    }

}
