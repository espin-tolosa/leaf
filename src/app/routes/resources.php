<?php declare(strict_types=1);

use Symfony\Component\Routing\Route as RoutingRoute;

//! This file is my own implementation of the Big ball of mud pattern

/**
 * Add more routes
 * This abstraction is really bad, because I'm chaining the routes object between type of routes as follows:
 * 1. web
 * 2. resources
 * 
 * And finally I'm importing it to the consumer which requires to see all routes in the same object to avoid branching the code
 * I need a better abstraction to this created routes-> pass to web->pass to resources->consumed at index
 */

require_once realpath(__DIR__ . '/web.php');

/**
 * Route for either public or restricted to some users
 * It handle authorization and type content header
 * !but it has to be splitted and decoupled
 */

$routes->add('public', new RoutingRoute('/public/{file}', [
	'file' => '',
	'__controller' => function ($request) {
		global $response; //!This anoying line is needed to allow the included file have access to the response which is declared in the global scope
		extract($request->attributes->all(), EXTR_SKIP);	
		ob_start();
/**
 * Middleware Authorization
 * 
 * this has entity has to be created and layered before request match and the second check not being hard-coded
 * then I could use clause guards properly instead of need to nest the response
 */

 if($request->cookies->has('jwt') && $request->cookies->get('jwt') == "irebljpnnpiv0ceaoa62psa01c") {

	/**
	 * Resource request classification coupled to File Serving
	 */

	if($file == "favicon.svg") {
		$response->headers->set('Content-Type', 'image/svg+xml');
		readfile ( realpath(__DIR__ . '/../../public/' . $file));
	}

	else {
		$properties = explode('.', $file);
		$type = $properties[count($properties)-1];

			if( isset($type) &&  $type == "js") {
				$response->headers->set('Content-Type', 'application/javascript');
				$response->headers->set('Cache-Control','max-age=604800, public');
				readfile ( realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
			}
			else if( isset($type) &&  $type == "css") {
				$response->headers->set('Content-Type', 'text/css');
				$response->headers->set('Cache-Control','max-age=604800, public');
				readfile ( realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
		}
	}
	
}
	}
]));

