<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/01/17
 * Time: 14:01
 */

namespace EssentialsBundle\Entity\DateTime;

/**
 * Class DateTime
 * @package Entity\Common\DateTime
 */
class DateTime extends \DateTime implements \JsonSerializable
{
    public function __construct($time = "now", \DateTimeZone $timezone = null)
    {
        ($timezone) ?: $timezone = new \DateTimeZone('UTC');
        parent::__construct($time, $timezone);
        $this->modify($time);
    }

    public function modify($modify)
    {
        return ('now' == $modify)
            ? parent::setTimestamp(time())
            : parent::modify($modify);
    }

    public function __toString()
    {
        return $this->jsonSerialize();
    }

    public function jsonSerialize()
    {
        return $this->format(\DateTime::RFC3339);
    }

    /**
     * @param string $format
     * @param string $time
     * @param null $timezone
     * @return DateTime
     */
    public static function createFromFormat($format, $time, $timezone = null)
    {
        $tmp = parent::createFromFormat($format, $time, $timezone);
        $dt = new DateTime($tmp->format(self::RFC3339), $timezone);

        unset($tmp);

        return $dt;
    }
}
