<?php

namespace Cino\LaravelChronos\Tests\Database\Concerns;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use Cino\LaravelChronos\Eloquent\Model;
use DateTime;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class ChronosTimestampsTest extends TestCase
{
    public function dateValues(): array
    {
        return [
            'timestamp' => [
                1545207000,
                new Chronos('2018-12-19T08:10:00.000000+0000'),
            ],
            'chronos instance' => [
                new Chronos('2018-12-19T08:10:00.000000+0000'),
                new Chronos('2018-12-19T08:10:00.000000+0000'),
            ],
            'datetime instance' => [
                new DateTime('2018-12-19T08:10:00.000000+0000'),
                new Chronos('2018-12-19T08:10:00.000000+0000'),
            ],
            'datetime immutable instance' => [
                new DateTimeImmutable('2018-12-19T08:10:00.000000+0000'),
                new Chronos('2018-12-19T08:10:00.000000+0000'),
            ],
            'string' => [
                '2018-12-19T08:10:00.000000+0000',
                new Chronos('2018-12-19T08:10:00.000000+0000'),
            ],
            'simple' => [
                '2018-12-19',
                new Chronos('2018-12-19T00:00:00.000000+0000'),
            ],
        ];
    }

    /**
     * @dataProvider dateValues
     * @param $value
     * @param \Cake\Chronos\Chronos $expected
     */
    public function testAsDateTime($value, Chronos $expected)
    {
        $model = new TimestampsTestModel();

        $this->assertInstanceOf(ChronosInterface::class, $model->asDateTime($value));
        $this->assertEquals($expected, $model->asDateTime($value));
    }

    public function testAsDateTimeException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to parse time string');

        $model = new TimestampsTestModel();
        $model->asDateTime('invalid date');
    }

    public function testFreshTimestamp(): void
    {
        $model = new TimestampsTestModel();

        $this->assertInstanceOf(ChronosInterface::class, $model->freshTimestamp());
    }
}

class TimestampsTestModel extends Model
{

}
