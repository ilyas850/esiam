<?php

namespace App\Exports;

use App\Student;
use App\Kelas;
use App\Prodi;
use App\Angkatan;
use App\Student_record;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Dosen_pembimbing;
use App\Periode_tahun;
use App\Periode_tipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataMhsExport implements FromView, ShouldAutoSize
{
    
    use Exportable;

    public function __construct($nmprd, $nmthun, $nmtp)
    {
        
        $this->nmprd = $nmprd;
        $this->nmthun = $nmthun;
        $this->nmtp = $nmtp;
    }

    public function view(): View
    {
        
        return view('export_excel/datamhs', [           

            'val' => Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                          ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                          ->join('dosen_pembimbing', 'student_record.id_student', 'dosen_pembimbing.id_student')
                          ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                          ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                          ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                          ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                          ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                          ->where('periode_tahun.id_periodetahun', $this->nmthun)
                          ->where('periode_tipe.id_periodetipe', $this->nmtp)
                          ->where('student_record.status', 'TAKEN')
                          ->where('student.active', 1)
                          ->where('student.kodeprodi', $this->nmprd)
                          ->select(DB::raw('DISTINCT(student_record.id_student)'), 'kelas.kelas','student.nim','angkatan.angkatan', 'prodi.prodi', 'student.nama','prodi.kodeprodi')
                          ->orderBy('student.nim', 'ASC')
                          ->orderBy('student.idangkatan', 'ASC')
                          ->get()
        ]);
    }
}
