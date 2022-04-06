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

class DataTaExport implements FromView, ShouldAutoSize
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
        return view('prausta/export_excel_ta', [
            'cek' => Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
                ->join('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
                ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602'])
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
                ->where('student_record.status', 'TAKEN')
                ->where('id_periodetahun', $this->id1)
                ->where('id_periodetipe', $this->id2)
                ->where('student.kodeprodi', $this->kodeprodi)
                ->where('student.active', 1)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select(
                    'student.nama',
                    'student.nim',
                    'kelas.kelas',
                    'prodi.prodi',
                    'angkatan.angkatan',
                    'prausta_setting_relasi.tempat_prausta',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tanggal_selesai',
                    'prausta_setting_relasi.dosen_pembimbing',
                    'prausta_setting_relasi.dosen_penguji_1',
                    'prausta_setting_relasi.dosen_penguji_2',
                    'prausta_setting_relasi.jam_mulai_sidang',
                    'prausta_setting_relasi.jam_selesai_sidang',
                    'prausta_setting_relasi.ruangan',
                    'prausta_master_kategori.kategori'
                )
                ->get()
        ]);
    }
}
