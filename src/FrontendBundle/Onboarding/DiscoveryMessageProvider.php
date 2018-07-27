<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/11/17
 * Time: 15:30
 */

namespace FrontendBundle\Onboarding;

use DatabaseBundle\Controller\Api\V1\NoSql\MetricController;
use EssentialsBundle\Entity\Account\Account;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class DiscoveryMessageProvider
{
    protected const METRICS = [
        'aws.ec2.instances' => [
            'mailData' => [
                'label' => 'EC2',
                'icon' => 'icone_EC2.png',
                'total' => 0,
            ],
            'query' => [
                "periods" => [
                    "lastminute",
                    "last5minutes",
                    "last10minutes",
                    "last15minutes",
                    "last30minutes",
                    "lasthour",
                    "today",
                ],
                "query" => [
                    "aggregation" => "max",
                    "query" => [
                        "aggregation" => "sum",
                        "groupBy" => [
                            "time" => "second",
                        ],
                    ],
                ],
            ],
        ],
        'aws.ec2.elb.instances' => [
            'mailData' => [
                'label' => 'ELB',
                'icon' => 'icone_ELB.png',
                'total' => 0,
            ],
            "query" => [
                "periods" => [
                    "lastminute",
                    "last5minutes",
                    "last10minutes",
                    "last15minutes",
                    "last30minutes",
                    "lasthour",
                    "today",
                ],
                "query" => [
                    "aggregation" => "count",
                    "query" => [
                        "aggregation" => "min",
                        "groupBy" => [
                            "time" => "second",
                            "tags" => ["::fm::elbName"],
                        ],
                    ],
                ],
            ],
        ],
        'aws.ec2.ebs' => [
            'mailData' => [
                'label' => 'EBS',
                'icon' => 'icone_EBS.png',
                'total' => 0,
            ],
            "query" => [
                "periods" => [
                    "lastminute",
                    "last5minutes",
                    "last10minutes",
                    "last15minutes",
                    "last30minutes",
                    "lasthour",
                    "today",
                ],
                "query" => [
                    "aggregation" => "count",
                    "query" => [
                        "aggregation" => "max",
                        "groupBy" => [
                            "tags" => ["::fm::volumeId",],
                            "time" => "second",
                        ],
                    ],
                ],
            ],
        ],
        'aws.ec2.autoscaling.instances' => [
            'mailData' => [
                'label' => 'Auto Scaling',
                'icon' => 'icone_AUTOSCALING.png',
                'total' => 0,
            ],
            "query" => [
                "periods" => [
                    "lastminute",
                    "last5minutes",
                    "last10minutes",
                    "last15minutes",
                    "last30minutes",
                    "lasthour",
                    "today",
                ],
                "query" => [
                    "aggregation" => "max",
                    "query" => [
                        "aggregation" => "sum",
                        "groupBy" => [
                            "time" => "second",
                        ],
                    ],
                ],
            ],
        ],
        'aws.s3.bucket.versioning' => [
            'mailData' => [
                'label' => 'S3',
                'icon' => 'icone_S3.png',
                'total' => 0,
            ],
            "query" => [
                "periods" => [
                    "lastminute",
                    "last5minutes",
                    "last10minutes",
                    "last15minutes",
                    "last30minutes",
                    "lasthour",
                    "today",
                ],
                "query" => [
                    "aggregation" => "sum",
                    "query" => [
                        "aggregation" => "max",
                        "groupBy" => [
                            "tags" => [
                                "::fm::region",
                                "::fm::bucketName",
                            ],
                        ],
                        "time" => "second",
                    ],
                ],
            ],
        ],
        'aws.glacier.vault.size' => [
            'mailData' => [
                'label' => 'Glacier',
                'icon' => 'icone_GLACIER.png',
                'total' => 0,
            ],
            "query" => [
                "periods" => [
                    "lastminute",
                    "last5minutes",
                    "last10minutes",
                    "last15minutes",
                    "last30minutes",
                    "lasthour",
                    "today",
                ],
                "query" => [
                    "aggregation" => "count",
                    "query" => [
                        "aggregation" => "max",
                        "groupBy" => [
                            "tags" => ["::fm::vaultName",],
                            "time" => "second",
                        ],
                    ],
                ],
            ],
        ],
    ];

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Account $account
     * @return array
     */
    protected function getDiscoveryData(Account $account): array
    {
        $request = new Request();
        $controller = new MetricController();
        $controller->setContainer($this->container);
        $controller->loginUser($account, new Request());

        $summary = [];
        $total = 0;
        foreach (self::METRICS as $name => $data) {
            $request->query->replace(['q' => json_encode($data['query'])]);
            $response = $controller->getMetricHistoryAction($request, $name);
            $response = json_decode($response->getContent(), true);
            $value = $response['series'][0]['points'][0]['value'];

            if (!$value) {
                continue;
            }

            $summary[$name] = $data['mailData'];
            $summary[$name]['total'] = $value;
            $total += $value;
        }

        unset($controller);
        unset($request);

        return [
            'total' => $total,
            'collectors' => array_values($summary),
        ];
    }

    /**
     * @param Account $account
     * @return Swift_Message
     */
    public function getMailMessage(Account $account): Swift_Message
    {
        $message = new Swift_Message('[ FiveMetrics ] Welcome!');
        $message->setFrom([ $this->container->getParameter('mailer_user') => 'FiveMetrics'], 'FiveMetrics')
            ->setTo($account->getEmail())
            ->setBody(
                $this->container->get('twig')->render(
                    'Emails/onBoarding.html.twig',
                    $this->getDiscoveryData($account)
                ),
                'text/html'
            );

        return $message;
    }
}
