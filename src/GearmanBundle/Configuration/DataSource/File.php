<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 08:52
 */

namespace GearmanBundle\Configuration\DataSource;

use GearmanBundle\Collection\Configuration\JobServerCollection;
use GearmanBundle\Collection\Configuration\WorkerCollection;
use GearmanBundle\Entity\Configuration;
use GearmanBundle\Entity\Configuration\Worker;
use GearmanBundle\Entity\Configuration\JobServer;
use GearmanBundle\Configuration\LoaderInterface;
use EssentialsBundle\FunctionCaller;

/**
 * Class File
 * @package Gearman\Configuration\DataSource
 */
class File implements LoaderInterface
{
    const CONF = '/etc/app/gearman/conf.json';

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var FunctionCaller
     */
    protected $fnCaller;

    /**
     * File constructor.
     * @param string|null $filename
     */
    public function __construct(string $filename = null)
    {
        (is_null($filename))
            ? $this->setFilename(self::CONF)
            : $this->setFilename($filename);

        $this->fnCaller = new FunctionCaller();
    }

    /**
     * @return string
     * @TODO Is it really necessary?
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return File
     */
    public function setFilename(string $filename): File
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string
     */
    protected function loadConfiguration(): string
    {
        $data = $this->fnCaller->file_get_contents($this->getFilename());

        if ($data) {
            return $data;
        }

        throw new \RuntimeException(
            "It couldn't load the configuration file: "
            . $this->getFilename()
        );
    }

    /**
     * @param array $data
     * @return WorkerCollection
     */
    protected function buildWorkers(array $data)
    {
        $workers = new WorkerCollection();

        foreach ($data as $worker) {
            $workers->add(
                new Worker(
                    $worker['class'],
                    $worker['desired'],
                    (isset($worker['configuration'])) ? $worker['configuration'] : null
                )
            );
        }

        return $workers;
    }

    /**
     * @param array $data
     * @return JobServerCollection
     */
    protected function buildJobServers(array $data)
    {
        $jobServers = new JobServerCollection();

        foreach ($data as $jobServer) {
            $jobServers->add(new JobServer($jobServer['address']));
        }

        return $jobServers;
    }

    /**
     * @return Configuration
     */
    public function load(): Configuration
    {
        $data = json_decode($this->loadConfiguration(), true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \RuntimeException("It couldn't process the loaded configuration.");
        }

        return new Configuration(
            $this->buildWorkers($data['workers']),
            $this->buildJobServers($data['jobservers'])
        );
    }
}
