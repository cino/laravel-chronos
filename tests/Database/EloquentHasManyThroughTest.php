<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Schema\Blueprint;

class EloquentHasManyThroughTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('countries', function (Blueprint $table) {
            $table->increments('id');
        });

        $this->schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('country_id');

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        $this->schema()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    protected function seedData(): void
    {
        EloquentHasManyTroughCountry::query()->create(['id' => 1]);
        EloquentHasManyTroughUser::query()->create(['id' => 1, 'country_id' => 1]);
        EloquentHasManyTroughPost::query()->create(['id' => 1, 'user_id' => 1, 'date' => Chronos::now()]);
        EloquentHasManyTroughPost::query()->create(['id' => 2, 'user_id' => 1, 'date' => Chronos::now()]);
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('posts');
        $this->schema()->drop('users');
        $this->schema()->drop('countries');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $country = EloquentHasManyTroughCountry::query()->first();
        $posts = $country->posts;

        foreach ($posts as $post) {
            $this->assertInstanceOf(ChronosInterface::class, $post->created_at);
            $this->assertInstanceOf(ChronosInterface::class, $post->date);
            $this->assertInstanceOf(ChronosInterface::class, $post->updated_at);
        }
    }
}


class EloquentHasManyTroughCountry extends Model
{
    protected $fillable = ['id'];

    protected $table = 'countries';

    public $timestamps = false;

    public function posts(): HasManyThrough
    {
        return $this->hasManyThrough(
            EloquentHasManyTroughPost::class,
            EloquentHasManyTroughUser::class,
            'country_id',
            'user_id'
        );
    }
}

class EloquentHasManyTroughUser extends Model
{
    protected $fillable = ['id', 'country_id'];

    protected $table = 'users';

    public $timestamps = false;
}

class EloquentHasManyTroughPost extends Model
{
    protected $dates = ['date'];

    protected $fillable = ['date', 'id', 'user_id'];

    protected $table = 'posts';
}
