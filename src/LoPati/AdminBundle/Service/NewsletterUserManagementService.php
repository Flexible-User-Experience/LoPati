<?php

namespace LoPati\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Repository\NewsletterGroupRepository;
use LoPati\NewsletterBundle\Repository\NewsletterUserRepository;
use Symfony\Component\Validator\Validator\RecursiveValidator as Validator;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

/**
 * Class NewsletterUserManagementService
 *
 * @category Service
 * @package  LoPati\AdminBundle\Service
 * @author   David Romaní <david@flux.cat>
 */
class NewsletterUserManagementService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Validator
     */
    private $ev;

    /**
     * @var NewsletterUserRepository
     */
    private $ur;

    /**
     * @var NewsletterGroupRepository
     */
    private $gr;

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
     * @param EntityManager  $em
     * @param Validator      $ev
     */
    public function __construct(EntityManager $em, Validator $ev)
    {
        $this->em = $em;
        $this->ev = $ev;
        $this->ur = $this->em->getRepository('NewsletterBundle:NewsletterUser');
        $this->gr = $this->em->getRepository('NewsletterBundle:NewsletterGroup');
    }

    /**
     * Write User
     *
     * @param NewsletterUser $searchedUser
     *
     * @return array|bool
     * @throws \Exception
     */
    public function writeUser(NewsletterUser $searchedUser)
    {
        $searchedGroup = null;
        if ($searchedUser->getImprotedGroup()) {
            $searchedGroup = $this->gr->findOneBy(['name' => $searchedUser->getImprotedGroup()]);
        }

        $user = $this->ur->findOneBy(['email' => $searchedUser->getEmail()]);
        if ($user) {
            // existing user
            $user->setName($searchedUser->getName());
            $user->setCity($searchedUser->getCity());
            $user->setBirthdate($searchedUser->getBirthdate());
            $user->setPhone($searchedUser->getPhone());
//            if ($searchedGroup) {
//                $user->addGroup($searchedGroup);
//            }
            $this->em->flush();

            return true;

        } else {
            // new user
            $emailConstraint = new EmailConstraint();
            $errors = $this->ev->validateValue($searchedUser->getEmail(), $emailConstraint);
            if ($errors->count() == 0) {
                $user = new NewsletterUser();
                $user->setName($searchedUser->getName());
                $user->setEmail($searchedUser->getEmail());
                $user->setCity($searchedUser->getCity());
                $user->setBirthdate($searchedUser->getBirthdate());
                $user->setPhone($searchedUser->getPhone());
                $user->setIdioma('ca');
                $user->setActive(true);
//                if ($searchedGroup) {
//                    $user->addGroup($searchedGroup);
//                }
                $this->em->persist($user);
                $this->em->flush();

                return true;
            }
        }

        return false;
    }
}
