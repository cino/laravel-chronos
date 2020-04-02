<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Schema\Blueprint;

class EloquentHasOneTroughTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('suppliers', function (Blueprint $table) {
            $table->increments('id');
        });

        $this->schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('supplier_id');

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });

        $this->schema()->create('history', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    protected function seedData(): void
    {
        EloquentHasOneTroughSupplier::query()->create(['id' => 1]);
        EloquentHasOneTroughUser::query()->create(['id' => 1, 'supplier_id' => 1]);
        EloquentHasOneTroughHistory::query()->create(['id' => 1, 'user_id' => 1, 'date' => Chronos::now()]);
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('history');
        $this->schema()->drop('suppliers');
        $this->schema()->drop('users');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $supplier = EloquentHasOneTroughSupplier::query()->first();
        $userHistory = $supplier->userHistory;

        $this->assertInstanceOf(ChronosInterface::class, $userHistory->created_at);
        $this->assertInstanceOf(ChronosInterface::class, $userHistory->date);
        $this->assertInstanceOf(ChronosInterface::class, $userHistory->updated_at);
    }
}

class EloquentHasOneTroughSupplier extends Model
{
    protected $fillable = ['id'];

    protected $table = 'suppliers';

    public $timestamps = false;

    public function userHistory(): HasOneThrough
    {
        return $this->hasOneThrough(
            EloquentHasOneTroughHistory::class,
            EloquentHasOneTroughUser::class,
            'supplier_id',
            'user_id'
        );
    }
}

class EloquentHasOneTroughHistory extends Model
{
    protected $dates = ['date'];

    protected $fillable = ['date', 'id', 'user_id'];

    protected $table = 'history';
}

class EloquentHasOneTroughUser extends Model
{
    protected $fillable = ['id', 'supplier_id'];

    protected $table = 'users';

    public $timestamps = false;
}
