<?php declare(strict_types=1);

use Leaf\Http\Events\AuthorizationEvent;
use Leaf\Http\Events\ContentTypeEvent;
use Leaf\Http\Events\ResponseEvent;
use Leaf\Http\Response\Kernel;
use Leaf\Plugins\AuthorizationListener;
use Leaf\Plugins\ContentLengthListener;
use Leaf\Plugins\ContentTypeListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$containerBuilder = new ContainerBuilder();
		//$containerBuilder->register('mailer_provider', Leaf\Services\MailerProvider::class )->addArgument('developmentMailService');
		//$containerBuilder->register('mailer', Leaf\Services\Mailer::class)->setArguments([new Reference('mailer_provider')]);

/**
 * Symfony Entities
 */

$containerBuilder->register('response', Response::class);
$containerBuilder->register('context', RequestContext::class);
$containerBuilder->register('matcher', UrlMatcher::class)->setArguments([$routes, new Reference('context')]);
$containerBuilder->register('controller_resolver', ControllerResolver::class);
	
/**
 * Events Types
 */

$containerBuilder->register('response_event', ResponseEvent::class)->setArguments([new Reference('response'), $request]);
$containerBuilder->register('content_type_event', ContentTypeEvent::class)->setArguments([new Reference('response'), 'type']);

/**
 * Event Listeners
 */

$containerBuilder->register('listener.authorization', AuthorizationListener::class)->setArguments([new Reference('response_event')]);
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