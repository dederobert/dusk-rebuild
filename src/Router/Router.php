<?php

namespace DuskPHP\Core\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router implements MiddlewareInterface
{
    public function pipe(Route $route): self
    {
        $this->routes[$route->method][] = $route;
        if ($route->isNamed()) {
            $this->namedRoutes[$route->name] = $route;
        } else {
            throw new RouterException("The name's route must be defined");
        }

        return $this;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!isset($this->routes[$request->getMethod()])) {
            throw new RouterException('The method ' . $request->getMethod() . " doesn't exist");
        }
        foreach ($this->routes[$request->getMethod()] as $route) {
            if ($route->match($request)) {
                return $route->middleware->process($request, $handler);
            }
        }
        $m = new \DuskPHP\Core\Errors\Controller404();

        return $m->process($request, $handler);
    }
}
