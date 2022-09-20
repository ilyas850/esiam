<?php

namespace App\Exports;

use App\Student;
use App\Kelas;
use App\Prodi;
use App\Angkatan;
use App\Student_record;
use App\Kurikulum_periode;
use Illuminate\Support\Facades\DB;
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
            'ck' => DB::select("CALL absensi_mahasiswa_prodi_kelas(?)", array($this->id))
            
        ]);
    }
}
