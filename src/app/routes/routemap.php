<?php declare(strict_types=1);

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route as RoutingRoute;

/**
 * Pages resolver
 */

function build_resource_path(string $file) {
	return realpath(__DIR__ . '/../pages/' . $file . '.php');
}

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
			render_template($request);
	}
]));

/**
 * SPA Controller
 */

$routes->add('spa', new RoutingRoute('/spa', [
	'file' => '',
	'__controller' => function ($request) {
			render_template($request);
	}
]));

/**
 * Routes for Page Resources
 */

$routes->add('public', new RoutingRoute('/public/{file}', [
	'file' => '',
	'__controller' => function ($request) {
			render_template($request);
	}
]));
