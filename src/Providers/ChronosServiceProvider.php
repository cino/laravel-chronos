<?php

namespace Cino\LaravelChronos\Providers;

use Cake\Chronos\Chronos;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Date;

class ChronosServiceProvider extends ServiceProvider
{
    public function register()
    {
        Date::use(Chronos::class);
    }
}
