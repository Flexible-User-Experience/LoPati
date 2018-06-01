<?php

namespace LoPati\AdminBundle\Controller;

use LoPati\NewsletterBundle\Entity\IsolatedNewsletterPost;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class IsolatedNewsletterPostAdminController
 *
 * @category AdminController
 */
class IsolatedNewsletterPostAdminController extends Controller
{
    /**
     * Redirect the user depend on this choice.
     *
     * @param IsolatedNewsletterPost $object
     *
     * @return RedirectResponse
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();

        return new RedirectResponse(
            $this->getRedirectToUrl(
                $request,
                $object,
                'admin_lopati_newsletter_isolatednewsletter_edit',
                array('id' => $object->getNewsletter()->getId())
            )
        );
    }

    /**
     * Get redirect URL depend on choice
     *
     * @param Request $request
     * @param mixed $object
     * @param string $redirectRoute
     * @param array $params
     *
     * @return bool|string
     */
    protected function getRedirectToUrl(Request $request, $object, $redirectRoute, array $params = array())
    {
        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            $router = $this->get('router');
            $url = $router->generate($redirectRoute, $params);
        }
        if (null !== $request->get('btn_create_and_list')) {
            $url = $this->admin->generateUrl('list');
        }

        if (null !== $request->get('btn_create_and_create')) {
            $params = array();
            if ($this->admin->hasActiveSubClass()) {
                $params['subclass'] = $request->get('subclass');
            }
            $url = $this->admin->generateUrl('create', $params);
        }

        if ($this->getRestMethod() === 'DELETE') {
            $url = $this->admin->generateUrl('list');
        }

        if (!$url) {
            foreach (array('edit', 'show') as $route) {
                if ($this->admin->hasRoute($route) && $this->admin->isGranted(strtoupper($route), $object)) {
                    $url = $this->admin->generateObjectUrl($route, $object);
                    break;
                }
            }
        }

        if (!$url) {
            $url = $this->admin->generateUrl('list');
        }

        return $url;
    }
}
