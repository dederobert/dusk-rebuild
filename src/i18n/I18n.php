<?php

namespace DuskPHP\Core\i18n;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class I18n implements MiddlewareInterface
{
    private $default_lang;
    private $langs = [];

    public function __construct(string $default_lang)
    {
        $this->default_lang = $default_lang;
        $this->langs[] = $default_lang;
    }

    public function pipe(string $lang): self
    {
        if (!in_array($lang, $this->langs, true)) {
            $this->langs[] = $lang;
        }

        return $this;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $lang = explode('/', $request->getQueryParams()['url'])[0];
        $query = $request->getQueryParams();
        if (!in_array($lang, $this->langs, true)) {
            $lang = $this->default_lang;
        } else {
            //Remove the lang params in url
            $query['url'] = substr_replace($query['url'], '', 0, mb_strlen($lang) + 1);
        }
        $query['lang'] = $lang;
        $request = $request->withQueryParams($query);

        $response = $handler->handle($request);
        //TODO Translate $response
        $response->getBody()->write('LANG : ' . $lang);

        return $response;
    }
}
