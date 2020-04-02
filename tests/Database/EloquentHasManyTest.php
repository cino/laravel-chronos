<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Schema\Blueprint;

class EloquentHasManyTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
        });

        $this->schema()->create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    protected function seedData(): void
    {
        EloquentHasManyUser::query()->create(['id' => 1]);

        $phone = new EloquentHasManyPhone();
        $phone->user_id = 1;
        $phone->date = Chronos::now();
        $phone->save();

        $phone = new EloquentHasManyPhone();
        $phone->user_id = 1;
        $phone->date = Chronos::now();
        $phone->save();
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('users');
        $this->schema()->drop('phones');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $user = EloquentHasManyUser::query()->first();
        foreach ($user->phones as $phone) {
            $this->assertInstanceOf(ChronosInterface::class, $phone->created_at);
            $this->assertInstanceOf(ChronosInterface::class, $phone->date);
            $this->assertInstanceOf(ChronosInterface::class, $phone->updated_at);
        }
    }
}

class EloquentHasManyUser extends Model
{
    protected $fillable = ['id'];

    protected $table = 'users';

    public $timestamps = false;

    public function phones(): HasMany
    {
        return $this->hasMany(EloquentHasManyPhone::class, 'user_id', 'id');
    }
}

class EloquentHasManyPhone extends Model
{
    protected $dates = ['date'];

    protected $table = 'phones';
}
