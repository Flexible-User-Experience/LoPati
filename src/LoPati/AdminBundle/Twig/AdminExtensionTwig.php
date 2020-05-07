<?php

namespace LoPati\AdminBundle\Twig;

use LoPati\NewsletterBundle\Entity\IsolatedNewsletter;
use LoPati\NewsletterBundle\Enum\NewsletterTypeEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AdminExtensionTwig
 *
 * @category Twig
 */
class AdminExtensionTwig extends AbstractExtension
{
    /**
     * Twig Functions
     */

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('getAssetType', array($this, 'getAssetType')),
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
