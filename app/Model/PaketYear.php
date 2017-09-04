<?php

namespace App\Model;


use App\Helper\Model;

class PaketYear extends Model
{

    public $timestamps = false;
    protected $table = 'paketyear';
    protected $fillable = ['paket_id', 'year'];
}
