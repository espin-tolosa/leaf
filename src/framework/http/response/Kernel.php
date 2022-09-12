<?php declare(strict_types=1);

namespace Leaf\Http\Response;

use Exception;
use Leaf\Http\Events\RequestEvent;
use Leaf\Http\Events\ResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;


/**
 * Request / Response process
 *
 * 1.			Match Request
 ! 2.			Call Controller (busines logic and rewrite the status, only here)
 * 2.1.			Get Request Parameters, Busines Logic, Rewrite Status Code if needed and Call Renderer with Proccessed parameters
 * 2.2.  		Create a Response reading some new status that could be possibly generated in Controller
 ! 2.3.			Call View Template OB Renderer
 * 2.4.   	Return Response with content, status, header
 * 3.			Send Response to the client
 */

class Kernel implements HttpKernelInterface {
	
	private UrlMatcher $matcher;
	private ControllerResolver $controllerResolver;
	//private ArgumentResolver $argumentResolver;
	private EventDispatcher $dispatcher;
	
	public function __construct(
	UrlMatcherInterface $matcher,
	ControllerResolverInterface $controllerResolver
	/*, ArgumentResolver $argumentResolver */,
	EventDispatcher $dispatcher )
		{
		$this->matcher = $matcher;
		$this->controllerResolver = $controllerResolver;
		//$this->argumentResolver = $argumentResolver;
		$this->dispatcher = $dispatcher;
	}
	
	public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST,	bool $catch = false): Response	 {

		$this->matcher->getContext()->fromRequest($request);

		/**
		 * Symfony Get Request Context
		 */
		
		 $this->matcher->getContext()->fromRequest($request);

		/**
		 * Check Authorization
		 */

		try {
			$this->dispatcher->dispatch(new RequestEvent($request), 'kernel.authorization');
		}
		catch (UnauthorizedAccessException $exception) {
			return new Response(null,401);
		}
		
		/**
		 * Add Dispatcher to global request
		 */
		
		$request->attributes->add(['dispatcher' => $this->dispatcher]);

		/**
		 * Match the route and call the controller
		 */
		
		try {
			$response = $this->callControllerThrowable($request->getPathInfo(), $request);
		}
		
		catch (Routing\Exception\ResourceNotFoundException $exception) {
			$request->attributes->add(['exception' => $exception->getMessage()]);
			$response = $this->callControllerThrowable('/not-found', $request);
		}
		
		catch (Exception $exception) {
			$request->attributes->add(['exception' => $exception->getMessage()]);
			$response = $this->callControllerThrowable('/server-error', $request);
		}

		/**
		 * Post process response
		 */

		$this->dispatcher->dispatch(new ResponseEvent($response, $request), 'kernel.response.content-length');

		return $response;
	}
	
	private function callControllerThrowable($path, Request $request) {
			$request->attributes->add($this->matcher->match($path)); //!migth throw
			$controller = $this->controllerResolver->getController($request);
			return call_user_func($controller, $request);
	}
}