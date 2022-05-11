<?php

namespace App\Exports;

use App\Prausta_setting_relasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class DataBimbinganTaExport implements FromView, ShouldAutoSize
{
    use Exportable;
    public function __construct($kode)
    {

        $this->kode = $kode;
    }

    public function view(): View
    {
        return view('kaprodi/monitoring/export_excel_bim_ta', [
            'data' => Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
                ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
                ->where('student.active', 1)
                ->where('student.kodeprodi', $this->kode)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select(
                    DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                    'student.nama',
                    'student.nim',
                    'prodi.prodi',
                    'kelas.kelas',
                    'angkatan.angkatan',
                    'prausta_setting_relasi.id_settingrelasi_prausta',
                    'prausta_setting_relasi.dosen_pembimbing'
                )
                ->groupBy(
                    'student.nama',
                    'prausta_setting_relasi.id_settingrelasi_prausta',
                    'student.nim',
                    'prodi.prodi',
                    'kelas.kelas',
                    'angkatan.angkatan',
                    'prausta_setting_relasi.dosen_pembimbing'
                )
                ->orderBy('student.nim', 'DESC')
                ->get()
        ]);
    }
}
