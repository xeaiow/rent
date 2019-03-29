<?php

use App\Recipient;

Route::get('/', function () {
    return view('index');
});

Route::post('/set/rental', 'RentController@setRental');

Route::get('/get/rental/{date}/{room}', 'RentController@getRental');

Route::get('/get/user/rental/{token}', 'RentController@getUserRental');

Route::post('/set/remove/user/rental', 'RentController@setRemoveUserRental');

Route::post('/login/status', 'RentController@getLoginStatus');

Route::post('/login', 'RentController@login');

Route::get('/get/rental/{date}', 'RentController@getAllRental');

/* Admin */

Route::group(['prefix' => '/', 'middleware' => 'notAdmin'], function () {

    Route::post('/pineapple/login', 'AdminController@loginHandle');
    Route::get('/pineapple/login', 'AdminController@loginPage');
});

Route::group(['prefix' => '/', 'middleware' => 'admin'], function () {

    Route::get('/pineapple', 'AdminController@index');
    Route::get('/pineapple/get/rental', 'AdminController@getAllRental');
    Route::get('/pineapple/get/edit/rental/{id}', 'AdminController@getEditRental');
    Route::get('/pineapple/get/statistics', 'AdminController@statistics');
    Route::post('/pineapple/update/rental', 'AdminController@setEditRental');
    Route::get('/pineapple/reject/rental/{id}', 'AdminController@setRejectRental');
    Route::post('/pineapple/add/rental', 'AdminController@setRental');
    Route::get('/pineapple/logout', 'AdminController@logout');
    Route::post('/pineapple/add/recipient', 'AdminController@setRecipient');
    Route::get('/pineapple/get/recipient', 'AdminController@getRecipient');
});

Route::get('/excel', 'AdminController@excel');

Route::get('sendmail', function () {

    $users = Recipient::where('switch', 1)->get();
        
        foreach ($users as $key => $value)
        {
            $name = $value['name'];
            $email = $value['email'];

            $data = [
                'name' => '嗨~'.$name.'，已完成今日的教室預約系統的資料備份。'
            ];

            Mail::send('email.welcome', $data, function ($message) use ($email) {
                $message->to($email)->subject('中原資管教室預約資料庫備份')->attach(storage_path('app\backups\rent.sql'));
            });
        }

        
        return 'Your email has been sent successfully!';
});

Route::get('/exe', 'AdminController@exe');