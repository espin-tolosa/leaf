<?php

namespace App\Leaf\Tests;

use Leaf\Http\Response\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing;

final class FrameworkTest extends TestCase
{
	public function testControllerResponse() {
    $matcher = $this->createMock(Routing\Matcher\UrlMatcher ::class);
    // use getMock() on PHPUnit 5.3 or below
    // $matcher = $this->getMock(Routing\Matcher\UrlMatcherInterface::class);

    $matcher
        ->method('match')
        ->will($this->returnValue([
            '_route' => '/index/{name}',
            '_controller' => function($name) {return new Response('sam', 200);},
        ]));

    $matcher
        ->method('getContext')
        ->will($this->returnValue($this->createMock(Routing\RequestContext::class)));
    $controllerResolver = new ControllerResolver();
		$eventDispatcher = new EventDispatcher();

    $framework = new Kernel($matcher, $controllerResolver, $eventDispatcher);

    $response = $framework->handle(new Request());

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertStringContainsString('sam', $response->getContent());
	}
}