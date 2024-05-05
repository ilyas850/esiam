<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Student extends Model
{

  protected $table = 'student';

  protected $primaryKey = 'idstudent';

  protected $dates = ['tgllahir'];

  public function kelas()
  {
    return $this->BelongsTo(Kelas::class, 'idstatus');
  }

  // protected $dateFormat = 'Y-m-d H:i';
}
