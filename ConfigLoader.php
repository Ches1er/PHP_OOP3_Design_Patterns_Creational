<?php

class ConfigLoader extends FileStorage

{
    protected $datapath = DOCROOT."configs/";

    public function GetParameters(){
        return $config = $this->fs_getAll();
    }
}
