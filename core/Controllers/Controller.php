<?php

namespace CLB\Core\Controllers;

use CLB\Controller\IController;
use CLB\Core\Templates\TemplateRenderer;
use CLB\File\File;
use Symfony\Component\HttpFoundation\Request;

class Controller implements IController
{
    protected Request $request;
    protected TemplateRenderer $templateRenderer;
    protected File $filesystem;

    protected bool $useTemplateRenderer = false;


    public function __construct()
    {
        $this->filesystem = new File();
        if($this->useTemplateRenderer){
            $this->templateRenderer = new TemplateRenderer('../resources/templates');
        }
    }

    public function execute($className, $method, $params = [])
    {
        return call_user_func(array($className, $method), $params);
    }
}
