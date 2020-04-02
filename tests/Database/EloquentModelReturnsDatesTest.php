<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class EloquentModelReturnsDatesTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->dateTime('datetime');
            $table->timestamps();
        });
    }

    protected function seedData(): void
    {
        $article = new EloquentModelReturnsDatesArticle();
        $article->date = Chronos::now();
        $article->datetime = Chronos::now();
        $article->save();
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('articles');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $article = EloquentModelReturnsDatesArticle::query()->first();

        $this->assertInstanceOf(ChronosInterface::class, $article->created_at);
        $this->assertInstanceOf(ChronosInterface::class, $article->date);
        $this->assertInstanceOf(ChronosInterface::class, $article->datetime);
        $this->assertInstanceOf(ChronosInterface::class, $article->updated_at);
    }
}

class EloquentModelReturnsDatesArticle extends Model
{
    protected $dates = ['date', 'datetime'];

    protected $fillable = ['date', 'datetime'];

    protected $table = 'articles';
}
