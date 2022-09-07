<?php declare(strict_types=1);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;


/**
 * Import Routes
 */

require_once realpath(__DIR__ . '/../app/routes/web.php');
require_once realpath(__DIR__ . '/../app/routes/resources.php');

/**
 * Read Request
 */
 
$request = Request::createFromGlobals();
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

/**
 * Template rendering
 */
	
try {
	$response = new Response();
	$request->attributes->add($matcher->match($request->getPathInfo()));
	call_user_func($request->attributes->get('__controller'), $request);
	$response->setContent(ob_get_clean());
	$response->setStatusCode(200);
}

catch (Routing\Exception\ResourceNotFoundException $exception) {
	$response = new Response('Not Found', 404);
}

catch (Exception $exception) {
	$response = new Response('Server Error', 500);
}

$response->send();
