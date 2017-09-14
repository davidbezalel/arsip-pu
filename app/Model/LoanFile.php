<?php

namespace App\Model;


use App\Helper\Model;

class LoanFile extends Model
{
    public $timestamps = false;
    protected $table = 'loanfile';
    protected $fillable = ['filesubmission_id', 'handledby'];
}
