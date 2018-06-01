<?php

namespace LoPati\NewsletterBundle\Listener;

use Doctrine\ORM\EntityManager;
use Tystr\Bundle\SendgridBundle\Event\WebHookEvent;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class SendgridListener.
 *
 * @category Listener
 */
class SendgridListener
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Methods.
     */

    /**
     * SendgridListener constructor.
     *
     * @param EntityManager $em
     * @param Logger        $logger
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailProcessed(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Email processed: '.$event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailDropped(WebHookEvent $event)
    {
        $this->logger->warning('[SDL] Email dropped: '.$event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailDelivered(WebHookEvent $event)
    {
        $this->logger->info('[SDL] Email delivered: '.$event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * @param WebHookEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onEmailBounce(WebHookEvent $event)
    {
        $this->logger->warning('[SDL] Address bounced: '.$event->getEmail()->getOrElse('!!! unknown email !!!'));
        $this->anomalyManagement($event);
    }

    /**
     * @param WebHookEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onEmailSpam(WebHookEvent $event)
    {
        $this->logger->error('[SDL] Address spammed: '.$event->getEmail()->getOrElse('!!! unknown email !!!'));
        $this->anomalyManagement($event);
    }

    /**
     * @param WebHookEvent $event
     */
    public function onEmailUnsubscribe(WebHookEvent $event)
    {
        $this->logger->error('[SDL] Address unsubscribed: '.$event->getEmail()->getOrElse('!!! unknown email !!!'));
    }

    /**
     * Manage user bounces or spam reports.
     *
     * @param WebHookEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function anomalyManagement(WebHookEvent $event)
    {
        $user = $this->em->getRepository('NewsletterBundle:NewsletterUser')->findOneBy(array(
            'email' => $event->getEmail(),
        ));

        if ($user) {
            $user->setFail($user->getFail() + 1);
            $this->logger->warning('[SDL] Anomaly detected on '.$user->getEmail().' total fails amount = '.$user->getFail());
            if ($user->getFail() > 3) {
                $user->setActive(false);
                $this->logger->warning('[SDL] Email '.$user->getEmail().' banned!');
            }
            $this->em->flush();
        }
    }
}
