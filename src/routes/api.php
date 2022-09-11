<?php declare(strict_types=1);

namespace Set\Routes;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Route as RoutingRoute;

class ApiRoutes {

	public static function add($routes) {

	/**
	 * Index Controller
	 */

	$routes->add('event', new RoutingRoute('/event/{id}', [
		'name' => 'invited',

		'_controller' => function ($request) {
			$id = $request->attributes->get('id');
			$data = ['id' => $id];
			return new JsonResponse($data, 200, ["Content-Type" => "application/json"]);
			//return new Response($content->json_encode, 200);
		}
	]));

	//return $routes;
	}
}