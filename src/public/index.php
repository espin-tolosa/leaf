<?php declare(strict_types=1);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;


/**
 * Create Routes
 */

require_once realpath(__DIR__ . '/../app/routes/routemap.php');

/**
 * Read Request
 */
 
$request = Request::createFromGlobals();
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

/**
 * Match Request to send a proper Response
 */


try {
	extract($matcher->match($request->getPathInfo()), EXTR_SKIP);	
	ob_start();
	$response = new Response();
	include build_resource_path($_route) ;
	$response->setContent(ob_get_clean());
	$response->setStatusCode(200);
}

catch (Routing\Exception\ResourceNotFoundException $exception) {
	ob_start();
	$response = new Response();
	require build_resource_path('notfound') ;
	$response->setContent(ob_get_clean());
	$response->setStatusCode(404);
}

catch (Exception $exception) {
	$response = new Response('An error occurred', 500);	
}

$response->send();
