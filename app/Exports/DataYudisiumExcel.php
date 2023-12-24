<?php

namespace App\Exports;


use App\Yudisium;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataYudisiumExcel implements FromView, ShouldAutoSize
{
    use Exportable;

    public function view(): View
    {
        return view('sadmin/export/data_yudisium_xls', [
            'data' => Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                })
                ->where('student.active', 1)
                ->select(
                    'yudisium.nama_lengkap',
                    'yudisium.tmpt_lahir',
                    'yudisium.tgl_lahir',
                    'yudisium.nik',
                    'student.nim',
                    'prodi.prodi'
                )
                ->orderBy('student.nim', 'ASC')
                ->get()

        ]);
    }
}
