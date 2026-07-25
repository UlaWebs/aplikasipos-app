<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coa extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'header_akun', 
    ];
}