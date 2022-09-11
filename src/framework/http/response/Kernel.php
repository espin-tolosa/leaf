<?php declare(strict_types=1);

namespace Leaf\Http\Response;

use Exception;
use Leaf\Http\Events\AuthorizationEvent;
use Leaf\Http\Events\ResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Matcher\UrlMatcher;

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
	UrlMatcher $matcher,
	ControllerResolver $controllerResolver
	/*, ArgumentResolver $argumentResolver */,
	EventDispatcher $dispatcher )
		{
		$this->matcher = $matcher;
		$this->controllerResolver = $controllerResolver;
		//$this->argumentResolver = $argumentResolver;
		$this->dispatcher = $dispatcher;
	}
	
	public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST,	bool $catch = false): Response	 {
		
		/**
		 * Check Authorization
		 */

		$response = new Response();
		$this->dispatcher->dispatch(new ResponseEvent($response, $request), 'kernel.authorization');
		if($request->attributes->has('response') && $request->attributes->get('response')->getStatusCode() === 401)
		{
			return $request->attributes->get('response');
		}

		/**
		 * Process Request
		 */

		$this->matcher->getContext()->fromRequest($request);

		try {
			$request->attributes->add($this->matcher->match($request->getPathInfo()));
			
			$controller = $this->controllerResolver->getController($request);
			//$arguments = $this->argumentResolver->getArguments($request, $controller);

			$request->attributes->add(['dispatcher' => $this->dispatcher]);

			$response = call_user_func($controller, $request);
		}
		
		catch (Routing\Exception\ResourceNotFoundException $exception) {
			$request->attributes->add($this->matcher->match('/not-found'));
			$request->attributes->add(['exception' => $exception->getMessage()]);
			
			$controller = $this->controllerResolver->getController($request);
			$response = call_user_func($controller, $request);
		}
		
		catch (Exception $exception) {
			$request->attributes->add($this->matcher->match('/server-error'));
			$request->attributes->add(['exception' => $exception->getMessage()]);
		
			$controller = $this->controllerResolver->getController($request);
			$response = call_user_func($controller, $request);
		}

		/**
		 * Post process response
		 */

		$this->dispatcher->dispatch(new ResponseEvent($response, $request), 'kernel.response.content-length');

		return $response;
	}
}