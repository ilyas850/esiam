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
}
