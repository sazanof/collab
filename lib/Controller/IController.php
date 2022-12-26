<?php

namespace CLB\Controller;

interface IController
{
    public function execute($className, $method, $params = []);
}
