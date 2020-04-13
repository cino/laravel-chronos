# Laravel Chronos

<a href="https://github.com/cino/laravel-chronos/actions"><img src="https://github.com/cino/laravel-chronos/workflows/LaravelChronos/badge.svg" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/cino/laravel-chronos/?branch=master"><img src="https://scrutinizer-ci.com/g/cino/laravel-chronos/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://poser.pugx.org/cino/laravel-chronos/license"><img src="https://poser.pugx.org/cino/laravel-chronos/license" alt="Code Coverage"></a>

This package is for enabling the usage of Chronos in Laravel, this will not cover all cases but at least gives you Chronos instances in favor of Carbon, most of the time. (You'll see that in the Laravel source there are enough cases where it calls Carbon directly.)


## Installation

The preferred method of installation is via [Composer](). Run the following
command to install the package and add it as a requirement to your project's
`composer.json`:

```bash
composer require cino/laravel-chronos
```

## Usage

There are now 2 options to add this behaviour to your models. Either of the options will override functions to return a Chronos object instead of a Carbon object, the first and preferred option is to use the Chronos trait from \Cino\LaravelChronos\Eloquent\Chronos like below: 

### Trait
```php
use Cino\LaravelChronos\Eloquent\Chronos;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use Chronos;
}
```

### Extending model
The second option is to change your models to extend the Model class from \Cino\LaravelChronos\Eloquent\Model which actually also uses the trait from above.

```php

use Cino\LaravelChronos\Eloquent\Model;

class MyModel extends Model
{

}
```

## License
This open-source software is licenced under the [MIT license](LICENSE.md).
