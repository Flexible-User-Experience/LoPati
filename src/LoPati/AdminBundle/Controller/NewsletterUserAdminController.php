<?php

namespace LoPati\AdminBundle\Controller;

use LoPati\NewsletterBundle\Entity\NewsletterUser;
use Lopati\NewsletterBundle\Repository\NewsletterUserRepository;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class NewsletterUserAdminController extends Controller
{
    /**
     * Export action
     *
     * @param Request $request request
     *
     * @return Response|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \RuntimeException
     * @throws AccessDeniedException
     */
    public function exportAction(Request $request)
    {
        if (false === $this->admin->isGranted('EXPORT')) {
            throw new AccessDeniedException();
        }
        $what = Array(
            'id',
            'email',
            'idioma',
            'active',
            'fail',
            'createdString'
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

    public function batchActionGroup(ProxyQueryInterface $selectedModelQuery)
    {
        /** @var NewsletterUserRepository $nur */
        $nur = $this->get('doctrine.orm.entity_manager')->getRepository('NewsletterBundle:NewsletterUser');

        if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException();
        }
        /** @var Request $request */
        $request = $this->get('request');
        /** @var Session $session */
        $session = $this->get('session');
        $modelManager = $this->admin->getModelManager();
        $targets = $request->get('idx');
        if (count($targets) == 0) {
            $session->getFlashBag()->add('sonata_flash_info', 'Escull almenys 1 usuari per assignar al grup');

            return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
        }
        // do group logic
        try {
            foreach ($targets as $target) {
                /** @var NewsletterUser $user */
                $user = $nur->find($target);
                if ($user) {
//                    $user->addGroup()
                    $modelManager->update($user);
                } else {
                    throw new AccessDeniedException('User ID:' . $target . ' not exists');
                }
            }

        } catch (\Exception $e) {
            $session->getFlashBag()->add('sonata_flash_error', 'Error durant l\'asignació a grup');

            return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
        }

        $session->getFlashBag()->add('sonata_flash_success', 'Asignació a grup efectuada correctament');

        return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
    }
}
