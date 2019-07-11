<?php namespace ABWebDevelopers\Snapper\Command\Exception;

use ABWebDevelopers\Snapper\Exception\BaseException;

/**
 * Exception thrown if a command has no actual command specified.
 *
 * @copyright AB Web Developers
 * @author Ben Thomson <ben@abweb.com.au>
 * @since 1.0.0
 */
class CommandNotSpecifiedException extends BaseException
{
    public $message = 'You must specify a command to run.';

    public $code = 2001;
}
