<?php

namespace App\Exports;

use App\Beasiswa_trans;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataPengajuanBeasiswa implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct($id_tahun, $id_tipe)
    {

        $this->thn = $id_tahun;

        $this->tp = $id_tipe;
    }

    public function view(): View
    {

        return view('bauk/export/data_pengajuan_beasiswa', [



            'data' =>  Beasiswa_trans::join('student', 'beasiswa_trans.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                })
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('periode_tahun', 'beasiswa_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                ->join('periode_tipe', 'beasiswa_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                ->join('semester', 'beasiswa_trans.id_semester', '=', 'semester.idsemester')
                ->where('beasiswa_trans.status', 'ACTIVE')
                ->where('beasiswa_trans.id_periodetahun',  $this->thn)
                ->where('beasiswa_trans.id_periodetipe', $this->tp)
                ->select(
                    'student.idstudent',
                    'student.nim',
                    'student.nama',
                    'prodi.prodi',
                    'kelas.kelas',
                    'student.tgllahir',
                    'student.tmptlahir',
                    'student.hp',
                    'student.email',
                    'beasiswa_trans.id_trans_beasiswa',
                    'semester.semester',
                    'beasiswa_trans.validasi_bauk',
                    'beasiswa_trans.validasi_wadir3',
                    'beasiswa_trans.status',
                    'periode_tahun.periode_tahun',
                    'periode_tipe.periode_tipe',
                    'beasiswa_trans.ipk'
                )
                ->get()
        ]);
    }
}
