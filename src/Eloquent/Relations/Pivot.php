<?php

namespace Cino\LaravelChronos\Eloquent;

use Cino\LaravelChronos\Eloquent\Concerns\ChronosTimestamps;
use Illuminate\Database\Eloquent\Relations\Pivot as BasePivot;

class Pivot extends BasePivot
{
    use ChronosTimestamps;
}
