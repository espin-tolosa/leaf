<?php declare(strict_types=1);

namespace Leaf\Plugins;

use Leaf\Http\Events\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentLengthListener implements EventSubscriberInterface
{
	public static function getSubscribedEvents() {
		return ['kernel.response' => 'onResponse'];		
	}

  public function onResponse(ResponseEvent $event) {
    $response = $event->getResponse();
    $headers = $response->headers;

    if (!$headers->has('Content-Length') && !$headers->has('Transfer-Encoding')) {
			$headers->set('Content-Length', strval(strlen($response->getContent())));
    }
  }
}