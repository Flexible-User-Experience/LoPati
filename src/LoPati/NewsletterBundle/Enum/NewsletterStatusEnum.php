<?php

namespace LoPati\NewsletterBundle\Enum;

/**
 * NewsletterStatusEnum class
 *
 * @category Enum
 * @package  LoPati\NewsletterBundle\Enum
 * @author   David Romaní <david@flux.cat>
 */
class NewsletterStatusEnum
{
    const WAITING = 0;
    const SENDING = 1;
    const SENDED  = 2;

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array(
            self::WAITING => 'pendent',
            self::SENDING => 'enviant',
            self::SENDED  => 'enviat',
        );
    }
}
