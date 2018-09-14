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
