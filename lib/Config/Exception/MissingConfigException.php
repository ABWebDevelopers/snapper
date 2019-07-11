<?php namespace ABWebDevelopers\Snapper\Config\Exception;

use ABWebDevelopers\Snapper\Exception\BaseException;

/**
 * Exception thrown for a missing configuration file.
 *
 * @copyright AB Web Developers
 * @author Ben Thomson <ben@abweb.com.au>
 * @since 1.0.0
 */
class MissingConfigException extends BaseException
{
    public $message = 'The configuration file is missing.';

    public $code = 1000;
}
