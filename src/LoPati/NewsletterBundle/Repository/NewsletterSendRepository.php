<?php

namespace Lopati\NewsletterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use LoPati\NewsletterBundle\Entity\Newsletter;

class NewsletterSendRepository extends EntityRepository
{
    /**
     * Get items by newsletter with max limit
     *
     * @param Newsletter $newsletter
     * @param integer    $max
     *
     * @return array
     */
    public function getItemsByNewsletter(Newsletter $newsletter, $max)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT s FROM NewsletterBundle:NewsletterSend s WHERE s.newsletter = :newsletter');
        $query->setParameter('newsletter', $newsletter);
        $query->setMaxResults($max);

        return $query->getResult();
    }
}
