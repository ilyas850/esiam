<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Student extends Model
{

  protected $table = 'student';

  protected $primaryKey = 'idstudent';

  protected $dates = ['tgllahir'];

  protected $dateFormat = 'Y-m-d H:i';
}
