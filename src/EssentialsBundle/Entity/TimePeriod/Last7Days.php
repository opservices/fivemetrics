<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 9:37 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class Last7Days
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Last7Days extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-7 day");
    }
}