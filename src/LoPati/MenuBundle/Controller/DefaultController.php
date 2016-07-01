<?php

namespace LoPati\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LoPati\BlogBundle\Entity;
use LoPati\MenuBundle\Entity\SubCategoria;

class DefaultController extends Controller
{
	public function pintaMenuAction($id = null, $pagina = null)
    {
		$em = $this->getDoctrine()->getManager();
		if ($id) {
			$pagina = $em->getRepository('BlogBundle:Pagina')->find($id);
		}
		$categories = $em->getRepository('MenuBundle:Categoria')->findCategories();

		return $this->render('MenuBundle:Default:menu.html.twig', array(
            'categories' => $categories,
            'id' => $id,
            'pagina' => $pagina,
        ));
	}
	
	public function creaLinkAction($idPagina, $tipus, $actiu, $titol, $llista)
    {
		if ($llista == 1) {
			$pagina = null;
		} else {
			$em = $this->getDoctrine()->getManager();
			$pagina = $em->getRepository('BlogBundle:Pagina')->find($idPagina);
//			$logger = $this->get('logger');
//			$logger->info('id val:'.$idPagina);
		}

		return $this->render('MenuBundle:Default:creaLink.html.twig', array(
            'id'     => $idPagina,
            'pagina' => $pagina,
            'tipus'  => $tipus,
            'actiu'  => $actiu,
            'titol'  => $titol,
            'llista' => $llista,
        ));
	}
	
	public function subCategoriesAction($idPagina)
    {
		$em = $this->getDoctrine()->getManager();
		$pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $idPagina));
		$categoria = $pagina->getCategoria();
		/*$logger = $this->get('logger');
		$logger->info('id val:'.$categoria);*/
		$subcategories2 = $em->getRepository('MenuBundle:SubCategoria')->findSubCategories($categoria);
        if ($idPagina == $this->container->getParameter('id_page_item_projectes') ||
            $categoria->getId() == $this->container->getParameter('id_categoria_projectes')) {
            if (count($subcategories2) > 0) {
                /** @var SubCategoria $irradiadorSubcategoria */
                $irradiadorSubcategoria = new SubCategoria();
                $irradiadorSubcategoria->setNom('Irradiador');
                $irradiadorSubcategoria->setOrdre(123);
                array_push($subcategories2, $irradiadorSubcategoria);
            }
        }
		
		return $this->render('MenuBundle:Default:subCategories.html.twig', array(
            'subcategories' => $subcategories2,
            'id' => $idPagina,
        ));
	}
	
	public function pintaMenuLlistaAction($categoria_id, $subcategoria_id = null, $onlycategories = null)
    {
		$em = $this->getDoctrine()->getManager();
		$categories = $em->getRepository('MenuBundle:Categoria')->findCategories();
		$subcategories = $em->getRepository('MenuBundle:SubCategoria')->findSubCategories($categoria_id);
        if ($categoria_id == $this->container->getParameter('id_categoria_projectes')) {
            if (count($subcategories) > 0) {
                /** @var SubCategoria $irradiadorSubcategoria */
                $irradiadorSubcategoria = new SubCategoria();
                $irradiadorSubcategoria->setNom('Irradiador');
                $irradiadorSubcategoria->setOrdre(123);
                array_push($subcategories, $irradiadorSubcategoria);
            }
        }

		return $this->render('MenuBundle:Default:menuLlista.html.twig', array(
            'categories' => $categories,
            'categoria_id' => $categoria_id,
            'subcategoria_id' => $subcategoria_id,
            'subcategories' => $subcategories,
            'onlycategories' => $onlycategories,
        ));
	}
}
