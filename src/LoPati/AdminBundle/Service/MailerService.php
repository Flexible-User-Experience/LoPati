<?php

namespace LoPati\AdminBundle\Service;

use LoPati\AdminBundle\Entity\EmailToken;
use SendGrid;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

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

    /** @var RouterInterface */
    private $router;

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
     * @param RouterInterface $router
     * @param Logger          $logger
     * @param string          $sgApiKey
     * @param string          $sgFromName
     * @param string          $sgFromEmail
     */
    public function __construct(KernelInterface $kernel, RouterInterface $router, Logger $logger, $sgApiKey, $sgFromName, $sgFromEmail)
    {
        $this->kernel = $kernel;
        $this->router = $router;
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

    /**
     * Deliver a newsletter to a bunch of users.
     *
     * @param string             $subject              Email subject
     * @param array|EmailToken[] $emailDestinationList EmailToken collection of entities to deliver and change token
     * @param mixed              $content              HTML email content
     *
     * @return bool True if everything goes well
     *
     * @throws \Exception
     */
    public function batchDelivery($subject, array $emailDestinationList, $content)
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

                /** @var EmailToken $destEmail */
                foreach ($chunk as $destEmail) {
                    $personalitzation = new SendGrid\Personalization();
                    $pTo = new SendGrid\Email(null, $destEmail->getEmail());
                    $personalitzation->addTo($pTo);
                    $personalitzation->addSubstitution('%token%', $this->router->generate('newsletter_unsuscribe', array(
                        'token' => $destEmail->getToken(),
                    ), Router::ABSOLUTE_URL));
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
