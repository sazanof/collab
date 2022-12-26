<?php

namespace CLB\Template;

interface ITemplateRenderer
{
    public function loadTemplate(string $name): string;
}
