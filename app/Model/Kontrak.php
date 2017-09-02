<?php

namespace App\Model;


use App\Helper\Model;

class Kontrak extends Model
{
    public $timestamps = false;
    protected $table = 'kontrak';
    protected $fillable = ['ppk_id', 'paket_id'];
}
