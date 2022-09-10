<?php declare(strict_types=1);

namespace Set\Framework\App\Http\Events;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Simple Demo Class of Event of some type of object
 */

final class ResponseEvent extends Event {

	public const NAME = 'kernel.response'; // fully qualified name where type works like a namespace

	private Response $response;
	private Request $request;

	public function __construct(Response $response, Request $request) {
		$this->response = $response;
		$this->request = $request;
	}

	public function getResponse() {
		return $this->response;
	}

	public function getRequest() {
		return $this->request;
	}

}