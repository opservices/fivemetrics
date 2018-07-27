<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 10:57 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Today
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Today extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $strTime = $this->start->format('Y-m-d\T00:00:00P');

        $this->start = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );
    }
}