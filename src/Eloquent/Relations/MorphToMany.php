<?php

namespace Cino\LaravelChronos\Eloquent\Relations;

use Cino\LaravelChronos\Eloquent\Concerns\ChronosGetPivotClass;
use Cino\LaravelChronos\Eloquent\Concerns\ChronosRelations;
use Illuminate\Database\Eloquent\Relations\MorphToMany as BaseMorphToMany;

class MorphToMany extends BaseMorphToMany
{
    use ChronosGetPivotClass;
    use ChronosRelations;

    /**
     * Create a new pivot model instance.
     *
     * @param array $attributes
     * @param bool $exists
     * @return \Cino\LaravelChronos\Eloquent\Relations\MorphPivot
     */
    public function newPivot(array $attributes = [], $exists = false)
    {
        $using = $this->using;

        $pivot = $using
            ? $using::fromRawAttributes($this->parent, $attributes, $this->table, $exists)
            : MorphPivot::fromAttributes($this->parent, $attributes, $this->table, $exists);

        $pivot->setPivotKeys($this->foreignPivotKey, $this->relatedPivotKey)
            ->setMorphType($this->morphType)
            ->setMorphClass($this->morphClass);

        return $pivot;
    }
}
