<?php

namespace Cino\LaravelChronos;

use Cake\Chronos\Chronos;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class ChronosServiceProvider extends ServiceProvider
{
    public function register()
    {
        Date::use(Chronos::class);
    }
}
