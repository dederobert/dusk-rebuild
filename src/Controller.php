<?php

namespace DuskPHP\Core;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Controller implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $query = $request->getQueryParams();
        $controllerName = '\\DuskPHP\\Core\\Controller\\' . ucfirst($query['controller']) . 'Controller';
        $ret = \call_user_func([$controllerName, $query['action']]);
        $response = new Response();
        $response->getBody()->write($ret);

        return $response;
    }
}
