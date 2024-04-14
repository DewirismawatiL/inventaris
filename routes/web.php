<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


// STUFF
// struktur pemanggilan route-> method('/namapath', 'NamaController@namaFunction');
// trash untuk menampilkan data yang udah hapus
// route diurutkan berdasarkan path yang tidak dinamis lalu yang dinamis, diurutkan dengan garis miringnya dari terkecil
$router->get('/stuffs', 'StuffController@index');
$router->post('/stuffs/store', 'StuffController@store');
$router->get('/stuffs/trash', 'StuffController@trash');

$router->get('/stuffs/{id}', 'StuffController@show');
$router->patch('/stuffs/update/{id}', 'StuffController@update');
$router->delete('/stuffs/delete/{id}', 'StuffController@destroy');
// softdeletes : trash, restore, undo
$router->get('/stuffs/trash/restore/{id}', 'StuffController@restore');
$router->get('/stuffs/trash/permanen-delete/{id}', 'StuffController@permanenDelete');
 
$router->get('/user', 'UserController@index');
$router->post('/user/store', 'UserController@store');
$router->get('/user/trash', 'UserController@trash');

$router->get('/user/{id}', 'UserController@show');
$router->delete('/user/delete/{id}', 'UserController@destroy');
$router->get('/user/trash/restore/{id}', 'UserController@restore');
$router->get('/user/trash/permanen-delete/{id}', 'UserController@permanenDelete');

$router->get('/inbound-stuffs/data', 'InboundStuffController@index');
$router->post('/inbound-stuffs/store','InboundStuffController@store');
$router->post('/inbound-stuffs/delete/{id}','InboundStuffController@destroy');
$router->get('/inbound-stuffs/trash', 'InboundStuffController@trash');
$router->get('/restore/{id}', 'InboundStuffController@restore');
$router->delete('/inbound-stuffs/permanent-delete/{id}', 'InboundStuffController@permanentDelete');