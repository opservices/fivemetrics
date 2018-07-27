<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 9:52 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class TimePeriodLAstMonth
 * @package EssentialsBundle\Entity\TimePeriod
 */
class LastMonth extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();

        $this->modifyDatesToNow();
        $this->start->modify("-1 month");
        $strTime = $this->start->format('Y-m-01\T00:00:00P');
        $this->start = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );

        $this->end->modify("-1 month");
        $strTime = $this->end->format('Y-m-d\T23:59:59P');
        $this->end = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );
    }
}