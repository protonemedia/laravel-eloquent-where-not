<?php declare(strict_types=1);

namespace ProtoneMedia\LaravelEloquentWhereNot\Tests;

use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use PDO;
use ProtoneMedia\LaravelEloquentWhereNot\WhereNot;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Model::unguard();

        WhereNot::addMacro();

        $this->app['config']->set('app.key', 'base64:yWa/ByhLC/GUvfToOuaPD7zDwB64qkc/QkaQOrT5IpE=');

        $this->app['config']->set('database.connections.mysql', [
            'driver'         => 'mysql',
            'url'            => env('DATABASE_URL'),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', '3306'),
            'database'       => env('DB_DATABASE', 'scope_as_select_test'),
            'username'       => env('DB_USERNAME', 'homestead'),
            'password'       => env('DB_PASSWORD', 'secret'),
            'unix_socket'    => env('DB_SOCKET', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
            'options'        => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ]);

        $this->artisan('migrate:fresh');

        include_once __DIR__ . '/create_tables.php';

        (new \CreateTables)->up();
    }
}
