<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * [NEW] Export Class Rekapitulasi Perkuliahan & BAP
 * Meng-export rekapitulasi tatap muka BAP dosen dan kelas kuliah ke template Excel via Maatwebsite Excel FromView.
 */
class DataRekapPerkuliahanExport implements FromView, ShouldAutoSize
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
        $bapFilterSql = "";
        $mainFilterSql = "";
        $bindings = [$this->id_periodetahun, $this->id_periodetipe];

        if (!empty($this->id_prodi) && $this->id_prodi !== 'all') {
            $bapFilterSql = " AND (prd.kodeprodi = ? OR kp.id_prodi = ?) ";
            $mainFilterSql = " AND (prd.kodeprodi = ? OR kp.id_prodi = ?) ";
            $bindings[] = $this->id_prodi;
            $bindings[] = $this->id_prodi;
        }

        $bapCountSql = "
            SELECT 
                kp.id_makul,
                kp.id_kelas,
                kp.id_dosen,
                COUNT(DISTINCT bp.pertemuan) AS jml_per,
                COUNT(DISTINCT CASE WHEN bp.metode_kuliah = 'Online' THEN bp.pertemuan END) as jml_online,
                COUNT(DISTINCT CASE WHEN bp.metode_kuliah = 'Offline' THEN bp.pertemuan END) as jml_offline
            FROM bap bp
            JOIN kurikulum_periode kp ON kp.id_kurperiode = bp.id_kurperiode
            JOIN prodi prd ON prd.id_prodi = kp.id_prodi
            WHERE bp.status = 'ACTIVE' 
                AND kp.status = 'ACTIVE' 
                AND kp.id_periodetahun = ?
                AND kp.id_periodetipe = ?
                {$bapFilterSql}
            GROUP BY kp.id_makul, kp.id_kelas, kp.id_dosen
        ";

        $outerBindings = array_merge($bindings, [$this->id_periodetahun, $this->id_periodetipe]);
        if (!empty($this->id_prodi) && $this->id_prodi !== 'all') {
            $outerBindings[] = $this->id_prodi;
            $outerBindings[] = $this->id_prodi;
        }

        $data = DB::select("
            SELECT 
                MIN(kp.id_kurperiode) as id_kurperiode, 
                MIN(mk.kode) as kode,
                MIN(mk.makul) as nama_makul,
                CONCAT(MIN(mk.kode), ' - ', MIN(mk.makul)) AS makul, 
                CONCAT(COALESCE(MIN(mk.set_sks_teori), MIN(mk.akt_sks_teori), 0), '/', COALESCE(MIN(mk.set_sks_praktek), MIN(mk.akt_sks_praktek), 0)) AS sks, 
                MIN(prd.prodi) as prodi, 
                GROUP_CONCAT(DISTINCT NULLIF(prd.konsentrasi, '') SEPARATOR ', ') as daftar_konsentrasi,
                MIN(kls.kelas) as kelas, 
                COALESCE(MIN(dsn.nama), 'Belum Ditentukan') as nama, 
                COALESCE(MIN(aa.jml_per), 0) as jml_per,
                COALESCE(MIN(aa.jml_online), 0) as jml_online,
                COALESCE(MIN(aa.jml_offline), 0) as jml_offline
            FROM kurikulum_periode kp
            JOIN matakuliah mk ON mk.idmakul = kp.id_makul
            JOIN prodi prd ON prd.id_prodi = kp.id_prodi
            JOIN kelas kls ON kls.idkelas = kp.id_kelas
            LEFT JOIN dosen dsn ON dsn.iddosen = kp.id_dosen
            LEFT JOIN ({$bapCountSql}) aa ON aa.id_makul = kp.id_makul 
                                        AND aa.id_kelas = kp.id_kelas 
                                        AND (aa.id_dosen = kp.id_dosen OR (aa.id_dosen IS NULL AND kp.id_dosen IS NULL))
            WHERE kp.id_periodetahun = ? 
                AND kp.id_periodetipe = ? 
                AND kp.status = 'ACTIVE' 
                AND mk.active = 1
                {$mainFilterSql}
            GROUP BY prd.kodeprodi, kp.id_kelas, kp.id_makul, kp.id_dosen
            ORDER BY MIN(mk.kode), MIN(kls.kelas) ASC
        ", $outerBindings);

        return view('export_excel/data_rekap_perkuliahan', [
            'data' => $data,
            'namaperiodetahun' => $this->namaperiodetahun,
            'namaperiodetipe' => $this->namaperiodetipe,
            'namaprodi' => $this->namaprodi,
        ]);
    }
}
