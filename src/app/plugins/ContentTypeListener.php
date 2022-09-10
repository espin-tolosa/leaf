<?php declare(strict_types=1);

namespace Set\Framework\App\plugins;

use Set\Framework\App\Http\Events\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentTypeListener implements EventSubscriberInterface {
    public static function getSubscribedEvents() {
      return ['kernel.response' => 'onResponse'];
    }

    public function onResponse(ResponseEvent $event) {
      $response = $event->getResponse();

      if ($response->isRedirection()
          || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
          || 'html' !== $event->getRequest()->getRequestFormat()
      ) {
          return;
      }

      $response->setContent($response->getContent().'GA CODE');
    }
}