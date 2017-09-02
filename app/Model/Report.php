<?php

namespace App\Model;


use App\Helper\Model;

class Report extends Model
{
    public $timestamps = false;
    protected $table = 'report';
    protected $fillable = ['kontrak_id', 'report_classification_id', 'report_param_id'];
}
