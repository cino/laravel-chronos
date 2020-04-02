<?php

namespace Cino\LaravelChronos\Eloquent\Relations;

use Cino\LaravelChronos\Eloquent\Concerns\ChronosGetPivotClass;
use Cino\LaravelChronos\Eloquent\Concerns\ChronosInteractsWithPivotTable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BaseBelongsToMany;

class BelongsToMany extends BaseBelongsToMany
{
    use ChronosGetPivotClass;
    use ChronosInteractsWithPivotTable;
}
