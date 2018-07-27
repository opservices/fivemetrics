<?php


namespace DataSourceBundle\Tests\Aws\CostExplorer;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;

trait DataProvider
{
    protected $monthlyCostResult = [
        'ResultsByTime' => [
            [
                'TimePeriod' => [
                    'Start' => '2018-07-01',
                    'End' => '2018-07-31',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '313.0164395738',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
        ],
    ];

    protected $dailyCostResult = [
        'ResultsByTime' => [
            [
                'TimePeriod' => [
                    'Start' => '2018-07-01',
                    'End' => '2018-07-02',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '253.0696701324',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-02',
                    'End' => '2018-07-03',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '8.255634483',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-03',
                    'End' => '2018-07-04',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '9.5125776685',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-04',
                    'End' => '2018-07-05',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '9.2931010864',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-05',
                    'End' => '2018-07-06',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '8.6275998635',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-06',
                    'End' => '2018-07-07',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '8.7867508459',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-07',
                    'End' => '2018-07-08',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '8.2797581965',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-08',
                    'End' => '2018-07-09',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '6.5656020111',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-09',
                    'End' => '2018-07-10',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0.6257452865',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-10',
                    'End' => '2018-07-11',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-11',
                    'End' => '2018-07-12',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-12',
                    'End' => '2018-07-13',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-13',
                    'End' => '2018-07-14',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-14',
                    'End' => '2018-07-15',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-15',
                    'End' => '2018-07-16',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-16',
                    'End' => '2018-07-17',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-17',
                    'End' => '2018-07-18',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-18',
                    'End' => '2018-07-19',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-19',
                    'End' => '2018-07-20',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-20',
                    'End' => '2018-07-21',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-21',
                    'End' => '2018-07-22',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-22',
                    'End' => '2018-07-23',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-23',
                    'End' => '2018-07-24',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-24',
                    'End' => '2018-07-25',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-25',
                    'End' => '2018-07-26',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-26',
                    'End' => '2018-07-27',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-27',
                    'End' => '2018-07-28',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-28',
                    'End' => '2018-07-29',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-29',
                    'End' => '2018-07-30',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
            [
                'TimePeriod' => [
                    'Start' => '2018-07-30',
                    'End' => '2018-07-31',
                ],
                'Total' => [
                    'UnblendedCost' => [
                        'Amount' => '0',
                        'Unit' => 'USD',
                    ],
                ],
                'Groups' => [],
                'Estimated' => true,
            ],
        ],
    ];

    protected $serviceCostResult = [
        'GroupDefinitions' => [
            [
                'Type' => 'DIMENSION',
                'Key' => 'SERVICE',
            ],
        ],
        'ResultsByTime' => [
            [
                'TimePeriod' => [
                    'Start' => '2018-07-01',
                    'End' => '2018-07-31',
                ],
                'Total' => [],
                'Groups' => [
                    [
                        'Keys' => [
                            'Amazon Elastic Compute Cloud - Compute',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '251.9023191242',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'AWS Cost Explorer',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0.11',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'AWS Key Management Service',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'AWS Lambda',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0.0001587784',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'Amazon CloudFront',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0.0066848613',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'EC2 - Other',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '55.2982079782',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'AWS CloudTrail',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'Amazon Elastic Load Balancing',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '4.5512238005',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'Amazon Glacier',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0.0001639971',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'Amazon Route 53',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0.5044232',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'Amazon Simple Notification Service',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0.0000000843',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'Amazon Simple Queue Service',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'Amazon Simple Storage Service',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0.6432577498',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                    [
                        'Keys' => [
                            'AmazonCloudWatch',
                        ],
                        'Metrics' => [
                            'UnblendedCost' => [
                                'Amount' => '0',
                                'Unit' => 'USD',
                            ],
                        ],
                    ],
                ],
                'Estimated' => true,
            ],
        ],
    ];

    public static function createTimePeriod($format = 'Y-m-d')
    {
        return (new TimePeriodProvider)->getCustomTimePeriod(
            DateTime::createFromFormat($format, '2018-07-01'),
            DateTime::createFromFormat($format, '2018-07-31')
        );
    }

}