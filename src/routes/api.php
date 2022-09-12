<?php declare(strict_types=1);

namespace Set\Routes;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Route as RoutingRoute;
use Symfony\Component\Routing\RouteCollection;

class ApiRoutes extends RouteCollection {

	public function __construct() {

	/**
	 * Index Controller
	 */

	$this->add('event', new RoutingRoute('/event/{id}', [
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