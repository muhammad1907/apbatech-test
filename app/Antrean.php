<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{

    protected $table = 'antriansoal';
    protected $primaryKey = 'nomorkartu';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nomorantrean',
        'angkaantrean',
        'norm',
        'namapoli',
        'kodepoli',
        'tglpriksa',
        'nomorkartu',
        'nik',
        'keluhan',
        'statusdipanggil'
    ];
}
