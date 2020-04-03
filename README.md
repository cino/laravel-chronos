# Laravel Chronos

<a href="https://github.com/cino/laravel-chronos/actions"><img src="https://github.com/cino/laravel-chronos/workflows/LaravelChronos/badge.svg" alt="Build Status"></a>
<a href="https://scrutinizer-ci.com/g/cino/laravel-chronos/?branch=master"><img src="https://scrutinizer-ci.com/g/cino/laravel-chronos/badges/coverage.png?b=master" alt="Code Coverage"></a>
<a href="https://poser.pugx.org/cino/laravel-chronos/license"><img src="https://poser.pugx.org/cino/laravel-chronos/license" alt="Code Coverage"></a>

This package is for enabling the usage of Chronos in Laravel, this will not cover all cases but at least gives you Chronos instances in favor of Carbon, most of the time. (You'll see that in the Laravel source there are enough cases where it calls Carbon directly.)


## Installation

The preferred method of installation is via [Composer][]. Run the following
command to install the package and add it as a requirement to your project's
`composer.json`:

```bash
composer require ramsey/uuid
```

## Usage

The only thing you need to do is change your models to extend the Model class from this package. When you do this all functions that interact with dates are overridden to return a Chronos object.

```php

use Cino\LaravelChronos\Eloquent\Model;

class MyModel extends Model
{

}
```

## License
This open-source software is licenced under the [MIT license](LICENSE.md).
