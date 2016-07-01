<?php

namespace LoPati\ArtistaBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use LoPati\ArtistaBundle\Entity\Artista;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pagina = $em->getRepository('BlogBundle:Pagina')->find($this->getIdPageItemIrradiador());
        $artistes = $em->getRepository('ArtistaBundle:Artista')->getActiveItemsSortedByPosition();
        return $this->render('ArtistaBundle:Default:index.html.twig', array(
            'pagina' => $pagina,
            'artistes' => $artistes,
        ));
    }

    public function detailAction($artista)
    {
        $em = $this->getDoctrine()->getManager();
        $pagina = $em->getRepository('BlogBundle:Pagina')->find($this->getIdPageItemIrradiador());
        $artistes = $em->getRepository('ArtistaBundle:Artista')->getActiveItemsSortedByPosition();
        $artistaEscollit = null;

        /** @var Artista $art */
        foreach ($artistes as $art) {
            if ($art->getSlug() == $artista) {
                $artistaEscollit = $art;
                break;
            }
        }

        if (is_null($artistaEscollit)) {
            throw new EntityNotFoundException('Artist slug ' . $artista . ' not found');
        }

        return $this->render('ArtistaBundle:Default:detail.html.twig', array(
            'pagina'   => $pagina,
            'artista'  => $artistaEscollit,
            'artistes' => $artistes,
        ));
    }

    /**
     * @return int
     */
    private function getIdPageItemIrradiador()
    {
        return $this->get('kernel')->getEnvironment() == 'test' ? 1 : $this->container->getParameter('id_page_item_irradiador');
    }
}
