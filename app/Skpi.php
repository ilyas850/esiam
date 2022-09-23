<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skpi extends Model
{
    protected $table = 'skpi';

    protected $primaryKey = 'id_skpi';

    protected $dates = ['date_masuk', 'date_lulus'];
}
