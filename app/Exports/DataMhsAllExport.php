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

class DataMhsAllExport implements FromView, ShouldAutoSize
{

    use Exportable;

    public function view(): View
    {

        return view('export_excel/datamhs', [
            'val' => DB::select('CALL data_mhs_aktif')
        ]);
    }
}
