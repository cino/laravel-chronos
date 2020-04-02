# Laravel Chronos

![LaravelChronos](https://github.com/cino/laravel-chronos/workflows/LaravelChronos/badge.svg)

This package is for enabling the usage of Chronos in Laravel, this will not cover all cases but at least gives you Chronos instances in favor of Carbon, most of the time. (You'll see that in the Laravel source there are enough cases where it calls Carbon directly.)

## Usage

The only thing you need to do is change your models to extend the Model class from this package. When you do this all functions that interact with dates are overridden to return a Chronos object.

```php

use Cino\LaravelChronos\Eloquent\Model;

class MyModel extends Model
{

}
```
