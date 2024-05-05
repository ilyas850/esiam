<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Ujian_transaction extends Model
{
  protected $table = 'ujian_transaction';

  protected $primaryKey = 'id_ujiantrans';

  public function getCreateAttribute()
  {
    return Carbon::parse($this->attributes['tanggal_ujian'])->translatedFormat('l, d F Y');
  }
}
