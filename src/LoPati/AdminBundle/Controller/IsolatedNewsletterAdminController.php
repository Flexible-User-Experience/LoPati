<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\AdminBundle\Form\Type\IsolatedNewsletterXlsFileUploadFormType;
use LoPati\AdminBundle\Service\MailerService;
use LoPati\NewsletterBundle\Entity\IsolatedNewsletter;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        if ($object->getGroup()) {
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersByGroup($object->getGroup());
        } else {
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->findAllEnabled();
        }

        /** @var NewsletterUser $user */
        foreach ($users as $user) {
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
     * @return RedirectResponse
     * @throws NotFoundHttpException If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function uploadAction(Request $request = null)
    {
        $form = $this->createForm(IsolatedNewsletterXlsFileUploadFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Filesystem $filesystem */
            $filesystem = $this->get('filesystem');
            /** @var UploadedFile $filename */
            $filename = $form->getData()['file'];
            if ($filename) {
                if (!$filesystem->exists($filename)) {
                    $this->get('session')->getFlashBag()->add(
                        'app_flash_error',
                        'No s\'ha trobat el fitxer adjunt.'
                    );
                } else {
                    $valid = false;
                    $types = array('Excel2007', 'Excel5', 'Excel2003XML');
                    foreach ($types as $type) {
                        $reader = \PHPExcel_IOFactory::createReader($type);
                        if ($reader->canRead($filename->getRealPath())) {
                            $valid = true;
                            break;
                        }
                    }
                    if ($valid) {
                        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($filename->getRealPath());
                        /** @var \PHPExcel_Worksheet $worksheet */
                        foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
                            /** @var \PHPExcel_Worksheet_Row $row */
                            foreach ($worksheet->getRowIterator() as $row) {
                                $cellIterator = $row->getCellIterator();
                                $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
                                $importedUser = new NewsletterUser();
                                /** @var \PHPExcel_Cell $cell */
                                foreach ($cellIterator as $cell) {
                                    if (!is_null($cell)) {
                                        if ($cell->getColumn() === 'A') {
                                            $importedUser->setName($cell->getCalculatedValue());
                                        }
                                        if ($cell->getColumn() === 'B') {
                                            $importedUser->setName($cell->getCalculatedValue());
                                        }
                                        if ($cell->getColumn() === 'C') {
                                            $importedUser->setGroups($cell->getCalculatedValue());
                                        }
                                        if ($cell->getColumn() === 'D') {
                                            $importedUser->setCity($cell->getCalculatedValue());
                                        }
                                        if ($cell->getColumn() === 'E') {
                                            $importedUser->setAge($cell->getCalculatedValue());
                                        }
                                        if ($cell->getColumn() === 'F') {
                                            $importedUser->setPhone($cell->getCalculatedValue());
                                        }

                                        $this->get('session')->getFlashBag()->add(
                                            'app_flash',
                                            '[' . $worksheet->getTitle() . '] [fila ' . $row->getRowIndex() . '] · [cel·la ' . $cell->getColumn() . '] ' . $cell->getCalculatedValue()
                                        );

                                    }
                                }
                            }
                        }
                    } else {
                        $this->get('session')->getFlashBag()->add(
                            'app_flash_error',
                            'L\'arxiu NO és compatible amb XLS.'
                        );
                    }
                }
            } else {
                $this->get('session')->getFlashBag()->add(
                    'app_flash_error',
                    'No has adjuntat cap fitxer.'
                );
            }
        }

        return $this->redirectToRoute('sonata_admin_dashboard');
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
