<?php

namespace CLB\Core\Controllers;

class InstallController extends Controller
{
    protected bool $useTemplateRenderer = true;

    public function install(): string
    {
        return $this->templateRenderer->loadTemplate('/install/install', ['test'=>'Hello from install']);
    }
}