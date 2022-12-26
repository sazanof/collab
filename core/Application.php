<?php

namespace CLB\Core;

use CLB\Core\Config\Config;
use CLB\Database\Database;
use CLB\Database\IDatabase;
use CLB\File\File;
use CLB\Router\IRouter;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Application
{
    /**
     * @var IRouter
     */
    protected IRouter $router;
    protected IDatabase $connection;
    protected Dotenv $env;
    protected File $filesystem;

    public function __construct(IRouter $router)
    {
        $this->router = $router;
        $this->filesystem = new File(realpath('../'));
        $this->env = Dotenv::createImmutable(realpath('../'));
        $this->env->load();
        $this->connection = $this->initDatabaseConnection();
    }

    public function isAppInstalled()
    {
        return $this->filesystem->exists('../config/NOT_INSTALLED');
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function initDatabaseConnection(): IDatabase
    {
        $config = new Config('database');
        return new Database($config);
    }

    public function watch(): RedirectResponse|bool
    {
        $request = Request::createFromGlobals();
        try {
            $matcher = $this->router->matchRoute($_SERVER['REQUEST_URI']);
            if (!$this->isAppInstalled() && !$matcher['public']) {
                return $this->router->redirectTo('/install');
            }

            $controllerResolver = new ControllerResolver();
            $argumentResolver = new ArgumentResolver();
            $request->attributes->add($matcher);
            $controller = $controllerResolver->getController($request);
            $arguments = $argumentResolver->getArguments($request, $controller);

            $r = call_user_func_array($controller, $arguments);

            // TODO move to bottom to customize error pages
            //TODO - check middleware
            $response = new Response($r);
            $response->send();
        } catch (ResourceNotFoundException $exception) {
            //TODO custom 404 page
            dump($exception->getMessage());
        } catch (MethodNotAllowedException $exception) {
            //TODO custom page
            dump($exception);
        } catch (\ReflectionException $e) {
            dump($e);
        }

        return true;
    }
}
