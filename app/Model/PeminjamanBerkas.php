<?php

namespace App\Model;


use App\Helper\Model;

class PeminjamanBerkas extends Model
{
    public $timestamps = false;
    protected $table = 'peminjaman_berkas';
    protected $fillable = ['document_report_id', 'handled_by'];
}
