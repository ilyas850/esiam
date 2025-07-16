<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
  protected $table = 'matakuliah';

  protected $primaryKey = 'idmakul';

  public function kurikulumTransactions()
    {
        return $this->hasMany(Kurikulum_transaction::class, 'id_makul', 'idmakul');
    }
}
