<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = ['account', 'password', 'remember_token', 'status'];
}
