<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 9:44 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class LastHour
 * @package EssentialsBundle\Entity\TimePeriod
 */
class LastHour extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-1 hour");
    }
}