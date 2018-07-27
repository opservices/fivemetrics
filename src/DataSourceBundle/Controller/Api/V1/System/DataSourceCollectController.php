<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/09/17
 * Time: 11:20
 */

namespace DataSourceBundle\Controller\Api\V1\System;

use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use EssentialsBundle\Api\ControllerAbstract;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DataSourceCollectController
 * @package DataSourceBundle\Controller\Api\V1
 * @Route("/system/collect")
 */
class DataSourceCollectController extends ControllerAbstract
{
    /**
     * @Route("/", name="apiSystemListCollects")
     * @Method({"GET"})
     */
    public function listCollectsAction(Request $request)
    {
        $collectInterval = (is_null($request->query->get('collectInterval')))
            ? null
            : $request->query->get('collectInterval');

        $isEnabled = (is_null($request->query->get('isEnabled')))
            ? null
            : $request->query->getBoolean('isEnabled');

        $mapper = $this->get('data.source.api.v1.mapper');
        $repo   = $this->get('doctrine')
            ->getRepository(DataSourceCollect::class);

        $collects = $repo->findByInterval($collectInterval, $isEnabled);

        return $this->createApiResponse(
            $mapper->getCollectsResponse(
                $collects,
                [
                    'time',
                    'account' => [ 'email', 'uid', 'id', 'password', 'roles', 'username' ],
                    'collects' => [
                        'id',
                        'isEnabled',
                        'lastUpdate',
                        'uid',
                        'dataSource' => [ 'name', 'maxConcurrency', 'collectInterval' ],
                        'parameters' => [ 'name', 'value' ],
                    ],
                ]
            )
        );
    }
}
