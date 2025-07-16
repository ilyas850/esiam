<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurikulum_master extends Model
{
  protected $table = 'kurikulum_master';

  protected $primaryKey = 'id_kurikulum';

  public function kurikulumTransactions()
  {
    return $this->hasMany(Kurikulum_transaction::class, 'id_kurikulum', 'id_kurikulum');
  }
}
