<?php 
require_once 'vendor/autoload.php';
use function Http\Response\send;

//Create a request using globals vars
$request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
//Create default response
$response = new \GuzzleHttp\Psr7\Response();
//Create dispatcher
$dispatcher = new \DuskPHP\Core\Dispatcher();

$i18n = new \DuskPHP\Core\i18n\I18n('fr');

$i18n->pipe('en')->pipe('be');


//Create middleware and pipe it in the dispatcher
//The router is a middleware which dispatch on the next middleware
$router = new \DuskPHP\Core\Router\Router();

$route_home = new \DuskPHP\Core\Router\Route();
$route_default = new \DuskPHP\Core\Router\Route();
$route_home->path('/')
	->middleware(new \DuskPHP\Core\HomePage())
	->name('homepage')
	->method('GET');
$route_default->path('/$controller$/$action$/...')
	->middleware(new \DuskPHP\Core\Controller())
	->name('default')
	->method('GET');

//Add new route with the get http's method which route default path to homepage middleware
$router->pipe($route_home)
	->pipe($route_default);

//Pipe router in the dispatcher
$dispatcher->pipe($i18n)
	->pipe($router);

//send the response to client
send($dispatcher->handle($request));

 ?>
 