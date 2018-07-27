<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 14:53
 */

namespace GearmanBundle\Tests\Configuration\DataSource;

use GearmanBundle\Collection\Configuration\JobServerCollection;
use GearmanBundle\Collection\Configuration\WorkerCollection;
use GearmanBundle\Entity\Configuration;
use GearmanBundle\Entity\Configuration\JobServer;
use GearmanBundle\Entity\Configuration\Worker;
use GearmanBundle\Configuration\DataSource\File;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

/**
 * Class FileTest
 * @package GearmanBundle\Tests\Configuration\DataSource
 */
class FileTest extends TestCase
{
    /**
     * @var File
     */
    protected $file;

    public function setUp()
    {
        $this->file = new File();
    }

    /**
     * @test
     */
    public function getDefaultConfigurationFile()
    {
        $this->assertEquals(
            File::CONF,
            $this->file->getFilename()
        );
    }

    /**
     * @test
     */
    public function setFilenameOnConstructor()
    {
        $file = new File('test');

        $this->assertEquals(
            'test',
            $file->getFilename()
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function loadConfigurationFileThatNotExists()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'file_get_contents' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('file_get_contents')
            ->will($this->returnValue(false));

        Reflection::setPropertyOnObject($this->file, 'fnCaller', $fnCaller);
        Reflection::callMethodOnObject($this->file, 'loadConfiguration');
    }

    /**
     * @test
     */
    public function loadConfigurationFile()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'file_get_contents' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('file_get_contents')
            ->will($this->returnValue('test'));

        Reflection::setPropertyOnObject($this->file, 'fnCaller', $fnCaller);

        $this->assertEquals(
            'test',
            Reflection::callMethodOnObject($this->file, 'loadConfiguration')
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function loadInvalidConfiguration()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'file_get_contents' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('file_get_contents')
            ->will($this->returnValue('test'));

        Reflection::setPropertyOnObject($this->file, 'fnCaller', $fnCaller);
        $this->file->load();
    }

    /**
     * @test
     */
    public function loadValidConfiguration()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'file_get_contents' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('file_get_contents')
            ->will($this->returnValue(
                '{ "jobservers": [{ "address": "127.0.0.1" }], "workers": [{ "class": "test", "desired": 10 }] }'
            ));

        Reflection::setPropertyOnObject($this->file, 'fnCaller', $fnCaller);

        $workers = new WorkerCollection();
        $workers->add(new Worker('test', 10));

        $jobServers = new JobServerCollection();
        $jobServers->add(new JobServer("127.0.0.1"));

        $this->assertEquals(
            new Configuration($workers, $jobServers),
            $this->file->load()
        );
    }
}
