<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
  protected $table = 'semester';

  protected $primaryKey = 'id_semester';

  public function kurikulumTransactions()
    {
        return $this->hasMany(Kurikulum_transaction::class, 'id_semester', 'idsemester');
    }
}
