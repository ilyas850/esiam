<?php

namespace App\Exports;

use App\Student;
use App\Kelas;
use App\Prodi;
use App\Angkatan;
use App\Student_record;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Periode_tahun;
use App\Periode_tipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataPrakerinExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct($id1, $id2, $kodeprodi)
    {

        $this->id1 = $id1;

        $this->id2 = $id2;

        $this->kodeprodi = $kodeprodi;
    }

    public function view(): View
    {
        return view('prausta/export_excel_prakerin', [
            'cek' => Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                })
                ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
                ->join('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->where('kurikulum_periode.id_periodetahun', $this->id1)
                ->where('kurikulum_periode.id_periodetipe', $this->id2)
                ->where('student_record.status', 'TAKEN')
                ->where('student.kodeprodi', $this->kodeprodi)
                ->whereIn('matakuliah.idmakul', [135, 177, 180, 205, 235, 281])
                ->whereNotNull('prausta_setting_relasi.tanggal_selesai')
                ->select(
                    'student.nama',
                    'student.nim',
                    'prodi.prodi',
                    'prausta_setting_relasi.tempat_prausta',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.dosen_pembimbing',
                    'prausta_setting_relasi.dosen_penguji_1',
                    'prausta_setting_relasi.dosen_penguji_2',
                    'prausta_setting_relasi.tanggal_mulai',
                    'prausta_setting_relasi.tanggal_selesai',
                    'prausta_setting_relasi.jam_mulai_sidang',
                    'prausta_setting_relasi.jam_selesai_sidang',
                    'prausta_setting_relasi.ruangan',
                    'prausta_master_kategori.kategori'
                )
                ->orderBy('student.nim', 'ASC')
                ->get()
        ]);
    }
}
