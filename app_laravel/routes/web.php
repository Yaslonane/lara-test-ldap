<?php

use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('about', function () {
    echo "Hello, this ABOUT";
});
Route::get('post/{id}', function($id){
    return $id;
});
Route::get('post/{id}/edit', function($id){
    return "Editing post to: ".$id;
});
Route::get('user/{id?}', function(Request $request, $id = 1){  //не явный параметр после явного, Всегда !!!!
    echo $request->path()."<br />";
    return "user id=".$id;
//})->where('id', '[0-9]+'); //ограничевает значение параметров по маске регулярки
})->whereNumber('id');

/*Route::prefix('admin')->group(function(){ //группа роутов
    Route::get('/', function(){
        return "admin";
    });
    Route::any('post/{id?}', function($id=0){
        return ($id==0) ? "нет записей!" : "admin->user->".$id;
    })->whereNumber('id');
    Route::match(['get', 'post'],'post/{id}/edit', function($id){
        return "admin->user->".$id."->редактирование!";
    })->whereNumber('id');
});*/
Route::redirect('users', 'user');



Route::match(['get', 'post'], '/products', function () {
    return "products page";
})->middleware('throttle:test'); //ограничение количества запросов на данный урл. Прописывается в RouteServiceProvider.php

Route::any('product/{id?}/{comment?}', function($id=0, $comment="no comment"){
    return "product id:".$id." comment: ".$comment.".";
});

/*Route::prefix('admin')->group(function(){
    Route::match(['get', 'post'], '/', function () {
        return "admin->index";
    });
    Route::match(['get', 'post'], '/auth', function () {
        return "admin->auth";
    });
    Route::match(['get', 'post'], '/products', function () {
        return "admin->products";
    });
    Route::match(['get', 'post'], '/clients', function () {
        return "admin->clients";
    });
});*/

Route::group(['prefix'=>'admin', 'middleware'=>'throttle:test'],function(){ //ограничение количества запросов на данную группу. Прописывается в RouteServiceProvider.php
    Route::match(['get', 'post'], '/', function () {
        return "admin->index";
    });
    Route::match(['get', 'post'], '/auth', function () {
        return "admin->auth";
    });
    Route::match(['get', 'post'], '/products', function () {
        return "admin->products";
    });
    Route::match(['get', 'post'], '/clients', function () {
        return "admin->clients";
    });
});

Route::any('secretpage', function(){
    return 'secretpage';
})->middleware('checklocalhost');

Route::any('inforequest', function(Request $request){
    return var_dump($request);
});

Route::any('home', [MainController::class, 'home']);
Route::any('map', [MainController::class, 'map']);

Route::any('message/{id}', [MainController::class, 'message']);

Route::any('request', MainController::class);

Route::get('/vvod', function () {
    return "vvod";
})->middleware('throttle:key');

Route::any('testview/', [MainController::class, 'testview']);

Route::any('testblade/', [MainController::class, 'testBlade']);

Route::any('extendsview/', [MainController::class, 'extendView']);

Route::any('testcomponents/', [MainController::class, 'testComponents']);

Route::any('testexception', [MainController::class, 'testException']);

Route::any('testlog', [MainController::class, 'testLog']);
