<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 9:35 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

class Last31Days extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-31 day");
    }
}