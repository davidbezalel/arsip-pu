<?php

namespace App\Model;


use App\Helper\Model;

class Paket extends Model
{
    public $timestamps = false;
    protected $table = 'paket';
    protected $fillable = ['title', 'ismultiyears', 'startyear', 'yearsofwork', 'admin_id', 'endyear'];
}
