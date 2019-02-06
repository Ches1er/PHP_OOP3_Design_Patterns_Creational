<?php

include "FileStorage.php";
include "Mysql_actions.php";

interface User {
    public function GetUser();
}

interface Note {
    public function GetNote();
}

interface UserAction{
    public function AddUser(User $data);
    public function ShowNotes(string $id);
}

interface NoteAction{

    public function AddNote($user_id,Note $note);
    public function DelNote($user_id,string $id);
}

class JsonUser implements User
{
    private $user=[];
    private $login;
    private $notes = [];

    public function __construct(string $login)
    {
        $this->login = $login;
    }

    public function GetUser()
    {
        $this->user["id"]=time()."_".mt_rand(1000,9999).mt_rand(1000,9999).mt_rand(1000,9999);
        $this->user["login"]=$this->login;
        $this->user["notes"] = $this->notes;
        return $this->user;
    }
}

class JsonNotes implements Note{

    private $note = [];
    private $note_name;
    private $note_content;


    public function __construct(string $note_name,string $note_content)
    {
        $this->note_name = $note_name;
        $this->note_content = $note_content;
    }

    public function GetNote()
    {
        $this->note["name"] = $this->note_name;
        $this->note["content"]=$this->note_content;
        return $this->note;
    }
}

class DBUser implements User{

    private $login;

    public function __construct(string $login){
        $this->login = $login;
    }

    public function GetUser():array{
       return ["user_name"=>$this->login];
    }
}

class DBNotes implements Note{

    private $note_name;
    private $note_content;
    private $user_id;
    private $note = [];

    public function __construct($note_name, $note_content){
        $this->note_name = $note_name;
        $this->note_content = $note_content;
    }

    public function GetNote(){
        $this->note["user_id"]=$this->user_id;
        $this->note["note_name"]=$this->note_name;
        $this->note["desc"]=$this->note_content;
        return $this->note;
    }
}

class JsonUserAction extends FileStorage implements UserAction{

    protected $datapath = DOCROOT."data/";

    public function AddUser(User $u):void
    {
        $new_user = $u->GetUser();
        $this->fs_append($new_user);
    }

    public function ShowNotes(string $id):array
    {
        $arr=$this->fs_getById($id);
        return $arr;
    }
}

class JsonNotesAction extends FileStorage implements NoteAction{

    protected $datapath = DOCROOT."data/";

    public function AddNote($user_id,Note $note):void
    {
        $data = $this->fs_getAll();
        $note_id = time();
        $arr=$note->GetNote();
        foreach ($data as &$datum){
            if ($datum["id"]===$user_id)$datum["notes"][$note_id]=$arr;
        }
        $this->fs_saveFile($data);
    }

    public function DelNote($user_id,string $id):void
    {
        $data = $this->fs_getAll();
        foreach ($data as &$datum){
            if ($datum["id"]===$user_id){
                $notes_array = &$datum["notes"];
                $notes_array = array_filter($notes_array,function ($key) use ($id){
                    return $key!=$id;
                },ARRAY_FILTER_USE_KEY);
            };
        }
        $this->fs_saveFile($data);
    }
}

class DBUserAction extends DBActions implements UserAction{

    public function AddUser(User $data)
    {
        $this->DBInsert("users",$data->GetUser());
    }

    public function ShowNotes(string $id)
    {
        return $this->DBSelectById("todo","user_id",$id);
    }
}

class DBNotesAction extends DBActions implements NoteAction{

    public function AddNote($user_id, Note $note)
    {
        $new_note=$note->GetNote();
        $new_note["user_id"]=$user_id;
        $this->DBInsert("todo",$new_note);
    }

    public function DelNote($user_id, string $id)
    {
        $this->DBDelete("todo",$id);
    }
}

//---------JSON------------

/*$u = new JsonUser("Petya");
$j = new JsonUserAction("todo");
$j->AddUser($u);*/

/*$n = new JsonNotes("drop","drop smth");
$j = new JsonNotesAction("todo");
$j->AddNote("1549445151_250424121876",$n);*/

//-------------------DB---------------------

/*$n = new DBNotes("buy","buy smth");
$db = new DBNotesAction(DataBaseConnection::inst("mySql"));
$db->AddNote(1,$n);*/


