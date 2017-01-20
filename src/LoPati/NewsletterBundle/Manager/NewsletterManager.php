<?php

namespace LoPati\NewsletterBundle\Manager;

use LoPati\AdminBundle\Service\MailerService;
use LoPati\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class NewsletterManager
 *
 * @category Manager
 * @package  LoPati\NewsletterBundle\Manager
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
     * @var MailerService
     */
    private $mailerService;

    /**
     * Constructor
     *
     * @param EngineInterface     $templatingEngine
     * @param TranslatorInterface $translator
     * @param MailerService       $mailerService
     */
    public function __construct(
        EngineInterface $templatingEngine,
        TranslatorInterface $translator,
        MailerService $mailerService
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->translator       = $translator;
        $this->mailerService    = $mailerService;
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
        $result = array(
            'id'                       => $id,
            'host'                     => $host,
            'pagines'                  => $newsletter,
            'idioma'                   => $lang,
            'visualitzar_correctament' => $this->getTrans('newsletter.visualitzar', $lang),
            'baixa'                    => $this->getTrans('newsletter.baixa', $lang),
            'lloc'                     => $this->getTrans('newsletter.lloc', $lang),
            'data'                     => $this->getTrans('newsletter.data', $lang),
            'publicat'                 => $this->getTrans('newsletter.publicat', $lang),
            'links'                    => $this->getTrans('newsletter.links', $lang),
            'organitza'                => $this->getTrans('newsletter.organitza', $lang),
            'suport'                   => $this->getTrans('newsletter.suport', $lang),
            'follow'                   => $this->getTrans('newsletter.follow', $lang),
            'colabora'                 => $this->getTrans('newsletter.colabora', $lang),
            'butlleti'                 => $this->getTrans('newsletter.butlleti', $lang),
            'token'                    => $token,
        );

        return $result;
    }

    /**
     * Send Mandril message
     *
     * @param string $subject
     * @param array  $emailDestinationList
     * @param mixed  $content
     *
     * @return boolean
     * @throws \Exception
     */
    public function sendMandrilMessage($subject, array $emailDestinationList, $content)
    {
        if (count($emailDestinationList) == 0) {
            throw new \Exception('Email destination list empty');
        }

        return $this->mailerService->delivery($subject, $emailDestinationList, $content);
    }
}
