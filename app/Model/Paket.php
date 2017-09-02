<?php

namespace App\Model;


use App\Helper\Model;

class Paket extends Model
{
    public $timestamps = false;
    protected $table = 'paket';
    protected $fillable = ['title', 'year', 'companyprovider'];
}
