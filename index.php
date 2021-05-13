<?php
require __DIR__ . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;
use App\Controllers\BooksController;

Router::get('/', function () {
    echo 'Library : Books CRUD';
});

Router::get('/books', function (Request $req, Response $res) {
    (new BooksController())->read($res);
});
Router::get('/books/([0-9]*)', function (Request $req, Response $res) {
    (new BooksController())->readSingle($req,$res);
});
Router::post('/books', function (Request $req, Response $res) {
    (new BooksController())->store($req,$res);
});
Router::post('/books/([0-9]*)', function (Request $req, Response $res) {
    (new BooksController())->update($req,$res);
});
Router::post('/books/([0-9]*)/delete', function (Request $req, Response $res) {
    (new BooksController())->delete($req,$res);
});
App::run();