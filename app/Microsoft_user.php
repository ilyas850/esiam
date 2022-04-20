<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Microsoft_user extends Model
{
    protected $table = 'microsoft_user';

    protected $primaryKey = 'id_microsoft_user';

    protected $fillable = [
        'id_student',
        'username',
        'password'
    ];
}
