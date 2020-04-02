<?php

namespace Cino\LaravelChronos\Tests\Feature\Eloquent;

use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Schema\Builder;
use PHPUnit\Framework\TestCase;

abstract class EloquentTestCase extends TestCase
{
    protected function connection(): ConnectionInterface
    {
        return Model::getConnectionResolver()->connection();
    }

    abstract protected function createSchema(): void;

    protected function schema(): Builder
    {
        return $this->connection()->getSchemaBuilder();
    }

    protected function setUp(): void
    {
        $db = new DB;

        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $db->bootEloquent();
        $db->setAsGlobal();

        $this->createSchema();
    }
}
