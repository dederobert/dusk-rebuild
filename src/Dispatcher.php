<?php

namespace DuskPHP\Core;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Dispatcher implements RequestHandlerInterface
{
    /**
     * @var array
     */
    private $middlewares = [];

    private $index = 0;

    private $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function pipe(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = $this->getMiddleware();
        ++$this->index;

        if (null === $middleware) {
            return $this->response;
        }

        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $this);
        }
    }

    private function getMiddleware(): MiddlewareInterface
    {
        if (isset($this->middlewares[$this->index])) {
            return $this->middlewares[$this->index];
        }

        return new \DuskPHP\Core\Errors\Controller500();
    }
}
