<?php declare(strict_types=1);

namespace Leaf\Plugins;

use Leaf\Http\Events\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorizationListener implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
      return ['kernel.authorization' => 'onAuthorization'];
    }

		public function onAuthorization(ResponseEvent $event) {

			$request = $event->getRequest();
			$response = $event->getResponse();

			$token = $request->cookies->has('jwt') ? $request->cookies->get('jwt') : null;

			/**
			 * Middleware Authorization
			 *
			 * this has entity has to be created and layered before request match and the second check not being hard-coded
			 * then I could use clause guards properly instead of need to nest the response
			 */

			if($token === null || $token !== "sam") {
				$response->setContent('Unauthorized');
				$response->setStatusCode(401);
				$response->headers->set('Content-Type', 'text/plain');
				$request->attributes->set('response', $response);
				//TODO: Add Custom Error Handling using HtppKernel Class
			}
		}
}