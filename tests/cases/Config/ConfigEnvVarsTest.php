<?php namespace ABWebDevelopers\Snapper\Tests\Cases\Config;

use ABWebDevelopers\Snapper\Config\Config;

class ConfigEnvVarsTest extends \PHPUnit\Framework\TestCase
{
    public function testEnvVars(): void
    {
        putenv('SNAPPER_CONN_SERVER=database');

        $config = new Config;
        $config->read(dirname(dirname(__DIR__)) . '/fixtures/config/config.valid.yaml');

        $this->assertEquals('database', $config->export()['connection']['server']);

        putenv('SNAPPER_CONN_SERVER');
    }

    public function testEnvVarsNotSpecified(): void
    {
        putenv('SNAPPER_CONN_SERVER=database');

        $config = new Config();

        $reflection = new \ReflectionClass($config);
        $property = $reflection->getProperty('envVars');
        $property->setAccessible(true);
        $property->setValue($config, []);

        $read = $reflection->getMethod('read');
        $read->invoke($config, dirname(dirname(__DIR__)) . '/fixtures/config/config.valid.yaml');

        $export = $reflection->getMethod('export');
        $data = $export->invoke($config);

        $this->assertEquals('localhost', $data['connection']['server']);

        putenv('SNAPPER_CONN_SERVER');
    }
}
