<?php

namespace Cino\LaravelChronos\Eloquent\Concerns;

use Cino\LaravelChronos\Eloquent\Relations\Pivot;

trait ChronosGetPivotClass
{
    /**
     * Get the class being used for pivot models.
     *
     * @return string
     */
    public function getPivotClass()
    {
        return $this->using ?? Pivot::class;
    }
}
