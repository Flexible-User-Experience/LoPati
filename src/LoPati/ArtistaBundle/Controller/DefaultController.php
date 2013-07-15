<?php

namespace LoPati\ArtistaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function detailAction($artista, $id)
    {
        return $this->render('ArtistaBundle:Default:index.html.twig', array(
            'artista' => $artista,
            'id' => $id,
        ));
    }
}
