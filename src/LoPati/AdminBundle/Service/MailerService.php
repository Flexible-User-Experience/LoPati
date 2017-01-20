<?php

namespace LoPati\AdminBundle\Service;

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
     * @var SendGrid
     */
    private $sendgrid;

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
     * @param SendGrid        $sendgrid
     */
    public function __construct(KernelInterface $kernel, Logger $logger, $sgApiKey, $sgFromName, $sgFromEmail, SendGrid $sendgrid)
    {
        $this->kernel      = $kernel;
        $this->logger      = $logger;
        $this->sgApiKey    = $sgApiKey;
        $this->sgFromName  = $sgFromName;
        $this->sgFromEmail = $sgFromEmail;
        $this->sendgrid    = $sendgrid;
    }

    /**
     * Deliver a notifitacion email task
     *
     * @param string $subject              Email subject
     * @param array  $emailDestinationList List of emails to deliver
     * @param mixed  $content              HTML email content
     *
     * @return integer
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
            $chunks = array_chunk($emailDestinationList, 950);
            /** @var array $chunk */
            foreach ($chunks as $chunk) {
                // slices of 950 emails per chunk
                $email = new Email();
                $email
                    ->setFrom($this->sgFromEmail)
                    ->setFromName($this->sgFromName)
                    ->setSubject($subject)
                    ->setTos(array($this->sgFromEmail))
                    ->setBccs($chunk)
                    ->setHtml($content);

                $this->sendgrid->send($email);
            }
        } catch (SendgridException $e) {
            $this->logger->error('ERROR: Sendgrid code: ' . $e->getCode());
            $this->logger->error('ERROR: Sendgrid msg: ' . $e->getMessage());
            foreach ($e->getErrors() as $er) {
                $this->logger->error('>>> Error: ' . $er);
            }

            return false;
        }

        return true;
    }
}
