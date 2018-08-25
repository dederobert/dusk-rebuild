<?php

namespace DuskPHP\Core\Router;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class Route
{
    private $path;
    private $middleware;
    private $name;
    private $method;

    public function __construct()
    {
    }

    public function match(ServerRequestInterface &$request): bool
    {
        //On retire les / en début et fin d'url
        $url = trim($request->getQueryParams()['url'], '/');

        //On cherche toute les chaînes de caractères entouré de '$'
        preg_match_all("\$[\w]+\$", $this->path, $matches);

        //Si l'url ne contient pas de paramètres entre '$' alors on fait un simple comparaison
        if (empty($matches[0])) {
            return $url === trim($this->path, '/');
        }

        //Si l'url contient un paramètre variadique '...',
        //alors on vérifie que l'on est suffisament d'élémént dans la requête
        if (1 === preg_match('#\.\.\.$#', $this->path)) {
            if (\count(explode('/', $url)) >= \count($matches[0])) {
                $this->parse($request, $matches, $url);

                return true;
            }

            return false;
        }

        if (\count(explode('/', $url)) === \count($matches[0])) {
            $request = $this->parse($request, $matches, $url);

            return true;
        }

        return false;
    }

    private function parse(ServerRequestInterface &$request, array $matches, string $url)
    {
        $query = $request->getQueryParams();
        $url_explode = explode('/', $url);
        foreach ($matches[0] as $index => $value) {
            $query[$value] = $url_explode[$index];
        }
        $request = $request->withQueryParams($query);
    }

    public function __get($name)
    {
        switch ($name) {
            case 'path':
                return $this->path;
            case 'middleware':
                return $this->middleware;
            case 'name':
                return $this->name;
            case 'method':
                return $this->method;
        }
    }

    public function path(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function middleware(MiddlewareInterface $middleware): self
    {
        $this->middleware = $middleware;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function isNamed(): bool
    {
        return !(null === $this->name) && !empty($this->name);
    }
}
