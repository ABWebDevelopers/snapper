<?php namespace ABWebDevelopers\Snapper\Tests\Cases\Config;

use ABWebDevelopers\Snapper\Config\Config;
use ABWebDevelopers\Snapper\Config\Exception\ConfigReadException;

class ConfigDefaultsTest extends \PHPUnit\Framework\TestCase
{
    public function testNoDefaults(): void
    {
        $this->expectException(ConfigReadException::class);
        $this->expectExceptionCode(1002);

        $config = new Config;
        $config->defaults = null;
        $config->read(dirname(dirname(__DIR__)) . '/fixtures/config/config.valid.yaml');
    }

    public function testArrayDefaults(): void
    {
        $config = new Config;
        $config->defaults += [
            'databases' => [
                'includeAll' => true,
                'exclude' => [
                    'information_schema',
                    'logs',
                    'mysql',
                    'performance_schema',
                ]
            ]
        ];
        $config->read(dirname(dirname(__DIR__)) . '/fixtures/config/config.missingParams.yaml');

        $this->assertArrayHasKey('databases', $config->export());
    }
}
