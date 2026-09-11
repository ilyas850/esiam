<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Student;
use App\Models\Kurikulum_periode;
use App\Models\Periode_tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class YayasanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get active academic year
     */
    private function getActiveTA()
    {
        return Periode_tahun::where('status', 'ACTIVE')->first();
    }

    /**
     * Dashboard Yayasan
     */
    public function yayasan_home()
    {
        $ta = $this->getActiveTA();

        // Quick stats
        $dosenTetap = Dosen::where('idstatus', 1)->where('active', 1)->count();
        $dosenTidakTetap = Dosen::whereIn('idstatus', [2, 3])->where('active', 1)->count();
        $mhsAktif = Student::where('active', 1)->count();
        $mhsTidakAktif = Student::where('active', 0)->count();

        return view('yayasan.yayasan_home', compact(
            'ta',
            'dosenTetap',
            'dosenTidakTetap',
            'mhsAktif',
            'mhsTidakAktif'
        ));
    }

    /**
     * Data Dosen Tetap
     */
    public function data_dosen_tetap()
    {
        $ta = $this->getActiveTA();

        $dosen = Dosen::where('idstatus', 1)
            ->where('active', 1)
            ->orderBy('nama')
            ->get();

        // Get matakuliah for each dosen in current TA
        $dosenWithMakul = [];
        foreach ($dosen as $d) {
            $makul = Kurikulum_periode::with(['makul', 'kelas', 'prodi'])
                ->where('id_dosen', $d->iddosen)
                ->where('id_periodetahun', optional($ta)->id_periodetahun)
                ->get()
                ->map(function ($kp) {
                    return [
                        'namakul' => optional($kp->makul)->makul ?? '-',
                        'sks_teori' => optional($kp->makul)->akt_sks_teori ?? 0,
                        'sks_praktek' => optional($kp->makul)->akt_sks_praktek ?? 0,
                        'kelas' => optional($kp->kelas)->kelas ?? '-',
                        'prodi' => optional($kp->prodi)->prodi ?? '-',
                    ];
                })
                ->unique(function ($item) {
                    return $item['namakul'] . '|' . $item['kelas'] . '|' . $item['prodi'];
                })
                ->values();

            $dosenWithMakul[] = [
                'dosen' => $d,
                'matakuliah' => $makul
            ];
        }

        // dd($dosenWithMakul);

        return view('yayasan.data_dosen_tetap', compact('ta', 'dosenWithMakul'));
    }

    /**
     * Data Dosen Tidak Tetap
     */
    public function data_dosen_tidak_tetap()
    {
        $ta = $this->getActiveTA();

        $dosen = Dosen::whereIn('idstatus', [2, 3])
            ->where('active', 1)
            ->orderBy('nama')
            ->get();

        // Get matakuliah for each dosen in current TA
        $dosenWithMakul = [];
        foreach ($dosen as $d) {
            $makul = Kurikulum_periode::with(['makul', 'kelas', 'prodi'])
                ->where('id_dosen', $d->iddosen)
                ->where('id_periodetahun', optional($ta)->id_periodetahun)
                ->get()
                ->map(function ($kp) {
                    return [
                        'namakul' => optional($kp->makul)->makul ?? '-',
                        'sks_teori' => optional($kp->makul)->akt_sks_teori ?? 0,
                        'sks_praktek' => optional($kp->makul)->akt_sks_praktek ?? 0,
                        'kelas' => optional($kp->kelas)->kelas ?? '-',
                        'prodi' => optional($kp->prodi)->prodi ?? '-',
                    ];
                })
                ->unique(function ($item) {
                    return $item['namakul'] . '|' . $item['kelas'] . '|' . $item['prodi'];
                })
                ->values();

            $dosenWithMakul[] = [
                'dosen' => $d,
                'matakuliah' => $makul
            ];
        }

        return view('yayasan.data_dosen_tidak_tetap', compact('ta', 'dosenWithMakul'));
    }

    /**
     * Data Mahasiswa Aktif
     */
    public function data_mahasiswa_aktif()
    {
        $ta = $this->getActiveTA();

        $mahasiswa = Student::leftJoin('prodi', function ($join) {
            $join->on('student.kodeprodi', '=', 'prodi.kodeprodi')
                ->on('student.kodekonsentrasi', '=', 'prodi.kodekonsentrasi');
        })
            ->leftJoin('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.active', 1)
            ->select(
                'student.nim',
                'student.nama',
                'student.email',
                'student.hp',
                'prodi.prodi as nama_prodi',
                'prodi.konsentrasi',
                'angkatan.angkatan as tahun_angkatan'
            )
            ->orderBy('student.nama')
            ->paginate(50);

        // Get counts by prodi with prodi name
        $countByProdi = Student::leftJoin('prodi', function ($join) {
            $join->on('student.kodeprodi', '=', 'prodi.kodeprodi')
                ->on('student.kodekonsentrasi', '=', 'prodi.kodekonsentrasi');
        })
            ->where('student.active', 1)
            ->select('prodi.prodi as nama_prodi', DB::raw('count(*) as total'))
            ->groupBy('prodi.prodi')
            ->get();

        return view('yayasan.data_mahasiswa_aktif', compact('ta', 'mahasiswa', 'countByProdi'));
    }

    /**
     * Data Mahasiswa Tidak Aktif
     */
    public function data_mahasiswa_tidak_aktif()
    {
        $ta = $this->getActiveTA();

        $mahasiswa = Student::leftJoin('prodi', function ($join) {
            $join->on('student.kodeprodi', '=', 'prodi.kodeprodi')
                ->on('student.kodekonsentrasi', '=', 'prodi.kodekonsentrasi');
        })
            ->leftJoin('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.active', 0)
            ->select(
                'student.nim',
                'student.nama',
                'student.email',
                'student.hp',
                'prodi.prodi as nama_prodi',
                'prodi.konsentrasi',
                'angkatan.angkatan as tahun_angkatan'
            )
            ->orderBy('student.nama')
            ->paginate(50);

        // Get counts by prodi with prodi name
        $countByProdi = Student::leftJoin('prodi', function ($join) {
            $join->on('student.kodeprodi', '=', 'prodi.kodeprodi')
                ->on('student.kodekonsentrasi', '=', 'prodi.kodekonsentrasi');
        })
            ->where('student.active', 0)
            ->select('prodi.prodi as nama_prodi', DB::raw('count(*) as total'))
            ->groupBy('prodi.prodi')
            ->get();

        return view('yayasan.data_mahasiswa_tidak_aktif', compact('ta', 'mahasiswa', 'countByProdi'));
    }
}
