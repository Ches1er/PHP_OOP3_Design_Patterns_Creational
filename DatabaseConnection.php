<?php

//include "FileStorage.php";
include "ConfigLoader.php";


class DatabaseConnection
{
    private static $instances = [];
    public static $config;
    const CONFIGPATH = DOCROOT."configs/";

    private function __construct()
    {
    }

    public static function inst(string $key){
        if (file_exists(self::CONFIGPATH . $key.".json")){
            if (!isset(self::$instances[$key])){
                self::$instances[$key] = new self();
                self::$instances[$key]::setConfig($key);
            }
            return self::$instances[$key];
        }
        return null;
    }

    private static function setConfig(string $key)
    {
        $config = new ConfigLoader($key);
        self::$config = $config->GetParameters();
    }

    public static function getConfig()
    {
        return self::$config;
    }

}

$f = DataBaseConnection::inst("mySql");
print_r($f->getConfig());
