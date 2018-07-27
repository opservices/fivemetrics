<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/09/17
 * Time: 11:20
 */

namespace DataSourceBundle\Controller\Api\V1;

use DataSourceBundle\Api\V1\DataSourceCollect\ParametersResolver;
use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Exception\Exceptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DataSourceCollectController
 * @package DataSourceBundle\Controller\Api\V1
 * @Route("/collect")
 */
class DataSourceCollectController extends ControllerAbstract
{
    /**
     * @Route("/", name="apiListCollects")
     * @Method({"GET"})
     * @SWG\Get(
     *     summary="Retrieve the collects properties.",
     *     @SWG\Parameter(
     *          name="isEnabled",
     *          in="query",
     *          type="boolean",
     *          description="Show only enabled or disabled collects. The default response will show all collects.",
     *     ),
     *     @SWG\Parameter(
     *          name="unique",
     *          in="query",
     *          type="boolean",
     *          description="It defines if a data source must be returned every time it's used.",
     *     ),
     *     @SWG\Parameter(
     *          name="properties",
     *          in="query",
     *          type="string",
     *          description="Should be used to select what collect must be returned. This parameter is an array using JSON format.<br>
    <h6>Allowed values</h6>
    <em>dataSource, isEnabled, lastUpdate, parameters</em><br><br>
    Example:<br>
    <code>[""dataSource"", ""isEnabled"", ""lastUpdate"", ""parameters""]</code><br>
    ",
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It will return an array with the collects properties.<br>
    Example: <br><br>
    {""time"":""0000-00-00T00:00:00+00:00"",""account"":{""email"":""myEmail"",""username"":""tester""},""collects"":[{""dataSource"":{""name"":""dataSourceName""},""parameters"":[{""name"":""aws.key"",""value"":""myKey""}],""isEnabled"":true,""lastUpdate"":null}]}"
     *     )
     * )
     */
    public function listCollectsAction(Request $request)
    {
        $isEnabled = (is_null($request->query->get('isEnabled')))
            ? null
            : $request->query->getBoolean('isEnabled');

        $unique = (is_null($request->query->get('unique')))
            ? false
            : $request->query->getBoolean('unique');

        $properties = (is_null($request->query->get('properties')))
            ? []
            : json_decode($request->query->get('properties'), true);

        $filter = ['account' => $this->getUser()];
        (is_null($isEnabled)) ?: $filter['isEnabled'] = $isEnabled;

        $collects = $this->get('doctrine')
            ->getRepository(DataSourceCollect::class)
            ->findBy($filter);

        $mapper = $this->get('data.source.api.v1.mapper');
        $collects = $mapper->getCollectsResponse(
            $collects,
            [
                'time',
                'collects' => [
                    'isEnabled',
                    'lastUpdate',
                    'uid',
                    'dataSource' => ['name'],
                    'parameters' => ['name', 'value'],
                ],
            ]
        );

        if (!empty($properties)) {
            $result = [];
            foreach ($collects as $accountCollects) {
                $time = $accountCollects['time'];

                $result = array_map(function ($collect) use ($properties) {
                    return array_filter($collect, function ($key) use ($properties) {
                        return (in_array($key, $properties));
                    }, ARRAY_FILTER_USE_KEY);
                }, $accountCollects['collects']);
            }

            $collects = [[
                'time' => $time ?? null,
                'collects' => $result,
            ]];
        }

        if ($unique) {
            $collects[0]['collects'] = array_values(array_unique(
                $collects[0]['collects'],
                SORT_REGULAR
            ));
        }

        return $this->createApiResponse($collects[0]);
    }

    /**
     * @Route("/", name="apiCreateCollects")
     * @Method({"POST"})
     * @param Request $request
     * @return mixed
     * @SWG\Post(
     *     summary="To create new collects.",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="collects",
     *          type="string",
     *          description="A JSON string representing the collect parameters.
    <br>The collection parameters must be in an array and each collection contains a valid DataSource.
    <br><br>Example:<br>
    <code>[{""dataSource"":{""name"":""dataSourceName""}, ""parameters"":[{""name"":""aws.key"",""value"":""myKey""}]}]</code><br>",
     *          @SWG\Schema(
     *              @SWG\Property(property="dataSource", type="object"),
     *              @SWG\Property(property="parameters", type="array")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="The collects was created.<br>
    <code>[{""time"":""0000-00-00T00:00:00+00:00"",""account"":{""email"":""myEmail"",""username"":""tester"",""dataSourceParameterValues"":[],""collects"":[]},""collects"":[{""dataSource"":{""name"":""dataSourceName""},""parameters"":[{""name"":""aws.key"",""value"":""myKey""}],""isEnabled"":true,""lastUpdate"":null}]}]</code>"
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="If a DataSource not was found."
     *     ),
     *     @SWG\Response(
     *          response=409,
     *          description="If a collect already exists."
     *     )
     * )
     */
    public function createCollectsAction(Request $request, string $uid = null)
    {
        $requestParams = json_decode($request->getContent(), true);
        (is_array($requestParams)) ?: $requestParams = [];

        /** @var ObjectManager $em */
        $em = $this->getDoctrine()->getManager();
        $dsRepo = $em->getRepository(DataSource::class);
        $resolver = new ParametersResolver();
        $response = new ArrayCollection();
        foreach ($requestParams as $data) {
            $ds = $dsRepo->findOneBy(['name' => $data['dataSource']['name']]);
            if (empty($ds)) {
                $message = sprintf("Data source [%s] not found.", $data['dataSource']['name']);
                $this->throwApiProblemResponse(Exceptions::RESOURCE_NOT_FOUND, [$message]);
            }

            $dsParams = $resolver->process($this->getUser(), $ds, $data['parameters']);
            $collects = $em->getRepository(DataSourceCollect::class)->findBy([
                'account' => $this->getUser(),
                'dataSource' => $ds
            ]);

            if (is_null($uid)) {
                $md5Parameters = md5($this->getParametersAsString($dsParams));

                /** @var DataSourceCollect $dbCollect */
                foreach ($collects as $dbCollect) {
                    $parameters = [];
                    foreach ($dbCollect->getParameterValues() as $parameter) {
                        $key = $parameter->getParameter()->getName();
                        $value = $parameter->getValue();
                        $parameters[$key] = $value;
                    }

                    $md5 = md5($this->getParametersAsString($parameters));

                    if ($md5 == $md5Parameters) {
                        $this->throwApiProblemResponse(
                            Exceptions::CONFLICT,
                            ['It seems like you are trying to add a duplicated collect.']
                        );
                    }
                }
            }

            $collect = new DataSourceCollect();
            $collect->setAccount($this->getUser())->setDataSource($ds);
            if (! is_null($uid)) {
                $collect->setUid($uid);
            }

            $dataSourceParametersValues = new ArrayCollection();
            foreach ($ds->getParameters() as $dataSourceParameter) {
                $value = $dsParams[$dataSourceParameter->getName()];
                $dataSourceParameterValue = new DataSourceParameterValue();
                $dataSourceParameterValue
                    ->setDataSource($ds)
                    ->setAccount($this->getUser())
                    ->setParameter($dataSourceParameter)
                    ->setCollect($collect)
                    ->setValue($value);

                $em->persist($dataSourceParameterValue);
                $dataSourceParametersValues->add($dataSourceParameterValue);
            }

            $collect->setParameterValues($dataSourceParametersValues);
            $em->persist($collect);
            $em->flush();
            $response->add($collect);
        }

        $mapper = $this->get('data.source.api.v1.mapper');
        return $this->createApiResponse(
            $mapper->getCollectsResponse(
                $response->toArray(),
                [
                    'time',
                    'collects' => [
                        'isEnabled',
                        'lastUpdate',
                        'uid',
                        'dataSource' => ['name',],
                        'parameters' => ['name', 'value',],
                    ],
                ]
            ),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param Request $request
     * @param $uid
     * @Route("/{uid}", name="apiUpdateCollectAction")
     * @Method({"PUT"})
     */
    public function updateCollectAction(Request $request, $uid)
    {
        $requestContent = json_decode($request->getContent(), true);
        (is_array($requestContent)) ?: $requestContent = [];

        $dsName = $requestContent['dataSource']['name'] ?? null;
        $params = $requestContent['parameters'] ?? [];
        $isEnabled = $requestContent['isEnabled'] ?? null;

        if ((is_null($dsName)) && (empty($params)) && (is_null($isEnabled))) {
            return $this->throwApiProblemResponse(
                Exceptions::VALIDATION_ERROR
            );
        }

        $response = new ArrayCollection();
        if (! is_null($dsName)) {
            $this->deleteCollectAction($uid);
            $createRequest = new Request(
                [],
                [],
                [],
                [],
                [],
                [],
                json_encode([ $requestContent ])
            );

            $tmp = $this->createCollectsAction($createRequest, $uid);
        } elseif ((is_array($params)) && (! empty($params))) {
            $collect = $this->retrieveCollect($uid);
            /** @var ArrayCollection $dsParams */
            $dsParams = $collect->getParameterValues();
            foreach ($params as $param) {
                foreach ($dsParams as $dsParam) {
                    /** @var DataSourceParameterValue $dsParam */
                    if ($dsParam->getParameter()->getName() != $param['name']) {
                        continue;
                    }

                    $dsParam->setValue($param['value']);
                }
            }
        }

        if ((! isset($collect)) || (! is_a($collect, DataSourceCollect::class))) {
            $collect = $this->retrieveCollect($uid);
        }

        if (! is_null($isEnabled)) {
            $collect->setIsEnabled(!!$isEnabled);
        }

        /** @var ObjectManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($collect);
        $em->flush();

        $response->add($this->retrieveCollect($uid));

        $mapper = $this->get('data.source.api.v1.mapper');
        $response = $mapper->getCollectsResponse($response->toArray(), [
            'collects' => [
                'isEnabled',
                'lastUpdate',
                'uid',
                'dataSource' => ['name',],
                'parameters' => ['name', 'value',],
            ],
        ]);

        return $this->createApiResponse($response['collects']);
    }

    /**
     * @param string $uid
     * @return DataSourceCollect
     */
    protected function retrieveCollect(string $uid): DataSourceCollect
    {
        /** @var ObjectManager $em */
        $em = $this->getDoctrine()->getManager();

        $collect = $em->getRepository(DataSourceCollect::class)
            ->findOneBy([
                'account' => $this->getUser(),
                'uid' => $uid
            ]);

        if (empty($collect)) {
            $this->throwApiProblemResponse(
                Exceptions::RESOURCE_NOT_FOUND,
                ["Collect uid '" . $uid . "'' not found."]
            );
        }

        return $collect;
    }

    /**
     * @Route("/{uid}", name="apiDeleteCollectAction")
     * @Method({"DELETE"})
     * @param Request $request
     * @return mixed
     * @SWG\Delete(
     *     summary="To delete an account.",
     *     @SWG\Parameter(
     *          required=true,
     *          name="uid",
     *          in="path",
     *          type="string",
     *          description="A collect uid to be deleted."
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="The collect was removed.",
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="The collect wasn't found.",
     *     )
     * )
     */
    public function deleteCollectAction($uid)
    {
        $collect = $this->retrieveCollect($uid);

        /** @var ObjectManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($collect);
        $em->flush();

        return $this->createApiResponse(
            ['The collect was removed.'],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    protected function getParametersAsString(array $parameters)
    {
        return array_reduce(array_keys($parameters), function ($carry, $item) use ($parameters) {
            return $carry . ';' . $item . '=' . $parameters[$item];
        });
    }
}
