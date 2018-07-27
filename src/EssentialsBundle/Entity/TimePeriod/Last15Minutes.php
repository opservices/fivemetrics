<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/26/17
 * Time: 8:58 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class Last15Minutes
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Last15Minutes extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-15 minute");
    }
}
