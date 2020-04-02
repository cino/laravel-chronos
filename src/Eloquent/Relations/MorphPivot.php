<?php

namespace Cino\LaravelChronos\Eloquent\Relations;

use Cino\LaravelChronos\Eloquent\Concerns\ChronosTimestamps;
use Illuminate\Database\Eloquent\Relations\MorphPivot as BaseMorphPivot;

class MorphPivot extends BaseMorphPivot
{
    use ChronosTimestamps;
}
