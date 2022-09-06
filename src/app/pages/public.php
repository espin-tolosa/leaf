<?php declare(strict_types=1);

/**
 * Middleware Authorization
 * 
 * this has entity has to be created and layered before request match and the second check not being hard-coded
 * then I could use clause guards properly instead of need to nest the response
 */

 if($request->cookies->has('jwt') && $request->cookies->get('jwt') == "irebljpnnpiv0ceaoa62psa01c") {

	/**
	 * Resource request classification coupled to File Serving
	 */

	if($file == "favicon.svg") {
		$response->headers->set('Content-Type', 'image/svg+xml');
		readfile ( realpath(__DIR__ . '/../../public/' . $file));
	}

	else {
		$properties = explode('.', $file);
		$type = $properties[count($properties)-1];

			if( isset($type) &&  $type == "js") {
				$response->headers->set('Content-Type', 'application/javascript');
				$response->headers->set('Cache-Control','max-age=604800, public');
				readfile ( realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
			}
			else if( isset($type) &&  $type == "css") {
				$response->headers->set('Content-Type', 'text/css');
				$response->headers->set('Cache-Control','max-age=604800, public');
				readfile ( realpath(__DIR__ . '/../../resources/'. $type .'/' . $file));
		}
	}
	
}