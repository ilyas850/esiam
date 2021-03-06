<?php

namespace App\Http\Controllers;

use Alert;
use App\User;
use App\Mhs;
use App\Dosen;
// use App\Visimisi;
use App\Angkatan;
use Carbon\Carbon;
use App\Student;
use App\Agama;
use App\Kelamin;
use App\Kaprodi;
use App\Informasi;
use App\Update_Mahasiswa;
use App\Periode_tahun;
use App\Kurikulum_transaction;
use App\Periode_tipe;
use App\Waktu_krs;
use App\Waktu_edom;
use App\Microsoft_user;
use App\Student_record;
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
        'student.idangkatan'
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

    $edom = Waktu_edom::all();
    foreach ($edom as $keyedom) {
      // code...
    }

    $info = Informasi::orderBy('created_at', 'DESC')->paginate(5);

    $angk = Angkatan::all();


    if ($akses == 1) {

      $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();

      $tp = Periode_tipe::orderBy('periode_tipe', 'DESC')->get();

      $ldate = date('m/d/Y');

      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::where('kodeprodi', 22)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');


      return view('home', ['fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'now' => $ldate, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $thn, 'tipe' => $tp]);
    } elseif ($akses == 2) {

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
    } elseif ($akses == 3) {

      $foto = $mhs->foto;

      $idprodi = $mhs->id_prodi;
      $idangkatan = $mhs->idangkatan;

      $data = DB::select('CALL standar_kurikulum(?,?,?)', array($idprodi, $idangkatan, $id));

      $data_mengulang = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
        ->join('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
        ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
        ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
        ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->join('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
        ->where('student.idstudent', $id)
        ->where('student_record.status', 'TAKEN')
        ->whereIn('student.active', [1, 5])
        ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR', 'semester.semester', 'kurikulum_master.nama_kurikulum')
        ->groupBy('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR', 'semester.semester', 'kurikulum_master.nama_kurikulum')
        ->get();

      return view('home', ['data_mengulang' => $data_mengulang, 'data' => $data, 'angk' => $angk, 'foto' => $foto, 'edom' => $keyedom, 'info' => $info, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $tahun, 'tipe' => $tipe]);
    } elseif ($akses == 4) {

      return view('home', ['mhs' => $mhs, 'id' => $id,]);
    } elseif ($akses == 5) {

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
    } elseif ($akses == 6) {
      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::where('kodeprodi', 22)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
        ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
        ->where('kaprodi.id_dosen', Auth::user()->id_user)
        ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama')
        ->get();
      foreach ($cek as $key) {
        // code...
      }

      return view('home', ['prd' => $key, 'fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
    } elseif ($akses == 7) {
      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::where('kodeprodi', 22)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', ['fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
    } elseif ($akses == 11) {

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
    } elseif ($akses == 9) {

      $mhs_ti = Student::where('kodeprodi', 23)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_tk = Student::where('kodeprodi', 22)
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info, 'fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti]);
    }
  }
}
