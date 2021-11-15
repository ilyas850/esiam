<?php

namespace App\Http\Controllers;

Use Alert;
use App\User;
use App\Mhs;
use App\Dosen;
use App\Visimisi;
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
use App\Student_record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $vm = Visimisi::all();

        foreach ($vm as $vm) {
          // code...
        }

        $visi = $vm->visi;

        $misi = $vm->misi;

        $tujuan = $vm->tujuan;

        $id = Auth::user()->username;
        $akses = Auth::user()->role;
        $mhs = Student::leftJoin('update_mahasiswas', 'nim_mhs', '=', 'student.nim')
                      ->where('student.nim', Auth::user()->username)
                      ->select('student.foto', 'student.hp', 'student.idangkatan', 'student.idstatus', 'student.email', 'student.kodeprodi', 'student.idstudent', 'student.nim', 'update_mahasiswas.hp_baru', 'update_mahasiswas.email_baru', 'update_mahasiswas.id_mhs', 'update_mahasiswas.id', 'update_mahasiswas.nim_mhs')
                      ->get();

        $dsn = Dosen::join('agama', 'dosen.idagama', '=', 'agama.idagama')
                    ->join('kelamin', 'dosen.idkelamin', '=', 'kelamin.idkelamin')
                    ->where('dosen.nik', $id)
                    ->select('kelamin.kelamin','dosen.nama', 'dosen.akademik', 'dosen.tmptlahir', 'dosen.tgllahir', 'agama.agama', 'dosen.hp', 'dosen.email')
                    ->first();

        $tahun = Periode_tahun::orderBy('id_periodetahun', 'ASC')->get();

        $tipe = Periode_tipe::all();

        $time = Waktu_krs::all();

        foreach ($time as $waktu) {

        }

        $edom = Waktu_edom::all();
        foreach ($edom as $keyedom) {
          // code...
        }

        $info = Informasi::orderBy('created_at', 'DESC')->paginate(5);

        $angk = Angkatan::all();


        if ($akses==1) {

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

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'fa'=>$mhs_fa,'tk'=>$mhs_tk,'ti'=>$mhs_ti,'now'=>$ldate, 'mhs' => $mhs, 'id' => $id, 'time' => $waktu, 'tahun' => $tahun, 'tipe' => $tipe]);
        }elseif ($akses==2) {

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'dsn'=>$dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $waktu, 'info'=>$info,]);
        }elseif ($akses==3) {

          foreach ($mhs as $valuefoto) {
            // code...
          }
          $foto = $valuefoto->foto;

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'angk'=>$angk, 'foto'=>$foto, 'edom'=>$keyedom, 'info'=>$info, 'mhs' => $mhs, 'id' => $id, 'time' => $waktu, 'tahun' => $tahun, 'tipe' => $tipe]);
        }elseif ($akses==4) {

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'mhs' => $mhs, 'id' => $id, ]);
        }elseif ($akses==5) {

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'dsn'=>$dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $waktu, 'info'=>$info,]);
        }elseif ($akses==6) {
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

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'prd'=>$key,'fa'=>$mhs_fa,'tk'=>$mhs_tk,'ti'=>$mhs_ti,'dsn'=>$dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $waktu, 'info'=>$info,]);
        }elseif ($akses==7) {
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

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'prd'=>$key,'fa'=>$mhs_fa,'tk'=>$mhs_tk,'ti'=>$mhs_ti,'dsn'=>$dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $waktu, 'info'=>$info,]);
        }elseif ($akses==11) {

          return view('home', ['tujuan'=>$tujuan,'visi'=>$visi,'misi'=>$misi,'dsn'=>$dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $waktu, 'info'=>$info,]);
        }elseif ($akses==9) {

          $mhs_ti = Student::where('kodeprodi', 23)
                          ->where('active', 1)
                          ->count('idstudent');

          $mhs_tk = Student::where('kodeprodi', 22)
                          ->where('active', 1)
                          ->count('idstudent');

          $mhs_fa = Student::where('kodeprodi', 24)
                          ->where('active', 1)
                          ->count('idstudent');

          return view('home', ['dsn'=>$dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $waktu, 'info'=>$info,'fa'=>$mhs_fa,'tk'=>$mhs_tk,'ti'=>$mhs_ti]);
        }
      }
    }
