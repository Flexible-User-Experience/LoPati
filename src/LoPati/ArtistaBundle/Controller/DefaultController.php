<?php

namespace LoPati\ArtistaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ArtistaBundle:Default:index.html.twig', array('name' => $name));
    }
}
