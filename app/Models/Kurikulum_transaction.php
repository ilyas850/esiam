<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum_transaction extends Model
{
  protected $table = 'kurikulum_transaction';

  protected $primaryKey = 'idkurtrans';

  public function kurperiode()
  {
    return $this->belongsTo(Kurikulum_periode::class, 'id_makul', 'id_makul');
  }

  // Relasi ke Kurikulum Master
  public function kurikulumMaster()
  {
    return $this->belongsTo(Kurikulum_master::class, 'id_kurikulum', 'id_kurikulum');
  }

   // Relasi ke Prodi
   public function prodi()
   {
       return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
   }

   // Relasi ke Semester
   public function semester()
   {
       return $this->belongsTo(Semester::class, 'id_semester', 'idsemester');
   }

   // Relasi ke Angkatan
   public function angkatan()
   {
       return $this->belongsTo(Angkatan::class, 'id_angkatan', 'idangkatan');
   }

   // Relasi ke Matakuliah
   public function matakuliah()
   {
       return $this->belongsTo(Matakuliah::class, 'id_makul', 'idmakul');
   }

    // Relasi ke Student Record (one to many)
    public function studentRecords()
    {
        return $this->hasMany(Student_record::class, 'id_kurtrans', 'idkurtrans')
                    ->where('status', 'TAKEN');
    }

    // Relasi ke Student Record untuk student tertentu
    public function studentRecord($id_student)
    {
        return $this->hasOne(Student_record::class, 'id_kurtrans', 'idkurtrans')
                    ->where('id_student', $id_student)
                    ->where('status', 'TAKEN');
    }

    // Scope untuk active status
    // public function scopeActive($query)
    // {
    //     return $query->where('status', 'ACTIVE');
    // }

    public function scopeActive($query)
    {
        return $query->where('kurikulum_transaction.status', 'ACTIVE');
    }

    public function scopeByProdi($query, $id_prodi)
    {
        return $query->where('kurikulum_transaction.id_prodi', $id_prodi);
    }

    public function scopeByAngkatan($query, $idangkatan)
    {
        return $query->where('kurikulum_transaction.id_angkatan', $idangkatan);
    }
}
