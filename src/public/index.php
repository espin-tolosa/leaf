<?php declare(strict_types=1);

use App\Leaf\Framework;
use Set\Framework\App\plugins\AuthorizationListener;
use Set\Framework\App\plugins\ContentLengthListener;
use Set\Framework\App\plugins\ContentTypeListener;
use Set\Framework\App\plugins\GoogleListener;
use Set\Framework\App\plugins\KernelListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
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

$framework = new Framework($matcher, $controllerResolver /*, $argumentResolver*/, $dispatcher);
$response = $framework->handle($request);
$response->send();