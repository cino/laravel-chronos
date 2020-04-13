<?php

namespace Cino\LaravelChronos\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use Chronos;
}
