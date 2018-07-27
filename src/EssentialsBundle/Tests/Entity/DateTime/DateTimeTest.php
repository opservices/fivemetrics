<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/02/17
 * Time: 20:14
 */

namespace EssentialsBundle\Tests\Entity\DateTime;

use EssentialsBundle\Entity\DateTime\DateTime;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * Class DateTimeTest
 * @package EssentialsBundle\Test\Entity\DateTime
 */
class DateTimeTest extends TestCase
{
    /**
     * @var DateTime
     */
    protected $dt;

    public function setUp()
    {
        $this->dt = new DateTime();
    }

    /**
     * @test
     */
    public function encodeDateTimeObject()
    {
        $this->assertEquals(
            '"' . $this->dt->format(DateTime::RFC3339) . '"',
            json_encode($this->dt)
        );
    }

    /**
     * @test
     */
    public function createDateTimeObjectFromFormat()
    {
        $this->assertInstanceOf(
            "EssentialsBundle\\Entity\\DateTime\\DateTime",
            DateTime::createFromFormat(
                DateTime::RFC3339,
                $this->dt->format(DateTime::RFC3339)
            )
        );
    }
}
