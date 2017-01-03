<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\AdminBundle\Service\MailerService;
use LoPati\NewsletterBundle\Entity\IsolatedNewsletter;
use LoPati\NewsletterBundle\Entity\NewsletterGroup;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class IsolatedNewsletterAdminController
 *
 * @category AdminController
 * @package  LoPati\AdminBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class IsolatedNewsletterAdminController extends Controller
{
    /**
     * @param Request|null $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function sendAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var IsolatedNewsletter $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find isolated newsletter record with ID:%s', $id));
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var MailerService $ms */
        $ms = $this->container->get('app.mailer.service');
        /** @var NewsletterGroup $group */
        $group = $object->getGroup();
        if ($group) {
            /** @var NewsletterUser $user */
            foreach ($group->getUsers() as $user) {
                $content = $this->renderView(
                    'AdminBundle:IsolatedNewsletter:preview.html.twig',
                    array(
                        'newsletter'   => $object,
                        'user_token'   => $user->getToken(),
                        'show_top_bar' => false,
                    )
                );
                $ms->delivery($object->getSubject(), array($user->getEmail()), $content);
            }
        }
        $this->get('session')->getFlashBag()->add('sonata_flash_success', 'El newsletter s\'ha enviat a totes les bústies.');

        return $this->redirect('../list');
    }

    /**
     * @param Request|null $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function previewAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var IsolatedNewsletter $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find isolated newsletter record with ID:%s', $id));
        }

        return $this->render(
            'AdminBundle:IsolatedNewsletter:preview.html.twig',
            array(
                'newsletter'   => $object,
                'user_token'   => 'undefined',
                'show_top_bar' => true,
            )
        );
    }

    /**
     * @param Request|null $request
     *
     * @return Response
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function testAction(Request $request = null)
    {
        $request = $this->resolveRequest($request);
        $id = $request->get($this->admin->getIdParameter());

        /** @var IsolatedNewsletter $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('Unable to find isolated newsletter record with ID:%s', $id));
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var MailerService $ms */
        $ms = $this->container->get('app.mailer.service');

        /** @var array $edl email destinations list */
        $edl = $this->getEdl();
        /** @var string $content message content */
        $content = $this->renderView(
            'AdminBundle:IsolatedNewsletter:preview.html.twig',
            array(
                'newsletter'   => $object,
                'user_token'   => 'undefined',
                'show_top_bar' => false,
            )
        );

        $result = $ms->delivery('[TEST] ' . $object->getSubject(), $edl, $content);
        if ($result == true) {
            $object->setTested(true);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_success',
                'S\'ha enviat correctament un email de test a les bústies: ' . NewsletterPageAdminController::testEmail1 . ', ' . NewsletterPageAdminController::testEmail2 . ' i ' . NewsletterPageAdminController::testEmail3
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_error',
                'S\'ha produït un ERROR en enviar el test.'
            );
        }

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

    /**
     * @return array
     */
    private function getEdl()
    {
        /** @var KernelInterface $ki */
        $ki = $this->container->get('kernel');

        /** @var array $edl email destinations list only for developer */
        $edl = array(
            NewsletterPageAdminController::testEmail3,
        );

        if ($ki->getEnvironment() === 'prod') {
            /** @var array $edl email destinations list */
            $edl = array(
                NewsletterPageAdminController::testEmail1,
                NewsletterPageAdminController::testEmail2,
                NewsletterPageAdminController::testEmail3,
            );
        }

        return $edl;
    }
}