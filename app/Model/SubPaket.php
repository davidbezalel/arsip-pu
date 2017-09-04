<?php

namespace App\Model;


use App\Helper\Model;

class SubPaket extends Model
{

    public $timestamps = false;
    protected $table = 'subpaket';
    protected $fillable = ['title', 'paket_id', 'reporttype_id'];
}
