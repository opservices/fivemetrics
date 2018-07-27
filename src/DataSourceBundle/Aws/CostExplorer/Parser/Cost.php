<?php

namespace DataSourceBundle\Aws\CostExplorer\Parser;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodAbstract;

abstract class Cost implements ParserInterface
{
    /**
     * @var DateTime
     */
    protected $start;

    /**
     * @var DateTime
     */
    protected $end;

    /**
     * @var DateTime
     */
    protected $now;

    /**
     * Cost constructor.
     * @param TimePeriodAbstract $timePeriod
     * @param DateTime|null $now
     */
    public function __construct(TimePeriodAbstract $timePeriod, DateTime $now = null)
    {
        list('start' => $this->start, 'end' => $this->end) = $timePeriod->getDates();
        $this->now = $now ?: new DateTime();
    }

    public function parse(array $costExplorerData): ResultSet
    {
        $result = array_reduce(
            $costExplorerData['ResultsByTime'],
            [$this, 'reduce'],
            new ResultSet()
        );

        $result->calculateForecast(
            $this->now->diff($this->end)->days + 1,
            $this->start->diff($this->end)->days + 1
        );

        return $result;
    }

    /**
     * @param ResultSet $carr
     * @param array $item
     * @return mixed
     */
    abstract protected function reduce(ResultSet $carr, array $item): ResultSet;
}