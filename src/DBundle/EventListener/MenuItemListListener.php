<?php

namespace DBundle\EventListener;

// ...

use DBundle\Model\MenuItemModel;
use Avanzu\AdminThemeBundle\Event\SidebarMenuEvent;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DBundle\Entity\DocCourrier;

class MenuItemListListener
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onSetupMenu(SidebarMenuEvent $event)
    {

        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }
    }

    protected function getMenu(Request $request)
    {


        $utilisateurs = $this->em->getRepository(DocCourrier::class)->findAll();
        $nbUsers = sizeof($utilisateurs);

        $menuItems = array(
            $homepage = new MenuItemModel('dashboard', 'Tableau de bord', 'd_homepage', [/* option */], 'fas fa-tachometer-alt'),
            $courierEntrant = (new MenuItemModel('courier_entrant', 'Courriers entrants', 'list_entrant', [/* option */], 'fas fa-inbox')),
            $courierSortant = new MenuItemModel('courier_sortant', 'Courriers sortants', '_list_sortant', [/* option */], 'fas fa-reply'),
            //$relance = new MenuItemModel('relance', 'Relance', 'liste_relance', [/* option */], 'fas fa-sync'),            
            $communication = new MenuItemModel('communication', 'Communications', 'communication_index', [/* option */], 'fas fa-sync'),
            $gestion_repertoire = new MenuItemModel('gestion_repertoire', 'Gestion', 'contribuables', [/* option */], 'fas fa-tasks'),
            $DFU = new MenuItemModel('dfu', 'DFU', 'dfu_liste', [/* option */], 'fas fa-clipboard-list'),
            $SQVF = new MenuItemModel('sqvf', 'SQVF', 'dossiers', [/* option */], 'fas fa-clipboard-list'),
            $missions = new MenuItemModel('missions', 'Missions', 'list_mission', [/* option */], 'fas fa-gears'),
            $taches = new MenuItemModel('taches', 'Tâches', 'taches', [/* option */], 'fas fa-clipboard'),
            $users = new MenuItemModel('users_index', 'Utilisateurs', 'users_index', [/* option */], 'fas fa-user'),
            $parametres = new MenuItemModel('parametres', 'Paramètres', 'd_parametres_service', [/* option */], 'fas fa-cogs'),
            $initialisation = new MenuItemModel('initialisation', 'Initialisation', '_truncate_tables', [/* option */], 'fas fa-cogs'),
                
            $logout = new MenuItemModel('logout', 'Déconnexion', 'fos_user_security_logout', [/* option */], 'fas fa-sign-out-alt '),
        );

        // $parametres->addChild(new MenuItemModel('dge', 'DGE', 'dge_setting', array(), 'child_2_route'));
        // $parametres->addChild(new MenuItemModel('service', 'Service', 'd_parametres_service', array(), 'child_2_route'));
        // $parametres->addChild(new MenuItemModel('Relance', 'Relance', 'relance_setting', array(), 'child_2_route'));
        // $parametres->addChild(new MenuItemModel('SAI', 'Service Accueil et Information', 'sai_setting', array(), 'child_2_route'));
        // $parametres->addChild(new MenuItemModel('tax', 'Taxe', 'assujettissement_setting', array(), 'child_2_route'));
        $parametres->addChild(new MenuItemModel('objet_courrier_entrant', 'Courrier entrant', 'list_entrant_cat', array(), 'child_2_route'));
        // $parametres->addChild(new MenuItemModel('objet_courrier_sortant', 'Courrier sortant', '_list_categorie_sortant', array(), 'child_2_route'));
        $parametres->addChild(new MenuItemModel('objet_tache', 'Type de tâche', 'list_tache_cat', array(), 'child_2_route'));
        $parametres->addChild(new MenuItemModel('attribution', 'Attribution', 'd_parametres_attribution', array(), 'child_2_route'));
        // $parametres->addChild(new MenuItemModel('initialisation', 'Initialisation', '_truncate_tables', array(), 'child_2_route'));

        return $this->activateByRoute($request->get('_route'), $menuItems);
    }

    protected function activateByRoute($route, $items)
    {

        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } else {
                if ($item->getRoute() == $route) {
                    $item->setIsActive(true);
                }
            }
        }

        return $items;
    }
}
