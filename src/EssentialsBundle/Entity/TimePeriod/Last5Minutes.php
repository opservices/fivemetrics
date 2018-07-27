<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/26/17
 * Time: 8:58 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class Last5Minutes
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Last5Minutes extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-5 minute");
    }
}