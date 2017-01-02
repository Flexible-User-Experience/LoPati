<?php

namespace LoPati\AdminBundle\Service;

use SendGrid;
use SendGrid\Email;
use SendGrid\Exception as SendgridException;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class MailerService
 *
 * @category Service
 * @package  FinquesFarnos\AppBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class MailerService
{
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
     * @param SendGrid $sendgrid
     * @param Logger   $logger
     * @param string   $sgApiKey
     */
    public function __construct(SendGrid $sendgrid, Logger $logger, $sgApiKey)
    {
        $this->sendgrid = $sendgrid;
        $this->logger = $logger;
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
                    ->setFrom('butlleti@lopati.cat')
                    ->setFromName('Centre d\'Art Lo Pati')
                    ->setSubject($subject)
                    ->setSmtpapiTos($chunk)
                    ->setHtml($content);
                $this->sendgrid->send($email); // => $result = is possible to read the result
            }

            return true;

        } catch (SendgridException $e) {
            $this->logger->error('Error ' . $e->getCode() . ' al enviar el test.');
            foreach ($e->getErrors() as $er) {
                $this->logger->error('Error ' . $er);
            }
        }

        return false;
    }
}
