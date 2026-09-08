<?php

namespace App\Exports;

use App\Models\Kurikulum_periode;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataJadwalExport implements FromView, ShouldAutoSize
{
    use Exportable;

    protected $id_periodetahun;
    protected $id_periodetipe;
    protected $id_prodi;
    protected $namaperiodetahun;
    protected $namaperiodetipe;
    protected $namaprodi;

    public function __construct($id_periodetahun, $id_periodetipe, $id_prodi = null, $namaperiodetahun = '', $namaperiodetipe = '', $namaprodi = 'Semua Program Studi')
    {
        $this->id_periodetahun = $id_periodetahun;
        $this->id_periodetipe = $id_periodetipe;
        $this->id_prodi = $id_prodi;
        $this->namaperiodetahun = $namaperiodetahun;
        $this->namaperiodetipe = $namaperiodetipe;
        $this->namaprodi = $namaprodi;
    }

    public function view(): View
    {
        $query = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->leftJoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->leftJoin('kurikulum_hari', 'kurikulum_hari.id_hari', '=', 'kurikulum_periode.id_hari')
            ->leftJoin('kurikulum_jam', 'kurikulum_jam.id_jam', '=', 'kurikulum_periode.id_jam')
            ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'kurikulum_periode.id_ruangan')
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('kurikulum_periode.id_periodetahun', $this->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $this->id_periodetipe);

        if (!empty($this->id_prodi) && $this->id_prodi !== 'all') {
            $prodiVal = $this->id_prodi;
            $query->where(function ($q) use ($prodiVal) {
                $q->where('kurikulum_periode.id_prodi', $prodiVal)
                  ->orWhere('prodi.kodeprodi', $prodiVal);
            });
        }

        $data = $query->select(
            DB::raw('MIN(kurikulum_periode.id_kurperiode) as id_kurperiode'),
            DB::raw('GROUP_CONCAT(DISTINCT kurikulum_periode.id_kurperiode) as ids_kurperiode'),
            'matakuliah.kode',
            'matakuliah.makul',
            DB::raw('COALESCE(NULLIF(matakuliah.set_sks_teori, 0), matakuliah.akt_sks_teori, 0) as sks_teori'),
            DB::raw('COALESCE(NULLIF(matakuliah.set_sks_praktek, 0), matakuliah.akt_sks_praktek, 0) as sks_praktek'),
            DB::raw('(COALESCE(NULLIF(matakuliah.set_sks_teori, 0), matakuliah.akt_sks_teori, 0) + COALESCE(NULLIF(matakuliah.set_sks_praktek, 0), matakuliah.akt_sks_praktek, 0)) as total_sks'),
            'prodi.prodi',
            'prodi.kodeprodi',
            DB::raw('GROUP_CONCAT(DISTINCT NULLIF(prodi.konsentrasi, "") SEPARATOR ", ") as daftar_konsentrasi'),
            'kelas.kelas',
            DB::raw("COALESCE(dosen.nama, 'Belum Ditentukan') as nama_dosen"),
            'kurikulum_hari.id_hari',
            DB::raw("COALESCE(kurikulum_hari.hari, '-') as hari"),
            DB::raw("COALESCE(kurikulum_jam.jam, '-') as jam"),
            DB::raw("COALESCE(ruangan.nama_ruangan, '-') as nama_ruangan")
        )
        ->groupBy(
            'kurikulum_periode.id_makul',
            'matakuliah.kode',
            'matakuliah.makul',
            'matakuliah.set_sks_teori',
            'matakuliah.akt_sks_teori',
            'matakuliah.set_sks_praktek',
            'matakuliah.akt_sks_praktek',
            'prodi.kodeprodi',
            'prodi.prodi',
            'kurikulum_periode.id_kelas',
            'kelas.kelas',
            'kurikulum_periode.id_dosen',
            'dosen.nama',
            'kurikulum_periode.id_hari',
            'kurikulum_hari.id_hari',
            'kurikulum_hari.hari',
            'kurikulum_periode.id_jam',
            'kurikulum_jam.jam',
            'kurikulum_periode.id_ruangan',
            'ruangan.nama_ruangan'
        )
        ->orderBy('prodi.prodi', 'ASC')
        ->orderBy('kelas.kelas', 'ASC')
        ->orderBy('kurikulum_hari.id_hari', 'ASC')
        ->orderBy('kurikulum_jam.jam', 'ASC')
        ->get();

        return view('export_excel/data_jadwal_perkuliahan', [
            'data' => $data,
            'namaperiodetahun' => $this->namaperiodetahun,
            'namaperiodetipe' => $this->namaperiodetipe,
            'namaprodi' => $this->namaprodi,
        ]);
    }
}
