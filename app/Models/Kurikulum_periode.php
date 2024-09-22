<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum_periode extends Model
{
  protected $table = 'kurikulum_periode';

  protected $primaryKey = 'id_kurperiode';

  public function tahun()
  {
    return $this->belongsTo(Periode_tahun::class, 'id_periodetahun', 'id_periodetahun');
  }

  public function tipe()
  {
    return $this->belongsTo(Periode_tipe::class, 'id_periodetipe', 'id_periodetipe');
  }

  public function makul()
  {
    return $this->belongsTo(Matakuliah::class, 'id_makul', 'idmakul');
  }

  public function dosen()
  {
    return $this->belongsTo(Dosen::class, 'id_dosen', 'iddosen');
  }

  public function kurtrans()
  {
    return $this->hasOne(Kurikulum_transaction::class, 'id_makul', 'id_makul');
  }

  public function semester ()
  {
    return $this->belongsTo(Semester::class, 'id_semester', 'idsemester');
  }

  public function kelas()
  {
    return $this->belongsTo(Kelas::class, 'id_kelas', 'idkelas');  
  }
}
