<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Mhs;
use App\User;
use App\Models\Agama;
use App\Models\Dosen;
use App\Models\Visimisi;
use App\Models\Angkatan;
use Carbon\Carbon;
use App\Models\Student;
use App\Models\Kelamin;
use App\Models\Kaprodi;
use App\Models\Informasi;
use App\Models\Update_mahasiswa;
use App\Models\Periode_tahun;
use App\Models\Kurikulum_transaction;
use App\Models\Periode_tipe;
use App\Models\Waktu_krs;
use App\Models\Waktu_edom;
use App\Models\Kuisioner_transaction;
use App\Models\Waktu;
use App\Models\Edom_transaction;
use App\Models\Student_record;
use App\Models\Prausta_setting_relasi;
use App\Models\Prausta_master_waktu;
use App\Models\Prausta_master_kode;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {

    $id = Auth::user()->id_user;
    $akses = Auth::user()->role;

    $mhs = Student::leftJoin('update_mahasiswas', 'nim_mhs', '=', 'student.nim')
      ->leftjoin('microsoft_user', 'student.idstudent', '=', 'microsoft_user.id_student')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student.idstudent', $id)
      ->select(
        'student.nama',
        'student.foto',
        'student.hp',
        'angkatan.angkatan',
        'kelas.kelas',
        'student.email',
        'prodi.prodi',
        'student.idstudent',
        'student.nim',
        'student.nisn',
        'update_mahasiswas.hp_baru',
        'update_mahasiswas.email_baru',
        'update_mahasiswas.id_mhs',
        'update_mahasiswas.id',
        'update_mahasiswas.nim_mhs',
        'microsoft_user.username',
        'microsoft_user.password',
        'prodi.id_prodi',
        'prodi.konsentrasi',
        'student.idangkatan',
        'student.kodeprodi',
        'student.virtual_account'
      )
      ->first();

    $dsn = Dosen::leftjoin('agama', 'dosen.idagama', '=', 'agama.idagama')
      ->leftjoin('kelamin', 'dosen.idkelamin', '=', 'kelamin.idkelamin')
      ->where('dosen.iddosen', $id)
      ->select('kelamin.kelamin', 'dosen.nama', 'dosen.akademik', 'dosen.tmptlahir', 'dosen.tgllahir', 'agama.agama', 'dosen.hp', 'dosen.email')
      ->first();

    $tahun = Periode_tahun::where('status', 'ACTIVE')->first();

    $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

    $time = Waktu_krs::first();

    // Auto-Close KRS if time has passed
    if ($time && $time->status == 1) {
      $deadline = \Carbon\Carbon::parse($time->waktu_akhir);
      if (\Carbon\Carbon::now()->greaterThan($deadline)) {
        $time->status = 0;
        $time->save();
      }
    }

    $edom = Waktu_edom::all();
    foreach ($edom as $keyedom) {
      // code...
    }

    $info = Informasi::orderBy('created_at', 'DESC')->paginate(5);

    $angk = Angkatan::all();

    if ($akses == 1) {
      $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->limit(7)->get();
      $tp = Periode_tipe::orderBy('periode_tipe', 'DESC')->get();

      $ldate = date('m/d/Y');

      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_trpl = Student::where('kodeprodi', 25)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_logs = Student::where('kodeprodi', 26)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', ['trpl' => $mhs_trpl, 'ti' => $mhs_ti, 'logs' => $mhs_logs, 'fa' => $mhs_fa, 'now' => $ldate, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $thn, 'tipe' => $tp]);
    } elseif ($akses == 2) {

      return redirect('dosen_home');
    } elseif ($akses == 3) {

      return redirect('mhs_home');
    } elseif ($akses == 4) {

      return view('home', ['mhs' => $mhs, 'id' => $id]);
    } elseif ($akses == 5) {

      return redirect('dosenluar_home');
    } elseif ($akses == 6) {

      return redirect('kaprodi_home');
    } elseif ($akses == 7) {
      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::whereIn('kodeprodi', [22, 25])
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', ['fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
    } elseif ($akses == 8) {

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info]);
    } elseif ($akses == 11) {
      $kode_pkl_magang = [1, 2, 3, 12, 15, 18, 21, 24, 27, 30, 33, 34, 35];
      $kode_sempro = [4, 5, 6, 13, 16, 19, 22, 25, 28, 31];
      $kode_ta_skripsi = [7, 8, 9, 14, 17, 20, 23, 26, 29, 32];

      $base_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
        ->where('prausta_setting_relasi.status', 'ACTIVE')
        ->where('student.active', 1);

      $count_pkl_magang = (clone $base_prausta)->whereIn('prausta_setting_relasi.id_masterkode_prausta', $kode_pkl_magang)
        ->count();

      $count_sempro = (clone $base_prausta)->whereIn('prausta_setting_relasi.id_masterkode_prausta', $kode_sempro)
        ->count();

      $count_ta_skripsi = (clone $base_prausta)->whereIn('prausta_setting_relasi.id_masterkode_prausta', $kode_ta_skripsi)
        ->count();

      $count_total_prausta = $count_pkl_magang + $count_sempro + $count_ta_skripsi;
      $count_mhs_unik = (clone $base_prausta)->distinct('prausta_setting_relasi.id_student')->count('prausta_setting_relasi.id_student');

      $pending_pkl = (clone $base_prausta)->whereIn('prausta_setting_relasi.id_masterkode_prausta', $kode_pkl_magang)
        ->where(function ($q) {
          $q->whereNull('prausta_setting_relasi.validasi_baak')->orWhere('prausta_setting_relasi.validasi_baak', '!=', 'SUDAH');
        })
        ->count();

      $pending_sempro = (clone $base_prausta)->whereIn('prausta_setting_relasi.id_masterkode_prausta', $kode_sempro)
        ->where(function ($q) {
          $q->whereNull('prausta_setting_relasi.validasi_baak')->orWhere('prausta_setting_relasi.validasi_baak', '!=', 'SUDAH');
        })
        ->count();

      $pending_ta = (clone $base_prausta)->whereIn('prausta_setting_relasi.id_masterkode_prausta', $kode_ta_skripsi)
        ->where(function ($q) {
          $q->whereNull('prausta_setting_relasi.validasi_baak')->orWhere('prausta_setting_relasi.validasi_baak', '!=', 'SUDAH');
        })
        ->count();

      $count_pending_val = $pending_pkl + $pending_sempro + $pending_ta;

      $jadwal_query = Prausta_master_waktu::join('periode_tahun', 'prausta_master_waktu.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
        ->join('periode_tipe', 'prausta_master_waktu.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
        ->join('prodi', 'prausta_master_waktu.id_prodi', '=', 'prodi.id_prodi')
        ->where('prausta_master_waktu.status', 'ACTIVE');

      if ($tahun) {
        $hasCurrentTahun = (clone $jadwal_query)->where('prausta_master_waktu.id_periodetahun', $tahun->id_periodetahun)->exists();
        if ($hasCurrentTahun) {
          $jadwal_query->where('prausta_master_waktu.id_periodetahun', $tahun->id_periodetahun);
          if ($tipe) {
            $hasCurrentTipe = (clone $jadwal_query)->where('prausta_master_waktu.id_periodetipe', $tipe->id_periodetipe)->exists();
            if ($hasCurrentTipe) {
              $jadwal_query->where('prausta_master_waktu.id_periodetipe', $tipe->id_periodetipe);
            }
          }
        }
      }

      $jadwal_prausta = $jadwal_query->select(
          'prausta_master_waktu.*',
          'periode_tahun.periode_tahun',
          'periode_tipe.periode_tipe',
          'prodi.prodi',
          'prodi.kodeprodi',
          'prodi.konsentrasi'
        )
        ->orderBy('periode_tahun.periode_tahun', 'DESC')
        ->orderBy('prausta_master_waktu.set_waktu_akhir', 'DESC')
        ->orderBy('prodi.prodi', 'ASC')
        ->get();

      $rekap_prodi = DB::table('prausta_setting_relasi')
        ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
        ->leftJoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->where('prausta_setting_relasi.status', 'ACTIVE')
        ->where('student.active', 1)
        ->select(
          DB::raw("COALESCE(prodi.prodi, 'Lainnya') as nama_prodi"),
          DB::raw("COUNT(CASE WHEN prausta_setting_relasi.id_masterkode_prausta IN (1, 2, 3, 12, 15, 18, 21, 24, 27, 30, 33, 34, 35) THEN 1 END) as jml_pkl"),
          DB::raw("COUNT(CASE WHEN prausta_setting_relasi.id_masterkode_prausta IN (4, 5, 6, 13, 16, 19, 22, 25, 28, 31) THEN 1 END) as jml_sempro"),
          DB::raw("COUNT(CASE WHEN prausta_setting_relasi.id_masterkode_prausta IN (7, 8, 9, 14, 17, 20, 23, 26, 29, 32) THEN 1 END) as jml_ta"),
          DB::raw("COUNT(prausta_setting_relasi.id_settingrelasi_prausta) as jml_total")
        )
        ->groupBy('prodi.prodi')
        ->orderBy('prodi.prodi', 'ASC')
        ->get();

      $latest_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
        ->leftJoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
        ->where('prausta_setting_relasi.status', 'ACTIVE')
        ->where('student.active', 1)
        ->select(
          'student.nama',
          'student.nim',
          'prodi.prodi',
          'prausta_master_kode.nama_prausta',
          'prausta_master_kode.kode_prausta',
          'prausta_setting_relasi.validasi_baak',
          'prausta_setting_relasi.created_at',
          'prausta_setting_relasi.id_settingrelasi_prausta'
        )
        ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
        ->limit(6)
        ->get();

      return view('home', compact(
        'dsn',
        'tahun',
        'tipe',
        'time',
        'info',
        'count_pkl_magang',
        'count_sempro',
        'count_ta_skripsi',
        'count_total_prausta',
        'count_mhs_unik',
        'pending_pkl',
        'pending_sempro',
        'pending_ta',
        'count_pending_val',
        'jadwal_prausta',
        'rekap_prodi',
        'latest_prausta'
      ));
    } elseif ($akses == 9) {

      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::whereIn('kodeprodi', [22, 25])
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info, 'fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti]);
    } elseif ($akses == 10) {
      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::whereIn('kodeprodi', [22, 25])
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', ['fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info]);
    } elseif ($akses == 12) {
      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::whereIn('kodeprodi', [22, 25])
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', compact('mhs_ti', 'mhs_tk', 'mhs_fa'));
    }

    // Check for Yayasan role (uses Spatie, not integer)
    if (Auth::user()->isYayasan()) {
      return redirect('yayasan_home');
    }
  }
}
