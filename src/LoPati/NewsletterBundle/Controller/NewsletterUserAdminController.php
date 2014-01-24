<?php
namespace LoPati\NewsletterBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
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
}
