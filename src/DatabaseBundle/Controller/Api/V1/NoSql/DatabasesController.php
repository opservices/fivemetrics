<?php

namespace DatabaseBundle\Controller\Api\V1\NoSql;

use EssentialsBundle\Api\ApiProblem;
use EssentialsBundle\Api\ApiProblemException;
use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Exception\Exceptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DatabasesController
 * @package DatabaseBundle\Controller\Api\V1\NoSql
 */
class DatabasesController extends ControllerAbstract
{
    /*
     * @return JsonResponse
     * @Route("/", name="apiShowDatabase")
     * @Method({"GET"})
     */
    /*
    public function showDatabaseAction()
    {
        $database   = $this->getUser()->getUid();
        $connection = $this->get('nosql.database.connection.provider')
            ->getConnection($database);

        $metrics = $connection->getClient()
            ->query(
                null,
                'SHOW MEASUREMENTS ON ' . $database
            )->getPoints();

        $data = [
            'database' => $database,
            'content'  => [
                'series' => $metrics
            ]
        ];

        return $this->createApiResponse($data);
    }
*/
    /*
     * @param Request $request
     * @return JsonResponse
     * @Route("/", name="apiCreateDatabase")
     * @Method({"POST"})
     */
    /*
    public function createDatabaseAction(Request $request)
    {
        $account = json_decode($request->getContent(), true);

        try {
            $account = new Account($account['id']);
        } catch (\Exception $e) {
            $this->throwApiProblemResponse(
                $e->getCode(),
                [ $e->getMessage() ]
            );
        }

        $database = $this->get('nosql.database.connection.provider')
            ->getConnection();

        $database = $database->getClient()->selectDB($account->getUid());
        $database->create();

        if (! $database->exists()) {
            throw new ApiProblemException(
                new ApiProblem(Exceptions::RUNTIME_ERROR)
            );
        }

        $headers = [
            'Location' => $this->generateUrl(
                'apiShowDatabase',
                [ 'databaseId' => $account->getUid() ]
            )
        ];

        return $this->createApiResponse(
            [ 'databaseId' => $account->getUid() ],
            Response::HTTP_CREATED,
            $headers
        );
    }
    */
}
