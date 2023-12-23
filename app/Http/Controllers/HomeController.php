<?php

namespace App\Http\Controllers;

use Alert;
use App\Mhs;
use App\User;
use App\Agama;
use App\Dosen;
use App\Visimisi;
use App\Angkatan;
use Carbon\Carbon;
use App\Student;
use App\Kelamin;
use App\Kaprodi;
use App\Informasi;
use App\Update_Mahasiswa;
use App\Periode_tahun;
use App\Kurikulum_transaction;
use App\Periode_tipe;
use App\Waktu_krs;
use App\Waktu_edom;
use App\Kuisioner_transaction;
use App\Waktu;
use App\Edom_transaction;
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

      $mhs_tk = Student::whereIn('kodeprodi', [22, 25])
        ->where('active', 1)
        ->count('idstudent');

      $mhs_fa = Student::where('kodeprodi', 24)
        ->where('active', 1)
        ->count('idstudent');

      return view('home', ['fa' => $mhs_fa, 'tk' => $mhs_tk, 'ti' => $mhs_ti, 'now' => $ldate, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $thn, 'tipe' => $tp]);
    } elseif ($akses == 2) {

      return redirect('dosen_home');
    } elseif ($akses == 3) {

      $waktu_edom = Waktu_edom::select('status', 'waktu_awal', 'waktu_Akhir')
        ->first();

      if ($waktu_edom->status == 1) {
        #cek jumlah KRS makul kecuali PKL dan TA / Magang dan Skripsi
        $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->where('student_record.id_student', $id)
          ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
          ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
          ->where('student_record.status', 'TAKEN')
          ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
          ->get();

        $hit = count($records);

        #cek jumlah pengisian EDOM
        $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->where('edom_transaction.id_student', $id)
          ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
          ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
          ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
          ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
          ->get();

        $sekhit = count($cekedom);

        if ($hit == $sekhit) {
          #cek kuisioner Pembimbing Akademik
          $cek_kuis_pa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 1)
            ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
            ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
            ->get();

          if (count($cek_kuis_pa) > 0) {
            #cek kuisioner BAAK
            $cek_kuis_baak = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
              ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
              ->where('kuisioner_transaction.id_student', $id)
              ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
              ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
              ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
              ->get();

            if (count($cek_kuis_baak) > 0) {
              #cek kuisioner BAUK
              $cek_kuis_bauk = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $id)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
                ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
                ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
                ->get();

              if (count($cek_kuis_bauk) > 0) {
                #cek kuisioner PERPUS
                $cek_kuis_perpus = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                  ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                  ->where('kuisioner_transaction.id_student', $id)
                  ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
                  ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
                  ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
                  ->get();

                if (count($cek_kuis_perpus) > 0) {
                  #cek kuisioner Beasiswa
                  $cek_kuis_beasiswa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                    ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                    ->where('kuisioner_transaction.id_student', $id)
                    ->where('kuisioner_master_kategori.id_kategori_kuisioner', 9)
                    ->where('kuisioner_transaction.id_periodetahun',  $tahun->id_periodetahun)
                    ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
                    ->get();

                  if (count($cek_kuis_beasiswa) > 0) {

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
                  } elseif (count($cek_kuis_beasiswa) == 0) {
                    Alert::error('Maaf anda belum melakukan pengisian kuisioner BEASISWA', 'MAAF !!');
                    return redirect('kuisioner_mahasiswa');
                  }
                } elseif (count($cek_kuis_perpus) == 0) {
                  Alert::error('Maaf anda belum melakukan pengisian kuisioner PERPUSTAKAAN', 'MAAF !!');
                  return redirect('kuisioner_mahasiswa');
                }
              } elseif (count($cek_kuis_bauk) == 0) {
                Alert::error('Maaf anda belum melakukan pengisian kuisioner BAUK', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
              }
            } elseif (count($cek_kuis_baak) == 0) {
              Alert::error('Maaf anda belum melakukan pengisian kuisioner BAAK', 'MAAF !!');
              return redirect('kuisioner_mahasiswa');
            }
          } elseif (count($cek_kuis_pa) == 0) {
            Alert::error('Maaf anda belum melakukan pengisian kuisioner Pembimbing Akademik', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
          }
        } else {
          Alert::error('Maaf anda belum melakukan pengisian EDOM', 'MAAF !!');
          return redirect('kuisioner_mahasiswa');
        }
      } else {

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
      }
    } elseif ($akses == 4) {

      return view('home', ['mhs' => $mhs, 'id' => $id,]);
    } elseif ($akses == 5) {
    
      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info,]);
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

      return view('home', ['dsn' => $dsn, 'tahun' => $tahun, 'tipe' => $tipe, 'time' => $time, 'info' => $info]);
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
  }
}
