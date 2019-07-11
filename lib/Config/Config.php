<?php namespace ABWebDevelopers\Snapper\Config;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Dflydev\DotAccessData\Data;
use ABWebDevelopers\Snapper\Config\Exception\ConfigReadException;

/**
 * Configuration object.
 *
 * Reads the configuration YAML file and stores the compiled configuration.
 *
 * @copyright AB Web Developers
 * @author Ben Thomson <ben@abweb.com.au>
 * @since 1.0.0
 */
class Config extends Data
{
    /**
     * Defines the required config values.
     *
     * @var array
     */
    protected $requires = [
        'databases.includeAll',
        'connection.server',
        'connection.username',
        'connection.password',
        'connection.port',
        'strategy',
        'storage',
        'mysqlDump.bin',
        'mysqlCli.bin',
    ];

    /**
     * Defines the expected type-castings of config values.
     *
     * @var array
     */
    protected $casts = [
        'databases' => 'array',
        'databases.includeAll' => 'boolean',
        'databases.include' => 'array',
        'databases.exclude' => 'array',
        'connection' => 'array',
        'connection.server' => 'string',
        'connection.username' => 'string',
        'connection.password' => 'string',
        'connection.port' => 'integer',
        'strategy' => 'array',
        'strategy.hourly' => 'array',
        'strategy.hourly.snapshotMinute' => 'integer',
        'strategy.hourly.backupsToKeep' => 'integer',
        'strategy.hourly.folderName' => 'string',
        'strategy.daily' => 'array',
        'strategy.daily.snapshotHour' => 'integer',
        'strategy.daily.snapshotMinute' => 'integer',
        'strategy.daily.backupsToKeep' => 'integer',
        'strategy.daily.folderName' => 'string',
        'strategy.weekly' => 'array',
        'strategy.weekly.snapshotDay' => 'integer',
        'strategy.weekly.snapshotHour' => 'integer',
        'strategy.weekly.snapshotMinute' => 'integer',
        'strategy.weekly.backupsToKeep' => 'integer',
        'strategy.weekly.folderName' => 'string',
        'strategy.monthly' => 'array',
        'strategy.monthly.snapshotDay' => 'integer',
        'strategy.monthly.snapshotHour' => 'integer',
        'strategy.monthly.snapshotMinute' => 'integer',
        'strategy.monthly.backupsToKeep' => 'integer',
        'strategy.monthly.folderName' => 'string',
        'storage' => 'array',
        'storage.local' => 'array',
        'storage.local.directory' => 'string',
        'storage.s3' => 'array',
        'storage.s3.bucket' => 'string',
        'storage.s3.region' => 'string',
        'storage.s3.key' => 'string',
        'storage.s3.secret' => 'string',
        'storage.ftp' => 'array',
        'storage.ftp.directory' => 'string',
        'storage.ftp.server' => 'string',
        'storage.ftp.username' => 'string',
        'storage.ftp.password' => 'string',
        'storage.ftp.port' => 'string',
        'storage.ftp.tls' => 'boolean',
        'storage.ftp.passive' => 'boolean',
        'compression' => 'array',
        'compression.type' => 'string',
        'compression.level' => 'integer',
        'encryption' => 'array',
        'encryption.cipher' => 'string',
        'encryption.publicKey' => 'string',
        'mysqlDump' => 'array',
        'mysqlDump.bin' => 'string',
        'mysqlDump.addLocks' => 'boolean',
        'mysqlDump.completeInsert' => 'boolean',
        'mysqlDump.extendedInsert' => 'boolean',
        'mysqlDump.disableKeys' => 'boolean',
        'mysqlDump.dropDatabase' => 'boolean',
        'mysqlDump.dropTable' => 'boolean',
        'mysqlDump.dropTrigger' => 'boolean',
        'mysqlDump.insertIgnore' => 'boolean',
        'mysqlDump.lockTables' => 'boolean',
        'mysqlDump.useReplace' => 'boolean',
        'mysqlCli' => 'array',
        'mysqlCli.bin' => 'string'
    ];

    /**
     * Defines the settings that may be provided by environment variables. Environment variables will always be used
     * over defined variables in the configuration.
     *
     * @var array
     */
    protected $envVars = [
        'connection.server' => 'SNAPPER_CONN_SERVER',
        'connection.username' => 'SNAPPER_CONN_USER',
        'connection.password' => 'SNAPPER_CONN_PASS',
        'connection.port' => 'SNAPPER_CONN_PORT',
        'storage.s3.key' => 'AWS_ACCESS_KEY_ID',
        'storage.s3.secret' => 'AWS_SECRET_ACCESS_KEY',
        'storage.ftp.server' => 'SNAPPER_FTP_SERVER',
        'storage.ftp.username' => 'SNAPPER_FTP_USER',
        'storage.ftp.password' => 'SNAPPER_FTP_PASS',
        'storage.ftp.port' => 'SNAPPER_FTP_PORT',
        'storage.ftp.tls' => 'SNAPPER_FTP_TLS',
        'storage.ftp.passive' => 'SNAPPER_FTP_PASV',
        'encryption.publicKey' => 'SNAPPER_ENCRYPT_PUBLIC_KEY',
    ];

    /**
     * Default values.
     *
     * @var array
     */
    protected $defaults = [
        'strategy.hourly.snapshotMinute' => 0,
        'strategy.hourly.backupsToKeep' => 24,
        'strategy.hourly.folderName' => 'hourly',
        'strategy.daily.snapshotHour' => 0,
        'strategy.daily.snapshotMinute' => 0,
        'strategy.daily.backupsToKeep' => 7,
        'strategy.daily.folderName' => 'daily',
        'strategy.weekly.snapshotDay' => 3,
        'strategy.weekly.snapshotHour' => 0,
        'strategy.weekly.snapshotMinute' => 0,
        'strategy.weekly.backupsToKeep' => 52,
        'strategy.weekly.folderName' => 'weekly',
        'strategy.monthly.snapshotDay' => 1,
        'strategy.monthly.snapshotHour' => 0,
        'strategy.monthly.snapshotMinute' => 0,
        'strategy.monthly.backupsToKeep' => 12,
        'strategy.monthly.folderName' => 'monthly',
        'compression.type' => 'gzip',
        'compression.level' => 6,
        'mysqlCli' => [
            'bin' => '/usr/bin/mysql'
        ],
        'mysqlDump.bin' => '/usr/bin/mysqldump',
        'mysqlDump.addLocks' => true,
        'mysqlDump.completeInsert' => false,
        'mysqlDump.extendedInsert' => true,
        'mysqlDump.disableKeys' => true,
        'mysqlDump.dropDatabase' => true,
        'mysqlDump.dropTable' => true,
        'mysqlDump.dropTrigger' => true,
        'mysqlDump.insertIgnore' => false,
        'mysqlDump.lockTables' => true,
        'mysqlDump.useReplace' => false,
    ];

    /**
     * Constructor
     *
     * @param string $configFile Path to the configuration file
     */
    public function __construct(string $configFile = null)
    {
        if (!is_null($configFile)) {
            $this->read($configFile);
        }
    }

    /**
     * Reads the configuration YAML file and stores the values within this object.
     *
     * @param string $configFile Path to the configuration file
     *
     * @return void
     */
    public function read(string $configFile): void
    {
        try {
            $config = Yaml::parseFile($configFile, Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);
        } catch (ParseException $e) {
            throw new ConfigReadException(
                'A parse error occurred when parsing the Snapper config file "' . $configFile . '". '
                . $e->getMessage(),
                1001,
                $e
            );
        }

        $this->import($config);

        $this->setDefaults();
        $this->includeEnvVars();
        $this->validate();
    }

    /**
     * Process default values.
     *
     * Default values within a heirarchy will require the parent be defined before they are added. Defaults will not be
     * added if the user has specified the configuration value in their configuration.
     *
     * @return void
     */
    protected function setDefaults()
    {
        if (!isset($this->defaults) || !count($this->defaults)) {
            return;
        }

        foreach ($this->defaults as $configKey => $defaultValue) {
            // Skip defined configuration values
            if ($this->has($configKey)) {
                continue;
            }

            if (strpos($configKey, '.') !== false) {
                // Get parent element
                $parent = implode('.', explode('.', $configKey, -1));

                // If the parent element is not found, skip this default.
                if (!$this->has($parent)) {
                    continue;
                }

                $this->set($configKey, $defaultValue);
            } else {
                $this->set($configKey, $defaultValue);
            }
        }
    }

    /**
     * Scans the environment variables for any configuration values for Snapper.
     *
     * @return void
     */
    protected function includeEnvVars(): void
    {
        if (!isset($this->envVars) || !count($this->envVars)) {
            return;
        }

        foreach ($this->envVars as $configKey => $envKey) {
            $envVar = getenv($envKey, true);
            if (!$envVar) {
                continue;
            }

            // Cast environment variables to their specified type
            settype($envVar, $this->casts[$configKey] ?? 'string');

            $this->set($configKey, $envVar);
        }
    }

    /**
     * Validates the configuration.
     *
     * @return void
     */
    protected function validate()
    {
        // Check required values
        if (isset($this->requires) && count($this->requires)) {
            foreach ($this->requires as $requires) {
                if (!$this->has($requires)) {
                    throw new ConfigReadException('Missing configuration value for "' . $requires . '"', 1002);
                }
            }
        }

        // Check casts
        if (isset($this->casts) && count($this->casts)) {
            foreach ($this->casts as $configKey => $expectedType) {
                $configValueType = gettype($this->get($configKey));

                if ($this->has($configKey) && $configValueType !== $expectedType) {
                    throw new ConfigReadException(
                        'Invalid configuration value for "' . $configKey . '" - expected (' . $expectedType . ') but
                        got (' . $configValueType . ')',
                        1003
                    );
                }
            }
        }
    }
}
