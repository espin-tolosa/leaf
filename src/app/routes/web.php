<?php declare(strict_types=1);

use Set\Framework\App\Routes\Template;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route as RoutingRoute;

/**
 * Create Routes for the web including resources like js, css, svg files
 */

$routes = new RouteCollection();

/**
 * Index Controller
 */

$routes->add('user_panel', new RoutingRoute('/user/{name}', [
	'name' => 'invited',

	'_controller' => function ($request) {
		$name = $request->attributes->get('name');
		$template = new Template($request);
		$template->render(['name' => $name]);
	}
]));

/**
 * SPA Controller
 */

$routes->add('spa', new RoutingRoute('/spa', [
	'_controller' => function ($request) {
		$template = new Template($request);
		$template->render();
	}
]));

/**
 * Routes for Page Resources
 */

$routes->add('is_even', new RoutingRoute('is-even/{number}', [
	'number' => null,

	'_controller' => function($request) {
		$number = $request->attributes->get('number');
		$isEven = $number % 2 === 0 ? "YES" : "NO";
		
		$template = new Template($request);
		$template->render(['number' => $number, 'isEven' => $isEven ]);
	}
]) );

/**
 * NOT FOUND
 */

 $routes->add('not_found', new RoutingRoute('/not-found', [
	'_controller' => function ($request) {
		$template = new Template($request);
		$template->render(['failedRoute' => $request->getPathInfo()]);
	}
]));

/**
 * SERVER ERROR
 */

 $routes->add('server_error', new RoutingRoute('/server-error', [
	'_controller' => function ($request) {
		$template = new Template($request);
		$template->render(['failedRoute' => $request->getPathInfo()]);
	}
]));

/**
 * Resources of the web: svg, css, js
 */

$routes->add('public', new RoutingRoute('/public/{file}', [
	'file' => '',

	'_controller' => function ($request) {
		$file = $request->attributes->get('file');	
		$token = $request->cookies->has('jwt') ? $request->cookies->get('jwt') : null;
		
		/**
		 * Middleware Authorization
		 * 
		 * this has entity has to be created and layered before request match and the second check not being hard-coded
		 * then I could use clause guards properly instead of need to nest the response
		 */
		
		if($token === null || $token !== "irebljpnnpiv0ceaoa62psa01c") {
			echo 'Unauthorized';
			return;
		}
		
		/**
		 * Resource request classification coupled to File Serving
		 */

		$properties = explode('.', $file);
		$type = $properties[count($properties)-1];
		ob_start();
		
		switch ($type) {
			case 'svg':
				header('Content-Type: image/svg+xml');
				readfile (realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
				break;

			case 'js':
				header('Content-Type: application/javascript, max-age=604800, public');
				readfile (realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
				break;

			case 'css':
				header('Content-Type: text/css, max-age=604800, public');
				readfile (realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
				break;
			
			default:
				echo 'Not found';
				$request->attributes->add(['status' => 404]);
				break;
		}	


	}
]));