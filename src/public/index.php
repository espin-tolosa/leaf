<?php declare(strict_types=1);

require_once realpath(__DIR__ . '/../../vendor/autoload.php');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$response = new Response();

$routemap = [
	'/' => 'index',
	'/spa' => 'spa',
	'/api' => 'api',
	'/notfound' => 'notfound',
	'/backoffice' => 'backoffice',
	'/authenticate' => 'authenticate'
];

function set_routemap_destination_folder(string $file) {
	return realpath(__DIR__ . '/../pages/' . $file . '.php');
}

$path = $request->getPathInfo();

if(isset($routemap[$path]))
{
	$page = $routemap[$path];
	extract($request->query->all());
	require set_routemap_destination_folder($page) ;
	$response->setStatusCode(200);
}
else
{
	$page = $routemap['/notfound'];
	require set_routemap_destination_folder($page) ;
	$response->setStatusCode(404);
}

$response->send();
