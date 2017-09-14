<?php

namespace App\Model;


use App\Helper\Model;

class PPKAppointment extends Model
{
    public $timestamps = false;
    protected $table = 'ppkappointment';
    protected $fillable = ['ppk_id', 'paket_id'];
}
