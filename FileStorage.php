<?php
define("DOCROOT",$_SERVER["DOCUMENT_ROOT"]);
define("CONFIGPATH",DOCROOT."configs/");


class FileStorage
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }
    private function fs_filename()
    {
        return CONFIGPATH . $this->file.".json";
    }
    public function fs_getAll()
    {
        $file_content = file_get_contents($this->fs_filename());
        return json_decode($file_content, true);
    }
    private function fs_saveFile($arr){
        file_put_contents($this->fs_filename(),json_encode($arr));
    }
    public function fs_append($data){
        $arr = $this->fs_getAll();
        $data["id"] = time()."_".mt_rand(1000,9999).mt_rand(1000,9999).mt_rand(1000,9999);
        $arr[] = $data;
        $this->fs_saveFile($arr);
    }
    public function fs_getById($id){
        $arr = $this->fs_getAll();
        foreach ($arr as $data){
            if($data["id"]===$id) return $data;
        }
        return null;
    }
    public function fs_del($id){
        $arr= $this->fs_getAll();
        $arr = array_filter($arr,function ($e) use ($id){
            return $e["id"]!=$id;
        });
        $this->fs_saveFile($arr);
    }
}