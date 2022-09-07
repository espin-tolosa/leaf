<?php declare(strict_types=1);

namespace Set\Framework\App\Routes;

use Symfony\Component\HttpFoundation\Request;

/**
 * Render the template
 * 
 * create an object buffer and send all the html
 */

class Template {

	private string $route;

	public function __construct(Request $request)
	{
		$this->route = $request->attributes->get('_route');
	}

	private function openBuffer() {
		$ob_is_unset = ob_get_level() == 0;	
		if($ob_is_unset)
		{
			ob_start();
		}
	}
	
	public function render(?array $view = [])
	{	
		extract($view, EXTR_SKIP);
		$this->openBuffer();
		include $this->build_resource_path($this->route);
	}
	
	/**
	 * Helper function to build a template string to the path of view resources templates actually
	 */
	
	private function build_resource_path(string $file) {
		return realpath(__DIR__ . '/../../resources/templates/' . $file . '.php');
	}
}

