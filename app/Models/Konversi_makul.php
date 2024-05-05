<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Konversi_makul extends Model
{
    protected $table = 'konversi_makul';

    protected $casts = [
        'created_at' => 'datetime:' . self::DATETIME_FORMAT,
        'updated_at' => 'datetime:' . self::DATETIME_FORMAT,
    ];

    protected const DATETIME_FORMAT = 'Y-m-d H:i:s';

    public function getDateFormat()
    {
        return self::DATETIME_FORMAT;
    }
}
