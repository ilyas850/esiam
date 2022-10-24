<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Waktu extends Model
{
    protected $table = 'waktu';

  protected $primaryKey = 'id_waktu';

  protected $dates = ['waktu_awal', 'waktu_akhir'];
}
