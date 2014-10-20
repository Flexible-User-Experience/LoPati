<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use Lopati\NewsletterBundle\Repository\NewsletterUserRepository;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;
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

    /**
     * Batch pre set group action
     *
     * @param ProxyQueryInterface $selectedModelQuery
     *
     * @return RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function batchActionGroup(ProxyQueryInterface $selectedModelQuery)
    {
        if ($this->admin->isGranted('EDIT') === false || $this->admin->isGranted('DELETE') === false) {
            throw new AccessDeniedException();
        }
        /** @var Router $router */
        $router = $this->get('router');
//        $modelManager = $this->admin->getModelManager();
        $selectedModels = $selectedModelQuery->execute();

        //$targets = $request->get('idx');
        // do group logic
//        foreach ($targets as $target) {
//            /** @var NewsletterUser $user */
//            $user = $nur->find($target);
//            if ($user) {
////                    $user->addGroup()
//                $modelManager->update($user);
//            } else {
//                throw new AccessDeniedException('User ID:' . $target . ' not exists');
//            }
//        }

        return new RedirectResponse($router->generate('admin_lopati_newsletter_newsletteruser_group'));
    }

    /**
     * Pre set group action
     *
     * @param Request $request
     *
     * @return Response
     */
    public function groupAction(Request $request)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $availableGroups = $em->getRepository('NewsletterBundle:NewsletterGroup')->getActiveItemsSortByNameQuery()->getResult();

        return $this->render('AdminBundle:Newsletter:preset.group.html.twig', array(
                'groups' => $availableGroups,
            ));
    }
}
