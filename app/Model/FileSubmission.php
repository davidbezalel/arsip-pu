<?php

namespace App\Model;


use App\Helper\Model;

class FileSubmission extends Model
{
    public $timestamps = false;
    protected $table = 'filesubmission';
    protected $fillable = ['report_id', 'handledby'];
}
