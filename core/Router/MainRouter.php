<?php

declare(strict_types=1);

namespace CLB\Core\Router;

use CLB\Core\Controllers\Controller;
use CLB\Router\IRouter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class MainRouter implements IRouter
{
    protected $exclusions = [];
    /**
     * @var RouteCollection|null
     */
    protected RouteCollection|null $routes = null;
    /**
     * @var Controller | null
     */
    protected ?Controller $executor = null;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function registerRoutes($routes): IRouter
    {
        $this->routes = $routes;
        return $this;
    }

    public function setRoute(string $url, string $method, array $action)
    {
        $route = new Route($url, [
            '_controller' => $action,
            '_method'=>$method,
        ]);
        $route->setMethods($method);
        $this->routes->add($url, $route);
        return $route;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function matchRoute($url): array
    {
        $context = new RequestContext();
        $context->fromRequest(Request::createFromGlobals());
        $matcher = new UrlMatcher($this->getRoutes(), $context);
        return $matcher->match($url);
    }

    /**
     * @param string $url
     * @return RedirectResponse
     */
    public function redirectTo(string $url): RedirectResponse
    {
        $redirect = new RedirectResponse($url);
        return $redirect->send();
    }

    public function getRoute(string $url)
    {

    }
}
