<?php

namespace LoPati\NewsletterBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NewsletterUserTransformer implements DataTransformerInterface//extends AbstractTransformer
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms NewsletterUser object to a string (UID)
     *
     * @param NewsletterUser|null $user
     *
     * @return string
     */
    public function transform($user)
    {
        if (is_null($user)) {
            return '';
        }

        return $user->getId();
    }

    /**
     * Transforms a string (UID) to a NewsletterUser object
     *
     * @param string $uid
     *
     * @return NewsletterUser|null
     * @throws TransformationFailedException if object (event) is not found.
     */
    public function reverseTransform($uid)
    {
        if (!$uid) {
            return null;
        }

        $user = $this->em
            ->getRepository('NewsletterBundle:NewsletterUser')
            ->find($uid);

        if (!$user) {
            throw new TransformationFailedException(sprintf(
                'NewsletterUser with ID "%s" does not exist!',
                $uid
            ));
        }

        return $user;
    }
}
