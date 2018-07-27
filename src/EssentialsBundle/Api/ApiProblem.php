<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/05/17
 * Time: 10:27
 */

namespace EssentialsBundle\Api;

use EssentialsBundle\Exception\Exceptions;
use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 * @link https://tools.ietf.org/html/rfc7807 Problem Details for HTTP APIs
 */
class ApiProblem
{
    /**
     * @var array
     */
    protected static $data = [
        Exceptions::GENERIC_ERROR => [
            'type'     => 'bad_request',
            'title'    => 'Bad request',
        ],
        Exceptions::VALIDATION_ERROR => [
            'type'     => 'validation_error',
            'title'    => 'There was a validation error',
        ],
        Exceptions::RUNTIME_ERROR => [
            'type'     => null,
        ],
        Exceptions::RESOURCE_NOT_FOUND => [
            'type'     => null,
        ],
        Exceptions::ACCESS_DENIED => [
            'type'     => 'access_denied',
            'title'    => 'Access denied',
        ],
        Exceptions::CONFLICT => [
            'type'     => 'conflict',
            'title'    => 'The request generated a conflict',
        ],
    ];

    /**
     * @var array
     */
    protected $extraData = [];

    /**
     * @var int
     */
    protected $exceptionCode;

    /**
     * @var string
     */
    protected $title;

    /**
     * ApiProblem constructor.
     * @param int $exceptionCode
     * @param array $extraData
     */
    public function __construct(
        int $exceptionCode = Exceptions::GENERIC_ERROR,
        array $extraData = []
    ) {
        if ($exceptionCode == 0) {
            $exceptionCode = Exceptions::RUNTIME_ERROR;
        }

        if (! isset(self::$data[$exceptionCode])) {
            throw new \InvalidArgumentException(
                "Unknown the problem error code: " . $exceptionCode
            );
        }

        if (isset($extraData['title'])) {
            $this->title = $extraData['title'];
        } elseif (isset(self::$data[$exceptionCode]['title'])) {
            $this->title = self::$data[$exceptionCode]['title'];
        } else {
            $this->title = Response::$statusTexts[$exceptionCode];
        }

        $this->exceptionCode = $exceptionCode;
        $this->extraData = $extraData;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $type = self::$data[$this->exceptionCode]['type'];

        /*
         * When "about:blank" is used, the title SHOULD be the same as the
         * recommended HTTP status phrase for that code (e.g., "Not Found" for
         * 404, and so on), although it MAY be localized to suit client
         * preferences (expressed with the Accept-Language request header).
         */
        if ((empty($type)) || ($type == 'about:blank')) {
            $type  = 'about:blank';
            $this->title = 'Not Found';
        }

        return array_merge(
            [
                'type'   => $type,
                'title'  => $this->getTitle(),
                'status' => $this->getStatusCode(),
            ],
            $this->extraData
        );
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     *
     */
    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->exceptionCode;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::$data[$this->exceptionCode]['type'];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
