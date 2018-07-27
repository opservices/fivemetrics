<?php

namespace EssentialsBundle\Controller\Api\V1;

use EssentialsBundle\Collection\Account\AccountConfigurationCollection;
use Swagger\Annotations as SWG;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Entity\Account\AccountConfiguration;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Exception\Exceptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AccountConfigurationController
 * @package EssentialsBundle\Controller\Api\V1
 * @Route("/account/configuration")
 */
class AccountConfigurationController extends ControllerAbstract
{
    /**
     * @Route("/", name="configuration_list")
     * @Method("GET")
     * @SWG\Get(
     *     summary="List the stored account configurations.",
     *     @SWG\Response(
     *          response=200,
     *          description="It will return a list with the account configurations.",
     *          @SWG\Schema(
     *              @SWG\Property(property="id", type="integer"),
     *              @SWG\Property(property="name", type="string"),
     *              @SWG\Property(property="value", type="string")
     *          ),
     *     )
     * )
     */
    public function getConfigurationsAction()
    {
        $em = $this->getDoctrine()
            ->getManager()
            ->getRepository(AccountConfiguration::class);

        $configurations = $em->findBy(['account' => $this->getUser()]);

        $response = array_map(function (AccountConfiguration $configuration) {
            return $configuration->toArray();
        }, $configurations);

        return $this->createApiResponse($response);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="configuration_register")
     * @Method("POST")
     * @SWG\Post(
     *     summary="To create a bulk of configurations.",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="configuration",
     *          type="string",
     *          description="A JSON string representing the account configurations.
     <br>The configuration name must have only numbers, characters, ""-"" or ""_"".
     <br><br>Example:
     <code>[{""name"":""configuration_name"",""value"":""configuration_value""}]</code><br>",
     *          @SWG\Schema(
     *              @SWG\Items(
     *                  @SWG\Property(property="name", type="string"),
     *                  @SWG\Property(property="value", type="string")
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It create a bulk of configurations.",
     *          @SWG\Schema(
     *              @SWG\Property(property="name", type="string"),
     *              @SWG\Property(property="value", type="string")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="If some configuration already exists.",
     *          @SWG\Schema(
     *              @SWG\Property(property="type", type="string"),
     *              @SWG\Property(property="title", type="string"),
     *              @SWG\Property(property="status", type="integer"),
     *              @SWG\Property(property="detail", type="string"),
     *          ),
     *     )
     * )
     */
    public function postConfigurationAction(Request $request)
    {
        $parameters = json_decode($request->getContent(), true);
        (is_array($parameters)) ?: $parameters = [];

        $em = $this->getDoctrine()->getManager();

        $builder = EntityBuilderProvider::factory(AccountConfiguration::class);
        $response = [];

        foreach ($parameters as $data) {
            /** @var AccountConfiguration $configuration */
            $configuration = $builder->factory($data, ['Default']);
            $configuration->setAccount($this->getUser());
            $em->persist($configuration);

            $response[] = $configuration->toArray();
        }

        try {
            $em->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new \InvalidArgumentException(
                "It looks like you are trying add duplicated configurations.",
                Exceptions::CONFLICT
            );
        }

        $response = $this->get('array.mapper')
            ->fieldSelector($response, [ [ "name", "value" ] ]);

        return $this->createApiResponse(
            $response,
            Response::HTTP_CREATED
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{name}", name="configuration_delete", requirements={"name": ".+"})
     * @Method("DELETE")
     * @SWG\Delete(
     *     summary="To delete a configuration.",
     *     @SWG\Parameter(
     *          required=true,
     *          name="name",
     *          in="path",
     *          type="string",
     *          description="It's the configuration name."
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="The configuration was removed.",
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="The configuration wasn't found.",
     *     )
     * )
     */
    public function deleteConfigurationAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(AccountConfiguration::class);

        $conf = $repository->findOneBy([
            'account' => $this->getUser(),
            'name' => $name
        ]);

        if (empty($conf)) {
            throw new \InvalidArgumentException(
                'Account configuration not found.',
                Exceptions::RESOURCE_NOT_FOUND
            );
        }

        $em->remove($conf);
        $em->flush();

        return $this->createApiResponse(
            '',
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="configuration_bulk_update")
     * @Method("PUT")
     * @SWG\Put(
     *     summary="Its goal is to update huge amount of account configurations.",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="configuration",
     *          type="string",
     *          description="A JSON string representing the account configurations array.
    <br>The configuration name must have only numbers, characters, ""."", ""-"" or ""_"".
    <br><br>Example:
    <code>[{""name"":""configuration_name"",""value"":""configuration_value""}]</code><br>",
     *          @SWG\Schema(
     *              @SWG\Property(property="name", type="string"),
     *              @SWG\Property(property="value", type="string")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="The configuration was updated.",
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="The configuration wasn't found.",
     *     )
     * )
     */
    public function putConfigurationBulkAction(Request $request)
    {
        $confs = json_decode($request->getContent(), true);
        (is_array($confs)) ?: $confs = [];

        $em = $this->getDoctrine()->getManager();
        $response = new AccountConfigurationCollection();

        foreach ($confs as $conf) {
            /** @var AccountConfiguration $newConf */
            $newConf = EntityBuilderProvider::factory(AccountConfiguration::class)
                ->factory($conf, ['Default']);

            $repository = $em->getRepository(AccountConfiguration::class);

            $conf = $repository->findOneBy([
                'account' => $this->getUser(),
                'name' => $newConf->getName()
            ]);

            if (empty($conf)) {
                throw new \InvalidArgumentException(
                    'Account configuration not found.',
                    Exceptions::RESOURCE_NOT_FOUND
                );
            }

            /** @var AccountConfiguration $conf */
            $conf->setName($newConf->getName())
                ->setValue($newConf->getValue());

            $response->add(clone $conf);
        }

        $em->flush();

        $mapper = $this->get('array.mapper');
        $response = $mapper->fieldSelector(
            $response->toArray(),
            [
                [
                    'name',
                    'value',
                ],
            ]
        );

        return $this->createApiResponse(
            $response,
            Response::HTTP_OK
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{name}", name="configuration_update", requirements={"name": "^([a-zA-Z0-9\-\_])+$"})
     * @Method("PUT")
     * @SWG\Put(
     *     summary="To update a configuration.",
     *     @SWG\Parameter(
     *          required=true,
     *          name="name",
     *          in="path",
     *          type="string",
     *          description="It's the configuration name.
     <br>The configuration name must have only numbers, characters, ""."", ""-"" or ""_""."
     *     ),
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="configuration",
     *          type="string",
     *          description="A JSON string representing the account configurations.
    <br>The configuration name must have only numbers, characters, ""."", ""-"" or ""_"".
    <br><br>Example:
    <code>{""name"":""configuration_name"",""value"":""configuration_value""}</code><br>",
     *          @SWG\Schema(
     *              @SWG\Property(property="name", type="string"),
     *              @SWG\Property(property="value", type="string")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="The configuration was updated.",
     *     ),
     *     @SWG\Response(
     *          response=404,
     *          description="The configuration wasn't found.",
     *     )
     * )
     */
    public function putConfigurationAction(Request $request, $name)
    {
        $data = json_decode($request->getContent(), true);
        (is_array($data)) ?: $data = [];

        /** @var AccountConfiguration $newConf */
        $newConf = EntityBuilderProvider::factory(AccountConfiguration::class)
            ->factory($data, ['Default']);

        $validName = preg_match("/^([a-zA-Z0-9\-\_\.])+$/", $newConf->getName());
        if (! $validName) {
            throw new \InvalidArgumentException(
                'The configuration name must have only numbers, characters, ".", "-" or "_".',
                Exceptions::VALIDATION_ERROR
            );
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(AccountConfiguration::class);

        $conf = $repository->findOneBy([
            'account' => $this->getUser(),
            'name' => $name
        ]);

        if (empty($conf)) {
            throw new \InvalidArgumentException(
                'Account configuration not found.',
                Exceptions::RESOURCE_NOT_FOUND
            );
        }

        /** @var AccountConfiguration $conf */
        $conf->setName($newConf->getName())
            ->setValue($newConf->getValue());

        $em->flush();

        return $this->createApiResponse(
            $conf->toArray(),
            Response::HTTP_OK
        );
    }
}
