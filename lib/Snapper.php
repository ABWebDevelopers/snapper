<?php namespace ABWebDevelopers\Snapper;

use League\CLImate\CLImate;
use ABWebDevelopers\Snapper\Config\Config;
use ABWebDevelopers\Snapper\Command\MysqlCli;

class Snapper
{
    public static $instance;

    public $cli;

    public $config;

    protected $version = '1.0.0';

    public static function init()
    {
        self::getInstance()->run();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function run()
    {
        $instance = $this->getInstance();

        $instance->cli = new CLImate;

        // Program header
        $instance->cli->out(
            '<bold><yellow>Snapper -</yellow></bold> Version <green>' . $instance->version . '</green>'
        );
        $instance->cli->out(
            'Database snapshot utility by AB Web Developers (https://abweb.com.au/).'
        )->br();

        $instance->cli->arguments->add([
            'config' => [
                'prefix' => 'c',
                'longPrefix' => 'config',
                'description' => 'Configuration file path',
                'defaultValue' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.yaml'
            ],
            'help' => [
                'longPrefix' => 'help',
                'description' => 'Prints out the help for this command.',
                'noValue' => true
            ]
        ]);
        $instance->cli->arguments->parse();

        if ($instance->cli->arguments->defined('help')) {
            $this->showHelp();
        } else {
            $this->runSnapshots();
        }
    }

    protected function runSnapshots()
    {
        $instance = $this->getInstance();

        // Process configuration
        $this->config = new Config($instance->cli->arguments->get('config'));

        // Connect to MySQL and retrieve database list
        $command = new MysqlCli($this->config);
        $command->run();
    }

    protected function showHelp()
    {
        $instance = $this->getInstance();

        $instance->cli->usage();
    }
}
