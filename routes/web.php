<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('index');
});

Route::post('/set/rental', 'RentController@setRental');

Route::get('/get/rental/{date}/{room}', 'RentController@getRental');

Route::get('/get/rental/{user}', 'RentController@getUserRental');

Route::post('/login/status', 'RentController@getLoginStatus');

Route::post('/login', 'RentController@login');
