<?php

namespace LoPati\NewsletterBundle\Listener;

use Tystr\Bundle\SendgridBundle\Event\WebHookEvent;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class SendgridListener
 *
 * @category Listener
 * @package  ASBAE\AppBundle\Listener
 * @author   David Romaní <david@flux.cat>
 */
class SendgridListener
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     *
     *
     * Methods
     *
     *
     */

    /**
     * SendgridListener constructor
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailProcessed(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Email processed: ' . $event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailDropped(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Email dropped: ' . $event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailDelivered(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Email delivered: ' . $event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailBounce(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Address bounced: ' . $event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailSpam(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Address spammed: ' . $event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailUnsubscribe(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Address unsubscribed: ' . $event->getEmail()->getOrElse('!!! unknown email !!!'));
    }
}
