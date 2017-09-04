<?php

namespace App\Model;


use App\Helper\Model;

class KontrakDetail extends Model
{
    public $timestamps = false;
    protected $table = 'kontrakdetail';
    protected $fillable = ['kontrak_id', 'subpaket_id'];
}
