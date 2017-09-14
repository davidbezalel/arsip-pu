<?php

namespace App\Model;


use App\Helper\Model;

class Report extends Model
{
    public $timestamps = false;
    protected $table = 'report';
    protected $fillable = ['subpaket_id', 'reportparam_id', 'title'];
}
