<?php

namespace Cino\LaravelChronos\Eloquent;

use Cino\LaravelChronos\Eloquent\Concerns\ChronosTimestamps;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    use ChronosTimestamps;
}
