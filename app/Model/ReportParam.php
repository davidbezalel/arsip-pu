<?php

namespace App\Model;


use App\Helper\Model;

class ReportParam extends Model
{

    /**
     * @var String type
     */
    public static $utama = "Utama";
    public static $bulanan = 'BULANAN';

    public $timestamps = false;
    protected $table = 'report_param';
}
