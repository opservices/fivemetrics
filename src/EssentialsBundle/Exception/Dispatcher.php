<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/01/17
 * Time: 10:09
 */

namespace EssentialsBundle\Exception;

use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class Dispatcher
 * @package EssentialsBundle\Exception
 */
class Dispatcher
{
    /**
     * @var LoggerInterface
     */
    protected $logger = null;

    public function __construct(LoggerInterface $logger = null)
    {
        (is_null($logger)) ?: $this->setLogger($logger);
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return Dispatcher
     */
    public function setLogger(LoggerInterface $logger): Dispatcher
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param $error
     * @param null $data
     * @return bool
     */
    public function send(
        $error,
        $data = null,
        $errorLevel = Logger::ERROR
    ): bool {
        $obj = new \stdClass();
        $obj->data = $data;

        if ($error instanceof \Throwable) {
            $obj->exception = get_class($error);
            $obj->message = $error->getMessage();
            $obj->trace = $error->getTrace();
        } else {
            $obj->message = $error;
        }

        $log = json_encode($obj);

        (is_null($this->logger))
            ? print $log . PHP_EOL
            : $this->logger->log($errorLevel, $log);

        return true;
    }
}
