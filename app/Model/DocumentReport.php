<?php

namespace App\Model;


use App\Helper\Model;

class DocumentReport extends Model
{
    public $timestamps = false;
    protected $table = 'document_report';
    protected $fillable = ['report_id', 'handled_by'];
}
