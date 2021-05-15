<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class BooksTest extends TestCase
{
    public $book= [
        'title'=>'Title for test book',
        'author'=>'Author of test book',
        'isbn'=>'1234567891011',
        'release_date'=>'2012-06-01'
    ];


    public function testGETAllBooks()
    {
        $client = new Client();
        $res = $client->get( 'http://localhost:8000/books');
        $data = json_decode($res->getBody(true), true);
        $this->assertEquals(200, $res->getStatusCode(),'Request failed Response status code :'.$res->getStatusCode());
        $this->assertArrayHasKey('success', $data,"Success response not received");
        $this->assertArrayHasKey('books', $data,"Success response not received");
    }
    public function testBookInputValidation()
    {
        $book_data =[
            'title'=>'',
            'author'=>' ',
            'isbn'=>'12345',
            'release_date'=>'asd'
        ];
        $client = new Client();
        try{
            $client->post('http://localhost:8000/books',['json'=>$book_data]);
        } catch(\Exception $e){
            $this->assertEquals(422, $e->getResponse()->getStatusCode(),'Request failed Response status code :'.$e->getResponse()->getStatusCode());
        }

    }
    public function testNewBook()
    {
        $client = new Client();
        $res = $client->post('http://localhost:8000/books',['json'=>$this->book]);
        $data = json_decode($res->getBody(true), true);
        $this->assertEquals(200, $res->getStatusCode(),'Request failed Response status code :'.$res->getStatusCode());
        $this->assertArrayHasKey('success', $data,"Success response not received");
        $this->assertArrayHasKey('message', $data,"Response message not received");
    }
    public function testGETBook()
    {
        $client = new Client();
        $res = $client->request('GET', 'http://localhost:8000/books/'.$this->book['isbn']);
        $data = json_decode($res->getBody(true), true);
        $this->assertEquals(200, $res->getStatusCode(),'Request failed Response status code :'.$res->getStatusCode());
        $this->assertArrayHasKey('book', $data,"Book parameter missing from response");
        $this->assertArrayHasKey('isbn', $data['book'],"ISBN parameter missing from book data");
        $this->assertArrayHasKey('author', $data['book'],"Author parameter missing from book data");
        $this->assertArrayHasKey('title', $data['book'],"Title parameter missing from book data");
        $this->assertArrayHasKey('release_date', $data['book'],"Release Date parameter missing from book data");
    }
    public function testUpdateBook()
    {
        $updated_book =[
            'title'=>'Updated '.$this->book['title'],
            'author'=>'Updated '.$this->book['author'],
            'isbn'=>'1234567891011',
            'release_date'=>'2012-06-01'
        ];
        $client = new Client();
        $res = $client->post('http://localhost:8000/books/'.$this->book['isbn'],['json'=>$updated_book]);
        $data = json_decode($res->getBody(true), true);
        $this->assertEquals(200, $res->getStatusCode(),'Request failed Response status code :'.$res->getStatusCode());
        $this->assertArrayHasKey('success', $data,"Success response not received");
        $this->assertArrayHasKey('message', $data,"Response message not received");
    }
    public function testDeleteBook()
    {
        $client = new Client();
        $res = $client->post('http://localhost:8000/books/'.$this->book['isbn'].'/delete');
        $data = json_decode($res->getBody(true), true);
        $this->assertEquals(200, $res->getStatusCode(),'Request failed Response status code :'.$res->getStatusCode());
    }

}