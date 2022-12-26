<?php

namespace CLB\Core\Controllers;

use CLB\File\File;

class LocaleController extends Controller
{
    protected bool $useTemplateRenderer = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function getTranslation($lang) {
        return $this->filesystem->get('/resources/locales/' . $lang . '.json');
    }
}