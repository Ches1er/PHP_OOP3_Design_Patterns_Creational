<?php

            //Singleton

class A
{
    private static $foo = null;

    private function __construct()
    {
    }

    public static function getFoo()
    {
        return self::$foo;
    }

    public static function setFoo(int $foo)
    {
        if (self::$foo === null) self::$foo = $foo;
        return self::$foo;
    }
}

            //Multiton

class Multiton
{
    private static $instances = [];
    private $PDO = "\"mysql:dbname='Default';host=127.0.0.1;port=3306\";
                        charset=utf8,
                            username:\"root\",passwd:\"\",
                                [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
                        ]";

    public static function inst(string $key = "default")
    { //если нам необходимо какое то дефолтное значение
        if (!isset(self::$instances[$key])) self::$instances[$key] = new self();
        return self::$instances[$key];
    }

    public function getPDO(): string
    {
        return $this->PDO;
    }

    public function setPDO($PDO): void
    {
        $this->PDO = $PDO;
    }

}

/*$a = Multiton::inst("Shop");
$a->setPDO("\"mysql:dbname='Shop';host=127.0.0.1;port=3306\";
charset=utf8,
username:\"root\",passwd:\"\",
[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
]");
echo $a->getPDO() . "\n";
$b = Multiton::inst("Sales");
$b->setPDO("\"mysql:dbname='Sales';host=127.0.0.1;port=3306\";
charset=utf8,
username:\"root\",passwd:\"\",
[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
]");
echo $b->getPDO() . "\n";

$c = Multiton::inst();
echo $c->getPDO() . "\n";*/

            //Factory

interface Connection{
    public function getDesc();
}

interface ConnectionCreator{
    public function createConnection():Connection;
}

class DataBaseConnection implements Connection{
    public function getDesc(){
        return "DB";
    }
}

class SftpConnection implements Connection{
    public function getDesc(){
        return "sftp";
    }
}

class SftpConnectionCreator implements ConnectionCreator{

    public function createConnection(): Connection
    {
        return new SftpConnection();
    }
}

class DataBaseConnectionCreator implements ConnectionCreator{
    public function createConnection(): Connection
    {
        return new DataBaseConnection();
    }
}

/*const MODE = "sfpt";
if (MODE=='db')$creator = new DataBaseConnectionCreator;
if (MODE=='sfpt')$creator = new SftpConnectionCreator;

$conn1 = $creator->createConnection();
echo $conn1->getDesc();
$conn2 = $creator->createConnection();
echo $conn2->getDesc();*/

        //ABSTRACT FACTORY

interface TV{
    public function getTVFunc();
}
interface Phone{
    public function getPhoneFunc($name);
}
interface Factory{
    public function createTV():TV;
    public function createPhone():Phone;
}

class SamsTV implements TV{

    public function getTVFunc(){
        echo "Samsung TV";
    }
}

class LGTv implements TV{

    public function getTVFunc(){
        echo "LG TV";
    }
}

class SamsPhone implements Phone{

    public function getPhoneFunc($name)
    {
        echo "Take call from $name by Sams";
    }
}

class LGPhone implements Phone{

    public function getPhoneFunc($name)
    {
        echo "Take call from $name by LG";
    }
}

class SamsFactory implements Factory{

    public function createTV(): TV
    {
        return new SamsTV();
    }

    public function createPhone(): Phone
    {
        return new SamsPhone();
    }
}

class LGFactory implements Factory{

    public function createTV(): TV
    {
        return new LGTv();
    }

    public function createPhone(): Phone
    {
        return new LGPhone();
    }
}

$f = new LGFactory();
$f_p=$f->createPhone();
echo $f_p->getPhoneFunc("vasia")."\n";
echo $f->createTV()->getTVFunc()."\n";

        //BUILDER

class DbConnection{
    private $dbname;
    private $user;
    private $pass;
    private $type;

    public function __construct($dbname, $user, $pass, $type)
    {
        $this->dbname = $dbname;
        $this->user = $user;
        $this->pass = $pass;
        $this->type = $type;
    }

    public function getDbname()
    {
        return $this->dbname;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getType()
    {
        return $this->type;
    }

}

class DbConnectionBuilder{
    private $dbname="db";
    private $user="root";
    private $pass="";
    private $type="Mysql";

    public function Dbname(string $dbname): DbConnectionBuilder
    {
        $this->dbname = $dbname;
        return $this;
    }

    public function User(string $user): DbConnectionBuilder
    {
        $this->user = $user;
        return $this;
    }

    public function Pass(string $pass): DbConnectionBuilder
    {
        $this->pass = $pass;
        return $this;
    }

    public function Type(string $type): DbConnectionBuilder
    {
        $this->type = $type;
        return $this;
    }

    public function build(){
        return new DbConnection($this->dbname,$this->user,$this->pass,$this->type);
    }
}

$dbcon = (new DbConnectionBuilder())->User('vasia')->type('PgsQL')->build();
echo $dbcon->getDbname()."   ".$dbcon->getUser();

