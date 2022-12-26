<?php

namespace CLB\Core\Config;

use CLB\Config\IConfig;
use CLB\Core\Exceptions\ConfigurationNotFoundException;
use CLB\File\File;

class Config implements IConfig
{
    private mixed $configArray = [];

    /**
     * @param $fileName
     * @throws ConfigurationNotFoundException
     */
    public function __construct($fileName){
        $pathToConfig = realpath("../config/{$fileName}.php");
        $fs = new File($pathToConfig);
        if ($fs->exists($pathToConfig)){
            $this->configArray = require_once($pathToConfig);
        } else {
            throw new ConfigurationNotFoundException();
        }
    }

    public function getConfig(): array
    {
        return $this->configArray;
    }

    public function getConfigValue($key): mixed
    {
        return $this->configArray[$key];
    }

    /**
     * Helper for get method
     * @param $array
     * @param $key
     * @return mixed
     */
    private static function getValueFromArray($array, $key) {
        return $array[$key];
    }

    /**
     * Get current config value
     * Example Config::get('app.pages.first') => config/app.php -> return [ 'pages' => ['first' => 1, 'second' => 2]]
     */
    public static function get($key){
        $explodedKey = explode('.',$key);
        $file = $explodedKey[0];
        unset($explodedKey[0]);
        if(!empty($explodedKey)){
            try {
                $cnf = new self($file);
                $currentValue = $cnf->configArray;
                foreach ($explodedKey as $_key){
                    $currentValue = $cnf::getValueFromArray($currentValue, $_key);
                }
                return $currentValue;
            } catch (ConfigurationNotFoundException $e) {
                // log here
            }

        } else {
            return '';
        }
    }
}