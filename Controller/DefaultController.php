<?php

namespace Lks\ManPowerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LksManPowerBundle:Default:index.html.twig', array('name' => $name));
    }
}
