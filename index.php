<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;

require_once realpath(__DIR__ . '/vendor/autoload.php');

$request = Request::createFromGlobals();

/**
 * Bootstrap: DI Container
 */

require_once realpath(__DIR__ . '/src/public/bootstrap.php');

/**
 * Launch Kernel Req/Res
 */

$response = $containerBuilder->get('kernel')->handle($request);

$response->send();
