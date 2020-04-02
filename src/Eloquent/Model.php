<?php

namespace Cino\LaravelChronos\Eloquent;

use Cino\LaravelChronos\Eloquent\Concerns\ChronosRelations;
use Cino\LaravelChronos\Eloquent\Concerns\ChronosTimestamps;
use Cino\LaravelChronos\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Collection;

abstract class Model extends BaseModel
{
    use ChronosRelations;
    use ChronosTimestamps;

    /**
     * Create a new pivot model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $parent
     * @param array $attributes
     * @param string $table
     * @param bool $exists
     * @param string|null $using
     * @return \Cino\LaravelChronos\Eloquent\Relations\Pivot
     */
    public function newPivot(BaseModel $parent, array $attributes, $table, $exists, $using = null)
    {
        return $using ? $using::fromRawAttributes($parent, $attributes, $table, $exists)
            : Pivot::fromAttributes($parent, $attributes, $table, $exists);
    }

    /**
     * Reload the current model instance with fresh attributes from the database.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function refresh()
    {
        if (!$this->exists) {
            return $this;
        }

        $this->setRawAttributes(
            static::newQueryWithoutScopes()->findOrFail($this->getKey())->attributes
        );

        $this->load(Collection::make($this->relations)->reject(function ($relation) {
            return $relation instanceof Pivot;
        })->keys()->all());

        $this->syncOriginal();

        return $this;
    }
}
