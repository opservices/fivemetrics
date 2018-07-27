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
 * Class LastYear
 * @package EssentialsBundle\Entity\TimePeriod
 */
class LastYear extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();

        $this->modifyDatesToNow();
        $this->start->modify("-1 year");
        $strTime = $this->start->format('Y-01-01\T00:00:00P');
        $this->start = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );

        $this->end->modify("-1 year");
        $strTime = $this->end->format('Y-12-31\T23:59:59P');
        $this->end = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );
    }
}