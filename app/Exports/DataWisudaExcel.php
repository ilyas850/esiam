<?php

namespace App\Exports;

use App\Wisuda;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataWisudaExcel implements FromView, ShouldAutoSize
{
    use Exportable;

    public function view(): View
    {
        return view('sadmin/export/data_wisuda_xls', [
        'data' => Wisuda::join('student', 'wisuda.id_student', '=', 'student.idstudent')
            ->leftjoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.active', 1)
            ->select(
                'wisuda.id_wisuda',
                'wisuda.nama_lengkap',
                'wisuda.nim',
                'wisuda.tahun_lulus',
                'wisuda.ukuran_toga',
                'wisuda.no_hp',
                'wisuda.email',
                'wisuda.nik',
                'wisuda.alamat_ktp',
                'wisuda.alamat_domisili',
                'wisuda.nama_ayah',
                'wisuda.nama_ibu',
                'wisuda.no_hp_ayah',
                'wisuda.no_hp_ibu',
                'wisuda.alamat_ortu',
                'wisuda.status_vaksin',
                'wisuda.file_foto',
                'wisuda.npwp',
                'wisuda.validasi',
                'wisuda.id_student',
                'wisuda.id_prodi',
                'prodi.prodi',
                'kelas.kelas',
                'wisuda.tempat_kerja'
            )
            ->get()
        ]);
    }
}
