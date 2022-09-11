<?php declare(strict_types=1);

namespace Leaf\Http\Events;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Simple Demo Class of Event of some type of object
 */

final class ContentTypeEvent extends Event {

	public const PREFIX = 'routes.response.content-type.';
	public string $NAME;

	private Response $response;

	public function __construct(Response $response, $type) {
		$this->response = $response;
		$this->NAME = self::PREFIX . $type;
	}

	public function getResponse() {
		return $this->response;
	}
}