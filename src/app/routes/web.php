<?php declare(strict_types=1);

use Set\Framework\resources\ResourceRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route as RoutingRoute;

class WebRoutes {

	public static function add($routes) {

	/**
	 * Index Controller
	 */

	$routes->add('user_panel', new RoutingRoute('/user/{name}', [
		'name' => 'invited',

		'_controller' => function ($request) {
			$name = $request->attributes->get('name');
			$resource = new ResourceRenderer($request);
			$status = $resource->template(['name' => $name]);
			return new Response(ob_get_clean(), $status ? $status : 200);
		}
	]));

	/**
	 * SPA Controller
	 */

	$routes->add('spa', new RoutingRoute('/spa', [
		'_controller' => function ($request) {
			$resource = new ResourceRenderer($request);
			$status = $resource->template();
			return new Response(ob_get_clean(), $status ? $status : 200);
		}
	]));

	/**
	 * NOT FOUND
	 */

	 $routes->add('not_found', new RoutingRoute('/not-found', [
		'_controller' => function ($request) {
			$resource = new ResourceRenderer($request);
			$exception = $request->attributes->get('exception');
			$resource->template(['failedRoute' => $request->getPathInfo(), 'exception' => $exception]);
			return new Response(ob_get_clean(), 404);
		}
	]));

	/**
	 * SERVER ERROR
	 */

	 $routes->add('server_error', new RoutingRoute('/server-error', [
		'_controller' => function ($request) {
			$resource = new ResourceRenderer($request);
			$exception = $request->attributes->get('exception');
			$resource->template(['failedRoute' => $request->getPathInfo(), 'exception' => $exception]);
			return new Response(ob_get_clean(), 500);
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

			if($token === null || $token !== "irebljpnnpiv0ceaoa62psa01") {
				$response = new Response('Unauthorized', 401);
				$response->headers->set('Content-Type', 'text/plain');
				return $response;
			}

			/**
			 * Resource request classification coupled to File Serving
			 */

			$properties = explode('.', $file);
			$type = $properties[count($properties)-1];
			$resource = new ResourceRenderer($request); //$resource->media() doesn't need the request infact

			$response = new Response();
			switch ($type) {
				case 'svg':
					$response->headers->set('Content-Type', 'image/svg+xml');
					break;

				case 'js':
					$response->headers->set('Content-Type', 'text/javascript, max-age=604800, public, UTF-8');
					break;

				case 'css':
					$response->headers->set('Content-Type', 'text/css, max-age=604800, public');
					break;

				default:
					$response->headers->set('Content-Type', 'text/plain; charset=UTF-8');
					$response = new Response('Bad Request: resource <strong> ' . $file . '</strong> of type <strong>' .  $type . '</strong> is not valid', 400);
					return $response;
			}

			$status = $resource->media($file);
			$response->setContent(ob_get_clean());
			$response->setStatusCode($status ? $status : 200);
			return $response;
		}
	]));

	return $routes;
	}
}