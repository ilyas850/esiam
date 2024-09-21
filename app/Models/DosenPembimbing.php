<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenPembimbing extends Model
{
    protected $table = 'dosen_pembimbing';

    protected $primaryKey = 'id';

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'iddosen');
    }
}
