<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';
    protected $fillable = ['user_id' , 'code' , 'expire_at']; 
}
