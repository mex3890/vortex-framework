<?php

namespace Core\jobs;

use Core\Helpers\StringFormatter;

class CronJob
{
    public const WEEK_DAYS = 'WEEK_DAYS';
    public const MONTHS = 'MONTHS';
    public const DAYS = 'DAYS';
    public const HOURS = 'HOURS';
    public const MINUTES = 'MINUTES';
    public const EVERY_WEEKDAY = '*';
    public const MONDAY_FRIDAY = '1-5';
    public const WEEKEND_DAYS = '0,6';
    public const SUNDAY = '0';
    public const MONDAY = '1';
    public const TUESDAY = '2';
    public const WEDNESDAY = '3';
    public const THURSDAY = '4';
    public const FRIDAY = '5';
    public const SATURDAY = '6';
    public const JANUARY = '1';
    public const FEBRUARY = '2';
    public const MARCH = '3';
    public const APRIL = '4';
    public const MAY = '5';
    public const JUNE = '6';
    public const JULY = '7';
    public const AUGUST = '8';
    public const SEPTEMBER = '9';
    public const OCTOBER = '10';
    public const NOVEMBER = '11';
    public const DECEMBER = '12';
    public const EVERY_MONTH = '*';
    public const EVEN_MONTHS = '*/2';
    public const ODD_MONTHS = '1-11/2';
    public const EVERY_FOUR_MONTHS = '*/4';
    public const EVERY_HALF_YEAR = '*/6';
    public const EVERY_DAY = '*';
    public const EVEN_DAYS = '*/2';
    public const ODD_DAYS = '1-31/2';
    public const EVERY_FIVE_DAYS = '*/5';
    public const EVERY_TEN_DAYS = '*/10';
    public const EVERY_HALF_MONTH = '*/15';
    public const EVERY_HOUR = '*';
    public const EVEN_HOUR = '*/2';
    public const ODD_HOURS = '1-23/2';
    public const EVERY_SIX_HOURS = '*/6';
    public const EVERY_TWELVE_HOURS = '*/12';
    public const EVERY_MINUTE = '*';
    public const EVEN_MINUTES = '*/2';
    public const ODD_MINUTES = '1-59/2';
    public const EVERY_FIVE_MINUTES = '*/5';
    public const EVERY_FIFTEEN_MINUTES = '*/15';
    public const EVERY_THIRTY_MINUTES = '*/30';

    private bool $save_output_on_log = false;
    private bool $save_output_on_file = false;
    private bool $save_output_on_database = false;
    
    private array $parameters = [
        self::MINUTES => '*',
        self::HOURS => '*',
        self::DAYS => '*',
        self::WEEK_DAYS => '*',
        self::MONTHS => '*',
    ];

    /**
     * @param string|array $minutes
     * Use available self constants or numbers [0-59]<br>
     * @return $this
     * <b>Constants:</b> EVERY_MINUTE, EVEN_MINUTES, ODD_MINUTES, EVERY_FIVE_MINUTES,
     * EVERY_FIFTEEN_MINUTES, EVERY_THIRTY_MINUTES
     */
    public function minutes(string|array $minutes): static
    {
        return $this->mountStringParameter(self::MINUTES, $minutes);
    }

    /**
     * @param string|array $hours
     * Use available self constants or numbers [0-23]<br>
     * @return $this<br>
     * <b>Constants:</b> EVERY_HOUR, EVEN_HOUR, ODD_HOURS, EVERY_SIX_HOURS, EVERY_TWELVE_HOURS
     */
    public function hours(string|array $hours): static
    {
        return $this->mountStringParameter(self::HOURS, $hours);
    }

    /**
     * @param string|array $days
     * Use available self constants or numbers [1-31]<br>
     * @return $this<br>
     * <b>Constants:</b> EVERY_DAY, EVEN_DAYS, ODD_DAYS, EVERY_FIVE_DAYS, EVERY_TEN_DAYS, EVERY_HALF_MONTH
     */
    public function days(string|array $days): static
    {
        return $this->mountStringParameter(self::DAYS, $days);
    }

    /**
     * @param string|array $months
     * Use available self constants
     * @return $this<br>
     * <b>Constants:</b> JANUARY, FEBRUARY, [ . . . ], EVERY_MONTH, EVEN_MONTHS,
     * ODD_MONTHS, EVERY_FOUR_MONTHS, EVERY_HALF_YEAR
     */
    public function months(string|array $months): static
    {
        return $this->mountStringParameter(self::MONTHS, $months);
    }

    /**
     * @param string|array $week_days
     * Use available self constants
     * @return $this
     * <b>Constants:</b> EVERY_DAY, SUNDAY, MONDAY, TUESDAY, WEDNESDAY, THURSDAY, FRIDAY, SATURDAY,
     * MONDAY_FRIDAY, SATURDAY_SUNDAY
     */
    public function weekDays(string|array $week_days): static
    {
        return $this->mountStringParameter(self::WEEK_DAYS, $week_days);
    }

    public function saveOutputOnFIle()
    {

    }

    public function saveOutputOnLog()
    {

    }

    public function saveOutputOnDatabase()
    {

    }

    public function test(): array|string
    {
        return $this->parameters;
    }

    private function mountStringParameter(string $parameter_key, array|string $values): static
    {
        if(is_array($values)) {
            $this->parameters[$parameter_key] = StringFormatter::mountStringByArray($values);

            return $this;
        }

        $this->parameters[$parameter_key] = $values;

        return $this;
    }
}