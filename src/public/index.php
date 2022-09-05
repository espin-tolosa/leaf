<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once realpath("./vendor/autoload.php");

$request = Request::createFromGlobals();

$content = 'Hello ' . $request->query->get('name', 'World');

$content .= ' | ' . $request->headers->get('host');
$content .= ' | ' . $request->headers->get('accept');

$response = new Response(sprintf(htmlspecialchars($content, ENT_QUOTES, 'UTF-8')));

$response->send();




echo "<br> REMOTE:";
echo $_SERVER['REMOTE_ADDR'];
echo "<br> FORWARDED:";
echo $_SERVER['HTTP_X_FORWARDED_FOR'];