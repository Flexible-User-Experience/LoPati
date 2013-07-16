<?php

namespace LoPati\ArtistaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pagina = $em->getRepository('BlogBundle:Pagina')->find(124);
        $artistes = $em->getRepository('ArtistaBundle:Artista')->getActiveItemsSortedByPosition();
        return $this->render('ArtistaBundle:Default:index.html.twig', array(
            'pagina' => $pagina,
            'artistes' => $artistes,
        ));
    }

    public function detailAction($artista)
    {
        $em = $this->getDoctrine()->getManager();
        $pagina = $em->getRepository('BlogBundle:Pagina')->find(124);
        $artistes = $em->getRepository('ArtistaBundle:Artista')->getActiveItemsSortedByPosition();
        foreach ($artistes as $art) {
            if ($art->getSlug() == $artista) {
                $artistaEscollit = $art;
                break;
            }
        }
        return $this->render('ArtistaBundle:Default:detail.html.twig', array(
            'pagina' => $pagina,
            'artista' => $artistaEscollit,
            'artistes' => $artistes,
        ));
    }
}
