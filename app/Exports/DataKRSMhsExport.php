<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Angkatan;
use App\Models\Student_record;
use App\Models\Kurikulum_periode;
use App\Models\Kurikulum_transaction;
use App\Models\Periode_tahun;
use App\Models\Periode_tipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataKRSMhsExport implements FromView, ShouldAutoSize
{

    use Exportable;

    public function __construct($prd, $ta, $tp, $kd)
    {

        $this->prd = $prd;

        $this->ta = $ta;

        $this->tp = $tp;

        $this->kd = $kd;
    }

    public function view(): View
    {

        return view('export_excel/datanilaikhs', [



            'nilai' => Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                ->where('kurikulum_periode.id_periodetahun', $this->ta)
                ->where('kurikulum_periode.id_periodetipe', $this->tp)
                ->where('kurikulum_periode.id_prodi', $this->prd)
                ->where('student_record.status', 'TAKEN')
                ->where('student.kodeprodi', $this->kd)
                ->select('prodi.prodi', 'kelas.kelas', 'student.nim', 'student.nama', 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as akt_sks_hasil'))
                ->get()
        ]);
    }
}
