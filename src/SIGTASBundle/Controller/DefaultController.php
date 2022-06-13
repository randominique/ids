<?php

namespace SIGTASBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SIGTASBundle:Default:index.html.twig');
    }
}
