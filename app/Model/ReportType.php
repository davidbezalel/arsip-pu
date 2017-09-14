<?php

namespace App\Model;


use App\Helper\Model;

class ReportType extends Model
{
    public $timestamps = false;
    protected $table = 'reporttype';

    public static function getutamaid () {
        $id = self::where('title', '=', 'Utama')->get(['id'])->first();
        return $id['id'];
    }

    public static function getmcid () {
        $id = self::where('title', '=', 'MC')->get(['id'])->first();
        return $id['id'];
    }
}
