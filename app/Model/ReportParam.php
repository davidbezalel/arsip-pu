<?php

namespace App\Model;


use App\Helper\Model;

class ReportParam extends Model
{

    /**
     * @var String type
     */
    public static $pokok = "POKOK";
    public static $bulanan = 'BULANAN';

    public $timestamps = false;
    protected $table = 'report_param';
}
