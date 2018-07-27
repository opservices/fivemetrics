<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/23/17
 * Time: 9:01 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class TimePeriodAbstract
 * @package EssentialsBundle\Entity\TimePeriodTest
 */
abstract class TimePeriodAbstract implements TimePeriodInterface
{
    /**
     * @var DateTime
     */
    protected $start;

    /**
     * @var DateTime
     */
    protected $end;

    public function __construct(DateTime $datetime = null)
    {
        $this->setDates($datetime ?? new DateTime());
        $this->update();
    }

    /**
     * Define start and end date(time) for current period.
     * @param DateTime $datetime
     */
    private function setDates(Datetime $datetime)
    {
        $this->start = clone($datetime);
        $this->end = clone($datetime);
    }

    public function getDates()
    {
        return ['start' => $this->start, 'end' => $this->end];
    }

    /**
     * @param string $format
     * @return string
     */
    public function getStart(string $format = TimePeriodInterface::NO_SQL_FORMAT): string
    {
        return $this->start->format($format);
    }

    /**
     * @param string $format
     * @return string
     */
    public function getEnd(string $format = TimePeriodInterface::NO_SQL_FORMAT): string
    {
        return $this->end->format($format);
    }

    /**
     * Modify start objects and end objects to now.
     */
    protected function modifyDatesToNow()
    {
        $this->end->setTimestamp(time());
        $this->start->setTimestamp(time());
    }
}
