<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Cino\LaravelChronos\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Schema\Blueprint;

class EloquentMorphToManyTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->schema()->create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->timestamps();
        });

        $this->schema()->create('taggables', function (Blueprint $table) {
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('taggable_id');
            $table->string('taggable_type');
            $table->timestamps();
        });
    }

    protected function seedData(): void
    {
        $post = EloquentMorphToManyPost::query()->create(['id' => 1]);
        $tag = EloquentMorphToManyTag::query()->create(['id' => 1, 'date' => Chronos::now()]);

        $tag->posts()->save($post);
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('taggables');
        $this->schema()->drop('tags');
        $this->schema()->drop('posts');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $tag = EloquentMorphToManyTag::query()->first();
        foreach ($tag->posts as $post) {
            $this->assertInstanceOf(ChronosInterface::class, $post->created_at);
            $this->assertInstanceOf(ChronosInterface::class, $post->updated_at);
        }

        $post = EloquentMorphToManyPost::query()->first();

        foreach ($post->tags as $tag) {
            $this->assertInstanceOf(ChronosInterface::class, $tag->created_at);
            $this->assertInstanceOf(ChronosInterface::class, $tag->date);
            $this->assertInstanceOf(ChronosInterface::class, $tag->updated_at);
        }
    }
}


class EloquentMorphToManyPost extends Model
{
    protected $fillable = ['id'];

    protected $table = 'posts';

    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            EloquentMorphToManyTag::class,
            'taggable',
            null,
            null,
            'tag_id'
        );
    }
}

class EloquentMorphToManyTag extends Model
{
    protected $dates = ['date'];

    protected $fillable = ['id', 'date'];

    protected $table = 'tags';

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(
            EloquentMorphToManyPost::class,
            'taggable',
            null,
            'tag_id'
        );
    }
}
