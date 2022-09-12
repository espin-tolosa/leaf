<?php declare(strict_types=1);

use Leaf\Http\Events\ContentTypeEvent;
use Leaf\Http\Events\RequestEvent;
use Leaf\Http\Events\ResponseEvent;
use Leaf\Http\Response\Kernel;
use Leaf\Plugins\AuthorizationListener;
use Leaf\Plugins\ContentLengthListener;
use Leaf\Plugins\ContentTypeListener;
use Set\Routes\ApiRoutes;
use Set\Routes\Routes;
use Set\Routes\WebRoutes;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$containerBuilder = new ContainerBuilder();

/**
 * Symfony Entities
 */

$containerBuilder->register('response', Response::class);
$containerBuilder->register('context', RequestContext::class);

$containerBuilder->register('matcher', UrlMatcher::class)
->setArguments([new Reference('routes'), new Reference('context')]);

$containerBuilder->register('controller_resolver', ControllerResolver::class);
	
/**
 * User Routes
 */

$containerBuilder->register('web_routes', WebRoutes::class);
$containerBuilder->register('api_routes', ApiRoutes::class);

$containerBuilder->register('routes', Routes::class)->setArguments([
	new Reference('web_routes'),
	new Reference('api_routes')
]);
/**
 * Events Types
 */

$containerBuilder->register('request_event', RequestEvent::class)->setArguments([$request]);
$containerBuilder->register('response_event', ResponseEvent::class)->setArguments([new Reference('response'), $request]);
$containerBuilder->register('content_type_event', ContentTypeEvent::class)->setArguments([new Reference('response'), 'type']);

/**
 * Event Listeners
 */

$containerBuilder->register('listener.authorization', AuthorizationListener::class)->setArguments([new Reference('request_event')]);
$containerBuilder->register('listener.content_type', ContentTypeListener::class)->setArguments([new Reference('response_event')]);
$containerBuilder->register('listener.content_length', ContentLengthListener::class)->setArguments([new Reference('content_type_event')]);

/**
 * Event Subscribers
 */

$containerBuilder->register('dispatcher', EventDispatcher::class)
	->addMethodCall('addSubscriber', [new Reference('listener.authorization')])
	->addMethodCall('addSubscriber', [new Reference('listener.content_type')])
	->addMethodCall('addSubscriber', [new Reference('listener.content_length')]);

/**
 * Kernel Request/Response
 */

$containerBuilder->register('kernel', Kernel::class)
    ->setArguments([
        new Reference('matcher'),
        new Reference('controller_resolver'),
        new Reference('dispatcher')
    ])->addMethodCall('handle', [$request]);