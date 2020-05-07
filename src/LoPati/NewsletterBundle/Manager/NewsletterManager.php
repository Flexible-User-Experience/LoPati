<?php

namespace LoPati\NewsletterBundle\Manager;

use LoPati\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class NewsletterManager.
 *
 * @category Manager
 *
 * @author   David Romaní <david@flux.cat>
 */
class NewsletterManager
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Constructor.
     *
     * @param EngineInterface     $templatingEngine
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EngineInterface $templatingEngine,
        TranslatorInterface $translator
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->translator = $translator;
    }

    /**
     * Get translation helper.
     *
     * @param $msg
     * @param $lang
     *
     * @return string
     */
    private function getTrans($msg, $lang)
    {
        return $this->translator->trans(
            $msg,
            array(),
            'messages',
            $lang
        );
    }

    /**
     * Build newsletter content.
     *
     * @param int         $id
     * @param Newsletter  $newsletter
     * @param string      $host
     * @param string      $lang
     * @param string|null $token
     *
     * @return array
     */
    public function buildNewsletterContentArray($id, $newsletter, $host, $lang, $token = null)
    {
        return array(
            'id' => $id,
            'host' => $host,
            'pagines' => $newsletter,
            'idioma' => $lang,
            'visualitzar_correctament' => $this->getTrans('newsletter.visualitzar', $lang),
            'baixa' => $this->getTrans('newsletter.baixa', $lang),
            'lloc' => $this->getTrans('newsletter.lloc', $lang),
            'data' => $this->getTrans('newsletter.data', $lang),
            'publicat' => $this->getTrans('newsletter.publicat', $lang),
            'links' => $this->getTrans('newsletter.links', $lang),
            'organitza' => $this->getTrans('newsletter.organitza', $lang),
            'suport' => $this->getTrans('newsletter.suport', $lang),
            'follow' => $this->getTrans('newsletter.follow', $lang),
            'colabora' => $this->getTrans('newsletter.colabora', $lang),
            'butlleti' => $this->getTrans('newsletter.butlleti', $lang),
            'token' => $token,
        );
    }
}
