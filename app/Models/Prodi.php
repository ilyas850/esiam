<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
  protected $table = 'prodi';

  protected $primaryKey = 'id_prodi';

  public function students()
  {
    return $this->hasMany(Student::class, 'kodeprodi', 'kodeprodi')
      ->where(function ($query) {
        // Jika prodi memiliki konsentrasi, maka relasikan dengan student
        if (!is_null($this->kodekonsentrasi)) {
          $query->where('kodekonsentrasi', $this->kodekonsentrasi);
        }
      });
  }
}
