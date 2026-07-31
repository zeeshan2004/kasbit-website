<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Always isolate automated tests from the local MySQL website database,
     * even when Laravel's production configuration has been cached.
     */
    public function createApplication()
    {
        $testingEnvironment = [
            'APP_CONFIG_CACHE' => dirname(__DIR__).'/bootstrap/cache/config.testing.php',
            'APP_ENV' => 'testing',
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => ':memory:',
            'SESSION_DRIVER' => 'array',
            'CACHE_STORE' => 'array',
            'QUEUE_CONNECTION' => 'sync',
            'MAIL_MAILER' => 'array',
        ];

        foreach ($testingEnvironment as $key => $value) {
            $_SERVER[$key] = $value;
            $_ENV[$key] = $value;
        }

        /** @var Application $app */
        $app = parent::createApplication();

        if (! $app->environment('testing')
            || config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:') {
            throw new RuntimeException('Tests must use the isolated in-memory SQLite database.');
        }

        return $app;
    }
}
