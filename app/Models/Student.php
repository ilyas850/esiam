<?php

namespace App\Models;

use App\User;
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

  // public function prodi()
  // {
  //   return $this->belongsTo(Prodi::class, 'kodeprodi', 'kodeprodi')
  //     ->where(function ($query) {
  //       // Jika kodekonsentrasi ada, maka tambahkan dalam where clause
  //       if (!is_null($this->kodekonsentrasi)) {
  //         $query->where('kodekonsentrasi', $this->kodekonsentrasi);
  //       }
  //     });
  // }

  public function dosenPembimbing()
  {

    return $this->belongsTo(DosenPembimbing::class, 'idstudent', 'id_student');
  }

  // Relasi ke User
  public function user()
  {
    return $this->belongsTo(User::class, 'idstudent', 'id_user');
  }

  // Relasi ke Prodi (dengan 2 kondisi)
  public function prodi()
  {
    return $this->belongsTo(Prodi::class, ['kodeprodi', 'kodekonsentrasi'], ['kodeprodi', 'kodekonsentrasi']);
  }

  // Alternatif untuk prodi jika belongsTo dengan multiple key tidak support
  public function prodiRelation()
  {
    return $this->hasOne(Prodi::class)
      ->where('prodi.kodeprodi', '=', $this->attributes['kodeprodi'] ?? '')
      ->where('prodi.kodekonsentrasi', '=', $this->attributes['kodekonsentrasi'] ?? '');
  }

  // Scope untuk active students
  public function scopeActive($query)
  {
      return $query->where('active', 1);
  }

}
