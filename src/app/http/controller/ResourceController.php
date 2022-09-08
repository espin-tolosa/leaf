<?php declare(strict_types=1);

namespace Set\Framework\App\Http\Controller;

class ResourceController {

	public function send($request) {
		$file = $request->attributes->get('file');	
		$token = $request->cookies->has('jwt') ? $request->cookies->get('jwt') : null;
		
		/**
		 * Middleware Authorization
		 * 
		 * this has entity has to be created and layered before request match and the second check not being hard-coded
		 * then I could use clause guards properly instead of need to nest the response
		 */
		
		if($token === null || $token !== "irebljpnnpiv0ceaoa62psa01c") {
			echo 'Unauthorized';
			$request->attributes->add(['status' => 401]);
			return;
		}
		
		/**
		 * Resource request classification coupled to File Serving
		 */

		$properties = explode('.', $file);
		$type = $properties[count($properties)-1];
		ob_start();
		
		switch ($type) {
			case 'svg':
				header('Content-Type: image/svg+xml');
				readfile (realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
				break;

			case 'js':
				header('Content-Type: application/javascript, max-age=604800, public');
				readfile (realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
				break;

			case 'css':
				header('Content-Type: text/css, max-age=604800, public');
				readfile (realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
				break;
			
			default:
				echo 'Not found';
				$request->attributes->add(['status' => 404]);
				break;
		}	

	}
}
