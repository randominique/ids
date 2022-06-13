<?php

namespace DBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GestionDesActivitesControllerTest extends WebTestCase
{
    public function testTosurdeclarationperiodque()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'taxation_d_office_sur_declaration_periodique');
    }

    public function testTosurdeclarationperiodiqueaetablir()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/TOSurDeclarationPeriodiqueAEtablir');
    }

    public function testTosurdeclarationrealisees()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'to_declaration_periodique_realisees');
    }

    public function testDefaillantsannexetva()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'defaillants_annexe_tva');
    }

    public function testContribuablesavecanomaliestva()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'contribuables_avec_anomalies_tva');
    }

    public function testcontribuables()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'contribuables');
    }

    public function testMouvementdossiersaudfu()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'mouvement_dossier_dfu');
    }

    public function testSituationdossiersaudfu()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'situation_dossier_dfu');
    }

    public function testAssujettissement()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'assujettissement');
    }

    public function testDeclarationsdepotparecheance()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'declarations_depot_par_echeance');
    }

    public function testDeclarationsdepotparcontribuables()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'declarations_depot_par_contribuables');
    }

    public function testDeclarationsdepotpargestionnaire()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'declaration_depot_par_gestionnaire');
    }

    public function testDeclarationsnondepotparecheance()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'declarations_non_depot_par_echeance');
    }

    public function testDeclarationsnondepotparcontribuables()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'declarations_non_depot_par_contribuables');
    }

    public function testDeclarationsnondepotpargestionnaire()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'declaration_non_depot_par_gestionnaire');
    }

    public function testRectificationdeclaration()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'rectification_declaration');
    }

    public function testSuiviacompte()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'suivi_acompte');
    }

}
