<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student_record extends Model
{
  protected $table = 'student_record';

  protected $primaryKey = 'id_studentrecord';

  public function student()
  {
    return $this->belongsTo(Student::class, 'idstudent', 'id_student');
  }

  public function kurperiode()
  {
    return $this->belongsTo(Kurikulum_periode::class, 'id_kurperiode', 'id_kurperiode');
  }

  public function kurtrans()
  {
    return $this->belongsTo(Kurikulum_transaction::class, 'idkurtrans', 'id_kurtrans');
  }
}
