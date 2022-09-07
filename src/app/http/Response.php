<?php declare(strict_types=1);

namespace Set\Framework\App\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class Response {

	public static function sendView(Request $request, int $status) {
		call_user_func($request->attributes->get('_controller'), $request);
		$status = $request->attributes->has('status') ? $request->attributes->get('status') : $status;
		return Response::createResponse(ob_get_clean(), $status);
	}

	public static function sendNotFound(Request $request) {
		call_user_func($request->attributes->get('_controller'), $request);
		return Response::createResponse(ob_get_clean(), 404);

	}
	
	public static function sendServerError(Request $request) {
		call_user_func($request->attributes->get('_controller'), $request);
		return Response::createResponse(ob_get_clean(), 500);

	}

	private static function createResponse($content, $status) {
		$response = new HttpFoundationResponse();
		$response->setContent($content);
		$response->setStatusCode($status);
		return $response;

	}
}