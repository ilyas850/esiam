<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Student extends Model
{

  protected $table = 'student';

  protected $primaryKey = 'idstudent';

  protected $dates = ['tgllahir'];

  public function kelas()
  {
    return $this->BelongsTo(Kelas::class, 'idstatus', 'idkelas');
  }

  public function student_records()
  {
    return $this->hasMany(Student_record::class, 'id_student', 'idstudent');
  }

  public function angkatan()
  {
    return $this->BelongsTo(Angkatan::class, 'idangkatan', 'idangkatan');
  }

  public function prodi()
  {
    return $this->belongsTo(Prodi::class, 'kodeprodi', 'kodeprodi')
      ->where(function ($query) {
        // Jika kodekonsentrasi ada, maka tambahkan dalam where clause
        if (!is_null($this->kodekonsentrasi)) {
          $query->where('kodekonsentrasi', $this->kodekonsentrasi);
        }
      });
  }

  public function dosenPembimbing()
  {
    
    return $this->belongsTo(DosenPembimbing::class, 'idstudent', 'id_student');
  }
}
