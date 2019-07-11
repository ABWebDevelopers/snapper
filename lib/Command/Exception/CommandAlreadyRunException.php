<?php namespace ABWebDevelopers\Snapper\Command\Exception;

use ABWebDevelopers\Snapper\Exception\BaseException;

/**
 * Exception thrown if a command is run multiple times.
 *
 * @copyright AB Web Developers
 * @author Ben Thomson <ben@abweb.com.au>
 * @since 1.0.0
 */
class CommandAlreadyRunException extends BaseException
{
    public $message = 'This command has already been run.';

    public $code = 2000;
}
