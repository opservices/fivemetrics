<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/26/17
 * Time: 8:58 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class LastMinute
 * @package EssentialsBundle\Entity\TimePeriod
 */
class LastMinute extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-1 minute");
    }
}