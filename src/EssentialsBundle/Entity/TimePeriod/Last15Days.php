<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/26/17
 * Time: 10:04 AM
 */

namespace EssentialsBundle\Entity\TimePeriod;

/**
 * Class Last15Days
 * @package EssentialsBundle\Entity\TimePeriod
 */
class Last15Days extends TimePeriodAbstract
{
    /**
     * @inheritdoc
     */
    public function update()
    {
        $this->modifyDatesToNow();
        $this->start->modify("-15 day");
    }
}
