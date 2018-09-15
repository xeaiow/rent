<?php

Route::get('/', function () {
    return view('index');
});

Route::post('/set/rental', 'RentController@setRental');

Route::get('/get/rental/{date}/{room}', 'RentController@getRental');

Route::get('/get/user/rental/{token}', 'RentController@getUserRental');

Route::post('/login/status', 'RentController@getLoginStatus');

Route::post ('/login', 'RentController@login');

Route::get('/get/rental/{date}', 'RentController@getAllRental');

/* Admin */

Route::get('/pineapple', 'AdminController@index');

Route::get('/pineapple/get/rental', 'AdminController@getAllRental');

Route::get('/pineapple/get/edit/rental/{id}', 'AdminController@getEditRental');

Route::post('/pineapple/update/rental', 'AdminController@setEditRental');

Route::get('/pineapple/reject/rental/{id}', 'AdminController@setRejectRental');
