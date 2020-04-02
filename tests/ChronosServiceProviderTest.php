<?php

namespace Cino\LaravelChronos\Tests;

use ArrayAccess;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\ChronosServiceProvider;
use Illuminate\Contracts\Foundation\Application as ApplicationInterface;
use Illuminate\Support\Facades\Date;
use Mockery;
use PHPUnit\Framework\TestCase;

class ChronosServiceProviderTest extends TestCase
{
    /**
     * @var \ArrayAccess|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    protected $app;

    /**
     * @var \Cino\LaravelChronos\ChronosServiceProvider
     */
    protected $provider;

    public function setUp(): void
    {
        $this->app = Mockery::mock(ArrayAccess::class);

        /** @var ApplicationInterface $app */
        $app = $this->app;

        $this->provider = new ChronosServiceProvider($app);
    }

    public function testRegister(): void
    {
        $this->provider->register();

        $this->assertInstanceOf(ChronosInterface::class, Date::now());
    }
}
