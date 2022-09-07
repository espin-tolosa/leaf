<?php declare(strict_types=1);

use Set\Framework\App\Routes\Template;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route as RoutingRoute;

/**
 * Create Routes
 */

$routes = new RouteCollection();

/**
 * Index Controller
 */

$routes->add('user-panel', new RoutingRoute('/user/{name}', [
	'name' => 'invited',

	'__controller' => function ($request) {
		$name = $request->attributes->get('name');
		$template = new Template($request);
		$template->render(['name' => $name]);
	}
]));

/**
 * SPA Controller
 */

$routes->add('spa', new RoutingRoute('/spa', [
	'__controller' => function ($request) {
		$template = new Template($request);
		$template->render();
	}
]));

/**
 * Routes for Page Resources
 */

$routes->add('is_even', new RoutingRoute('is-even/{number}', [
	'number' => null,

	'__controller' => function($request) {
		$number = $request->attributes->get('number');
		$isEven = $number % 2 == 0 ? "YES" : "NO";
		
		$template = new Template($request);
		$template->render(['number' => $number, 'isEven' => $isEven ]);
	}
]) );

/**
 * NOT FOUND
 */

 $routes->add('not_found', new RoutingRoute('/not-found', [
	'__controller' => function ($request) {
		$template = new Template($request);
		$template->render(['failedRoute' => $request->getPathInfo()]);
	}
]));

/**
 * SERVER ERROR
 */

 $routes->add('server_error', new RoutingRoute('/server-error', [
	'__controller' => function ($request) {
		$template = new Template($request);
		$template->render(['failedRoute' => $request->getPathInfo()]);
	}
]));