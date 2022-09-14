<?php declare(strict_types=1);

namespace Set\Routes;

use Symfony\Component\Routing\RouteCollection;

class Routes extends RouteCollection {

	public function __construct(WebRoutes $web, ApiRoutes $api, ErrorRoutes $error)
	{
		$this->addCollection($web);
		$this->addCollection($api);
		$this->addCollection($error);
	}
}