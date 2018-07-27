<?php

namespace FrontendBundle\Tests\Controller\Api\V1;

use Aws\Command;
use Aws\Exception\AwsException;
use DataSourceBundle\Aws\EC2\EC2;
use EssentialsBundle\Api\Test\ApiTestCase;
use FrontendBundle\Controller\Api\V1\OnboardingController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OnboardingControllerTest
 * @package FrontendBundle\Controller
 */
class OnboardingControllerTest extends ApiTestCase
{
    protected $controller;
    protected $mockedService;
    protected $request;

    protected function setUp()
    {
        $this->controller = new OnboardingController;
        $this->mockedService = $this->createMock(EC2::class);
        $this->request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            '{"aws.key": "my_aws_key", "aws.secret": "my_aws_secret"}'
        );
    }

    /**
     * @param int $code
     *
     * @testdox Should throw an Exception when given credentials have some limitation
     * @dataProvider codes
     */
    public function checkLimitedCredentials(int $code)
    {
        $this->mockedService->method('checkCredential')
            ->willThrowException($this->createException($code));

        try {
            $this->controller->checkCredentialsAction($this->request, ['EC2' => $this->mockedService]);
        } catch (\Exception $e) {
            $errors = $e->getApiProblem()->toArray();
            $this->assertArrayHasKey("EC2", $errors['errors']);
            $this->assertEquals($errors['errors']['EC2'], 'unit-test');
        }
    }


    public function codes()
    {
        return [
            [Response::HTTP_FORBIDDEN],
            [Response::HTTP_UNAUTHORIZED],
        ];
    }

    /**
     * @param int $code
     * @return AwsException
     */
    protected function createException(int $code): AwsException
    {
        return new AwsException('', new Command(''), [
            'response' => new Response('Unit-Test Response', $code),
            'message' => 'unit-test',
        ]);
    }

    /**
     * @testdox Should return a "Credential is OK" message for a no exception flow
     *
     */
    public function checkCredentialsOK()
    {
        $this->mockedService->method('checkCredential')
            ->willReturn(true);

        /** @var Response $response */
        $response = $this->controller->checkCredentialsAction(
            $this->request,
            [$this->mockedService]
        );

        $this->assertEquals($response->getContent(), '"Credential is OK"');
    }
}
