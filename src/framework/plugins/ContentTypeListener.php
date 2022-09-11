<?php declare(strict_types=1);

namespace Leaf\Plugins;

use Leaf\Http\Events\ContentTypeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentTypeListener implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
      return [
				ContentTypeEvent::PREFIX .'js' => 'onJS',
				ContentTypeEvent::PREFIX .'css' => 'onCSS',
				ContentTypeEvent::PREFIX .'svg' => 'onSVG',
			];
    }

    public function onJS(ContentTypeEvent $event) {
      $response = $event->getResponse();
			$response->headers->set('Content-Type', 'application/javascript');
    }
    
		public function onSVG(ContentTypeEvent $event) {
      $response = $event->getResponse();
			$response->headers->set('Content-Type', 'image/svg+xml');
    }
    
		public function onCSS(ContentTypeEvent $event) {
      $response = $event->getResponse();
			$response->headers->set('Content-Type', 'text/css, max-age=604800, public');
    }
}
