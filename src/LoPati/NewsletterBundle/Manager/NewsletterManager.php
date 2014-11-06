<?php

namespace LoPati\NewsletterBundle\Manager;

use Hip\MandrillBundle\Message;
use LoPati\NewsletterBundle\Entity\Newsletter;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Hip\MandrillBundle\Dispatcher;

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
     * @var Dispatcher
     */
    protected $mandrilDispatcher;

    /**
     * Constructor
     *
     * @param EngineInterface $templatingEngine
     * @param Translator      $translator
     * @param Dispatcher      $mandrilDispatcher
     */
    public function __construct(EngineInterface $templatingEngine, Translator $translator, Dispatcher $mandrilDispatcher)
    {
        $this->templatingEngine = $templatingEngine;
        $this->translator = $translator;
        $this->mandrilDispatcher = $mandrilDispatcher;
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
     * @return array|bool
     * @throws \Exception
     */
    public function sendMandrilMessage($subject, array $emailDestinationList, $content)
    {
        if (count($emailDestinationList) == 0) {
            throw new \Exception('Email destination list empty');
        }

        $message = new Message();
        $message
            ->setSubject($subject)
            ->setFromName('Centre d\'Art Lo Pati')
            ->setFromEmail('butlleti@lopati.cat')
            ->setTrackClicks(true)
            ->setHtml($content)
        ;

        foreach ($emailDestinationList as $email) {
            $message->addTo($email, null, 'bcc');
        }

        return $this->mandrilDispatcher->send($message);
    }
}
