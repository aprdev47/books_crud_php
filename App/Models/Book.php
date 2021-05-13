<?php namespace  App\Models;

use App\Lib\Config;
use App\Lib\DB;

class Book
{
    private $db_table = "books";
    private $db;
    public $isbn;
    public $author;
    public $title;
    public $release_date;
    public function __construct(){
        $this->db = DB::getInstance();
    }

    public function all(){
        $result = $this->db->queryData("select * from ".$this->db_table);
        return $result;
    }
    public function find($isbn){
        $result = $this->db->queryData("select * from ". $this->db_table ." where isbn = ".$isbn." LIMIT 1");
        return $result;
    }
    public function create(){
        $status = $this->db->query("INSERT INTO ". $this->db_table ." (isbn, author, title, release_date) VALUES ('$this->isbn', '$this->author', '$this->title', '$this->release_date')");
        return $status;
    }
    public function update($isbn){
        $status = $this->db->query("UPDATE ". $this->db_table ." SET isbn='$this->isbn', author='$this->author',title='$this->title',release_date='$this->release_date' where isbn =".$isbn);
        return $status;
    }
    public function delete($isbn){
        $status = $this->db->query("DELETE FROM ". $this->db_table ." where isbn =".$isbn);
        return $status;
    }
}