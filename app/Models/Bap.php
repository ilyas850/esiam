<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bap extends Model
{
    protected $table = 'bap';

    protected $primaryKey = 'id_bap';

    protected $dates = ['tanggal'];

    protected $fillable = [
        'id_kurperiode',
        'id_tipekuliah',
        'id_dosen',
        'pertemuan',
        'id_bap',
        'materi_kuliah',
        'waktu',
        'tanggal',
        'jam_mulai',
        'jam_selsai',
        'jenis_kuliah',
        'metode_kuliah',
        'praktikum',
        'media_pembelajaran',
        'link_materi',
        'id_rps',
        'alasan_pembaharuan_materi',
    ];
}
