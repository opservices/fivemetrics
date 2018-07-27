<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 14:28
 */

namespace GearmanBundle\Tests\Configuration;

use GearmanBundle\Configuration\Loader;
use PHPUnit\Framework\TestCase;

/**
 * Class LoaderTest
 * @package GearmanBundle\Tests\Configuration
 */
class LoaderTest extends TestCase
{
    /**
     * @test
     */
    public function getInstance()
    {
        $this->assertInstanceOf(
            "GearmanBundle\\Configuration\\LoaderInterface",
            Loader::getInstance()
        );
    }
}
