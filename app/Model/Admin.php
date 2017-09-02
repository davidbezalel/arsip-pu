<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $hidden = ['password', 'remember_token'];
    protected $fillable = ['name', 'adminid', 'password'];
}
