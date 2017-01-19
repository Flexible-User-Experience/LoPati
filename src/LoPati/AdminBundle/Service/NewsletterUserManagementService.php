<?php

namespace LoPati\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use LoPati\NewsletterBundle\Entity\NewsletterUser;
use LoPati\NewsletterBundle\Repository\NewsletterGroupRepository;
use LoPati\NewsletterBundle\Repository\NewsletterUserRepository;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface as Validator;
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
     * @return bool true if everything goes well
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
            if ($searchedUser->getName()) {
                $user->setName($searchedUser->getName());
            }
            if ($searchedUser->getPostalCode()) {
                $user->setPostalCode($searchedUser->getPostalCode());
            }
            if ($searchedUser->getPhone()) {
                $user->setPhone($searchedUser->getPhone());
            }
            if ($searchedUser->getBirthyear()) {
                $user->setBirthyear($searchedUser->getBirthyear());
            }
            if ($searchedGroup && !$user->getGroups()->contains($searchedGroup)) {
                $user->addGroup($searchedGroup);
            }
            $this->em->flush();

            return true;

        } else {
            // new user
            $emailConstraint = new EmailConstraint();
            $notBlankConstraint = new NotBlankConstraint();
            /** @var ConstraintViolationListInterface $errors */
            $errors = $this->ev->validate($searchedUser->getEmail(), array($emailConstraint, $notBlankConstraint));
//            $errors = $this->ev->validateValue($searchedUser->getEmail(), array($emailConstraint, $notBlankConstraint));
            if ($errors->count() == 0) {
                $user = new NewsletterUser();
                $user->setName($searchedUser->getName());
                $user->setEmail($searchedUser->getEmail());
                $user->setPostalCode($searchedUser->getPostalCode());
                $user->setPhone($searchedUser->getPhone());
                $user->setBirthyear($searchedUser->getBirthyear());
                $user->setIdioma('ca');
                $user->setActive(true);
                if ($searchedGroup) {
                    $user->addGroup($searchedGroup);
                }
                $this->em->persist($user);
                $this->em->flush();

                return true;
            }
        }

        return false;
    }
}
