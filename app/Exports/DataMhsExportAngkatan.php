<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Angkatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataMhsExportAngkatan implements FromView, ShouldAutoSize
{
    use Exportable;


    public function __construct($angkatan)
    {

        $this->angk = $angkatan;
    }

    public function view(): View
    {
        return view('sadmin/export/data_mhs', [

            'val' => Student::leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->where('student.idangkatan', $this->angk)
                ->select('kelas.kelas', 'student.nim', 'angkatan.angkatan', 'prodi.prodi', 'student.nama', 'prodi.prodi', 'student.idstudent', 'student.intake')
                ->orderBy('student.nim', 'ASC')
                ->orderBy('student.idangkatan', 'ASC')
                ->get()
        ]);
    }
}
