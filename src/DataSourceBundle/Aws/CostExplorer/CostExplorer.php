<?php

namespace DataSourceBundle\Aws\CostExplorer;

use Aws\CostExplorer\CostExplorerClient;
use DataSourceBundle\Aws\ClientAbstract;
use DataSourceBundle\Aws\CostExplorer\Parser\ParserInterface;
use DataSourceBundle\Aws\CostExplorer\Parser\ResultSet;
use DataSourceBundle\Aws\CostExplorer\Parser\TypeEnum as Type;
use DataSourceBundle\Entity\Aws\Region\Virginia;
use EssentialsBundle\Entity\TimePeriod\TimePeriodAbstract as TimePeriod;
use DataSourceBundle\Aws\CostExplorer\GranularityEnum as GEnum;

class CostExplorer extends ClientAbstract
{
    const VERSION = '2017-10-25';

    /**
     * @var CostExplorerClient
     */
    protected $client = null;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var TimePeriod
     */
    private $timePeriod;

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var ParserFactory
     */
    protected $factory;

    /**
     * CostExplorer constructor.
     * @param string $key
     * @param string $secret
     * @param TimePeriod $timePeriod
     */
    public function __construct(string $key, string $secret, TimePeriod $timePeriod)
    {
        parent::__construct($key, $secret, new Virginia());

        $this->config = new Config;
        $this->client = new CostExplorerClient([
            'region' => $this->getRegion()->getCode(),
            'version' => self::VERSION,
            'credentials' => $this->getCredential()
        ]);

        $this->setTimePeriod($timePeriod);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @inheritdoc
     */
    public function checkCredential(): bool
    {
        $this->getCost();
        return true;
    }

    /**
     * @param string $granularity
     * @return ResultSet
     */
    public function getCost(string $granularity = GEnum::MONTHLY): ResultSet
    {
        $this->setParser($this->getParserFactory()->create(
            $this->getTimePeriod(),
            $granularity
        ));

        $this->getConfig()->setGranularity($granularity);
        return $this->getCostAndUsage();
    }

    /**
     * @return ResultSet
     */
    public function getCostByService(): ResultSet
    {
        $this->getConfig()->groupByService();
        $this->setParser($this->getParserFactory()->create());
        return $this->getCostAndUsage();
    }

    /**
     * @return ResultSet
     */
    protected function getCostAndUsage(): ResultSet
    {
        return $this->getParser()->parse($this->getRawResult());
    }

    /**
     * @param array $config
     * @codeCoverageIgnore
     * @return array
     */
    public function getRawResult(array $config = []): array
    {
        $this->getConfig()->merge($config);
        return $this->doRequest();
    }

    /**
     * @return ParserFactory
     */
    public function getParserFactory(): ParserFactory
    {
        return $this->factory ?? new ParserFactory;
    }

    /**
     * @param ParserFactory $factory
     */
    protected function setParserFactory(ParserFactory $factory): void
    {
        $this->factory = $factory;
    }

    /**
     * @return ParserInterface
     */
    public function getParser(): ParserInterface
    {
        return $this->parser ?? $this->getParserFactory()->create();
    }

    /**
     * @param ParserInterface $parser
     */
    protected function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }

    /**
     * @return TimePeriod
     */
    public function getTimePeriod(): TimePeriod
    {
        return $this->timePeriod;
    }

    /**
     * @param TimePeriod $timePeriod
     */
    protected function setTimePeriod(TimePeriod $timePeriod): void
    {
        $this->timePeriod = $timePeriod;
        $this->getConfig()->setTimePeriod($timePeriod);
    }

    /**
     * @return CostExplorerClient
     */
    public function getClient(): CostExplorerClient
    {
        return $this->client;
    }

    /**
     * @return array
     */
    protected function doRequest(): array
    {
        return $this->getClient()
            ->getCostAndUsage($this->getConfig()->toArray())
            ->toArray();
    }
}
