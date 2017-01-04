<?php

namespace LoPati\AdminBundle\Twig;

use LoPati\NewsletterBundle\Entity\IsolatedNewsletter;
use LoPati\NewsletterBundle\Enum\NewsletterTypeEnum;

/**
 * Class AdminExtensionTwig
 *
 * @category Twig
 * @package  LoPati\AdminBundle\Twig
 * @author   David Romaní <david@flux.cat>
 */
class AdminExtensionTwig extends \Twig_Extension
{
    /**
     *
     *
     * Twig Functions
     *
     *
     */

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getAssetType', array($this, 'getAssetType')),
        );
    }

    /**
     * @param IsolatedNewsletter $object
     *
     * @return string
     */
    public function getAssetType($object)
    {
        $result = 'images/newsletter_type_noticies.png';
        if ($object->getType() === NewsletterTypeEnum::NEWS) {
            $result = 'images/newsletter_type_noticies.png';
        } elseif ($object->getType() === NewsletterTypeEnum::EVENTS) {
            $result = 'images/newsletter_type_activitats.png';
        } elseif ($object->getType() === NewsletterTypeEnum::EXPOSITIONS) {
            $result = 'images/newsletter_type_exposicions.png';
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }
}
