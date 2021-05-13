<?php namespace App\Controllers;

use App\Lib\DB;
use App\Lib\Request;
use App\Lib\Response;
use App\Models\Book;

class BooksController
{
    public $database;
    public $db;
    public function __construct(){

    }
    public static function read(Response $res){
        $books = new Book();
        $books = $books->all();
        $res->toJSON([
            'success' => true,
            'books' =>  $books
        ]);
    }
    public function readSingle(Request $req,Response $res){
        $isbn = $req->params[0];
        $books = new Book();
        $books = $books->find($isbn);
        if(empty($books)) {
            $response_data = [
                'success' => false,
                'message' => "Book with ISBN:".$isbn." not found!"
            ];
        }
        else {
            $book = $books[0];
            $response_data = [
                'success' => true,
                'book' =>  $book
            ];
        }
        $res->toJSON($response_data);
    }
    public function store(Request $req,Response $res)
    {
        $data = $req->getJSON();
        $errors = $this->validateBookData($data);
        if(!empty($errors)) $res->status(400)->toJSON(['success' => false,'errors' => $errors]);
        else{
            $book = new Book();
            $book->isbn = $data->isbn;
            $book->author = $data->author;
            $book->title = $data->title;
            $book->release_date = $data->release_date;
            $status = $book->create();
            if($status) $message = "New book added. ISBN : ".$data->isbn;
            else $message = "Book not added! Check data";
            $res->toJSON([
                'success' => $status,
                'message' => $message
            ]);
        }

    }
    public function update(Request $req,Response $res){
        $isbn = $req->params[0];
        $data = $req->getJSON();
        $errors = $this->validateBookData($data);
        if(!empty($errors)) $res->status(400)->toJSON(['success' => false,'errors' => $errors]);
        else{
            $book = new Book();
            $book->isbn = $data->isbn;
            $book->author = $data->author;
            $book->title = $data->title;
            $book->release_date = $data->release_date;
            $status = $book->update($isbn);
            if($status) $message = "Updated book. ISBN : ".$data->isbn;
            else $message = "Book not updated! Check data";
            $res->toJSON([
                'success' => $status,
                'message' => $message
            ]);
        }
    }
    public function delete(Request $req,Response $res){
        $isbn = $req->params[0];
        $book = new Book();
        $status = $book->delete($isbn);
        $res->toJSON([
            'success' => $status,
            'message' => "Book deleted"
        ]);
    }
    public function validateBookData($data){
        $errors = [];
        if($data->isbn ==""||strlen($data->isbn)>13||strlen($data->isbn)<10) {
            array_push($errors,"ISBN cannot be empty and digits should be between 10 and 13");
        }
        if(!preg_match('/[a-zA-Z]/',$data->author)) {
            array_push($errors,"Author parameter is required");
        }
        if(!preg_match('/[a-zA-Z]/',$data->title)) {
            array_push($errors,"Title parameter is required");
        }
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data->release_date)&&$data->release_date!="") {
            array_push($errors,"Date should be in format : YYYY-MM-DD");
        }
        return $errors;
    }
}