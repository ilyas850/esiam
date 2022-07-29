<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Yudisium extends Model
{
    protected $table = 'yudisium';

    protected $primaryKey = 'id_yudisium';

    protected $dates = ['tgl_lahir'];
}
