<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prausta_trans_penilaian extends Model
{
    protected $table = 'prausta_trans_penilaian';

    protected $primaryKey = 'id_trans_penilaian';

    protected $fillable = [
        'id_settingrelasi_prausta',
        'id_penilaian_prausta',
        'nilai',
        'created_by'
    ];
}
