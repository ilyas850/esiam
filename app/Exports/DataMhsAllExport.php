<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Angkatan;
use App\Models\Student_record;
use App\Models\Kurikulum_periode;
use App\Models\Kurikulum_transaction;
use App\Models\Dosen_pembimbing;
use App\Models\Periode_tahun;
use App\Models\Periode_tipe;
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
