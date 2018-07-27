<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 09:31
 */

namespace EssentialsBundle\Tests\Entity\Shell\Command;

use EssentialsBundle\Entity\Shell\Command\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package EssentialsBundle\Test\Entity\Shell\Command
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validCommandsData
     * @param $data
     */
    public function buildValidCommand($data)
    {
        $command = Builder::build($data);

        $this->assertInstanceOf(
            'EssentialsBundle\Entity\Shell\Command\Command',
            $command
        );
    }

    public function validCommandsData()
    {
        $metrics = [
            '{
                "executable": "/bin/ls",
                "arguments":[
                    {
                        "name": "-l",
                        "value": "/root"
                    }, {
                        "name": "-h"
                    }
                ]
            }',
            '{
                "executable": "/bin/ls",
                "arguments":[]
            }',
            '{
                "executable": "/bin/ls",
                "arguments":[
                    {
                        "name": "-l"
                    }
                ]
            }'
        ];

        foreach ($metrics as $metric) {
            yield [ json_decode($metric, true) ];
        }
    }
}
