<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/05/17
 * Time: 10:48
 */

namespace EssentialsBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * This class defines HTTP code aliases to be used inside the system.
 * Important:
 *     All exception codes must be a Response code to avoid API problems.
 * @package EssentialsBundle\Exception
 */
class Exceptions
{
    const GENERIC_ERROR      = Response::HTTP_BAD_REQUEST;

    const VALIDATION_ERROR   = Response::HTTP_BAD_REQUEST;

    const RUNTIME_ERROR      = Response::HTTP_INTERNAL_SERVER_ERROR;

    const RESOURCE_NOT_FOUND = Response::HTTP_NOT_FOUND;

    const ACCESS_DENIED      = Response::HTTP_FORBIDDEN;

    const CONFLICT           = Response::HTTP_CONFLICT;
}
