<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/09/17
 * Time: 10:00
 */

namespace DataSourceBundle\Tests\Entity\DataSource;

use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValue;
use Doctrine\Common\Collections\ArrayCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class DataSourceCollectTest extends TestCase
{
    /**
     * @var DataSourceCollect
     */
    protected $dsCollect;

    public function setUp()
    {
        $accountBuilder = EntityBuilderProvider::factory(Account::class);
        $dsBuilder = EntityBuilderProvider::factory(DataSource::class);
        $dsCollectBuilder = EntityBuilderProvider::factory(DataSourceCollect::class);

        $data = [
            'account' => $accountBuilder->factory([ 'email' => 'test@test.com' ], []),
            'dataSource' => $dsBuilder->factory([ 'name' => 'test.unit' ], []),
            'isEnabled' => true,
        ];

        $this->dsCollect = $dsCollectBuilder->factory($data, []);
    }

    /**
     * @test
     */
    public function getProperties()
    {
        $this->assertEquals(
            'test@test.com',
            $this->dsCollect->getAccount()->getEmail()
        );

        $this->assertEquals(
            'test.unit',
            $this->dsCollect->getDataSource()->getName()
        );

        $this->assertTrue($this->dsCollect->isEnabled());
    }

    /**
     * @test
     */
    public function setIsEnabled()
    {
        $this->dsCollect->setIsEnabled(false);
        $this->assertFalse($this->dsCollect->isEnabled());
    }

    /**
     * @test
     */
    public function setLastReceivedData()
    {
        $dt = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            '2017-09-21 10:30:00'
        );

        $this->dsCollect->setLastUpdate($dt);

        $this->assertSame(
            $dt,
            $this->dsCollect->getLastUpdate()
        );
    }

    /**
     * @test
     */
    public function setParameterValues()
    {
        $dsParamBuilder = EntityBuilderProvider::factory(DataSourceParameter::class);
        $builder = EntityBuilderProvider::factory(DataSourceParameterValue::class);

        $arr = new ArrayCollection();
        $arr->add($builder->factory([
            'collect' => $this->dsCollect,
            'account' => $this->dsCollect->getAccount(),
            'dataSource' => $this->dsCollect->getDataSource(),
            'parameter' => $dsParamBuilder->factory([
                'dataSource' => $this->dsCollect->getDataSource(),
                'name' => 'test',
            ]),
            'value' => 'unit'
        ]));

        $this->dsCollect->setParameterValues($arr);

        $this->assertSame($arr, $this->dsCollect->getParameterValues());
    }
}
