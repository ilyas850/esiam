<?php

namespace App\Exports;

use App\Student;
use App\Kelas;
use App\Prodi;
use App\Angkatan;
use App\Student_record;
use App\Kurikulum_periode;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataNilaiExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        return view('export_excel/nilai', [

            'mhs' => Student::all(),
            'prd' => Prodi::all(),
            'kls' => Kelas::all(),
            'angk' => Angkatan::all(),
            //cek mahasiswa
            'ck' => Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->where('id_kurperiode', $this->id)
                                ->where('student_record.status','TAKEN')
                                ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                                ->orderBy('student.nim', 'ASC')
                                ->get()
        ]);
    }
}
