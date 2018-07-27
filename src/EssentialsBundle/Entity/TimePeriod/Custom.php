<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/25/17
 * Time: 3:36 PM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Custom
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Custom extends TimePeriodAbstract
{
    /**
     * Custom constructor.
     * @param DateTime $start
     * @param DateTime $end
     */
    public function __construct(DateTime $start, DateTime $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
    }
}
