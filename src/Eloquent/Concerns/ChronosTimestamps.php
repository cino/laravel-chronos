<?php

namespace Cino\LaravelChronos\Eloquent\Concerns;

use Cake\Chronos\Chronos;
use Cake\Chronos\ChronosInterface;
use DateTimeInterface;

trait ChronosTimestamps
{
    /**
     * Return a timestamp as DateTime object with time set to 00:00:00.
     *
     * @param mixed $value
     * @return \Cake\Chronos\Chronos
     */
    protected function asDate($value)
    {
        return $this->asDateTime($value)->startOfDay();
    }

    /**
     * Return a timestamp as DateTime object.
     *
     * @param mixed $value
     * @return \Cake\Chronos\Chronos
     */
    public function asDateTime($value)
    {
        // If this value is already a Chronos instance, we shall just return it as is.
        // This prevents us having to re-instantiate a Chronos instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof ChronosInterface) {
            return $value;
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof DateTimeInterface) {
            return Chronos::instance($value);
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return Chronos::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Chronos instances from that format. Again, this provides for simple date
        // fields on the database.
        if ($this->isStandardDateFormat($value)) {
            return Chronos::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        // If everything else try parsing.
        return Chronos::parse($value);
    }

    public function freshTimestamp()
    {
        return new Chronos;
    }
}
