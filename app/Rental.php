<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $table = 'rental';

    protected $fillable = ['title', 'description', 'room', 'period'];
}
