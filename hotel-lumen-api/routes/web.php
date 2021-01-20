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
        $router->post('updateUsers/{id}','UsersController@updateUser');
        $router->get('deleteUsers/{id}','UsersController@deleteUser');
    });

    // this routes will used for store realted api
    $router->group(['prefix'=>'store'],function($router){
        $router->get('getAllStoreProduct','StoreController@getAllStoreElements');
        $router->get('getStoreProduct/{id}','StoreController@getUsersById');
        $router->post('saveStoreProduct','StoreController@saveStoreElementRecord');
        $router->post('updateStoreProduct/{productId}','StoreController@updateStoreElementRecord');
        $router->get('deleteStoreProduct/{productId}','StoreController@deleteStoreElementRecord');
    });

    // this routes will used for table api
    $router->group(['prefix'=>'hotelTable'],function($router){
        $router->get('list', 'HotelTableController@show');
        $router->post('save', 'HotelTableController@store');
        $router->post('update/{tableId}', 'HotelTableController@update');
        $router->get('delete/{tableId}', 'HotelTableController@destroy');
    });

    // this routes will used for products realted api
    $router->group(['prefix'=>'products'],function($router){
        $router->get('getAllProducts','UsersController@getAllUsers');
        $router->get('getProduct/{id}','UsersController@getUsersById');
        $router->post('saveProduct','UsersController@create');
        $router->post('updateProduct/{id}','UsersController@updateUser');
        $router->get('deleteProduct/{id}','UsersController@deleteUser');
    });

    // this routes will used for food api
    $router->group(['prefix'=>'food'],function($router){
        $router->get('list', 'FoodController@show');
        $router->post('save', 'FoodController@saveFoodElementRecord');
        $router->post('update/{foodId}', 'FoodController@update');
        $router->get('delete/{foodId}', 'FoodController@destroy');
    });

    // this routes will used for invoice api
    $router->group(['prefix'=>'invoice'],function($router){
        $router->get('list', 'InvoiceController@show');
        $router->get('getData/{invoiceId}', 'InvoiceController@getInvoiceById');
        $router->post('save', 'InvoiceController@saveInvoiceElementRecord');
        $router->post('update/{invoiceId}', 'InvoiceController@update');
        $router->get('delete/{invoiceId}', 'InvoiceController@destroy');
    });

    // this routes will used for invoice api
    $router->group(['prefix'=>'orders/table'],function($router){
        $router->get('freeTabelList', 'FreeTabelController@show');
        $router->get('bookedTabelList', 'BookedTabelController@show');
        $router->post('save', 'BookedTabelController@store');
        $router->post('update/{orderTabelId}', 'BookedTabelController@update');
        $router->get('delete/{orderTabelId}', 'BookedTabelController@destroy');
    });

    // this routes will used for invoice api
    $router->group(['prefix'=>'orders/food'],function($router){
        $router->get('list/{orderTbleId}', 'OrderFoodController@getOrdersByTabelId');
        $router->post('save', 'OrderFoodController@store');
        $router->post('update/{orderFoodId}', 'OrderFoodController@update');
        $router->get('delete/{orderFoodId}', 'OrderFoodController@destroy');
    });

    // this routes will used for invoice api
    $router->group(['prefix'=>'room'],function($router){
        $router->get('list', 'RoomController@getListOfRoom');
        $router->get('list/bookedRoom', 'RoomController@getListOfBookedRoom');
        $router->get('list/freeRoom', 'RoomController@getListOfFreeRoom');
        $router->get('list/todayCheckOutRooms', 'RoomController@getListOfTodayCheckOutRoom');
        $router->get('list/customer', 'RoomController@getTotalCountOfCustomer');
        $router->get('getBookingDetails/{roomBookingId}', 'RoomController@getRoomBookingDetails');
        $router->post('saveRoomBooking', 'RoomController@saveBookRoomDetails');
        $router->post('updateRoomBooking/{roomBookingId}', 'RoomController@updateBookRoomDetails');
        $router->get('deleteRoomBooking/{roomBookingId}', 'RoomController@deleteBookRoomDetails');
        // -------------- Room releated service
        $router->get('getDetails/{roomId}', 'RoomController@getRoomDetails');
        $router->post('saveRoom', 'RoomController@saveRoomDetails');
        $router->post('updateRoom/{roomId}', 'RoomController@updateRoomDetails');
        $router->get('deleteRoom/{roomId}', 'RoomController@deleteRoomDetails');
    });
});

