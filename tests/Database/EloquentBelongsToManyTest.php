<?php

namespace Cino\LaravelChronos\Tests\Database;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use Cino\LaravelChronos\Eloquent\Relations\BelongsToMany;
use Cino\LaravelChronos\Eloquent\Relations\Pivot;
use Illuminate\Database\Schema\Blueprint;

class EloquentBelongsToManyTest extends EloquentTestCase
{
    protected function createSchema(): void
    {
        $this->schema()->create('users', function (Blueprint $table) {
            $table->increments('id');
        });

        $this->schema()->create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->timestamps();
        });

        $this->schema()->create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        $this->schema()->create('role_user_using', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    protected function seedData(): void
    {
        $user = EloquentBelongsToManyUser::query()->create(['id' => 1]);
        EloquentBelongsToManyRole::query()->create(['date' => Chronos::now()]);
        EloquentBelongsToManyRole::query()->create(['date' => Chronos::now()]);
        EloquentBelongsToManyRole::query()->create(['date' => Chronos::now()]);

        $user->roles()->sync([3, 1, 2]);
        $user->rolesUsing()->sync([3, 1, 2]);
        $user->save();
    }

    protected function tearDown(): void
    {
        $this->schema()->drop('role_user');
        $this->schema()->drop('roles');
        $this->schema()->drop('users');
    }

    public function testDateFieldsReturnChronos(): void
    {
        $this->seedData();

        $user = EloquentBelongsToManyUser::query()->first();

        // Trigger refresh to ensure relations are still working after.
        $user = $user->refresh();

        foreach ($user->roles as $role) {
            $this->assertInstanceOf(ChronosInterface::class, $role->created_at);
            $this->assertInstanceOf(ChronosInterface::class, $role->date);
            $this->assertInstanceOf(ChronosInterface::class, $role->updated_at);
        }

        foreach ($user->rolesUsing as $role) {
            $this->assertInstanceOf(ChronosInterface::class, $role->created_at);
            $this->assertInstanceOf(ChronosInterface::class, $role->date);
            $this->assertInstanceOf(ChronosInterface::class, $role->updated_at);
        }

        $this->assertSame(Pivot::class, $user->roles()->getPivotClass());
        $this->assertSame(EloquentBelongsToManyRoleUsing::class, $user->rolesUsing()->getPivotClass());
    }
}

class EloquentBelongsToManyUser extends Model
{
    protected $fillable = ['id'];

    protected $table = 'users';

    public $timestamps = false;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(EloquentBelongsToManyRole::class, 'role_user', 'role_id', 'user_id');
    }

    public function rolesUsing(): BelongsToMany
    {
        return $this
            ->belongsToMany(EloquentBelongsToManyRole::class, 'role_user', 'role_id', 'user_id')
            ->using(EloquentBelongsToManyRoleUsing::class);
    }
}

class EloquentBelongsToManyRole extends Model
{
    protected $dates = ['date'];

    protected $fillable = ['date', 'id', 'user_id'];

    protected $table = 'roles';
}

class EloquentBelongsToManyRoleUsing extends Pivot
{
    protected $table = 'role_user_using';
}
