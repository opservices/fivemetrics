<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 10:59 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Yesterday
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Yesterday extends TimePeriodAbstract
{
    /**
     * @see TimePeriodInterface update
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-1 day");
        $strTime = $this->start->format('Y-m-d\T00:00:00P');

        $this->start = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );

        $this->end->modify("-1 day");
        $strTime = $this->end->format('Y-m-d\T23:59:59P');
        $this->end = DateTime::createFromFormat(
            'Y-m-d\TH:i:sP',
            $strTime
        );
    }
}
