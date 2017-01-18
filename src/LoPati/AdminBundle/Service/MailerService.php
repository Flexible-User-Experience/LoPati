<?php

namespace LoPati\AdminBundle\Service;

use LoPati\AdminBundle\Controller\NewsletterPageAdminController;
use SendGrid;
use SendGrid\Email;
use SendGrid\Exception as SendgridException;
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
     * @param SendGrid        $sendgrid
     * @param Logger          $logger
     * @param string          $sgApiKey
     */
    public function __construct(KernelInterface $kernel, SendGrid $sendgrid, Logger $logger, $sgApiKey)
    {
        $this->kernel   = $kernel;
        $this->sendgrid = $sendgrid;
        $this->sendgrid = $sendgrid;
        $this->logger   = $logger;
        $this->sgApiKey = $sgApiKey;
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
            foreach ($chunks as $chunk) {
                $email = new Email();
                $email
                    ->setFrom('info@lopati.cat')
                    ->setFromName('Centre d\'Art Lo Pati')
                    ->setSubject($subject)
                    ->setHtml($content);
                if ($this->kernel->getEnvironment() == 'prod') {
                    $email->setSmtpapiTos($chunk);
                } else {
                    $email->setSmtpapiTos(array(NewsletterPageAdminController::testEmail3));
                }
                $this->sendgrid->send($email); // => $result = is possible to read the result
            }
        } catch (SendgridException $e) {
            $this->logger->error('ERROR: Sendgrid code: ' . $e->getCode());
            foreach ($e->getErrors() as $er) {
                $this->logger->error('>>> Error: ' . $er);
            }

            return false;
        }

        return true;
    }
}
