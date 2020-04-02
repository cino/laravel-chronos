<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Schema\Blueprint;

class EloquentHasOneTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
        });

        $this->schema()->create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    protected function seedData(): void
    {
        EloquentHasOneUser::query()->create(['id' => 1]);
        EloquentHasOnePhone::query()->create(['user_id' => 1]);
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('phones');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $user = EloquentHasOneUser::query()->first();
        $phone = $user->phone;

        $this->assertInstanceOf(ChronosInterface::class, $phone->created_at);
        $this->assertInstanceOf(ChronosInterface::class, $phone->updated_at);
    }
}

class EloquentHasOneUser extends Model
{
    protected $fillable = ['id'];

    protected $table = 'users';

    public $timestamps = false;

    public function phone(): HasOne
    {
        return $this->hasOne(EloquentHasOnePhone::class, 'user_id', 'id');
    }
}

class EloquentHasOnePhone extends Model
{
    protected $dates = ['date'];

    protected $fillable = ['user_id'];

    protected $table = 'phones';
}
