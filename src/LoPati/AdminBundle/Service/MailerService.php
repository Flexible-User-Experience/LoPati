<?php

namespace LoPati\AdminBundle\Service;

use SendGrid;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class MailerService.
 *
 * @category Service
 *
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
     * Methods.
     */

    /**
     * Constructor.
     *
     * @param KernelInterface $kernel
     * @param Logger          $logger
     * @param string          $sgApiKey
     * @param string          $sgFromName
     * @param string          $sgFromEmail
     */
    public function __construct(KernelInterface $kernel, Logger $logger, $sgApiKey, $sgFromName, $sgFromEmail)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
        $this->sgApiKey = $sgApiKey;
        $this->sgFromName = $sgFromName;
        $this->sgFromEmail = $sgFromEmail;
        $this->sendgrid = new SendGrid($this->sgApiKey);
    }

    /**
     * Deliver a notifitacion email task.
     *
     * @param string $subject              Email subject
     * @param array  $emailDestinationList List of emails to deliver
     * @param mixed  $content              HTML email content
     *
     * @return bool True if everything goes well
     *
     * @throws \Exception
     */
    public function delivery($subject, array $emailDestinationList, $content)
    {
        if (count($emailDestinationList) == 0) {
            throw new \Exception('Email destination list empty');
        }

        try {
            // sliced recipients in portions of 100 items
            // (Sendgrid can send up to 1000 recipents per mail and 100 mails per connection)
            $chunks = array_chunk($emailDestinationList, 950);
            $from = new SendGrid\Email($this->sgFromName, $this->sgFromEmail);
            $to = new SendGrid\Email($this->sgFromName, $this->sgFromEmail);
            $mailContent = new SendGrid\Content('text/html', $content);
            /** @var array $chunk */
            foreach ($chunks as $chunk) {
                // slices of 950 emails per chunk
                $mail = new SendGrid\Mail($from, $subject, $to, $mailContent);

                /** @var string $destEmail */
                foreach ($chunk as $destEmail) {
                    $personalitzation = new SendGrid\Personalization();
                    $pTo = new SendGrid\Email(null, $destEmail);
                    $personalitzation->addTo($pTo);
                    $personalitzation->addSubstitution('%token%', $destEmail); // TODO change by token
                    $mail->addPersonalization($personalitzation);
                }

                $this->sendgrid->client->mail()->send()->post($mail);
            }
        } catch (\Exception $e) {
            $this->logger->error('ERROR: Sendgrid code: '.$e->getCode());
            $this->logger->error('ERROR: Sendgrid msg: '.$e->getMessage());

            return false;
        }

        return true;
    }
}
