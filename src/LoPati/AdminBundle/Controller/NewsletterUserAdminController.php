<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use RuntimeException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class NewsletterUserAdminController
 *
 * @category AdminController
 * @package  LoPati\AdminBundle\Controller
 * @author   David Romaní <david@flux.cat>
 */
class NewsletterUserAdminController extends Controller
{
    /**
     * Export action
     *
     * @param Request $request request
     *
     * @return Response|StreamedResponse
     *
     * @throws RuntimeException
     * @throws AccessDeniedException
     */
    public function exportAction(Request $request)
    {
        if (false === $this->admin->isGranted('EXPORT')) {
            throw new AccessDeniedException();
        }
        $what = Array(
            'id',
            'createdString',
            'name',
            'email',
            'postalCode',
            'groupsString',
            'phone',
            'birthyear',
            'fail',
            'active',
        );
        $format = $request->get('format');
        $allowedExportFormats = (array)$this->admin->getExportFormats();
        if (!in_array($format, $allowedExportFormats)) {
            throw new \RuntimeException(
                sprintf(
                    'Export in format `%s` is not allowed for class: `%s`. Allowed formats are: `%s`',
                    $format,
                    $this->admin->getClass(),
                    implode(', ', $allowedExportFormats)
                )
            );
        }
        $filename = sprintf(
            'export_%s_%s.%s',
            strtolower(substr($this->admin->getClass(), strripos($this->admin->getClass(), '\\') + 1)),
            date('Y_m_d_H_i_s', strtotime('now')),
            $format
        );
        $datagrid = $this->admin->getDatagrid();
        $datagrid->buildPager();
        $flick = $this->admin->getModelManager()->getDataSourceIterator($datagrid, $what);

        return $this->get('sonata.admin.exporter')->getResponse($format, $filename, $flick);
    }

    /**
     * Batch pre set group action
     *
     * @param ProxyQueryInterface $selectedModelQuery
     *
     * @return RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function batchActionGroup(ProxyQueryInterface $selectedModelQuery)
    {
        if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException();
        }
        /** @var Router $router */
        $router = $this->get('router');
        /** @var Session $session */
        $session = $this->get('session');
        /** @var Request $request */
        $request = $this->get('request');
        $session->getFlashBag()->add('setgroupuids', $request->get('idx'));

        return new RedirectResponse($router->generate('admin_lopati_newsletter_newsletteruser_group'));
    }

    /**
     * Pre set group action
     *
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function groupAction()
    {
        if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException();
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $groups = $em->getRepository('NewsletterBundle:NewsletterGroup')->getActiveItemsSortByNameQuery()->getResult();
        $setgroupuids = $this->get('session')->getFlashBag()->get('setgroupuids');
        $users = new ArrayCollection();
        foreach ($setgroupuids[0] as $uid) {
            $user = $em->getRepository('NewsletterBundle:NewsletterUser')->find($uid);
            if ($user) {
                $users->add($user);
            }
        }

        return $this->renderWithExtraParams('AdminBundle:Newsletter:AddUserToGroup/preset_group_form.html.twig', array(
                'users'  => $users,
                'groups' => $groups,
            ));
    }

    /**
     * Final set group action
     *
     * @throws AccessDeniedException
     * @throws OptimisticLockException
     */
    public function setgroupAction()
    {
        if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException();
        }
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var Request $request */
        $request = $this->get('request');
        $group = $request->get('group');
        $users = $request->get('users');
        if (is_null($group)) {
            throw new AccessDeniedException();
        }
        $entityGroup = $em->getRepository('NewsletterBundle:NewsletterGroup')->find($group);
        if ($entityGroup) {
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $entityUser = $em->getRepository('NewsletterBundle:NewsletterUser')->find($user);
                    if ($entityUser && !$entityUser->getGroups()->contains($entityGroup)) {
                        $entityGroup->addUser($entityUser);
                    }
                }
                $em->persist($entityGroup);
                $em->flush($entityGroup);
                $this->get('session')->getFlashBag()->add('sonata_flash_success', 'S\'ha assignat el grup ' . $entityGroup->getName() . ' a ' . count($users) . ' usuari' . (count($users) > 1 ? 's' : '') . ' correctament.');
            } else {
                $this->get('session')->getFlashBag()->add('sonata_flash_error', 'Error al assignar el grup, no has escollit cap usuari.');
            }
        } else {
            $this->get('session')->getFlashBag()->add('sonata_flash_error', 'Error al assignar el grup ' . $entityGroup->getName() . ' a ' . count($users) . ' usuari' . (count($users) > 1 ? 's' : '') . '.');
        }

        return $this->redirect('list');
    }
}
