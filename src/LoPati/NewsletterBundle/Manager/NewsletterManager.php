<?php

namespace LoPati\NewsletterBundle\Manager;

use LoPati\NewsletterBundle\Entity\Newsletter;
use Mandrill;
use SendGrid;
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
     * @var Mandrill
     */
    protected $mandrilClient;

    /**
     * @var SendGrid
     */
    protected $sendgridClient;

    /**
     * Constructor
     *
     * @param EngineInterface $templatingEngine
     * @param Translator      $translator
     * @param Dispatcher      $mandrilDispatcher
     * @param Mandrill        $mandrilClient
     * @param SendGrid        $sendgripClient
     */
    public function __construct(EngineInterface $templatingEngine, Translator $translator, Dispatcher $mandrilDispatcher, Mandrill $mandrilClient, SendGrid $sendgripClient)
    {
        $this->templatingEngine = $templatingEngine;
        $this->translator = $translator;
        $this->mandrilDispatcher = $mandrilDispatcher;
        $this->mandrilClient = $mandrilClient;
        $this->sendgripClient = $sendgripClient;
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

        $message = new SendGrid\Email();
        $message
            ->setSubject($subject)
            ->setFromName('Centre d\'Art Lo Pati')
            ->setFrom('butlleti@lopati.cat')
            ->setHtml($content)
        ;

        foreach ($emailDestinationList as $email) {
            $message->addBcc($email);
        }

        return $this->sendgridClient->send($message);
    }

    /**
     * Get Mandrill rejects list and clear up local database
     */
    public function getRejectList()
    {
        return $this->mandrilClient->rejects->getList();
    }
}
