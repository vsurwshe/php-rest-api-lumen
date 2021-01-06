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

$router->get('/test','UsersController@test');

// this group will used for without any middelware
$router->group(['prefix'=>'api'],function($router){
    $router->post('login',['uses' => 'AuthController@login']);
    $router->post('register','AuthController@register');
});

$router->group(['prefix'=>'api', 'middleware' => 'auth'],function($router){
    // this routes will used for users realted api
    $router->group(['prefix'=>'users'],function($router){
        $router->get('getAllUsers','UsersController@getAllUsers');
        $router->get('getUsers/{id}','UsersController@getUsersById');
        $router->post('saveUsers','UsersController@create');
        $router->put('updateUsers/{id}','UsersController@updateUser');
        $router->delete('deleteUsers/{id}','UsersController@deleteUser');
    });

    // this routes will used for store realted api
    $router->group(['prefix'=>'store'],function($router){
        $router->get('getAllStoreProduct','StoreController@getAllStoreElements');
        $router->get('getStoreProduct/{id}','StoreController@getUsersById');
        $router->post('saveStoreProduct','StoreController@saveStoreElementRecord');
        $router->put('updateStoreProduct/{productId}','StoreController@updateStoreElementRecord');
        $router->delete('deleteStoreProduct/{productId}','StoreController@deleteStoreElementRecord');
    });

    // this routes will used for table api
    $router->group(['prefix'=>'hotelTable'],function($router){
        $router->get('list', 'HotelTableController@show');
        $router->post('save', 'HotelTableController@store');
        $router->put('update/{tableId}', 'HotelTableController@update');
        $router->delete('delete/{tableId}', 'HotelTableController@destroy');
    });

    // this routes will used for products realted api
    $router->group(['prefix'=>'products'],function($router){
        $router->get('getAllProducts','UsersController@getAllUsers');
        $router->get('getProduct/{id}','UsersController@getUsersById');
        $router->post('saveProduct','UsersController@create');
        $router->put('updateProduct/{id}','UsersController@updateUser');
        $router->delete('deleteProduct/{id}','UsersController@deleteUser');
    });

    // this routes will used for food api
    $router->group(['prefix'=>'food'],function($router){
        $router->get('list', 'FoodController@show');
        $router->post('save', 'FoodController@saveFoodElementRecord');
        $router->put('update/{foodId}', 'FoodController@update');
        $router->delete('delete/{foodId}', 'FoodController@destroy');
    });

    // this routes will used for invoice api
    $router->group(['prefix'=>'invoice'],function($router){
        $router->get('list', 'InvoiceController@show');
        $router->get('getData/{invoiceId}', 'InvoiceController@getInvoiceById');
        $router->post('save', 'InvoiceController@saveInvoiceElementRecord');
        $router->put('update/{invoiceId}', 'InvoiceController@update');
        $router->delete('delete/{invoiceId}', 'InvoiceController@destroy');
    });

    // this routes will used for invoice api
    $router->group(['prefix'=>'orders/table'],function($router){
        $router->get('freeTabelList', 'FreeTabelController@show');
        $router->get('bookedTabelList', 'BookedTabelController@show');
        $router->post('save', 'BookedTabelController@store');
        $router->put('update/{orderTabelId}', 'BookedTabelController@update');
        $router->delete('delete/{orderTabelId}', 'BookedTabelController@destroy');
    });

    // this routes will used for invoice api
    $router->group(['prefix'=>'orders/food'],function($router){
        $router->get('list/{orderTbleId}', 'OrderFoodController@getOrdersByTabelId');
        $router->post('save', 'OrderFoodController@store');
        $router->put('update/{orderFoodId}', 'OrderFoodController@update');
        $router->delete('delete/{orderFoodId}', 'OrderFoodController@destroy');
    });
});

