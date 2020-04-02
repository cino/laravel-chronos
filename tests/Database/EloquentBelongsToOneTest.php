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
        $user = new EloquentBelongsToOneUser();
        $user->date = Chronos::now();
        $user->save();
        EloquentBelongsToOnePhone::query()->create(['user_id' => 1]);
    }


    protected function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('phones');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $phone = EloquentBelongsToOnePhone::query()->first();
        $user = $phone->first()->user;

        $this->assertInstanceOf(ChronosInterface::class, $user->created_at);
        $this->assertInstanceOf(ChronosInterface::class, $user->updated_at);
    }
}

class EloquentBelongsToOneUser extends Model
{
    protected $table = 'users';
}

class EloquentBelongsToOnePhone extends Model
{
    protected $fillable = ['user_id'];

    protected $table = 'phones';

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->BelongsTo(EloquentBelongsToOneUser::class, 'user_id', 'id');
    }
}
