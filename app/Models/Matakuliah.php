<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
  protected $table = 'matakuliah';

  protected $primaryKey = 'idmakul';

  public $timestamps = false;

  protected $fillable = [
    'kode',
    'makul',
    'akt_sks_teori',
    'akt_sks_praktek',
    'active',
  ];

  public function kurikulumTransactions()
    {
        return $this->hasMany(Kurikulum_transaction::class, 'id_makul', 'idmakul');
    }
}
