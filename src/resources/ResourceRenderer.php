<?php declare(strict_types=1);

namespace Set\Framework\resources;

use Symfony\Component\HttpFoundation\Request;

/**
 * Render the template
 * 
 * create an object buffer and send all the html: this is pure side effect class that writes in the php ob
 * aditionally, render methods: template and media can fail if the object is not found, so they can return also a status code
 * which can be used by controller in order to decide what status respond
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
		if(!$this->build_resource_path($this->route))
		{
			echo 'Server Error: Deleted Template Resource unexpectedly: ' . $this->route;
			return 500;
		}
		include ($this->build_resource_path($this->route)); //write resource to a Buffer
	}

	public function text(string $content) {
		extract($view, EXTR_SKIP);
		$this->openBuffer(); //open Buffer
		echo $content;
	}

	public function media(string $file) {
		
		$this->openBuffer();

		if(!$this->build_media_path($file))
		{
			echo '/*Not found error in file: ' . $file .'*/';
			return 404;
		}

		readfile ($this->build_media_path($file));	
	}
	
	/**
	 * Helper function to build a template string to the path of view resources templates actually
	 */
	
	private function build_resource_path(string $file) {
		return realpath(__DIR__ . '/' .  'templates' . '/' . $file . '.php');
	}

	private function build_media_path(string $file) {
		$properties = explode('.', $file);
		$type = $properties[count($properties)-1];
		return realpath(__DIR__ . '/' . $type .'/' . $file);

	}
}

