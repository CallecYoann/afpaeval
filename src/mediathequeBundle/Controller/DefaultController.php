<?php

namespace mediathequeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('mediathequeBundle:Default:index.html.twig');
    }
}
