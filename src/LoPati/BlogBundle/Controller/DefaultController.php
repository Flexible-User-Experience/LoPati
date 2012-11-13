<?php

namespace LoPati\BlogBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LoPati\MenuBundle\Util\Util;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;



class DefaultController extends Controller {
	
	
	public function searchAction() {
	
	
		$finder =$this->container->get('foq_elastica.finder.website.pagines');
		
		/** var array of Acme\UserBundle\Entity\User */
		$pagines = $finder->find('XYZ');
		
		/** var array of Acme\UserBundle\Entity\User limited to 10 results */
		//$pagines = $finder->find('bob', 10);
		
		
		return $this->render('BlogBundle:Default:search.html.twig',array('pagines'=>$pagines));
		
			
	}


    public function provaAction() {

        $em = $this->getDoctrine()->getManager();
        $pagina = $em->getRepository('BlogBundle:Pagina')->find(1);



        return $this->render('BlogBundle:Default:prova.html.twig',array('pagina'=>$pagina));


    }


	public function indexAction() {

		
		$req = $this->getRequest();
		
		if($this->get('session')->get('_locale')){
			
			$culture=$this->get('session')->get('_locale');
		}

		else{
	
		$session = $this->get('session');
	
		$culture=$req->getPreferredLanguage(array('ca', 'es', 'en'));	
	
		}
		return $this->redirect($this->generateUrl('portada', array('_locale' =>$culture)));
			
		
			
	}
	public function portadaAction($_locale) {

		//$pagination= null;
		$this->getRequest()->setLocale($_locale);
		$this->get('session')->get('_locale');
		$this->get('session')->set('_locale', $_locale);
		
		$em = $this->getDoctrine()->getManager(); //per  poder fer fer consultes a la base de dades
		$consulta = $em->createQuery('SELECT p, cat, sub FROM BlogBundle:Pagina p  JOIN p.categoria cat LEFT JOIN p.subCategoria sub
			WHERE p.portada = TRUE AND p.actiu = TRUE AND (p.subCategoria IS NOT NULL OR cat.nom = :categoria )ORDER BY p.data_publicacio DESC');
		
		//$consulta->setParameter('avui', new \DateTime('today'));
		$consulta->setParameter('categoria', 'Arxiu');
	$query = $em->createQuery($consulta);

	//$pagination = $consulta->getResult();
 		$paginator = $this->get('knp_paginator');
 		$pagination = $paginator->paginate(
 				$consulta,
 				$this->get('request')->query->get('page', 1),
				8/*limit per page*/
 		);
		
	//	$pagination = $consulta->getResult();
/*
		
		$categories = $em->getRepository('BlogBundle:Pagina')
		->findBy(array('actiu' => true));
		
		$em = $this->get('doctrine.orm.entity_manager');
		$dql = "SELECT a FROM VendorBlogBundle:Article a";
		$query = $em->createQuery($dql);
		
		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
				$query,
				$this->get('request')->query->get('page', 1)/*page number*//*,
				10/*limit per page*/
		//);
		//$pagination = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => 22));
		
		
		return $this->render('BlogBundle:Default:portada.html.twig', array('portades' => $pagination));
		

	}

	public function paginaAction($id) {
		
		$em = $this->getDoctrine()->getEntityManager(); //per  poder fer fer consultes a la base de dades
		$pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $id));
		
		$tipus_video=null;
		if ($pagina->getVideo()){
		$tipus_video= Util::getVideo($pagina->getVideo());
		}
		
		
		return $this->render('BlogBundle:Default:pagina.html.twig', array('pagina' => $pagina, 'id' => $id, 'tipus_video' => $tipus_video));
	}
	

	public function arbre_de_contingutAction(){
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT c FROM MenuBundle:Categoria c WHERE c.actiu = true ORDER BY c.ordre');
		$categories = $query->getResult();
		return $this->render('BlogBundle:Default:arbre_de_contingut.html.twig', array('categories' => $categories));
	}
	
	public function peuAction(){
		
		$em = $this->getDoctrine()->getEntityManager();
		//$categories = $em->getRepository('MenuBundle:Categoria')->findAll();
		$query = $em->createQuery('SELECT c FROM BlogBundle:Configuracio c WHERE c.id =1');
		
		$configuracio = $query->getOneOrNullResult();
		return $this->render('BlogBundle:Default:peu.html.twig', array('peu' => $configuracio));
	}
	
	public function llistaAction($categoria, $subcategoria, $categoria_id, $subcategoria_id	){
		
		$em = $this->getDoctrine()->getEntityManager();
	/*
		$consulta= $em->createQuery('SELECT p FROM BlogBundle:Pagina p JOIN p.categoria cat JOIN p.subCategoria sub WHERE p.actiu = :actiu 
				AND p.categoria = :categoria AND p.subCategoria = :subCategoria AND p.data_caducitat >= :data ORDER BY p.data_publicacio DESC');*/
		
		
		/*$consulta= $em->createQuery('SELECT p, cat, sub FROM BlogBundle:Pagina p JOIN p.categoria cat JOIN c.subCategoria sub WHERE p.actiu = :actiu
				AND p.categoria = :categoria AND p.subCategoria = :subCategoria AND ((p.data_caducitat >= :data) OR (p.data_caducitat IS NULL)) ORDER BY p.data_publicacio DESC ');*/
		
		$consulta= $em->createQuery('SELECT p, cat, sub FROM BlogBundle:Pagina p JOIN p.categoria cat JOIN p.subCategoria sub WHERE p.actiu = :actiu
				AND p.categoria = :categoria AND p.subCategoria = :subCategoria AND ((p.data_caducitat > :data) OR (p.data_caducitat IS NULL)) ORDER BY p.data_publicacio DESC ');
		/*
		$consulta->setParameter('categoria',$categoria);
		$consulta->setParameter('subCategoria',$subcategoria);
		$consulta->setParameter('actiu',1);*/
		$consulta->setParameter('data', new \DateTime('today'));
	
		$consulta->setParameter('categoria',$categoria_id);
		$consulta->setParameter('subCategoria',$subcategoria_id);
		$consulta->setParameter('actiu','1');
		$pagines = $consulta->getResult();
		
		return $this->render('BlogBundle:Default:llista.html.twig', array('pagines' => $pagines, 'categoria_id' => $categoria_id, 'subcategoria_id' => $subcategoria_id
				));
	}
	
	public function menuIdiomaAction(){
		$idioma = $this->getRequest()->getLocale();
		return $this->render('BlogBundle:Default:menuIdioma.html.twig', array('idioma'=>$idioma));
	}
	
	public function articleAction($id){
	
		$em = $this->getDoctrine()->getEntityManager(); //per  poder fer fer consultes a la base de dades
		$pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $id));
		
		$tipus_video=null;
		if ($pagina->getVideo()){
			$tipus_video= Util::getVideo($pagina->getVideo());
		}
		
		return $this->render('BlogBundle:Default:article.html.twig', array('pagina' => $pagina, 'id' => $id, 'tipus_video' => $tipus_video));
	}
	
	public function arxiuAction($categoria_id, $arxiu){
		
		$em = $this->getDoctrine()->getEntityManager(); //per  poder fer fer consultes a la base de dades
		$consulta= $em->createQuery('SELECT ar FROM BlogBundle:Arxiu ar WHERE ar.actiu = :actiu AND ar.imagePetita2Name IS NOT NULL
				AND  ar.imagePetitaName IS NOT NULL 	ORDER BY ar.any DESC ');
		$consulta->setParameter('actiu','1');
		$anys = $consulta->getResult();
		
		$consulta= $em->createQuery('SELECT cat FROM MenuBundle:Categoria cat WHERE cat.id = :id');
		$consulta->setParameter('id',$categoria_id);
		$arxiu2 = $consulta->getSingleResult();
		return $this->render('BlogBundle:Default:arxiu.html.twig', array('anys'=>$anys, 'categoria_id' => $categoria_id, 'arxiu' => $arxiu2));
		
	}
	
	public function arxiuLlistaAnyAction($any,$categoria_id, $arxiu){
	
		$em = $this->getDoctrine()->getEntityManager(); //per  poder fer fer consultes a la base de dades
	/*	$consulta= $em->createQuery('SELECT ar FROM BlogBundle:Arxiu ar WHERE ar.actiu = :actiu
				ORDER BY ar.any DESC ');
		$consulta->setParameter('actiu','1');
		$anys = $consulta->getResult();*/
		$consulta= $em->createQuery('SELECT ar FROM BlogBundle:Arxiu ar WHERE ar.actiu = :actiu
				AND ar.any = :any');
		$consulta->setParameter('actiu','1');
		$consulta->setParameter('any',$any);
		$any = $consulta->getSingleResult();
		
		$consulta2= $em->createQuery('SELECT p FROM BlogBundle:Pagina p WHERE p.data_caducitat <= :avui
				AND p.actiu = :actiu AND p.data_publicacio BETWEEN :data1 AND :data2 ORDER BY p.data_publicacio DESC');
		
		$consulta2->setParameter('avui', new \DateTime('today'));
		$consulta2->setParameter('actiu','1');
		$consulta2->setParameter('data1',new \DateTime($any."-01-01"));
		$consulta2->setParameter('data2',new \DateTime($any."-12-31"));
		$pagines = $consulta2->getResult();
		
		$consulta3= $em->createQuery('SELECT cat FROM MenuBundle:Categoria cat WHERE cat.id = :id');
		$consulta3->setParameter('id',$categoria_id);
		$categoria = $consulta3->getSingleResult();
		
		return $this->render('BlogBundle:Default:arxiuLlistaAny.html.twig', array('categoria_id' => $categoria->getId(), 'any' => $any, 'arxiu' => $arxiu, 'pagines' => $pagines, 'categoria' => $categoria ));
	
	}
	
	public function arxiuArticleAction($id){
		$em = $this->getDoctrine()->getEntityManager(); //per  poder fer fer consultes a la base de dades
		$pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $id));
		
		$tipus_video=null;
		if ($pagina->getVideo()){
			$tipus_video= Util::getVideo($pagina->getVideo());
		}
		return $this->render('BlogBundle:Default:articleArxiu.html.twig', array('pagina' => $pagina, 'id' => $id, 'tipus_video' => $tipus_video));
		
		
	}
	
	public function menuDretaArxiuAction($any_current=null, $categoria_id, $arxiu){
		$em = $this->getDoctrine()->getEntityManager();
		$consulta= $em->createQuery('SELECT ar FROM BlogBundle:Arxiu ar WHERE ar.actiu = :actiu AND ar.imagePetita2Name IS NOT NULL
				AND ar.imagePetitaName IS NOT NULL ORDER BY ar.any DESC');
		$consulta->setParameter('actiu','1');
		$anys = $consulta->getResult();
		
		/*$consulta2= $em->createQuery('SELECT cat FROM MenuBundle:Categoria cat WHERE cat.id = : categoria');
		$consulta2->setParameter('categoria',$categoria_id);
		$categoria = $consulta2->getSingleResult();
		*/
		return $this->render('BlogBundle:Default:menuDretaArxiu.html.twig', array( 'any_current'=> $any_current, 'anys'=>$anys, 'categoria_id' => $categoria_id, 'arxiu' => $arxiu));
		
	}
}
