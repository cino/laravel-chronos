# Laravel Chronos

This package is for enabling the usage of Chronos in Laravel, this will not cover all cases but at least gives you Chronos instances in favor of Carbon, most of the time. (You'll see that in the Laravel source there are enough cases where it calls Carbon directly.)

## Usage

### With Models

There are 2 ways to use Chronos with this package, you can either use the trait ChronosTimestamps in every Model/Pivot/MorphPivot you need to use Chronos, or you can extend the classes that are provided.

Example 1.

Extending the Model which already includes the trait ChronosTimestamps

```php

use Cino\LaravelChronos\Eloquent\Model;

class MyModel extends Model
{

}
```

Example 2.

Implementing the ChronosTimestamps trait directly onto your own model
```php

use Cino\LaravelChronos\Eloquent\Concerns\ChronosTimestamps;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    use ChronosTimestamps;
}
```

### With Pivot Relations

Modifying this behaviour for Pivots is pretty much the same, except you have to create your the pivot model in code and extend(or use the trait) the provided Pivot & MorphPivot class in this package. 
If you need more information on how to use your own Pivot model I suggest the [Laravel documentation](https://laravel.com/docs/7.x/eloquent-relationships#defining-custom-intermediate-table-models).
 
Example 1.

Creating your own pivot model & extend the provided Pivot class in this package.
```php

use Cino\LaravelChronos\Eloquent\Pivot;

class MyPivot extends Pivot
{

}
```

Example 2.

Implementing the ChronosTimestamps trait directly onto your own model
```php

use Cino\LaravelChronos\Eloquent\Concerns\ChronosTimestamps;
use Cino\LaravelChronos\Eloquent\Pivot;

class MyPivot extends Pivot
{
    use ChronosTimestamps;
}
```

### Special thanks

I'd like to point out that I had some great inspiration in the way of solving this with the trait from an existing package called [HealthEngineAU/laravel-chronos](https://github.com/HealthEngineAU/laravel-chronos).
