<?php

namespace LoPati\AdminBundle\Admin\Block;

use LoPati\AdminBundle\Form\Type\IsolatedNewsletterXlsFileUploadFormType;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormFactory;

/**
 * Class IsolatedNewsletterXlsImportBlock
 *
 * @category Block
 * @package  ECVulco\AppBundle\Admin\Block
 * @author   David Romaní <david@flux.cat>
 */
class IsolatedNewsletterXlsImportBlock extends AbstractBlockService
{
    /**
     * @var FormFactory
     */
    private $ff;

    /**
     * @var Router
     */
    private $rs;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * Constructor
     *
     * @param string          $name
     * @param EngineInterface $templating
     * @param FormFactory     $formFactory
     * @param Router          $router
     */
    public function __construct($name, EngineInterface $templating, FormFactory $formFactory, Router $router)
    {
        parent::__construct($name, $templating);
        $this->ff = $formFactory;
        $this->rs = $router;
    }

    /**
     * Execute
     *
     * @param BlockContextInterface $blockContext
     * @param Response              $response
     *
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $form = $this->ff->create(
            IsolatedNewsletterXlsFileUploadFormType::class,
            null,
            array(
                'action' => $this->rs->generate('sonata_admin_dashboard'),
            )
        );

        return $this->renderResponse(
            $blockContext->getTemplate(),
            array(
                'block'    => $blockContext->getBlock(),
                'settings' => $blockContext->getSettings(),
                'title'    => 'Importar XLS usuaris newsletter',
                'form'     => $form->createView(),
            ),
            $response
        );
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'isolated_newsletter_xls_import';
    }

    /**
     * Define the default options for the block.
     *
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'title'    => 'Title',
                'content'  => 'Default content',
                'template' => 'AdminBundle:IsolatedNewsletter:block_xls_import.html.twig',
            )
        );
    }
}
