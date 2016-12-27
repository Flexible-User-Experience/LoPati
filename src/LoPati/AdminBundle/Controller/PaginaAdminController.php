<?php

namespace LoPati\AdminBundle\Controller;

use LoPati\BlogBundle\Entity\Pagina;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class AdminController
 *
 * @category AdminController
 * @package  LoPati\AdminBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class PaginaAdminController extends Controller
{
    /**
     * Custom show action redirect to public frontend view
     *
     * @param Request $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function duplicateAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var Pagina $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find page object with ID = %s', $id));
        }

        $em = $this->getDoctrine()->getManager();
        $newPage = clone $object;
        $newPage
            ->setDocument1Name(null)
            ->setDocument2Name(null)
            ->setImageGran1Name(null)
            ->setImagePetitaName(null)
            ->setImagePetita2Name(null)
        ;
        $em->persist($newPage);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'sonata_flash_success',
            'La pàgina s\'ha duplicat correctament.'
        );

        return $this->redirect('../list');
    }

    /**
     * @param Request|null $request
     *
     * @return Request
     */
    private function resolveRequest(Request $request = null)
    {
        if (null === $request) {
            return $this->getRequest();
        }

        return $request;
    }
}
