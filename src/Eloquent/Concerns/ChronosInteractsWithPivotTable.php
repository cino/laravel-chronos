<?php

namespace Cino\LaravelChronos\Eloquent\Concerns;

use Illuminate\Database\Eloquent\Relations\Pivot;

trait ChronosInteractsWithPivotTable
{
    /**
     * Get the pivot models that are currently attached.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getCurrentlyAttachedPivots()
    {
        return $this->newPivotQuery()->get()->map(function ($record) {
            $class = $this->using ? $this->using : Pivot::class;

            $pivot = $class::fromRawAttributes($this->parent, (array)$record, $this->getTable(), true);

            return $pivot->setPivotKeys($this->foreignPivotKey, $this->relatedPivotKey);
        });
    }
}
