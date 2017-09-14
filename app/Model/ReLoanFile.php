<?php

namespace App\Model;


use App\Helper\Model;

class ReLoanFile extends Model
{
    public $timestamps = false;
    protected $table = 'reloanfile';
    protected $fillable = ['loanfile_id', 'handledby'];
}
