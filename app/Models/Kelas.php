<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
  protected $table = 'kelas';

  protected $primaryKey = 'idkelas';

  // Relasi ke Student
  public function students()
  {
      return $this->hasMany(Student::class, 'idstatus', 'idkelas');
  }
}
