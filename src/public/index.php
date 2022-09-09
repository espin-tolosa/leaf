<?php declare(strict_types=1);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

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
class Framework {
	
	private UrlMatcher $matcher;
	private ControllerResolver $controllerResolver;
	//private ArgumentResolver $argumentResolver;
	
	public function __construct(UrlMatcher $matcher, ControllerResolver $controllerResolver /*, ArgumentResolver $argumentResolver */ ) {
		$this->matcher = $matcher;
		$this->controllerResolver = $controllerResolver;
		//$this->argumentResolver = $argumentResolver;
	}
	
	public function handle(Request $request) {
		$this->matcher->getContext()->fromRequest($request);
	
		try {
			$request->attributes->add($this->matcher->match($request->getPathInfo()));
			
			$controller = $this->controllerResolver->getController($request);
			//$arguments = $this->argumentResolver->getArguments($request, $controller);

			return call_user_func($controller, $request);
		}
		
		catch (Routing\Exception\ResourceNotFoundException $exception) {
			$request->attributes->add($this->matcher->match('/not-found'));
			$request->attributes->add(['exception' => $exception->getMessage()]);
			
			$controller = $this->controllerResolver->getController($request);
			return call_user_func($controller, $request);
		}
		
		catch (Exception $exception) {
			$request->attributes->add($this->matcher->match('/server-error'));
			$request->attributes->add(['exception' => $exception->getMessage()]);
		
			$controller = $this->controllerResolver->getController($request);
			return call_user_func($controller, $request);
		}
	}
	
	
}

/**
 * Read Routes
 */

require_once realpath(__DIR__ . '/../app/routes/web.php');
$request = Request::createFromGlobals();

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
//$argumentResolver = new ArgumentResolver();

$framework = new Framework($matcher, $controllerResolver /*, $argumentResolver*/);
$response = $framework->handle($request);
$response->send();