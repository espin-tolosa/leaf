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

$routes->add('index', new RoutingRoute('/index/{name}', [
	'name' => 'World',
	'__controller' => function ($request) {
		$name = $request->attributes->get('number');
		$template = new Template($request);
		$template->render(['name' => $name]);
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
$routes->add('is_even', new RoutingRoute('is_even/{number}', [
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
