<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Update_mahasiswa extends Model
{
  use SoftDeletes;
  protected $datas = ['deleted_at'];

  protected $table = 'update_mahasiswas';
}
