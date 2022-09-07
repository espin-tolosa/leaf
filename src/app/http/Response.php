<?php declare(strict_types=1);

namespace Set\Framework\App\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class Response {

	public static function sendView(Request $request, int $status) {
		$response = new HttpFoundationResponse();
		call_user_func($request->attributes->get('__controller'), $request);
		$response->setContent(ob_get_clean());
		$response->setStatusCode($status);

		return $response;
	}
}