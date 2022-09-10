<?php declare(strict_types=1);

use Leaf\Http\Response\Kernel;
use Set\Routes\WebRoutes;
use Leaf\Plugins\AuthorizationListener;
use Leaf\Plugins\ContentLengthListener;
use Leaf\Plugins\ContentTypeListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

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

/**
 * Register a Dispatcher and two Listeners as example
 */

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new AuthorizationListener());
$dispatcher->addSubscriber(new ContentTypeListener());
$dispatcher->addSubscriber(new ContentLengthListener());

/**
 * Call framework Request -> Response processor
 */

$kernel = new Kernel($matcher, $controllerResolver /*, $argumentResolver*/, $dispatcher);
$response = $kernel->handle($request);
$response->send();