<?php

namespace LoPati\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use LoPati\AdminBundle\Entity\EmailNameToken;
use LoPati\AdminBundle\Entity\EmailToken;
use LoPati\AdminBundle\Form\Type\IsolatedNewsletterXlsFileUploadFormType;
use LoPati\AdminBundle\Service\MailerService;
use LoPati\AdminBundle\Service\NewsletterUserManagementService;
use LoPati\NewsletterBundle\Entity\IsolatedNewsletter;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Enum\NewsletterStatusEnum;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class IsolatedNewsletterAdminController.
 *
 * @category AdminController
 */
class IsolatedNewsletterAdminController extends Controller
{
    /**
     * @param Request|null $request
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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
        /** @var string $content message content */
        $content = $this->renderView(
            'AdminBundle:IsolatedNewsletter:preview.html.twig',
            array(
                'newsletter' => $object,
                'show_top_bar' => false,
                'show_bottom_bar' => true,
            )
        );

        if ($object->getGroup()) {
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->getActiveUsersByGroup($object->getGroup());
        } else {
            $users = $em->getRepository('NewsletterBundle:NewsletterUser')->findAllEnabled();
        }

        $emailsDestinationList = array();
        /** @var NewsletterUser $user */
        foreach ($users as $user) {
            $emailsDestinationList[] = new EmailToken($user->getEmail(), $user->getToken());
        }

        $result = $ms->batchDelivery($object->getSubject(), $emailsDestinationList, $content);
        if ($result == false) {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_error',
                'S\'ha produït un ERROR en enviar el newsletter. Contacta amb l\'administrador del sistema'
            );
        } else {
            $object->setState(NewsletterStatusEnum::SENDED);
            $object->setBeginSend(new \DateTime('now'));
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_success',
                'El newsletter s\'ha enviat a totes les bústies.'
            );
        }

        return $this->redirect('../list');
    }

    /**
     * @param Request|null $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
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

        return $this->renderWithExtraParams(
            'AdminBundle:IsolatedNewsletter:preview.html.twig',
            array(
                'newsletter' => $object,
                'show_top_bar' => true,
                'show_bottom_bar' => false,
            )
        );
    }

    /**
     * @param Request|null $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     * @throws \Doctrine\ORM\OptimisticLockException
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
        /** @var string $content message content */
        $content = $this->renderView(
            'AdminBundle:IsolatedNewsletter:preview.html.twig',
            array(
                'newsletter' => $object,
                'show_top_bar' => false,
                'show_bottom_bar' => false,
            )
        );

        $result = $ms->batchDelivery('[TEST] '.$object->getSubject(), $this->getTestEmailsDestinationList(), $content);
        if ($result === false) {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_error',
                'S\'ha produït un ERROR en enviar el test.'
            );
        } else {
            $object->setTested(true);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_success',
                'S\'ha enviat correctament un email de test a les bústies: '.$this->getParameter('email_address_test_1').', '.$this->getParameter('email_address_test_2').' i '.$this->getParameter('email_address_test_3')
            );
        }

        return $this->redirect('../list');
    }

    /**
     * @param Request|null $request
     *
     * @return RedirectResponse
     *
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
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
                        /** @var NewsletterUserManagementService $ums */
                        $ums = $this->get('app.newsletter_user_manager.service');
                        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($filename->getRealPath());
                        $wrongImportsCounter = 0;
                        $rightImportsCounter = 0;
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
                                        // name
                                        if ($cell->getColumn() === 'A') {
                                            $importedUser->setName($cell->getValue());
                                        }
                                        // email
                                        if ($cell->getColumn() === 'B') {
                                            $importedUser->setEmail($cell->getValue());
                                        }
                                        // group
                                        if ($cell->getColumn() === 'C') {
                                            $importedUser->setImprotedGroup($cell->getValue());
                                        }
                                        // postal code
                                        if ($cell->getColumn() === 'D') {
                                            $importedUser->setPostalCode($cell->getValue());
                                        }
                                        // phone
                                        if ($cell->getColumn() === 'E') {
                                            $importedUser->setPhone($cell->getValue());
                                        }
                                        // birthyear
                                        if ($cell->getColumn() === 'F') {
                                            $importedUser->setBirthyear($cell->getValue());
                                        }
                                    }
                                }
                                $importedUser->setActive(true);
                                $importedUser->setIdioma('ca');
                                $result = $ums->writeUser($importedUser);

                                if ($result === false) {
                                    ++$wrongImportsCounter;
                                    $this->get('session')->getFlashBag()->add(
                                        'app_flash_error',
                                        '['.$worksheet->getTitle().'] [fila '.$row->getRowIndex().'] '.$importedUser->getImportXlsString()
                                    );
                                } else {
                                    ++$rightImportsCounter;
                                }
                            }
                        }
                        $this->get('session')->getFlashBag()->add('app_flash', 'S\'ha importat un total de: '.($wrongImportsCounter + $rightImportsCounter).' registres.');
                        $this->get('session')->getFlashBag()->add('app_flash', 'Registres importats correctament: '.$rightImportsCounter);
                        $this->get('session')->getFlashBag()->add('app_flash', 'Errors detectats: '.$wrongImportsCounter);
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
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     */
    public function previewRGPD2018NewsletterAgreementAction()
    {
        return $this->renderWithExtraParams(
            'AdminBundle:RGPD2018NewsletterAgreement:preview.html.twig',
            array(
                'show_top_bar' => true,
                'show_bottom_bar' => true,
            )
        );
    }

    /**
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     * @throws \Exception
     */
    public function testRGPD2018NewsletterAgreementAction()
    {
        /** @var MailerService $ms */
        $ms = $this->container->get('app.mailer.service');
        /** @var string $content message content */
        $content = $this->renderView(
            'AdminBundle:RGPD2018NewsletterAgreement:preview.html.twig',
            array(
                'show_top_bar' => false,
                'show_bottom_bar' => false,
            )
        );

        $edl = array(
            new EmailNameToken($this->getParameter('email_address_test_1'), 'Direcció', 'fake-token-1'),
            new EmailNameToken($this->getParameter('email_address_test_2'), 'Comunicació', 'fake-token-2'),
            new EmailNameToken($this->getParameter('email_address_test_3'), 'Test', 'obzl55srmiow4k48c0wsgwo4wk8kwc8'),
        );

        $result = $ms->batchDeliveryRGPD2018NewsletterAgreement('[TEST] [GDPR] Acceptar subscripció newsletter · Aceptar subscripción newsletter · Accept newsletter subscription', $edl, $content);
        if ($result == false) {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_error',
                'S\'ha produït un ERROR en enviar el test.'
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_success',
                'S\'ha enviat correctament un email de test a les bústies: '.$this->getParameter('email_address_test_1').','.$this->getParameter('email_address_test_2').' i '.$this->getParameter('email_address_test_3')
            );
        }

        return $this->redirectToList();
    }

    /**
     * @return Response
     *
     * @throws NotFoundHttpException     If the object does not exist
     * @throws AccessDeniedHttpException If access is not granted
     * @throws \Exception
     */
    public function sendRGPD2018NewsletterAgreementAction()
    {
        /** @var MailerService $ms */
        $ms = $this->container->get('app.mailer.service');
        /** @var string $content message content */
        $content = $this->renderView(
            'AdminBundle:RGPD2018NewsletterAgreement:preview.html.twig',
            array(
                'show_top_bar' => false,
                'show_bottom_bar' => false,
            )
        );

        $edl = array();
        $newsletterUsers = $this->container->get('doctrine')->getRepository('NewsletterBundle:NewsletterUser')->findEnabledWithoutFails();
        /** @var NewsletterUser $newsletterUser */
        foreach ($newsletterUsers as $newsletterUser) {
            $edl[] = new EmailNameToken($newsletterUser->getEmail(), $newsletterUser->getName() ? $newsletterUser->getName() : $newsletterUser->getEmail(), $newsletterUser->getToken());
            $newsletterUser->setActive(false);
        }
        // set all users to disabled newsletter deliveries
        $this->container->get('doctrine')->getManager()->flush();

        $result = $ms->batchDeliveryRGPD2018NewsletterAgreement('[GDPR] Acceptar subscripció newsletter · Aceptar subscripción newsletter · Accept newsletter subscription', $edl, $content);
        if ($result == false) {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_error',
                'S\'ha produït un ERROR en enviar el butlletí.'
            );
        } else {
            $this->get('session')->getFlashBag()->add(
                'sonata_flash_success',
                'S\'ha enviat correctament el email a '.count($newsletterUsers).' usuaris'
            );
        }

        return $this->redirectToList();
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
    private function getTestEmailsDestinationList()
    {
        /** @var array $edl email destinations list only for developer */
        $edl = array(
            new EmailToken($this->getParameter('email_address_test_1'), 'fake-token-1'),
            new EmailToken($this->getParameter('email_address_test_2'), 'fake-token-2'),
            new EmailToken($this->getParameter('email_address_test_3'), 'fake-token-3'),
        );

        return $edl;
    }
}
