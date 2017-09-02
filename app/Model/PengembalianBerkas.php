<?php

namespace App\Model;


use App\Helper\Model;

class PengembalianBerkas extends Model
{
    public $timestamps = false;
    protected $table = 'pengembalian_berkas';
    protected $fillable = ['peminjaman_berkas_id', 'handled_by'];
}
