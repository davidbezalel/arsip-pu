<?php

namespace App\Model;


use App\Helper\Model;

class SubPaket extends Model
{

    public static $utama = "Utama";
    public static $bulanan = "Bulanan";

    public $timestamps = false;
    protected $table = 'subpaket';
    protected $fillable = ['title', 'paket_id', 'type'];
}
