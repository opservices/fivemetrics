<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/26/17
 * Time: 2:55 PM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class TimePeriodProvider
 * @package EssentialsBundle\Entity\TimePeriod
 */
class TimePeriodProvider
{
    private $timePeriods = [
        "lastminute"   => LastMinute::class,
        "last5minutes" => Last5Minutes::class,
        "last10minutes" => Last10Minutes::class,
        "last15minutes" => Last15Minutes::class,
        "last30minutes" => Last30Minutes::class,
        "last7days"    => Last7Days::class,
        "last24hours"  => Last24Hours::class,
        "last30days"   => Last30Days::class,
        "last31days"   => Last31Days::class,
        "lasthour"     => LastHour::class,
        "lastmonth"    => LastMonth::class,
        "lastweek"     => LastWeek::class,
        "lastyear"     => LastYear::class,
        "last15days"   => Last15Days::class,
        "thishour"     => ThisHour::class,
        "thismonth"    => ThisMonth::class,
        "thisweek"     => ThisWeek::class,
        "thisyear"     => ThisYear::class,
        "today"        => Today::class,
        "yesterday"    => Yesterday::class,
    ];


    /**
     * @param string $timePeriod
     * @return TimePeriodAbstract
     * @deprecated
     */
    public function factory($timePeriod = "last5minutes") : TimePeriodAbstract
    {
        return $this->get($timePeriod);
    }

    /**
     * Returns a time period object.
     *
     * Valids time periods are:
     *
     * Now (default), LastHour, Last5Minutes, Last24Hours, Last31Days, Last7Days, LastWeek,
     * LastMonth, LastYear, ThisHour, Today, ThisWeek, ThisMonth, ThisYear and
     * Yesterday.
     *
     * @param String $timePeriod TimePeriod name
     * @return TimePeriodAbstract
     * @throw RuntimeException
     */
    public function get($timePeriod = "last5minutes") : TimePeriodAbstract
    {
        $key = strtolower($timePeriod);

        if (array_key_exists($key, $this->timePeriods)) {
            $className = $this->timePeriods[$key];
            return new $className();
        }

        throw new \InvalidArgumentException(
            $timePeriod . " is an invalid time period.",
            Exceptions::VALIDATION_ERROR
        );
    }

    /**
     * Returns a custom TimePeriod
     *
     * @param DateTime $start instance
     * @param DateTime $end instance
     * @return TimePeriodAbstract
     */
    public function getCustomTimePeriod(DateTime $start, DateTime $end): TimePeriodAbstract
    {
        return new Custom($start, $end);
    }

    /**
     * Returns an array with all valid time periods.
     *
     * @return array
     */
    public function listTimePeriods(): array
    {
        return array_keys($this->timePeriods);
    }
}
