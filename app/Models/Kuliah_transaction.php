<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuliah_transaction extends Model
{
    protected $table = 'kuliah_transaction';

    protected $primaryKey = 'id_kultrans';

    protected $fillable = [
        'id_kurperiode',
        'id_dosen',
        'id_tipekuliah',
        'tanggal',
        'akt_jam_mulai',
        'akt_jam_selesai',
        'id_bap',
    ];
}
