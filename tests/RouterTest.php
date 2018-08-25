<?php
namespace DuskPHP\Core\Test;

use DuskPHP\Core\Router\Route;
use DuskPHP\Core\Router\Router;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private function makeRouter()
    {
        return new Router();
    }
    private function makeMiddleware()
    {
        $middleware = $this->getMockBuilder(MiddlewareInterface::class)->getMock();
        return $middleware;
    }
  
    public function testPipe()
    {
        $router = $this->makeRouter();
        $route = new \DuskPHP\Core\Router\Route();
        $route->path('/')
            ->name('test');
        $router->pipe($route);
        $this->assertInstanceOf(Router::class, $router);
    }
}