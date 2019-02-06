<?php

include "DatabaseConnection.php";

class DBActions{
    private $config;

    public function __construct(DataBaseConnection $config)
    {
        $this->config = $config;
    }
    private function MakeDbh(){
        static $dbh =null;
        $config_arr = $this->config->getConfig();
        if($dbh!=null) return $dbh;
        extract($config_arr);
        $dbh = new PDO(
            $type.":dbname=".$dbname.";host=127.0.0.1;port=3306;charset=utf8",
            $username,
            $passwd,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        return $dbh;
    }

    public function DBInsert(string $table,array $arr){
        $q = "INSERT INTO `{$table}`";
        $fields = array_keys($arr);
        $q.="(`".implode("`,`",$fields)."`)VALUES (:"
            .implode(",:",$fields).")";
        $stmt = $this->MakeDbh()->prepare($q);
        $stmt->execute($arr);
    }
    public function DBDelete(string $table,int $id){
        $q = "DELETE FROM `{$table}` WHERE `id_note`=?";
        $stmt = $this->MakeDbh()->prepare($q);
        $stmt->execute([$id]);
    }
    public function DBInsertAll(string $table,array $arr){
        $q = "INSERT INTO `{$table}`";
        $fields = array_keys($arr[0]);
        $q.="(`".implode("`,`",$fields)."`)VALUES (:"
            .implode(",:",$fields).")";
        $stmt = $this->MakeDbh()->prepare($q);
        foreach ($arr as $a){
            $stmt->execute($a);
        }
    }

    public function DBSelectById (string $table,string $field,int $id){
        $q = "SELECT * FROM `{$table}` WHERE {$field} = ?";
        $stmt=$this->MakeDbh()->prepare($q);
        $stmt->execute(["$id"]);
        return $stmt->fetchAll();
    }

    public function DBSelectAll(string $table,string $criteria,string $direction){
        //Where "criteria" means order by criteria, direction "DESC" or "ASC"
        $direct=$this->MakeDbh()->quote($direction);
        $direct=substr($direct,1,strlen($direct)-2);
        $stmt=$this->MakeDbh()->query("SELECT * FROM `{$table}` ORDER BY {$criteria} {$direct}");
        return $stmt->fetchAll();
    }

    public function DBSelectByPartOfTheName(string $table,string $part){
        if ($part===NULL)return NULL;
        $q = "SELECT * FROM `{$table}` WHERE `name` LIKE ?";
        $stmt=$this->MakeDbh()->prepare($q);
        $stmt->execute(["%$part%"]);
        return $stmt->fetchAll();
    }

    public function DBSelectPage(string $table,int $page,int $cpp){
        $offset = ($page-1)*$cpp;
        $q = "SELECT * FROM `{$table}` LIMIT {$cpp} OFFSET {$offset} ORDER BY `id` DESC";
        $stmt = $this->MakeDbh()->query($q);
        return $stmt->fetchAll();
    }

    public function DBGetCount($table):int {
        $q = "SELECT count(*) FROM `{$table}`";
        return (int)($this->MakeDbh()->query($q)->fetchColumn());
    }

}


