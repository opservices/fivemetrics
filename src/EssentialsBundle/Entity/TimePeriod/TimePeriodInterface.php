<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/23/17
 * Time: 8:57 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Interface TimePeriodInterface
 * @package EssentialsBundle\Entity\TimePeriodTest
 */
interface TimePeriodInterface
{
    const NO_SQL_FORMAT = "Uu000";

    const RELATIONAL_FORMAT = "Y-m-d H:i:s";

    const UNIX_TIMESTAMP = "U";

    /**
     * Returns time period's start.
     *
     * @param string $format
     * @return string
     */
    public function getStart(string $format = self::NO_SQL_FORMAT): string;

    /**
     * Returns time period's end.
     *
     * @param string $format
     * @return string
     */
    public function getEnd(string $format = self::NO_SQL_FORMAT): string;

    /**
     * Update time period's start and end date(time).
     */
    public function update();
}
