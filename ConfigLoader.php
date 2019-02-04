<?php

include "FileStorage.php";

class ConfigLoader extends FileStorage

{
    public function ShowParameters(){
        $config = $this->fs_getAll();
        foreach ($config as $key=>$value)echo $key.":".$value."\n";
    }
}

$f = new ConfigLoader("mySql");
$f->ShowParameters();