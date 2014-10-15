<?php

namespace LoPati\NewsletterBundle\Manager;

use LoPati\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class NewsletterManager {
    /**
     * @var EngineInterface
     */
    protected $templatingEngine;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor
     *
     * @param EngineInterface $templatingEngine
     * @param Translator      $translator
     */
    public function __construct(EngineInterface $templatingEngine, Translator $translator)
    {
        $this->templatingEngine = $templatingEngine;
        $this->translator = $translator;
    }

    /**
     * Get translation helper
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
     * Build newsletter content
     *
     * @param int        $id
     * @param Newsletter $newsletter
     * @param string     $host
     * @param string     $lang
     *
     * @return array
     */
    public function buildNewsletterContentArray($id, $newsletter, $host, $lang)
    {
        $result = array(
            'id'                       => $id,
            'host'                     => $host,
            'pagines'                  => $newsletter,
            'idioma'                   => $lang,
            'visualitzar_correctament' => $this->getTrans('newsletter.visualitzar', $lang),
            'baixa'                    => $this->getTrans('newsletter.baixa', $lang),
            'lloc'                     => $this->getTrans('newsletter.loc', $lang),
            'data'                     => $this->getTrans('newsletter.data', $lang),
            'publicat'                 => $this->getTrans('newsletter.publicat', $lang),
            'links'                    => $this->getTrans('newsletter.links', $lang),
            'organitza'                => $this->getTrans('newsletter.organitza', $lang),
            'suport'                   => $this->getTrans('newsletter.suport', $lang),
            'follow'                   => $this->getTrans('newsletter.follow', $lang),
            'colabora'                 => $this->getTrans('newsletter.colabora', $lang),
            'butlleti'                 => $this->getTrans('newsletter.butlleti', $lang),
        );

        return $result;
    }
}
