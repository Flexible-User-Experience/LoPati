<?php

namespace LoPati\BlogBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\BlogBundle\Entity\Pagina;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\NewsletterBundle;
use LoPati\NewsletterBundle\Repository\NewsletterUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LoPati\Utilities\Utils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController.
 */
class DefaultController extends Controller
{
    const THUMBNAILS_PER_PAGE_SLIDER = 10;
    const THUMBNAILS_PER_PAGE_NORMAL = 8;

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $pagines = null;
        $textabuscar = null;
        if ($request->getMethod() == 'POST') {
            $finder = $this->container->get('fos_elastica.finder.lopati_website.pagines');
            /** var array of Acme\UserBundle\Entity\User */
            $pagines = $finder->find($request->get('textabuscar'));
            /** var array of Acme\UserBundle\Entity\User limited to 10 results */
            $textabuscar = $request->get('textabuscar');
        }

        return $this->render('BlogBundle:Default:search.html.twig', array('pagines' => $pagines, 'textabuscar' => $textabuscar));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if ($this->get('session')->get('_locale')) {
            $culture = $this->get('session')->get('_locale');
        } else {
            $culture = $request->getPreferredLanguage(array('ca', 'es', 'en'));
        }

        return $this->redirect($this->generateUrl('portada', array('_locale' => $culture)));
    }

    /**
     * @param string $_locale
     *
     * @return Response
     */
    public function portadaAction($_locale)
    {
        $this->getRequest()->setLocale($_locale);
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $consulta = $em->getRepository('BlogBundle:Pagina')->getPortadaQueryOfCategory('Arxiu');
        $slides = $em->getRepository('BlogBundle:SliderImage')->getActiveSlidesSortByPosition();
        if ($slides) {
            $itemsPerPage = self::THUMBNAILS_PER_PAGE_SLIDER;
            $template = 'BlogBundle:Default:portada.slider.html.twig';
        } else {
            $itemsPerPage = self::THUMBNAILS_PER_PAGE_NORMAL;
            $template = 'BlogBundle:Default:portada.html.twig';
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($consulta, $this->getRequest()->query->get('page', 1), $itemsPerPage);

        return $this->render($template, array('portades' => $pagination, 'slides' => $slides));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function paginaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Pagina $pagina */
        $pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $id));
        $tipus_video = null;
        if ($pagina && $pagina->getVideo()) {
            $tipus_video = Utils::getVideo($pagina->getVideo());
        }

        return $this->render('BlogBundle:Default:pagina.html.twig', array('pagina' => $pagina, 'id' => $id, 'tipus_video' => $tipus_video));
    }

    /**
     * @return Response
     */
    public function arbre_de_contingutAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT c FROM MenuBundle:Categoria c WHERE c.actiu = true ORDER BY c.ordre');
        $categories = $query->getResult();

        return $this->render('AdminBundle:Admin:arbre_de_contingut.html.twig', array('categories' => $categories));
    }

    /**
     * @return Response
     */
    public function peuAction()
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT c FROM BlogBundle:Configuracio c WHERE c.id = 1');
        $configuracio = $query->getOneOrNullResult();

        return $this->render('BlogBundle:Default:peu.html.twig', array('peu' => $configuracio));
    }

    /**
     * @param $categoria
     * @param $subcategoria
     * @param $categoria_id
     * @param $subcategoria_id
     *
     * @return Response
     */
    public function llistaAction($categoria, $subcategoria, $categoria_id, $subcategoria_id)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $consulta = $em->createQuery(
            'SELECT p, cat, sub FROM BlogBundle:Pagina p JOIN p.categoria cat JOIN p.subCategoria sub WHERE p.actiu = :actiu
                            AND p.categoria = :categoria AND p.subCategoria = :subCategoria AND ((p.data_caducitat > :data) OR (p.data_caducitat IS NULL)) ORDER BY p.data_publicacio DESC '
        );
        /*
        $consulta->setParameter('categoria',$categoria);
        $consulta->setParameter('subCategoria',$subcategoria);
        $consulta->setParameter('actiu',1);*/
        $consulta->setParameter('data', new \DateTime('today'));
        $consulta->setParameter('categoria', $categoria_id);
        $consulta->setParameter('subCategoria', $subcategoria_id);
        $consulta->setParameter('actiu', '1');
        $pagines = $consulta->getResult();

        return $this->render(
            'BlogBundle:Default:llista.html.twig',
            array(
                'pagines' => $pagines,
                'categoria_id' => $categoria_id,
                'subcategoria_id' => $subcategoria_id,
            )
        );
    }

    /**
     * @return Response
     */
    public function menuIdiomaAction()
    {
        return $this->render('BlogBundle:Default:menuIdioma.html.twig');
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function articleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Pagina $pagina */
        $pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $id));
        $tipus_video = null;
        if ($pagina->getVideo()) {
            $tipus_video = Utils::getVideo($pagina->getVideo());
        }

        return $this->render(
            'BlogBundle:Default:article.html.twig',
            array('pagina' => $pagina, 'id' => $id, 'tipus_video' => $tipus_video)
        );
    }

    /**
     * @param int $categoria_id
     * @param $arxiu
     *
     * @return Response
     */
    public function arxiuAction($categoria_id, $arxiu)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $consulta = $em->createQuery(
            'SELECT ar FROM BlogBundle:Arxiu ar WHERE ar.actiu = :actiu AND ar.imagePetita2Name IS NOT NULL
                            AND  ar.imagePetitaName IS NOT NULL 	ORDER BY ar.any DESC '
        );
        $consulta->setParameter('actiu', '1');
        $anys = $consulta->getResult();

        $consulta = $em->createQuery('SELECT cat FROM MenuBundle:Categoria cat WHERE cat.id = :id');
        $consulta->setParameter('id', $categoria_id);
        $arxiu2 = $consulta->getSingleResult();

        return $this->render(
            'BlogBundle:Default:arxiu.html.twig',
            array('anys' => $anys, 'categoria_id' => $categoria_id, 'arxiu' => $arxiu2)
        );
    }

    /**
     * @param $any
     * @param $categoria_id
     * @param $arxiu
     *
     * @return Response
     */
    public function arxiuLlistaAnyAction($any, $categoria_id, $arxiu)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $consulta = $em->createQuery(
            'SELECT ar FROM BlogBundle:Arxiu ar WHERE ar.actiu = :actiu
                            AND ar.any = :any'
        );
        $consulta->setParameter('actiu', '1');
        $consulta->setParameter('any', $any);
        $any = $consulta->getSingleResult();
        $consulta2 = $em->createQuery(
            'SELECT p FROM BlogBundle:Pagina p WHERE p.data_caducitat <= :avui
                            AND p.actiu = :actiu AND p.data_publicacio BETWEEN :data1 AND :data2 ORDER BY p.data_publicacio DESC'
        );
        $consulta2->setParameter('avui', new \DateTime('today'));
        $consulta2->setParameter('actiu', '1');
        $consulta2->setParameter('data1', new \DateTime($any.'-01-01'));
        $consulta2->setParameter('data2', new \DateTime($any.'-12-31'));
        $pagines = $consulta2->getResult();
        $consulta3 = $em->createQuery('SELECT cat FROM MenuBundle:Categoria cat WHERE cat.id = :id');
        $consulta3->setParameter('id', $categoria_id);
        $categoria = $consulta3->getSingleResult();

        return $this->render(
            'BlogBundle:Default:arxiuLlistaAny.html.twig',
            array(
                'categoria_id' => $categoria->getId(),
                'any' => $any,
                'arxiu' => $arxiu,
                'pagines' => $pagines,
                'categoria' => $categoria,
            )
        );
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function arxiuArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Pagina $pagina */
        $pagina = $em->getRepository('BlogBundle:Pagina')->findOneBy(array('id' => $id));
        $tipus_video = null;
        if ($pagina && $pagina->getVideo()) {
            $tipus_video = Utils::getVideo($pagina->getVideo());
        }

        return $this->render(
            'BlogBundle:Default:articleArxiu.html.twig',
            array(
                'pagina' => $pagina,
                'id' => $id,
                'tipus_video' => $tipus_video,
            )
        );
    }

    /**
     * @param null $any_current
     * @param $categoria_id
     * @param $arxiu
     *
     * @return Response
     */
    public function menuDretaArxiuAction($any_current, $categoria_id, $arxiu)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $consulta = $em->createQuery(
            'SELECT ar FROM BlogBundle:Arxiu ar WHERE ar.actiu = :actiu AND ar.imagePetita2Name IS NOT NULL AND ar.imagePetitaName IS NOT NULL ORDER BY ar.any DESC'
        );
        $consulta->setParameter('actiu', '1');
        $anys = $consulta->getResult();

        return $this->render(
            'BlogBundle:Default:menuDretaArxiu.html.twig',
            array(
                'any_current' => $any_current,
                'anys' => $anys,
                'categoria_id' => $categoria_id,
                'arxiu' => $arxiu,
            )
        );
    }

    /**
     * @return Response
     */
    public function privacyPolicyAction()
    {
        return $this->render('BlogBundle:Default:privacy_policy.html.twig');
    }

    /**
     * @param string $token
     * @return RedirectResponse
     */
    public function newsletterAgreement($token)
    {
        /** @var NewsletterUser $newsletterUser */
        $newsletterUser = $this->getDoctrine()->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array('token' => $token));

        if (!$newsletterUser){
            throw new NotFoundHttpException('The user does not exist');
        }

        $newsletterUser->setActive(true);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('suscribe.confirmation.user'));

        return $this->redirectToRoute('inici');
    }
}
