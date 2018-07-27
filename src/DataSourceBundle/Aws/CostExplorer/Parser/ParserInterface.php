<?php

namespace DataSourceBundle\Aws\CostExplorer\Parser;


interface ParserInterface
{
    /**
     * @param array $costExplorerData
     * @return ResultSet
     */
    public function parse(array $costExplorerData): ResultSet;
}