<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prausta_trans_hasil extends Model
{
  protected $table = 'prausta_trans_hasil';

  protected $primaryKey = 'id_transhasil_prausta';

  protected $fillable = [
    'id_settingrelasi_prausta',
    'nilai_1',
    'nilai_huruf',
    'added_by',
    'status',
    'data_origin'
  ];
}
