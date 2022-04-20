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
    // $vm = Visimisi::all();

    // foreach ($vm as $vm) {
    //   // code...
    // }

    // $visi = $vm->visi;

    // $misi = $vm->misi;

    // $tujuan = $vm->tujuan;

    $id = Auth::user()->id_user;
    $akses = Auth::user()->role;
    $mhs = Student::leftJoin('update_mahasiswas', 'nim_mhs', '=', 'student.nim')
      ->leftjoin('microsoft_user', 'student.idstudent', '=', 'microsoft_user.id_student')
      ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
        'microsoft_user.password'
      )
      ->first();

    $dsn = Dosen::join('agama', 'dosen.idagama', '=', 'agama.idagama')
      ->join('kelamin', 'dosen.idkelamin', '=', 'kelamin.idkelamin')
      ->where('dosen.iddosen', $id)
      ->select('kelamin.kelamin', 'dosen.nama', 'dosen.akademik', 'dosen.tmptlahir', 'dosen.tgllahir', 'agama.agama', 'dosen.hp', 'dosen.email')
      ->first();

    $tahun = Periode_tahun::where('status', 'ACTIVE')->first();
<<<<<<< HEAD

    $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

=======

    $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

>>>>>>> f2566136b8bcdaa67b35ec415e3e48fdf485b04e
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

<<<<<<< HEAD
      return view('home', ['tujuan' => $tujuan, 'visi' => $visi, 'misi' => $misi, 'fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'now' => $ldate, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $thn, 'tipe' => $tp]);
    } elseif ($akses == 2) {

      return view('home', ['tujuan' => $tujuan, 'visi' => $visi, 'misi' => $misi, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
=======
      return view('home', ['fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'now' => $ldate, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $thn, 'tipe' => $tp]);
    } elseif ($akses == 2) {

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
>>>>>>> f2566136b8bcdaa67b35ec415e3e48fdf485b04e
    } elseif ($akses == 3) {

      $foto = $mhs->foto;

<<<<<<< HEAD
      return view('home', ['tujuan' => $tujuan, 'visi' => $visi, 'misi' => $misi, 'angk' => $angk, 'foto' => $foto, 'edom' => $keyedom, 'info' => $info, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $tahun, 'tipe' => $tipe]);
=======
      return view('home', ['angk' => $angk, 'foto' => $foto, 'edom' => $keyedom, 'info' => $info, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $tahun, 'tipe' => $tipe]);
>>>>>>> f2566136b8bcdaa67b35ec415e3e48fdf485b04e
    } elseif ($akses == 4) {

      return view('home', ['mhs' => $mhs, 'id' => $id,]);
    } elseif ($akses == 5) {

<<<<<<< HEAD
      return view('home', ['tujuan' => $tujuan, 'visi' => $visi, 'misi' => $misi, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
=======
      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
>>>>>>> f2566136b8bcdaa67b35ec415e3e48fdf485b04e
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

<<<<<<< HEAD
      return view('home', ['tujuan' => $tujuan, 'visi' => $visi, 'misi' => $misi, 'prd' => $key, 'fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
=======
      return view('home', ['prd' => $key, 'fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
>>>>>>> f2566136b8bcdaa67b35ec415e3e48fdf485b04e
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

      $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
        ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
        ->where('kaprodi.id_dosen', Auth::user()->id_user)
        ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama')
        ->get();
      foreach ($cek as $key) {
        // code...
      }

      return view('home', ['prd' => $key, 'fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $waktu, 'info' => $info,]);
    } elseif ($akses == 11) {

<<<<<<< HEAD
      return view('home', ['tujuan' => $tujuan, 'visi' => $visi, 'misi' => $misi, 'dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
=======
      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
>>>>>>> f2566136b8bcdaa67b35ec415e3e48fdf485b04e
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
