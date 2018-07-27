<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/26/17
 * Time: 8:58 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class Last10Minutes
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Last10Minutes extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-10 minute");
    }
}
