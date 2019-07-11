<?php namespace ABWebDevelopers\Snapper\Command;

use Dflydev\DotAccessData\Data;

/**
 * Command abstract.
 *
 * A Command is simply a wrapper around a command run through CLI, giving access to its STDOUT and STDERR feeds. A
 * Command instance is single-use - for multiple command runs, you should use instantiate a new instance of the Command
 * class.
 *
 * @copyright AB Web Developers
 * @author Ben Thomson <ben@abweb.com.au>
 * @since 1.0.0
 */
abstract class BaseCommand
{
    /**
     * Defines the CLI command used. This can also be a configuration variable name.
     *
     * @var string
     *
     * public $command = '';
     */

    /**
     * Map configuration variables to parameters for the command
     *
     * @var array
     *
     * public $argumentMap = [];
     */

    /**
     * Fixed arguments for the command.
     *
     * @var array
     *
     * public $fixedArguments = [];
     */

    /**
     * Stores the Snapper configuration.
     *
     * @var Data
     */
    public $config;

    /**
     * Determines if the CLI command has executed.
     *
     * @var boolean
     */
    protected $executed = false;

    /**
     * Contains the STDOUT feed.
     *
     * @var string
     */
    protected $stdOut = '';

    /**
     * Contains the STDERR feed.
     *
     * @var string
     */
    protected $stdErr = '';

    /**
     * Contains the exit code of the command.
     *
     * @var int
     */
    protected $exitCode = null;

    /**
     * Process resource.
     *
     * @var resource
     */
    protected $process;

    /**
     * Process pipes.
     *
     * @var array
     */
    protected $pipes;

    /**
     * Constructor
     *
     * @param Data $config
     * @return void
     */
    public function __construct(Data $config)
    {
        $this->config = $config;
    }

    /**
     * Runs the command line program and returns the output
     */
    public function run(): string
    {
        return $this->execute();
    }

    /**
     * Executes the command line program.
     *
     * @return string
     */
    public function execute(): string
    {
        if ($this->executed) {
            throw new Exception\CommandAlreadyRunException();
        }

        if (!property_exists($this, 'command')) {
            throw new Exception\CommandNotSpecifiedException();
        }

        $this->createProcess();

        return ($this->failed()) ? $this->stdErr : $this->stdOut;
    }

    /**
     * Returns if the command failed.
     *
     * @return bool
     */
    public function failed(): bool
    {
        if (!$this->executed) {
            return null;
        }

        return ($this->exitCode !== 0);
    }

    /**
     * Returns the standard output.
     *
     * @return string
     */
    public function getOutput(): string
    {
        if (!$this->executed) {
            return null;
        }

        return $this->stdOut;
    }

    /**
     * Returns the standard output.
     *
     * @return string
     */
    public function getError(): string
    {
        if (!$this->executed) {
            return null;
        }

        return $this->stdErr;
    }

    /**
     * Creates the process.
     *
     * @return void
     */
    protected function createProcess(): void
    {
        $command = $this->getCommand() . $this->getArguments();

        $this->process = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ], $this->pipes);

        // Set stream blocking modes
        stream_set_blocking($this->pipes[1], true);
        stream_set_blocking($this->pipes[2], true);

        // Close pipe for input
        fclose($this->pipes[0]);

        $this->readStreams();

        $this->executed = true;
        $this->exitCode = proc_close($this->process);
    }

    /**
     * Gets the command - either from the configuration variable, or from the `$command` property directly.
     *
     * @return string
     */
    protected function getCommand(): string
    {
        if ($this->config->has($this->command) && is_executable($this->config->get($this->command))) {
            return $this->config->get($this->command);
        } elseif (is_executable($this->command)) {
            return $this->command;
        } else {
            throw new Exception\CommandNotExecutableException();
        }
    }

    /**
     * Gets the arguments from the configuration variables
     *
     * @return string
     */
    protected function getArguments(): string
    {
        $arguments = [];

        if (property_exists($this, 'fixedArguments') && is_array($this->fixedArguments)) {
            foreach ($this->fixedArguments as $argument => $value) {
                // Skip if this is an invalid argument
                if (!preg_match(
                    '/^(([-]{1}([a-zA-Z0-9]{1}))|([-]{2}([a-zA-Z0-9][a-zA-Z0-9\-_]*)))$/',
                    $argument,
                    $matches
                )) {
                    continue;
                }
            }

            // Determine argument type
            $format = (!empty($matches[3])) ? 'long' : 'short';

            if ($format === 'long') {
                $arguments['--' . $matches[4] . '='] = escapeshellarg($value);
            } else {
                $arguments['-' . $matches[2] . ' '] = escapeshellarg($value);
            }
        }

        if (property_exists($this, 'argumentMap') && is_array($this->argumentMap)) {
            foreach ($this->argumentMap as $argument => $value) {
                // Skip if the config variable does not exist
                if (!$this->config->has($value)) {
                    continue;
                }

                // Skip if this is an invalid argument
                if (!preg_match(
                    '/^(([-]{1}([a-zA-Z0-9]{1}))|([-]{2}([a-zA-Z0-9][a-zA-Z0-9\-_]*)))$/',
                    $argument,
                    $matches
                )) {
                    continue;
                }

                // Determine argument type
                $format = (!empty($matches[3])) ? 'long' : 'short';

                if ($format === 'long') {
                    $arguments['--' . $matches[4] . '='] = escapeshellarg($this->config->get($value));
                } else {
                    $arguments['-' . $matches[2] . ' '] = escapeshellarg($this->config->get($value));
                }
            }
        }

        if (count($arguments)) {
            return ' ' . implode(' ', $arguments);
        } else {
            return '';
        }
    }

    /**
     * Reads the streams from the command.
     *
     * @return void
     */
    protected function readStreams()
    {
        $this->stdOut = stream_get_contents($this->pipes[1]);
        $this->stdErr = stream_get_contents($this->pipes[2]);

        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
    }
}
