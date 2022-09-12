<?php declare(strict_types=1);

namespace Leaf\Plugins;

use Leaf\Http\Events\RequestEvent;
use Leaf\Http\Response\UnauthorizedAccessException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorizationListener implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
      return ['kernel.authorization' => 'onAuthorization'];
    }

		public function onAuthorization(RequestEvent $event) {

			$request = $event->getRequest();

			$token = $request->cookies->has('jwt') ? $request->cookies->get('jwt') : null;

			/**
			 * Middleware Authorization
			 */

			if($token === null || $token !== "sam") {
				$event->stopPropagation();
				throw new UnauthorizedAccessException();
			}

		}
}