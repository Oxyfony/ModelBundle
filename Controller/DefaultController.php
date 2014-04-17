<?php

namespace Oxygen\Bundle\ModelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('OxygenModelBundle:Default:index.html.twig', array('name' => $name));
    }
}
