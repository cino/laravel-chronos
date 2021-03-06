<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;

class EloquentBelongsToOneTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->timestamps();
        });

        $this->schema()->create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    protected function seedData(): void
    {
        EloquentBelongsToUser::query()->create(['date' => Chronos::now()]);
        EloquentBelongsToPhone::query()->create(['user_id' => 1]);
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('phones');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $phone = EloquentBelongsToPhone::query()->first();
        $user = $phone->first()->user;

        $this->assertInstanceOf(ChronosInterface::class, $user->created_at);
        $this->assertInstanceOf(ChronosInterface::class, $user->date);
        $this->assertInstanceOf(ChronosInterface::class, $user->updated_at);
    }
}

class EloquentBelongsToUser extends Model
{
    protected $dates = ['date'];

    protected $fillable = ['date', 'user_id'];

    protected $table = 'users';
}

class EloquentBelongsToPhone extends Model
{
    protected $fillable = ['user_id'];

    protected $table = 'phones';

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->BelongsTo(EloquentBelongsToUser::class, 'user_id', 'id');
    }
}
