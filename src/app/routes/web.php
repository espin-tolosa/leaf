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
	'file' => '',
	'__controller' => function ($request) {
		$template = new Template($request);
		$template->render();
	}
]));

/**
 * Routes for Page Resources
 */

$routes->add('is-even', new RoutingRoute('is_even/{number}', [
	'number' => null,
	'__controller' => function($request) {
		$number = $request->attributes->get('number');
		$isEven = $number % 2 == 0 ? "YES" : "NO";
		
		$template = new Template($request);
		$template->render(['number' => $number, 'isEven' => $isEven ]);
		$template->render(['number' => $number, 'isEven' => $isEven ]);
		$template->render(['number' => $number, 'isEven' => $isEven ]);
	}
]) );

/**
 * NOT FOUND
 */

 $routes->add('not-found', new RoutingRoute('/notfound', [
	'file' => '',
	'__controller' => function ($request) {
		$template = new Template($request);
		$template->render(['failedRoute' => $request->getPathInfo()]);
	}
]));