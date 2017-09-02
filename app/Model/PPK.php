<?php

namespace App\Model;


use App\Helper\Model;

class PPK extends Model
{
    public $timestamps = false;
    protected $table = 'ppk';
    protected $fillable = ['companyname', 'ppkname', 'companyleader'];
}
