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
     * Twig Filters
     *
     *
     */

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('draw_type', array($this, 'drawIsolatedNewsletterType')),
        );
    }

    /**
     * @param IsolatedNewsletter $object
     *
     * @return string
     */
    public function drawIsolatedNewsletterType($object)
    {
        $htmlFragment = '';
        if ($object instanceof IsolatedNewsletter) {
            $htmlFragment .= mb_strtoupper($object->getTypeString());
        } else {
            $htmlFragment .= '---';
        }

        return $htmlFragment;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_extension';
    }
}
