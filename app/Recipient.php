<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $table = 'recipient';

    protected $fillable = ['name', 'email', 'switch'];
}
