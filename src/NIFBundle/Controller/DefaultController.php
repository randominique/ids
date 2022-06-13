<?php

namespace NIFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NIFBundle:Default:index.html.twig');
    }
}
