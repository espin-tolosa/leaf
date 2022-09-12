<?php declare(strict_types=1);

namespace Leaf\Http\Events;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Simple Demo Class of Event of some type of object
 */

final class RequestEvent extends Event {

	public const NAME = 'kernel.request'; // fully qualified name where type works like a namespace

	private Request $request;

	public function __construct($request) {
		$this->request = $request;
	}

	public function getRequest() {
		return $this->request;
	}

}