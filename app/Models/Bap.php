<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bap extends Model
{
    protected $table = 'bap';

    protected $primaryKey = 'id_bap';

    protected $dates = ['tanggal'];
}
