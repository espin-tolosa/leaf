<?php declare(strict_types=1);

namespace Set\Framework\App\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class Response {

	public static function sendView(Request $request, int $status) {
		$response = new HttpFoundationResponse();
		call_user_func($request->attributes->get('_controller'), $request);
		$content= ob_get_clean();
		$response->setContent($content);

		switch ($content) {
			case 'Not found':
				$status = 404;
				break;
			case 'Unauthorized':
				$status = 401;
				break;
		}
		$response->setStatusCode($status);

		return $response;
	}
}