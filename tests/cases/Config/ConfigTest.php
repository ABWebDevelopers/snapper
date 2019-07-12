<?php namespace ABWebDevelopers\Snapper\Tests\Cases\Config;

use ABWebDevelopers\Snapper\Config\Config;
use ABWebDevelopers\Snapper\Config\Exception\MissingConfigException;
use ABWebDevelopers\Snapper\Config\Exception\ConfigReadException;

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testValidConfig(): void
    {
        $config = new Config(dirname(dirname(__DIR__)) . '/fixtures/config/config.valid.yaml');

        $expected = [
            'databases' => [
                'includeAll' => true,
                'exclude' => [
                    0 => 'information_schema',
                    1 => 'logs',
                    2 => 'mysql',
                    3 => 'performance_schema',
                ],
            ],
            'connection' => [
                'server' => 'localhost',
                'username' => 'noUser',
                'password' => 'somePassword',
                'port' => 3306,
            ],
            'strategy' => [
                'hourly' => [
                    'snapshotMinute' => 0,
                    'backupsToKeep' => 24,
                    'folderName' => 'hourly',
                ],
                'daily' => [
                    'snapshotHour' => 0,
                    'snapshotMinute' => 0,
                    'backupsToKeep' => 7,
                    'folderName' => 'daily',
                ],
                'weekly' => [
                    'snapshotDay' => 3,
                    'snapshotHour' => 0,
                    'snapshotMinute' => 0,
                    'backupsToKeep' => 52,
                    'folderName' => 'weekly',
                ],
            ],
            'storage' => [
                'local' => [
                    'directory' => '/tmp',
                ],
            ],
            'mysqlDump' => [
                'bin' => '/usr/bin/mysqldump',
                'addLocks' => true,
                'completeInsert' => false,
                'extendedInsert' => true,
                'disableKeys' => true,
                'dropDatabase' => true,
                'dropTable' => true,
                'dropTrigger' => true,
                'insertIgnore' => false,
                'lockTables' => true,
                'useReplace' => false,
            ],
            'mysqlCli' => [
                'bin' => '/usr/bin/mysql',
            ],
        ];

        $this->assertEquals($expected, $config->export());
    }

    public function testMissingConfig(): void
    {
        $this->expectException(MissingConfigException::class);
        $this->expectExceptionCode(1000);

        $config = new Config(dirname(dirname(__DIR__)) . '/fixtures/config/config.missing.yaml');
    }

    public function testInvalidYamlConfig(): void
    {
        $this->expectException(ConfigReadException::class);
        $this->expectExceptionCode(1001);

        $config = new Config(dirname(dirname(__DIR__)) . '/fixtures/config/config.html');
    }

    public function testMissingRequiredParameters(): void
    {
        $this->expectException(ConfigReadException::class);
        $this->expectExceptionCode(1002);

        $config = new Config(dirname(dirname(__DIR__)) . '/fixtures/config/config.missingParams.yaml');
    }

    public function testInvalidParameters(): void
    {
        $this->expectException(ConfigReadException::class);
        $this->expectExceptionCode(1003);

        $config = new Config(dirname(dirname(__DIR__)) . '/fixtures/config/config.invalidParams.yaml');
    }
}
