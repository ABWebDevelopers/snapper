<?php namespace ABWebDevelopers\Snapper\Exception;

/**
 * Base Exception class for Snapper.
 *
 * @copyright AB Web Developers
 * @author Ben Thomson <ben@abweb.com.au>
 * @since 1.0.0
 */
class BaseException extends \Exception
{
    /**
     * The exception message.
     *
     * @var string
     */
    public $message = 'An unidentified exception occurred.';

    /**
     * The exception code.
     *
     * @var integer
     */
    public $code = 500;
}
