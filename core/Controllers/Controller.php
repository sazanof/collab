<?php

namespace CLB\Core\Controllers;

use CLB\Controller\IController;
use CLB\Core\Templates\TemplateRenderer;
use CLB\Database\CustomEntityManager;
use CLB\Database\Database;
use CLB\File\File;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class Controller implements IController
{
    protected Request $request;
    protected TemplateRenderer $templateRenderer;
    protected File $filesystem;
    protected ?EntityManager $em;

    protected bool $useTemplateRenderer = false;


    public function __construct()
    {
        $this->filesystem = new File();
        $this->em = Database::getInstance()->getEntityManager();
        if($this->useTemplateRenderer){
            $this->templateRenderer = new TemplateRenderer();
        }
    }

    public function execute($className, $method, $params = [])
    {
        return call_user_func(array($className, $method), $params);
    }
}
