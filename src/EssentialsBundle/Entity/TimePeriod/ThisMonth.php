<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 10:47 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class ThisMonth
 * @package EssentialsBundle\Entity\TimePeriod
 */
class ThisMonth extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $strTime = $this->start->format('Y-m-01\T00:00:00P');
        $this->start = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );
    }
}