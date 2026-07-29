<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sk_pengajaran extends Model
{
    protected $table = 'sk_pengajaran';

    protected $primaryKey = 'id_sk_pengajaran';

    protected $fillable = [
        'id_periodetahun',
        'id_periodetipe',
        'kodeprodi',
        'file',
        'status',
        'created_by'
    ];
}
