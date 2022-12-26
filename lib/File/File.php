<?php

namespace CLB\File;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

class File extends Filesystem
{
    public Filesystem $filesystem;
    protected string $root;
    protected string $resources;

    public function __construct($root = '../'){
        $this->root = Path::normalize(realpath($root));
        return $this;
    }

    public function get($path)
    {
        if (!$this->exists($this->root . $path)) {
            throw new FileNotFoundException();
        }
        return file_get_contents($this->root . $path);
    }
}