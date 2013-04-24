<?php

namespace LoPati\MenuBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use LoPati\BlogBundle\Entity;

class DefaultController extends Controller {

	public function indexAction($name) {
		return $this
				->render('MenuBundle:Default:index.html.twig',
						array('name' => $name));
	}

	public function pintaMenuAction($id=null, $pagina=null)
    {
		$em = $this->getDoctrine()->getManager();
		if ($id) {
			$em = $this->getDoctrine()->getManager();
			$pagina = $em->getRepository('BlogBundle:Pagina')->find($id);
		}
		$categories = $em->getRepository('MenuBundle:Categoria')->findCategories();
		return $this->render('MenuBundle:Default:menu.html.twig', array('categories' => $categories, 'id' => $id, 'pagina' => $pagina));
	}
	
	public function creaLinkAction($idPagina, $tipus, $actiu, $titol, $llista)
    {
		if ($llista == 1) {
			$pagina=null;
		} else {
			$em = $this->getDoctrine()->getManager();
			$pagina = $em->getRepository('BlogBundle:Pagina')->find($idPagina);
			$logger = $this->get('logger');
			$logger->info('id val:'.$idPagina);
		}
		return $this->render('MenuBundle:Default:creaLink.html.twig', array('pagina' => $pagina, 'id' => $idPagina, 'tipus' => $tipus, 'actiu' => $actiu, 'titol' => $titol, 'llista' => $llista));
	}
	
	public function subCategoriesAction($idPagina)
    {
		$em = $this->getDoctrine()->getManager();
		$pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $idPagina));
		$categoria = $pagina->getCategoria();
		
		/*$logger = $this->get('logger');
		$logger->info('id val:'.$categoria);*/

		$subcategories2 = $em->getRepository('MenuBundle:SubCategoria')->findSubCategories($categoria);
		
		return $this->render('MenuBundle:Default:subCategories.html.twig',array('subcategories' => $subcategories2, 'id' => $idPagina));
	}
	
	public function pintaMenuLlistaAction($categoria_id, $subcategoria_id=null, $onlycategories=null)
    {
		$em = $this->getDoctrine()->getManager();
		$categories = $em->getRepository('MenuBundle:Categoria')->findCategories();
		
		$em = $this->getDoctrine()->getManager();
		$subcategories = $em->getRepository('MenuBundle:SubCategoria')->findSubCategories($categoria_id);

		return $this->render('MenuBundle:Default:menuLlista.html.twig', array('categories' => $categories, 'categoria_id' => $categoria_id,'subcategoria_id' => $subcategoria_id, 'subcategories' => $subcategories, 'onlycategories' => $onlycategories));
	
	}

}
