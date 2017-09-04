<?php

namespace App\Model;


use App\Helper\Model;

class ReportClassification extends Model
{

    /**
     * @var String type
     */
    public static $utama = 'Utama';

    public $timestamps = false;
    protected $table = 'report_classification';
}
