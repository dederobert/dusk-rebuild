<?php

namespace DuskPHP\Core\Errors;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Controller404 implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('<h1 style="color: red;text-align:center;">Oups, page not found !</h1>');
        $response->withStatus(404);

        return $response;
    }
}
