<?php

namespace Leaf\Http\Response;

use Symfony\Component\Routing\Exception\ExceptionInterface;

/**
 * The request doesn't have accesss permissions
 *
 * This exception should trigger an HTTP 401 response in your application code.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 */
class UnauthorizedAccessException extends \RuntimeException implements ExceptionInterface
{
}
