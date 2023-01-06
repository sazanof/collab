<?php

namespace CLB\Core;

use CLB\Core\Config\Config;
use CLB\Database\Database;
use CLB\Database\IDatabase;
use CLB\Database\QueryBuilder;
use CLB\File\File;
use CLB\Router\IRouter;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use \Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    protected EntityManager $entityManager;

    public function __construct(IRouter $router)
    {
        $this->router = $router;
        $this->filesystem = new File(realpath('../'));
        $this->env = Dotenv::createImmutable(realpath('../'));
        try {
            $this->env->load();
            $this->connection = $this->initDatabaseConnection();
            $this->entityManager = $this->connection->getEntityManager();
        } catch (Exception $exception) {

        }
    }

    public function isAppInstalled()
    {
        return !$this->filesystem->exists('../config/NOT_INSTALLED');
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
            if(is_array($r) || is_object($r)){
                $response = new JsonResponse($r);
            } else {
                $response = new Response($r);
            }
            $response->send();
        } catch (ResourceNotFoundException $exception) {
            //TODO custom 404 page
            self::JsonResponseException($exception);
        } catch (MethodNotAllowedException $exception) {
            //TODO custom page
            self::JsonResponseException($exception);
        } catch (Exception $exception) {
            self::JsonResponseException($exception);
        }
        return true;
    }

    private static function JsonResponseException(Exception $exception, $statusCode = 500): void
    {
        $response = new JsonResponse([
            'code' => $exception->getCode(),
            'message' => $exception->getMessage()
        ], $statusCode);
        $response->send();
    }
}
