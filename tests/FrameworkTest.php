<?php declare(strict_types=1);

namespace App\Leaf\Tests;

use App\Leaf\Framework;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;

final class FrameworkTest extends TestCase
{
	public function testNotFoundHandling()
	{
			$framework = $this->getFrameworkForException(new ResourceNotFoundException());

			$response = $framework->handle(new Request());

			$this->assertEquals(404, $response->getStatusCode());
	}

	private function getFrameworkForException($exception)
	{
			$matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);
			// use getMock() on PHPUnit 5.3 or below
			// $matcher = $this->getMock(Routing\Matcher\UrlMatcherInterface::class);

			$matcher
					->expects($this->exactly(2))
					->method('match')
					->will($this->throwException($exception))
			;
			$matcher
					->expects($this->exactly(2))
					->method('getContext')
					->will($this->returnValue($this->createMock(Routing\RequestContext::class)))
			;
			$controllerResolver = $this->createMock(ControllerResolverInterface::class);
			//$argumentResolver = $this->createMock(ArgumentResolverInterface::class);

			return new Framework($matcher, $controllerResolver);
	}
//	//! PHPUnit assumes that neither the test code nor the tested code emit output or send headers
//	/**
//	 * @runInSeparateProcess
//	 */
//    public function testIndex()
//    {
//			  ob_start();
//        $_GET['name'] = 'Fabien';
//        //include "index.php";
//				include "../index.php";
//        $content = ob_get_clean();
//
//        $this->assertEquals('Hello Fabien', $content);
//    }
}