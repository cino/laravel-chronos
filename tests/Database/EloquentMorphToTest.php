<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class EloquentMorphToTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('posts', function (Blueprint $table) {
            $table->increments('id');
        });

        $this->schema()->create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('imageable_id');
            $table->string('imageable_type');
            $table->date('date');
            $table->timestamps();
        });
    }

    protected function seedData(): void
    {
        EloquentMorphToPost::query()->create(['id' => 1]);
        EloquentMorphToImage::query()->create([
            'imageable_id' => 1,
            'imageable_type' => EloquentMorphToPost::class,
            'date' => Chronos::now(),
        ]);
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('images');
        $this->schema()->drop('posts');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $post = EloquentMorphToPost::query()->first();
        $image = $post->image;

        $this->assertInstanceOf(ChronosInterface::class, $image->created_at);
        $this->assertInstanceOf(ChronosInterface::class, $image->date);
        $this->assertInstanceOf(ChronosInterface::class, $image->updated_at);
    }
}

class EloquentMorphToImage extends Model
{
    protected $dates = ['date'];

    protected $fillable = ['imageable_id', 'imageable_type', 'date'];

    protected $table = 'images';

    public function imageable()
    {
        return $this->morphTo();
    }
}

class EloquentMorphToPost extends Model
{
    protected $fillable = ['id'];

    protected $table = 'posts';

    public $timestamps = false;

    public function image()
    {
        return $this->morphOne(EloquentMorphToImage::class, 'imageable');
    }
}
