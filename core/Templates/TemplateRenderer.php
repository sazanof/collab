<?php

namespace CLB\Core\Templates;

use CLB\Template\ITemplateRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateRenderer implements ITemplateRenderer
{
    protected array $paths = [];
    private string $defaultPath;
    private Environment $template;

    public function __construct($defaultPath)
    {
        $this->defaultPath = $defaultPath;
        $this->paths[] = $this->defaultPath;
    }

    public function setPaths($paths = [])
    {
        $this->paths = array_merge($paths, $this->paths);
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function loadTemplate(string $name, $data = []): string
    {
        $loader = new FilesystemLoader($this->paths);
        $this->template = new Environment($loader);
        $nameWithExtention = "{$name}.twig";
        return $this->template->render($nameWithExtention, $data);
    }
}
