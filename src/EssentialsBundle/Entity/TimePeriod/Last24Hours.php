<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/23/17
 * Time: 9:28 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class Last24Hours
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Last24Hours extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-23 hour");
    }
}