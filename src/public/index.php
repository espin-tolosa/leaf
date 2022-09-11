<?php declare(strict_types=1);

use Set\Routes\WebRoutes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * Read Routes
 */
$routes = WebRoutes::add(new RouteCollection());

$request = Request::createFromGlobals();

/**
 * Bootstrap: DI Container
 */
require_once realpath(__DIR__ . '/bootstrap.php');

/**
 * Launch Kernel Req/Res
 */

$response = $containerBuilder->get('kernel')->handle($request);

$response->send();