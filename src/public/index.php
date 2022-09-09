<?php declare(strict_types=1);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use Set\Framework\App\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing;


/**
 * Import Routes
 */

require_once realpath(__DIR__ . '/../app/routes/web.php');

/**
 * Read Request
 */
 
$request = Request::createFromGlobals();
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

/**
 * Template rendering
 *
 * TODO: Move (2.2) Response Creation to (2.1.3) Controller, which is capable to set headers, so no needs to pass status through request object
 *
 * 1.			Match Request
 ! 2.    	Call Send View Wrapper (just sugar DRY for try/catch)
 ! 2.1.			Call Controller (busines logic and rewrite the status, only here)
 * 2.1.1.			Get Request Parameters, Busines Logic, Rewrite Status Code if needed and Call Renderer with Proccessed parameters
 ! 2.1.2.			Call View Template OB Renderer
 * 2.2.   	Create and Return a Response reading some new status that could be possibly generated in Controller
 * 2.3.   	Send Response
 */
	
try {
	$request->attributes->add($matcher->match($request->getPathInfo()));

	$controllerResolver = new ControllerResolver();
	$controller = $controllerResolver->getController($request);
	$response = call_user_func($controller, $request);
}

catch (Routing\Exception\ResourceNotFoundException $exception) {
	$request->attributes->add($matcher->match('/not-found'));
	$request->attributes->add(['exception' => $exception->getMessage()]);

	$controllerResolver = new ControllerResolver();
	$controller = $controllerResolver->getController($request);
	$response = call_user_func($controller, $request);
}

catch (Exception $exception) {
	$request->attributes->add($matcher->match('/server-error'));
	$request->attributes->add(['exception' => $exception->getMessage()]);

	$controllerResolver = new ControllerResolver();
	$controller = $controllerResolver->getController($request);
	$response = call_user_func($controller, $request);
}

$response->send();
