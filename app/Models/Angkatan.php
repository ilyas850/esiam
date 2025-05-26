<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
  protected $table = 'angkatan';

  protected $primaryKey = 'idangkatan';

  public $timestamps = false;

  protected $fillable = ['idangkatan', 'angkatan'];

  // Relasi ke Student
  public function students()
  {
      return $this->hasMany(Student::class, 'idangkatan', 'idangkatan');
  }
}
