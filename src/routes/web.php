<?php declare(strict_types=1);

namespace Set\Routes;

use Leaf\Http\Events\ContentTypeEvent;
use Set\Resources\ResourceRenderer;
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
			$attributes = explode('.', $file);

			/**
			 * Emmit Event which Type defined at run-time
			 */

			$type = ContentTypeEvent::PREFIX . $attributes[array_key_last($attributes)];
			
			/**
			 * Generate the Response
			 */

			$response = new Response();
			$resource = new ResourceRenderer($request); //$resource->media() doesn't need the request infact

			/**
			 * Call Dispatcher to set the content type of the file
			 */

			$request->attributes->get('dispatcher')->dispatch(new ContentTypeEvent($response, $type), $type);

    	if(!$response->headers->has('Content-Type'))
			{
				return new Response('Bad Request: not valid type of resource <strong>' . $file . '</strong>', 400, ['Content-Type' => 'text/html']);
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