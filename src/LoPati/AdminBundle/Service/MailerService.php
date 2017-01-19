<?php

namespace LoPati\AdminBundle\Service;

use \SendGrid;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class MailerService
 *
 * @category Service
 * @package  LoPati\AdminBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class MailerService
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var \SendGrid
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
     * @var string
     */
    private $sgFromName;

    /**
     * @var string
     */
    private $sgFromEmail;

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
     * @param KernelInterface $kernel
     * @param Logger          $logger
     * @param string          $sgApiKey
     * @param string          $sgFromName
     * @param string          $sgFromEmail
     */
    public function __construct(KernelInterface $kernel, Logger $logger, $sgApiKey, $sgFromName, $sgFromEmail)
    {
        $this->kernel      = $kernel;
        $this->logger      = $logger;
        $this->sgApiKey    = $sgApiKey;
        $this->sgFromName  = $sgFromName;
        $this->sgFromEmail = $sgFromEmail;
        $this->sendgrid    = new \SendGrid($sgApiKey);
    }

    /**
     * Deliver email notifitacion task
     *
     * @param string $subject
     * @param array  $emailDestinationList
     * @param mixed  $content
     *
     * @return array|bool
     * @throws \Exception
     */
    public function delivery($subject, array $emailDestinationList, $content)
    {
        if (count($emailDestinationList) == 0) {
            throw new \Exception('Email destination list empty');
        }

        try {
            // slice recipients in portions of 100 items
            //   Sendgrid can send up to 1000 recipents per mail and 100 mails per connection
            $chunks = array_chunk($emailDestinationList, 500);
            $from        = new SendGrid\Email($this->sgFromName, $this->sgFromEmail);
            $to          = new SendGrid\Email($this->sgFromName, $this->sgFromEmail);
            $mailContent = new SendGrid\Content('text/html', $content);
            /** @var array $chunk */
            foreach ($chunks as $chunk) {
                $mail = new SendGrid\Mail($from, $subject, $to, $mailContent);
//                $personalizations = $mail->personalization->getPersonalizations();
                /** @var SendGrid\Personalization $mail->personalization */
                $mail->personalization->addSubstitution('-token-', 'my-token');
                /** @var string $destEmail */
                foreach ($chunk as $destEmail) {
                    $bcc = new SendGrid\Personalization();
                    $mail->addPersonalization($bcc->addBcc($destEmail));

//                    $this->sendgrid->client->mail()->send()->post($mail);
                }



//                $email = new SendGrid\Email();
//                $email
//                    ->setFrom('info@lopati.cat')
//                    ->setFromName('Centre d\'Art Lo Pati')
//                    ->setSubject($subject)
//                    ->setHtml($content);
//                if ($this->kernel->getEnvironment() == 'prod') {
//                    $email->setSmtpapiTos($chunk);
//                } else {
//                    $email->setSmtpapiTos(array(NewsletterPageAdminController::testEmail3));
//                }
//                $this->sendgrid->send($email); // => $result = is possible to read the result
            }
        } catch (\Exception $e) {
            $this->logger->error('ERROR: Sendgrid code: ' . $e->getCode());
            $this->logger->error('ERROR: Sendgrid msg: ' . $e->getMessage());
//            foreach ($e->->getErrors() as $er) {
//                $this->logger->error('>>> Error: ' . $er);
//            }

            return false;
        }

        return true;
    }
}
