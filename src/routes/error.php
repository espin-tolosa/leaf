<?php declare(strict_types=1);

namespace Set\Routes;

use Set\Resources\ResourceRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route as RoutingRoute;
use Symfony\Component\Routing\RouteCollection;

class ErrorRoutes extends RouteCollection {

	public function __construct() {

	/**
	 * NOT FOUND
	 */

	 $this->add('not_found', new RoutingRoute('/not-found', [
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

	 $this->add('server_error', new RoutingRoute('/server-error', [
		'_controller' => function ($request) {
			$resource = new ResourceRenderer($request);
			$exception = $request->attributes->get('exception');
			$resource->template(['failedRoute' => $request->getPathInfo(), 'exception' => $exception]);
			return new Response(ob_get_clean(), 500);
		}
	]));
	}
}