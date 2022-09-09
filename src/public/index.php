<?php declare(strict_types=1);

use App\Leaf\Framework;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use WebRoutes;

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

/**
 * Read Routes
 */
$routes = WebRoutes::add(new RouteCollection());

$request = Request::createFromGlobals();

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
//$argumentResolver = new ArgumentResolver();

$framework = new Framework($matcher, $controllerResolver /*, $argumentResolver*/);
$response = $framework->handle($request);
$response->send();