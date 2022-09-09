<?php declare(strict_types=1);

namespace Set\Framework\resources;

use Symfony\Component\HttpFoundation\Request;

/**
 * Render the template
 * 
 * create an object buffer and send all the html
 */

class ResourceRenderer {

	private string $route;

	public function __construct(Request $request)
	{
		$this->route = $request->attributes->get('_route');
	}

	private function openBuffer() {
		ob_start();
	}
	
	public function template(?array $view = []) {	
		extract($view, EXTR_SKIP);
		$this->openBuffer(); //open Buffer
		include $this->build_resource_path($this->route); //write resource to a Buffer
	}

	public function text(string $content) {
		extract($view, EXTR_SKIP);
		$this->openBuffer(); //open Buffer
		echo $content;
	}

	public function media(string $file) {
		$properties = explode('.', $file);
		$type = $properties[count($properties)-1];
		$this->openBuffer();
		//ob_start();
		readfile (realpath(__DIR__ . '/' . $type .'/' . $file));
	}
	
	/**
	 * Helper function to build a template string to the path of view resources templates actually
	 */
	
	private function build_resource_path(string $file) {
		return realpath(__DIR__ . '/' .  'templates' . '/' . $file . '.php');
	}
}

