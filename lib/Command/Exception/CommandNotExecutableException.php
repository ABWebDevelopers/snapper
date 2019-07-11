<?php namespace ABWebDevelopers\Snapper\Command\Exception;

use ABWebDevelopers\Snapper\Exception\BaseException;

/**
 * Exception thrown if a command is not executable
 *
 * @copyright AB Web Developers
 * @author Ben Thomson <ben@abweb.com.au>
 * @since 1.0.0
 */
class CommandNotExecutableException extends BaseException
{
    public $message = 'The specified path is not executable.';

    public $code = 2002;
}
