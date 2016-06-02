<?php

namespace LoPati\NewsletterBundle\Manager;

use LoPati\NewsletterBundle\Entity\Newsletter;
use SendGrid;
use SendGrid\Email;
use SendGrid\Exception as SendgridException;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Bridge\Monolog\Logger;

class NewsletterManager
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var SendGrid
     */
    private $sendgrid;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $sgApiKey;

    /**
     * Constructor
     *
     * @param EngineInterface $templatingEngine
     * @param Translator      $translator
     * @param SendGrid        $sendgrid
     * @param Logger          $logger
     * @param string          $sgApiKey
     */
    public function __construct(EngineInterface $templatingEngine, Translator $translator, SendGrid $sendgrid, Logger $logger, $sgApiKey)
    {
        $this->templatingEngine = $templatingEngine;
        $this->translator = $translator;
        $this->sendgrid = $sendgrid;
        $this->logger = $logger;
        $this->sgApiKey = $sgApiKey;
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

//        $sg = new SendGrid($this->sgApiKey, array('turn_off_ssl_verification' => true));
//        $message = new SendGrid\Email();
//        $message
//            ->addTo('butlleti@lopati.cat')
//            ->setSubject($subject)
//            ->setFromName('Centre d\'Art Lo Pati')
//            ->setFrom('butlleti@lopati.cat')
//            ->setHtml($content)
//        ;

//        foreach ($emailDestinationList as $email) {
//            $message->addBcc($email);
//            $message->addSmtpapiTo($email);
//        }

        try {
            // slice recipients in portions of 100 items
            //   Sendgrid can send up to 1000 recipents per mail and 100 mails per connection
            $chunks = array_chunk($emailDestinationList, 500);
            foreach ($chunks as $chunk) {
                $email = new Email();
                $email
                    ->setFrom('butlleti@lopati.cat')
                    ->setFromName('Centre d\'Art Lo Pati')
                    ->setSubject($subject)
                    ->setSmtpapiTos($chunk)
                    ->setHtml($content)
                ;
                $this->sendgrid->send($email); // => $result = is possible to read the result
            }

            return true;
        } catch (SendgridException $e) {
            $this->logger->error('Error ' . $e->getCode() . ' al enviar el test.');
            foreach($e->getErrors() as $er) {
                $this->logger->error('Error ' . $er);
            }
        }

        return false;
    }
}
