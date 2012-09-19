<?php

namespace LoPati\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NewsletterBundle:Default:index.html.twig', array('name' => $name));
    }
}
