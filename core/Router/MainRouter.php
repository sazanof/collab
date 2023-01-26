<?php

declare(strict_types=1);

namespace CLB\Core\Router;

use CLB\Core\Controllers\Controller;
use CLB\Router\IRouter;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class MainRouter implements IRouter
{
    public const E_ROUTES_ADDED = 'on.routes.added';
    protected string $path;
    /**
     * @var RouteCollection
     */
    protected RouteCollection $routes;
    /**
     * @var Controller | null
     */
    protected ?Controller $executor = null;

    protected static ?MainRouter $instance = null;

    protected EventDispatcher $dispatcher;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function setDispatcher(EventDispatcher $dispatcher){
        $this->dispatcher = $dispatcher;
        $this->dispatcher->addListener(self::E_ROUTES_ADDED,function ($router){
            //dump('on.routes.added', $router);
        });
    }

    public static function getInstance(){
        return is_null(self::$instance) ? new self() : self::$instance;
    }

    public function registerRoutes($routes): IRouter
    {
        foreach ($routes as $url => $route){
            //TODO установка роута для install и upgrade, если приложение не установлено
            $_route = $this->setRoute($url, $route['methods'], $route['action']);

            if(isset($route['defaults'])){
                $_route->addDefaults($route['defaults']);
            }
        }
        return $this;
    }

    public function addRoutesFromAppInc(string $path = null) {
        $path = is_null($path) ? realpath('../inc/routes.php') : $path;
        $routes = require_once $path;
        if(!empty($routes)){
            $this->registerRoutes($routes);
        }
    }

    public function setRoute(string $url, array $methods, array $action)
    {
        $route = new Route($url, [
            '_controller' => $action,
            '_method'=>$methods,
        ]);
        $route->setMethods($methods);
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

    public function group($callback)
    {
        foreach (wrap($callback) as $route) {
            dump($route);
        }
    }

    public function getRoute(string $url)
    {
        // TODO: Implement getRoute() method.
    }
}
