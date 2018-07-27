<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 9:47 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class LastWeek
 * @package EssentialsBundle\Entity\TimePeriod
 */
class LastWeek extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-1 week");
        $this->start->modify("last sunday");
        $strTime = $this->start->format('Y-m-d\T00:00:00P');
        $this->start = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );

        $this->end->modify("-1 week");
        $this->end->modify("last sunday");
        $this->end->modify("+6 day");
        $strTime = $this->end->format('Y-m-d\T23:59:59P');
        $this->end = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );
    }
}