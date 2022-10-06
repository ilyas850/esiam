<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Alert;
use App\Bap;
use App\User;
use App\Dosen;
use App\Kelas;
use App\Prodi;
use App\Biaya;
use App\Kaprodi;
use App\Ruangan;
use App\Semester;
use App\Itembayar;
use App\Beasiswa;
use App\Kuitansi;
use App\Student;
use App\Angkatan;
use App\Informasi;
use App\Matakuliah;
use App\Ujian_transaction;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Student_record;
use App\Dosen_pembimbing;
use App\Edom_transaction;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_master;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Kuliah_transaction;
use App\Absensi_mahasiswa;
use App\Prausta_setting_relasi;
use App\Prausta_trans_bimbingan;
use App\Prausta_trans_hasil;
use App\Prausta_master_penilaian;
use App\Prausta_trans_penilaian;
use App\Soal_ujian;
use App\Setting_nilai;
use App\Standar;
use App\Sertifikat;
use App\Pedoman_akademik;
use App\Penangguhan_kategori;
use App\Penangguhan_trans;
use App\Yudisium;
use App\Wisuda;
use App\Exports\DataNilaiIpkMhsExport;
use App\Exports\DataNilaiIpkMhsProdiExport;
use App\Exports\DataNilaiExport;
use App\Exports\DataMhsExport;
use App\Exports\DataMhsAllExport;
use App\Exports\DataBimbinganPrakerinExport;
use App\Exports\DataBimbinganSemproExport;
use App\Exports\DataBimbinganTaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class KaprodiController extends Controller
{
  public function change_pass_kaprodi($id)
  {
    return view('kaprodi/change_pwd_kaprodi', ['dsn' => $id]);
  }

  public function store_pwd_kaprodi(Request $request, $id)
  {
    $this->validate($request, [
      'oldpassword' => 'required',
      'password' => 'required|min:7|confirmed',
    ]);

    $sandi = bcrypt($request->password);

    $user = User::find($id);

    $pass = password_verify($request->oldpassword, $user->password);

    if ($pass) {
      $user->password = $sandi;
      $user->save();

      Alert::success('', 'Password anda berhasil dirubah')->autoclose(3500);
      return redirect('home');
    } else {
      Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
      return redirect('home');
    }
  }

  public function lihat_semua_kprd()
  {
    $info = Informasi::orderBy('created_at', 'DESC')->get();

    return view('kaprodi/all_info', ['info' => $info]);
  }

  public function lihat_kprd($id)
  {
    $info = Informasi::find($id);

    return view('kaprodi/lihatinfo', ['info' => $info]);
  }

  public function mhs_aktif()
  {
    $tahun = Periode_tahun::whereNotIn('id_periodetahun', [1, 3, 4])
      ->orderBy('periode_tahun', 'ASC')
      ->get();
    $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();
    $prodi = Prodi::all();

    $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('dosen_pembimbing', 'student_record.id_student', 'dosen_pembimbing.id_student')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('periode_tahun.status', 'ACTIVE')
      ->where('periode_tipe.status', 'ACTIVE')
      ->where('student_record.status', 'TAKEN')
      ->where('student.active', 1)
      ->select(DB::raw('DISTINCT(student_record.id_student)'), 'kelas.kelas', 'student.nim', 'angkatan.angkatan', 'prodi.prodi', 'student.nama')
      ->orderBy('student.nim', 'ASC')
      ->orderBy('student.idangkatan', 'ASC')
      ->get();

    return view('kaprodi/master/mhs_aktif', ['aktif' => $val, 'thn' => $tahun, 'tp' => $tipe, 'prd' => $prodi]);
  }

  public function cari_mhs_aktif(Request $request)
  {
    $thn = $request->id_periodetahun;
    $tp = $request->id_periodetipe;
    $kd = $request->kodeprodi;

    $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('dosen_pembimbing', 'student_record.id_student', 'dosen_pembimbing.id_student')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('periode_tahun.id_periodetahun', $request->id_periodetahun)
      ->where('periode_tipe.id_periodetipe', $request->id_periodetipe)
      ->where('student_record.status', 'TAKEN')
      ->where('student.active', 1)
      ->where('student.kodeprodi', $request->kodeprodi)
      ->select(DB::raw('DISTINCT(student_record.id_student)'), 'kelas.kelas', 'student.nim', 'angkatan.angkatan', 'prodi.prodi', 'student.nama', 'prodi.kodeprodi')
      ->orderBy('student.nim', 'ASC')
      ->orderBy('student.idangkatan', 'ASC')
      ->get();

    $tahun = Periode_tahun::whereNotIn('id_periodetahun', [1, 3, 4])
      ->orderBy('periode_tahun', 'ASC')
      ->get();
    $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();
    $prodi = Prodi::all();

    $thun = Periode_tahun::where('id_periodetahun', $thn)->get();
    foreach ($thun as $keythn) {
      # code...
    }
    $ta = $keythn->periode_tahun;

    $tip = Periode_tipe::where('id_periodetipe', $tp)->get();
    foreach ($tip as $keytp) {
      # code...
    }
    $tpe = $keytp->periode_tipe;

    $prdi = Prodi::where('kodeprodi', $kd)->get();
    foreach ($prdi as $keyprd) {
      # code...
    }
    $prod = $keyprd->prodi;


    return view('kaprodi/master/mhs_aktif_new', ['ta' => $ta, 'tpe' => $tpe, 'prod' => $prod, 'data' => $val, 'idthn' => $thn, 'idtp' => $tp, 'idkd' => $kd, 'thn' => $tahun, 'tp' => $tipe, 'prd' => $prodi]);
  }

  public function export_data_mhs_aktif()
  {
    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->periode_tahun;

    $ganti = str_replace("/", "_", $tahun);

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->periode_tipe;

    $nama_file = 'Data Mahasiswa Aktif ' . ' ' . $ganti . ' ' . $tipe . '.xlsx';
    return Excel::download(new DataMhsAllExport, $nama_file);
  }

  public function export_xls_mhs_aktif(Request $request)
  {
    $prodi = Prodi::where('kodeprodi', $request->kodeprodi)->first();
    $prd = $prodi->prodi;
    $nmprd = $request->kodeprodi;

    $thn = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
    $tahun = $thn->periode_tahun;
    $nmthun = $request->id_periodetahun;

    $ganti = str_replace("/", "_", $tahun);

    $tp = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
    $tipe = $tp->periode_tipe;
    $nmtp = $request->id_periodetipe;

    $nama_file = 'Data Mahasiswa Aktif ' . ' ' . $prd . ' ' . $ganti . ' ' . $tipe . '.xlsx';
    return Excel::download(new DataMhsExport($nmprd, $nmthun, $nmtp), $nama_file);
  }

  public function mhs_bim()
  {
    $id = Auth::user()->id_user;

    $p = DB::select('CALL mhs_bim(?)', [$id]);

    $k =  DB::table('student_record')
      ->join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->leftjoin('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->leftjoin('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->select(
        'student.idstudent',
        'student.nama',
        'student.nim',
        'student_record.tanggal_krs',
        'angkatan.angkatan',
        'kelas.kelas',
        'prodi.prodi',
        'periode_tahun.periode_tahun',
        'periode_tipe.periode_tipe'
      )
      ->whereIn('student_record.id_studentrecord', (function ($query) {
        $query->from('student_record')
          ->select(DB::raw('MAX(student_record.id_studentrecord)'))
          ->where('student_record.status', 'TAKEN')
          ->groupBy('student_record.id_student');
      }))
      ->where('student.active', 1)
      ->where('dosen_pembimbing.id_dosen', $id)
      // ->where('student_record.status', 'TAKEN')
      ->get();

    return view('kaprodi/master/mhs_bim', ['mhs' => $k]);
  }

  public function record_nilai($id)
  {
    $mhs = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->where('student.idstudent', $id)
      ->select(
        'student.idstudent',
        'student.nama',
        'student.nim',
        'kelas.kelas',
        'prodi.prodi',
        'student.idangkatan',
        'student.idstatus',
        'student.kodeprodi'
      )
      ->first();

    $idangkatan = $mhs->idangkatan;
    $idstatus = $mhs->idstatus;
    $kodeprodi = $mhs->kodeprodi;

    $thn = Periode_tahun::where('status', 'ACTIVE')->get();
    foreach ($thn as $tahun) {
      // code...
    }

    $tp = Periode_tipe::where('status', 'ACTIVE')->get();
    foreach ($tp as $tipe) {
      // code...
    }

    $sub_thn = substr($tahun->periode_tahun, 6, 2);
    $tp = $tipe->id_periodetipe;
    $smt = $sub_thn . $tp;
    $angk = $mhs->idangkatan;

    if ($smt % 2 != 0) {
      $a = ($smt + 10 - 1) / 10;
      $b = $a - $angk;
      $c = $b * 2 - 1;
    } else {
      $a = ($smt + 10 - 2) / 10;
      $b = $a - $angk;
      $c = $b * 2;
    }

    $biaya = Biaya::where('idangkatan', $idangkatan)
      ->where('idstatus', $idstatus)
      ->where('kodeprodi', $kodeprodi)
      ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14')
      ->first();

    $cb = Beasiswa::where('idstudent', $id)->first();

    if (($cb) != null) {

      $daftar = $biaya->daftar - ($biaya->daftar * $cb->daftar) / 100;
      $awal = $biaya->awal - ($biaya->awal * $cb->awal) / 100;
      $dsp = $biaya->dsp - ($biaya->dsp * $cb->dsp) / 100;
      $spp1 = $biaya->spp1 - ($biaya->spp1 * $cb->spp1) / 100;
      $spp2 = $biaya->spp2 - ($biaya->spp2 * $cb->spp2) / 100;
      $spp3 = $biaya->spp3 - ($biaya->spp3 * $cb->spp3) / 100;
      $spp4 = $biaya->spp4 - ($biaya->spp4 * $cb->spp4) / 100;
      $spp5 = $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100;
      $spp6 = $biaya->spp6 - ($biaya->spp6 * $cb->spp6) / 100;
      $spp7 = $biaya->spp7 - ($biaya->spp7 * $cb->spp7) / 100;
      $spp8 = $biaya->spp8 - ($biaya->spp8 * $cb->spp8) / 100;
      $spp9 = $biaya->spp9 - ($biaya->spp9 * $cb->spp9) / 100;
      $spp10 = $biaya->spp10 - ($biaya->spp10 * $cb->spp10) / 100;
      $spp11 = $biaya->spp11 - (($biaya->spp11 * ($cb->spp11)) / 100);
      $spp12 = $biaya->spp12 - (($biaya->spp12 * ($cb->spp12)) / 100);
      $spp13 = $biaya->spp13 - (($biaya->spp13 * ($cb->spp13)) / 100);
      $spp14 = $biaya->spp14 - (($biaya->spp14 * ($cb->spp14)) / 100);
    } elseif (($cb) == null) {
      $daftar = $biaya->daftar;
      $awal = $biaya->awal;
      $dsp = $biaya->dsp;
      $spp1 = $biaya->spp1;
      $spp2 = $biaya->spp2;
      $spp3 = $biaya->spp3;
      $spp4 = $biaya->spp4;
      $spp5 = $biaya->spp5;
      $spp6 = $biaya->spp6;
      $spp7 = $biaya->spp7;
      $spp8 = $biaya->spp8;
      $spp9 = $biaya->spp9;
      $spp10 = $biaya->spp10;
      $spp11 = $biaya->spp11;
      $spp12 = $biaya->spp12;
      $spp13 = $biaya->spp13;
      $spp14 = $biaya->spp14;
    }

    //cek masa studi 
    $cek_study = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->where('student.idstudent', $id)
      ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
      ->first();

    if ($cek_study->study_year == 3) {
      $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 1)
        ->sum('bayar.bayar');

      $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 2)
        ->sum('bayar.bayar');

      $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 3)
        ->sum('bayar.bayar');

      $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 4)
        ->sum('bayar.bayar');

      $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 5)
        ->sum('bayar.bayar');

      $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 6)
        ->sum('bayar.bayar');

      $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 7)
        ->sum('bayar.bayar');

      $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 8)
        ->sum('bayar.bayar');

      $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 9)
        ->sum('bayar.bayar');

      $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 10)
        ->sum('bayar.bayar');

      $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 11)
        ->sum('bayar.bayar');

      $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 12)
        ->sum('bayar.bayar');

      $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 13)
        ->sum('bayar.bayar');
    } elseif ($cek_study->study_year == 4) {

      $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 18)
        ->sum('bayar.bayar');

      $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 19)
        ->sum('bayar.bayar');

      $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 20)
        ->sum('bayar.bayar');

      $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 21)
        ->sum('bayar.bayar');

      $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 22)
        ->sum('bayar.bayar');

      $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 23)
        ->sum('bayar.bayar');

      $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 24)
        ->sum('bayar.bayar');

      $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 25)
        ->sum('bayar.bayar');

      $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 26)
        ->sum('bayar.bayar');

      $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 27)
        ->sum('bayar.bayar');

      $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 28)
        ->sum('bayar.bayar');

      $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 29)
        ->sum('bayar.bayar');

      $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 30)
        ->sum('bayar.bayar');

      $sisaspp11 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 31)
        ->sum('bayar.bayar');

      $sisaspp12 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 32)
        ->sum('bayar.bayar');

      $sisaspp13 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 33)
        ->sum('bayar.bayar');

      $sisaspp14 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 34)
        ->sum('bayar.bayar');
    }

    if ($cek_study->study_year == 3) {
      $tots1 = $sisadaftar + $sisaawal + $sisaspp1;
      $tots2 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1;
      $tots3 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2;
      $tots4 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3;
      $tots5 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4;
      $tots6 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5;
      $tots7 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6;
      $tots8 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7;
      $tots9 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8;
      $tots10 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9;
    } elseif ($cek_study->study_year == 4) {
      $tots1 = $sisadaftar + $sisaawal + $sisaspp1;
      $tots2 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1;
      $tots3 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2;
      $tots4 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3;
      $tots5 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4;
      $tots6 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5;
      $tots7 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6;
      $tots8 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7;
      $tots9 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8;
      $tots10 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9;
      $tots11 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10;
      $tots12 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaspp11;
      $tots13 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaspp11 + $sisaspp12;
      $tots14 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaspp11 + $sisaspp12 + $sisaspp13;
    }

    if ($c == 1) {
      $cekbyr = $daftar + $awal + $spp1 - $tots1;
    } elseif ($c == 2) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 - $tots2;
    } elseif ($c == 3) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 - $tots3;
    } elseif ($c == 4) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $tots4;
    } elseif ($c == 5) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 - $tots5;
    } elseif ($c == 6) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $tots6;
    } elseif ($c == 7) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 - $tots7;
    } elseif ($c == 8) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $tots8;
    } elseif ($c == 9) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 - $tots9;
    } elseif ($c == 10) {
      $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $tots10;
    } elseif ($c == 11) {
      $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10) - $tots11;
    } elseif ($c == 12) {
      $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11) - $tots12;
    } elseif ($c == 13) {
      $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12) - $tots13;
    } elseif ($c == 14) {
      $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13) - $tots14;
    }

    if ($cekbyr == 0) {
      $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        ->where('student_record.id_student', $id)
        ->where('kurikulum_periode.id_periodetipe', $tp)
        ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
        ->where('student_record.status', 'TAKEN')
        ->select('kurikulum_periode.id_makul')
        ->groupBy('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen')
        ->get();
      $hit = count($records);

      $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
        ->where('edom_transaction.id_student', $id)
        ->where('kurikulum_periode.id_periodetipe', $tp)
        ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
        ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
        ->get();
      $sekhit = count($cekedom);

      if ($hit == $sekhit) {
        $makul = Matakuliah::all();
        $cek = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->where('student_record.id_student', $id)
          ->where('student_record.status', 'TAKEN')
          ->select('kurikulum_periode.id_makul', 'student.nama', 'student.nim', 'student.idstatus', 'student.kodeprodi', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode')
          ->groupBy('kurikulum_periode.id_makul', 'student.nama', 'student.nim', 'student.idstatus', 'student.kodeprodi', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode')
          ->get();
        foreach ($cek as $key) {
          // code...
        }

        return view('kaprodi/master/record_nilai', ['cek' => $cek, 'key' => $mhs]);
      } else {

        Alert::error('maaf mahasiswa tersebut belum melakukan pengisian edom', 'MAAF !!');
        return redirect('mhs_bim_kprd');
      }
    } else {

      Alert::warning('Maaf anda tidak dapat melihat nilai mahasiswa ini karena keuangannya belum memenuhi syarat');
      return redirect('mhs_bim_kprd');
    }
  }

  public function val_krs()
  {
    $ids = Auth::user()->id_user;
    $tahun = Periode_tahun::where('status', 'ACTIVE')->first();
    $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

    $mhs = Dosen_pembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student.active', 1)
      ->where('dosen_pembimbing.id_dosen', $ids)
      ->where('dosen_pembimbing.status', 'ACTIVE')
      ->select('dosen_pembimbing.id_student', 'student.nama', 'student.nim', 'angkatan.angkatan', 'prodi.prodi', 'kelas.kelas')
      ->get();

    $bim = Dosen_pembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
      ->leftjoin('student_record', 'dosen_pembimbing.id_student', '=', 'student_record.id_student')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('periode_tahun.status', 'ACTIVE')
      ->where('periode_tipe.status', 'ACTIVE')
      ->where('dosen_pembimbing.id_dosen', $ids)
      ->where('student.active', 1)
      ->where('student_record.status', 'TAKEN')
      ->select(DB::raw('count(student_record.id_student) as jml_krs'), 'student_record.remark', 'student_record.id_student')
      ->groupBy('student_record.remark', 'student_record.id_student')
      ->get();

    return view('kaprodi/validasi_krs', ['mhs' => $mhs, 'bim' => $bim, 'tahun' => $tahun, 'tipe' => $tipe]);
  }

  public function krs_validasi(Request $request)
  {

    $id = $request->id_student;

    $krs = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('student_record.id_student', $id)
      ->where('student_record.status', 'TAKEN')
      ->where('periode_tahun.status', 'ACTIVE')
      ->where('periode_tipe.status', 'ACTIVE')
      ->where('student_record.status', 'TAKEN')
      ->select(DB::raw('DISTINCT(kurikulum_transaction.idkurtrans)'), 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->groupBy('kurikulum_transaction.idkurtrans', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->get();

    $t = count($krs);

    $jumlah = 0;
    for ($i = 0; $i < $t; $i++) {
      $satu = $krs[$i];
      $skst[] = ($satu['akt_sks_teori']);
      $sksp[] = ($satu['akt_sks_praktek']);
    }

    $jumlahskst = array_sum($skst);
    $jumlahsksp = array_sum($sksp);

    $totalsks = $jumlahskst + $jumlahsksp;

    $id_dsn = Auth::user()->id_user;

    $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
      ->where('dosen_pembimbing.id_dosen', $id_dsn)
      ->where('student.idstudent', $id)
      ->where('student_record.status', 'TAKEN')
      ->whereIn('student.active', [1, 5])
      ->select('student_record.id_student', 'student.nama', 'student.nim', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR')
      ->groupBy('student_record.id_student', 'student.nama', 'student.nim', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR')
      ->get();

    $hitungnilai_de = count($data);

    if ($totalsks > 24) {
      Alert::warning('maaf sks yang diambil mahasiswa ini melebihi 24 sks', 'MAAF !!');
      return redirect('val_krs_kprd');
    } elseif ($totalsks <= 24) {

      $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
        ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
        ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
        ->where('periode_tahun.status', 'ACTIVE')
        ->where('periode_tipe.status', 'ACTIVE')
        ->where('student_record.status', 'TAKEN')
        ->where('student_record.id_student', $id)
        ->update(['student_record.remark' => $request->remark]);

      if ($hitungnilai_de > 0) {
        Alert::warning('Mahasiswa ini ada ' . $hitungnilai_de . ' matakuliah mengulang', 'Berhasil')->autoclose(3500);
        return redirect()->back();
      } elseif ($hitungnilai_de == 0) {
        Alert::success('', 'KRS Berhasil divalidasi')->autoclose(3500);
        return redirect()->back();
      }
    }
  }

  public function cek_krs($id)
  {
    //data mahasiswa
    $data_mhs = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->select('student.nama', 'student.nim', 'prodi.id_prodi', 'prodi.prodi', 'kelas.kelas', 'student.idangkatan', 'student.kodeprodi', 'student.idstatus')
      ->where('student.idstudent', $id)
      ->first();

    //kode prodi
    $prod = Prodi::where('kodeprodi', $data_mhs->kodeprodi)->first();

    //tambah krs
    $krs = Kurikulum_transaction::join('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->where('kurikulum_master.status', 'ACTIVE')
      ->where('periode_tahun.status', 'ACTIVE')
      ->where('periode_tipe.status', 'ACTIVE')
      ->where('kurikulum_periode.id_kelas', $data_mhs->idstatus)
      ->where('kurikulum_periode.id_prodi', $data_mhs->id_prodi)
      ->where('kurikulum_transaction.id_prodi', $data_mhs->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_mhs->idangkatan)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->select('kurikulum_periode.id_kurperiode', 'kurikulum_transaction.idkurtrans', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'dosen.nama', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'kelas.kelas')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('kurikulum_periode.id_kurperiode', 'ASC')
      ->get();

    //data krs diambil
    $val = Student_record::leftjoin('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->leftjoin('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->where('periode_tahun.status', 'ACTIVE')
      ->where('periode_tipe.status', 'ACTIVE')
      ->where('student_record.status', 'TAKEN')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'student_record.remark', 'student_record.id_student', 'student_record.id_studentrecord')
      ->get();

    //cek validasi krs
    $valkrs = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('periode_tahun.status', 'ACTIVE')
      ->where('periode_tipe.status', 'ACTIVE')
      ->where('student_record.status', 'TAKEN')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select(DB::raw('DISTINCT(student_record.remark)'), 'student.idstudent')
      ->get();


    if ($val->count() == 0) {

      Alert::error('', 'Maaf mahasiswa ini belum melakukan KRS')->autoclose(3500);
      return redirect()->back();
    } elseif ($val->count() > 0) {

      foreach ($valkrs as $valuekrs) {
        // code...
      }

      $b = $valuekrs->remark;
      return view('kaprodi/cek_krs', ['b' => $b, 'mhss' => $id, 'add' => $krs, 'val' => $val, 'key' => $data_mhs]);
    }
  }

  public function savekrs_new(Request $request)
  {
    $this->validate($request, [
      'id_student' => 'required',
      'id_kurperiode' => 'required',
    ]);

    $jml = count($request->id_kurperiode);
    for ($i = 0; $i < $jml; $i++) {
      $kurp = $request->id_kurperiode[$i];
      $idr = explode(',', $kurp, 2);
      $tra = $idr[0];
      $trs = $idr[1];
      $cekkrs = Student_record::where('id_student', $request->id_student)
        ->where('id_kurperiode', $tra)
        ->where('id_kurtrans', $trs)
        ->where('status', 'TAKEN')
        ->get();
    }

    if (count($cekkrs) > 0) {

      Alert::warning('maaf mata kuliah sudah dipilih', 'MAAF !!');
      return redirect()->back();
    } elseif (count($cekkrs) == 0) {
      $krs = new Student_record;
      $krs->tanggal_krs   = date("Y-m-d");
      $krs->id_student    = $request->id_student;
      $krs->id_kurperiode = $tra;
      $krs->id_kurtrans   = $trs;
      $krs->save();

      Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
      return redirect()->back();
    }
  }

  public function hapuskrsmhs(Request $request)
  {
    $id = $request->id_studentrecord;
    $cek = Student_record::find($id);
    $cek->status = $request->status;
    $cek->save();

    Alert::success('', 'Matakuliah berhasil dihapus')->autoclose(3500);
    return redirect()->back();
  }

  public function makul_diampu_dsn()
  {
    $iddsn = Auth::user()->id_user;

    $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
    $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();
    $nama_periodetahun = $periodetahun->periode_tahun;
    $nama_periodetipe = $periodetipe->periode_tipe;
    $idperiodetahun = $periodetahun->id_periodetahun;
    $idperiodetipe = $periodetipe->id_periodetipe;

    $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
    $tp = Periode_tipe::all();

    // $makul = DB::select('CALL makul_diampu_dsn(?,?,?)', [$iddsn, $idperiodetahun, $idperiodetipe]);

    $makul = DB::select('CALL matakuliah_diampu_dosen(?,?,?)', [$idperiodetahun, $idperiodetipe, $iddsn]);

    return view('kaprodi/matakuliah/makul_diampu_dsn', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
  }

  public function filter_makul_diampu_kprd(Request $request)
  {
    $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
    $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
    $nama_periodetahun = $periodetahun->periode_tahun;
    $nama_periodetipe = $periodetipe->periode_tipe;
    $idperiodetahun = $periodetahun->id_periodetahun;
    $idperiodetipe = $periodetipe->id_periodetipe;

    $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
    $tp = Periode_tipe::all();

    $id = Auth::user()->id_user;

    $makul = DB::select('CALL makul_diampu_dsn(?,?,?)', [$id, $idperiodetahun, $idperiodetipe]);


    return view('kaprodi/matakuliah/makul_diampu_dsn', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
  }

  public function cekmhs_dsn($id)
  {
    //cek setting nilai
    $nilai = Setting_nilai::where('id_kurperiode', $id)->first();

    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $id)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;

    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $id, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function export_xlsnilai(Request $request)
  {
    $id = $request->id_kurperiode;

    $mk = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', 'kelas.idkelas')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('matakuliah.makul', 'prodi.prodi', 'kelas.kelas')
      ->get();
    foreach ($mk as $keymk) {
      # code...
    }

    $mkul = $keymk->makul;
    $prdi = $keymk->prodi;
    $klas = $keymk->kelas;

    $nama_file = 'Nilai Matakuliah' . ' ' . $mkul . ' ' . $prdi . ' ' . $klas . '.xlsx';
    return Excel::download(new DataNilaiExport($id), $nama_file);
  }

  public function unduh_pdf_nilai(Request $request)
  {
    $id = $request->id_kurperiode;

    $mk = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', 'kelas.idkelas')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'dosen.nama', 'dosen.akademik', 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'prodi.prodi', 'kelas.kelas')
      ->get();

    $kelas_gabungan = DB::select('CALL absensi_mahasiswa_prodi_kelas(?)', [$id]);

    foreach ($mk as $key) {
      # code...
    }

    $makul = $key->makul;
    $tahun = $key->periode_tahun;
    $tipe = $key->periode_tipe;
    $kelas = $key->kelas;
    $bulan = array(
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    );
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    // return view('dosen/unduh_nilai_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'data'=>$key,'tb'=>$cks]);
    $pdf = PDF::loadView('kaprodi/matakuliah/unduh_nilai_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'data' => $key, 'tb' => $kelas_gabungan]);
    return $pdf->download('Nilai Matakuliah' . ' ' . $makul . ' ' . $tahun . ' ' . $tipe . ' ' . $kelas . '.pdf');
  }

  public function entri_bap($id)
  {
    $id_dosen = Auth::user()->id_user;
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->first();

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('kuliah_transaction.id_dosen', $id_dosen)
      ->select(
        'kuliah_transaction.kurang_jam',
        'kuliah_transaction.tanggal_validasi',
        'kuliah_transaction.payroll_check',
        'bap.id_bap',
        'bap.pertemuan',
        'bap.tanggal',
        'bap.jam_mulai',
        'bap.jam_selsai',
        'bap.materi_kuliah',
        'bap.metode_kuliah',
        'kuliah_tipe.tipe_kuliah',
        'bap.jenis_kuliah',
        'bap.hadir',
        'bap.tidak_hadir'
      )
      ->orderBy('bap.id_bap', 'ASC')
      ->get();

    return view('kaprodi/bap/bap', ['bap' => $bap, 'data' => $data]);
  }

  public function input_bap($id)
  {
    $jam = Kurikulum_jam::all();
    return view('kaprodi/bap/form_bap', ['id' => $id, 'jam' => $jam]);
  }

  public function save_bap(Request $request)
  {
    $message = [
      'max'       => ':attribute harus diisi maksimal :max KB',
      'required'  => ':attribute wajib diisi',
      'unique'    => ':attribute sudah terdaftar',
    ];
    $this->validate($request, [
      'pertemuan'               => 'required',
      'tanggal'                 => 'required',
      'jam_mulai'               => 'required',
      'jam_selsai'              => 'required',
      'jenis_kuliah'            => 'required',
      'id_tipekuliah'           => 'required',
      'metode_kuliah'           => 'required',
      'materi_kuliah'           => 'required',
      'file_kuliah_tatapmuka'   => 'image|mimes:jpg,jpeg,JPG,JPEG|max:2048',
      'file_materi_kuliah'      => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG,docx,DOCX,PDF|max:4000',
      'file_materi_tugas'       => 'image|mimes:jpg,jpeg,JPG,JPEG|max:2048',
    ], $message);

    $id_dosen = Auth::user()->id_user;
    $id_kurperiode = $request->id_kurperiode;

    $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$id_kurperiode]);

    $cek_bap = Bap::where('id_kurperiode', $request->id_kurperiode)
      ->where('id_dosen', Auth::user()->id_user)
      ->where('pertemuan', $request->pertemuan)
      ->where('status', 'ACTIVE')
      ->count();

    if ($cek_bap > 0) {
      Alert::error('Maaf pertemuan yang diinput sudah ada', 'maaf');
      return redirect()->back();
    } elseif ($cek_bap == 0) {
      $jml_idkurperiode = count($kelas_gabungan);

      for ($i = 0; $i < $jml_idkurperiode; $i++) {
        $kurperiode = $kelas_gabungan[$i];
        $id_kur = $kurperiode->id_kurperiode;

        $path_tatapmuka = 'File_BAP' . '/' . $id_dosen . '/' . $id_kur . '/' . 'Kuliah Tatap Muka';

        if (!File::exists($path_tatapmuka)) {
          File::makeDirectory(public_path() . '/' . $path_tatapmuka, 0777, true);
        }

        $path_materikuliah = 'File_BAP' . '/' . $id_dosen . '/' . $id_kur . '/' . 'Materi Kuliah';

        if (!File::exists($path_materikuliah)) {
          File::makeDirectory($path_materikuliah);
        }

        $path_tugaskuliah = 'File_BAP' . '/' . $id_dosen . '/' . $id_kur . '/' . 'Tugas Kuliah';

        if (!File::exists($path_tugaskuliah)) {
          File::makeDirectory($path_tugaskuliah);
        }

        $bap = new Bap();
        $bap->id_kurperiode = $id_kur;
        $bap->id_dosen = $id_dosen;
        $bap->pertemuan = $request->pertemuan;
        $bap->tanggal = $request->tanggal;
        $bap->jam_mulai = $request->jam_mulai;
        $bap->jam_selsai = $request->jam_selsai;
        $bap->jenis_kuliah = $request->jenis_kuliah;
        $bap->id_tipekuliah = $request->id_tipekuliah;
        $bap->metode_kuliah = $request->metode_kuliah;
        $bap->materi_kuliah = $request->materi_kuliah;
        $bap->media_pembelajaran = $request->media_pembelajaran;

        if ($i == 0) {
          if ($request->hasFile('file_kuliah_tatapmuka')) {
            $file = $request->file('file_kuliah_tatapmuka');
            $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $id_kur . '/' . 'Kuliah Tatap Muka';
            $file->move($tujuan_upload, $nama_file);
            $bap->file_kuliah_tatapmuka = $nama_file;
          }

          if ($request->hasFile('file_materi_kuliah')) {
            $file = $request->file('file_materi_kuliah');
            $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $id_kur . '/' . 'Materi Kuliah';
            $file->move($tujuan_upload, $nama_file);
            $bap->file_materi_kuliah = $nama_file;
          }

          if ($request->hasFile('file_materi_tugas')) {
            $file = $request->file('file_materi_tugas');
            $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $id_kur . '/' . 'Tugas Kuliah';
            $file->move($tujuan_upload, $nama_file);
            $bap->file_materi_tugas = $nama_file;
          }
        } elseif ($i > 0) {
          if ($request->hasFile('file_kuliah_tatapmuka')) {
            $tes1 = $kelas_gabungan[0];
            $d1 = $tes1->id_kurperiode;
            $file = $request->file('file_kuliah_tatapmuka');
            $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $d1 . '/' . 'Kuliah Tatap Muka';

            $tes2 = $kelas_gabungan[$i];
            $d2 = $tes2->id_kurperiode;
            $path = 'File_BAP' . '/' . $id_dosen . '/' . $d2 . '/' . 'Kuliah Tatap Muka';
            $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

            File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

            $bap->file_kuliah_tatapmuka = $nama_file1;
          }

          if ($request->hasFile('file_materi_kuliah')) {
            $tes1 = $kelas_gabungan[0];
            $d1 = $tes1->id_kurperiode;
            $file = $request->file('file_materi_kuliah');
            $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $d1 . '/' . 'Materi Kuliah';

            $tes2 = $kelas_gabungan[$i];
            $d2 = $tes2->id_kurperiode;

            $path = 'File_BAP' . '/' . $id_dosen . '/' . $d2 . '/' . 'Materi Kuliah';

            $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

            File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

            $bap->file_materi_kuliah = $nama_file1;
          }

          if ($request->hasFile('file_materi_tugas')) {
            $tes1 = $kelas_gabungan[0];
            $d1 = $tes1->id_kurperiode;
            $file = $request->file('file_materi_tugas');
            $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $d1 . '/' . 'Tugas Kuliah';

            $tes2 = $kelas_gabungan[$i];
            $d2 = $tes2->id_kurperiode;

            $path = 'File_BAP' . '/' . $id_dosen . '/' . $d2 . '/' . 'Tugas Kuliah';

            $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

            File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

            $bap->file_materi_tugas = $nama_file1;
          }
        }

        $bap->save();

        $users = DB::table('bap')
          ->limit(1)
          ->orderByDesc('id_bap')
          ->first();

        $kuliah = new Kuliah_transaction();
        $kuliah->id_kurperiode = $id_kur;
        $kuliah->id_dosen = $id_dosen;
        $kuliah->id_tipekuliah = $request->id_tipekuliah;
        $kuliah->tanggal = $request->tanggal;
        $kuliah->akt_jam_mulai = $request->jam_mulai;
        $kuliah->akt_jam_selesai = $request->jam_selsai;
        $kuliah->id_bap = $users->id_bap;
        $kuliah->save();
      }

      return redirect('entri_bap_kprd/' . $id_kur)->with('success', 'Data Berhasil diupload');
    }
  }

  public function entri_absen($id)
  {
    $idbap = Bap::where('id_bap', $id)->get();
    foreach ($idbap as $keybap) {
      # code...
    }
    $idp = $keybap->id_kurperiode;

    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idp]);

    return view('kaprodi/bap/absensi', ['absen' => $kelas_gabungan, 'idk' => $idp, 'id' => $id]);
  }

  public function save_absensi(Request $request)
  {
    $id_record = $request->id_studentrecord;
    $jmlrecord = count($id_record);

    $id_kur = $request->id_kurperiode;

    $id_bp = $request->id_bap;

    $absen = $request->absensi;
    $jmlabsen = count($absen);

    $cek_bap = Bap::where('id_bap', $id_bp)
      ->select('id_bap', 'id_kurperiode', 'pertemuan')
      ->first();

    if ($absen != null) {
      $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$id_kur]);

      $jml_kelas_gabungan = count($cek_kelas_gabungan);

      //looping entri absen semua
      for ($i = 0; $i < $jml_kelas_gabungan; $i++) {
        $kelas = $cek_kelas_gabungan[$i];

        $id_kurperiode = $kelas->id_kurperiode;

        $absen_mahasiswa = DB::select('CALL absensi_mahasiswa_prodi_kelas(?)', [$id_kurperiode]);

        $jml_mhs = count($absen_mahasiswa);

        $cek_idbap_gabungan = Bap::where('id_kurperiode', $kelas->id_kurperiode)
          ->where('pertemuan', $cek_bap->pertemuan)
          ->where('status', 'ACTIVE')
          ->select('id_bap')
          ->first();

        for ($j = 0; $j < $jml_mhs; $j++) {
          $kurperiode = $absen_mahasiswa[$j];

          $abs = new Absensi_mahasiswa();
          $abs->id_bap = $cek_idbap_gabungan->id_bap;
          $abs->id_studentrecord = $kurperiode->id_studentrecord;
          $abs->save();
        }
      }

      //looping untuk entri mahasiswa yang hadir
      for ($i = 0; $i < $jmlabsen; $i++) {
        $abs = $request->absensi[$i];

        $cek_idstudentrecord = Student_record::where('id_studentrecord', $abs)
          ->select('id_studentrecord', 'id_kurperiode')
          ->first();

        $cek_kelas = DB::select('CALL kelas_gabungan_prodi_kelas(?,?)', [$cek_idstudentrecord->id_kurperiode, $cek_bap->pertemuan]);
        $jml_kelas = count($cek_kelas);

        for ($l = 0; $l < $jml_kelas; $l++) {
          $idkelas = $cek_kelas[$l];

          $bap = Bap::join('absensi_mahasiswa', 'bap.id_bap', '=', 'absensi_mahasiswa.id_bap')
            ->where('bap.id_kurperiode', $idkelas->id_kurperiode)
            ->where('bap.pertemuan', $cek_bap->pertemuan)
            ->where('absensi_mahasiswa.id_studentrecord', $abs)
            ->where('bap.id_bap', $idkelas->id_bap)
            ->where('bap.status', 'ACTIVE')
            ->update(['absensi_mahasiswa.absensi' => 'ABSEN']);
        }
      }

      //looping untuk jumlah mahasiswa dari dan tidak
      for ($h = 0; $h < $jml_kelas_gabungan; $h++) {
        $kelas = $cek_kelas_gabungan[$h];

        $id_kurperiode = $kelas->id_kurperiode;

        $cek_idbap_gabungan = Bap::where('id_kurperiode', $kelas->id_kurperiode)
          ->where('pertemuan', $cek_bap->pertemuan)
          ->where('status', 'ACTIVE')
          ->select('id_bap')
          ->first();

        $jml_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
          ->where('absensi', 'ABSEN')
          ->count();
        $jml_tdk_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
          ->where('absensi', 'HADIR')
          ->count();

        $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['hadir' => $jml_hadir]);
        $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['tidak_hadir' => $jml_tdk_hadir]);
      }
    }

    return redirect('entri_bap_kprd/' . $id_kur);
  }

  public function edit_absen($id)
  {
    $kur = Bap::where('id_bap', $id)->first();

    $idk = $kur->id_kurperiode;
    $per = $kur->pertemuan;

    $p = DB::select('CALL editAbsenMahasiswa(?,?)', [$idk, $per]);
    // $p = DB::select('CALL editAbsenMhs(?,?)', array($idk, $per));

    return view('kaprodi/bap/edit_absen', ['idk' => $idk, 'abs' => $p, 'id' => $id]);
  }

  public function save_edit_absensi(Request $request)
  {
    //id BAP
    $id_bp = $request->id_bap;

    // cek bap yang sama
    $bap_gabungan = DB::select('CALL bap_gabungan(?)', [$id_bp]);
    $jml_bap_gabungan = count($bap_gabungan);

    //jumlah yang masuk/absen
    $absen = $request->absensi;

    //jumlah yang sebelumnya tidak masuk
    $absr = $request->abs;

    $cek_bap = Bap::where('id_bap', $id_bp)
      ->select('id_bap', 'id_kurperiode', 'pertemuan')
      ->first();

    if ($absen != null) {
      //looping untuk edit semua absen jadi HADIR
      for ($i = 0; $i < $jml_bap_gabungan; $i++) {
        $id_bap_gabungan = $bap_gabungan[$i];
        $get_id_bap = $id_bap_gabungan->id_bap;

        Absensi_mahasiswa::where('id_bap', $get_id_bap)->update(['absensi' => 'HADIR']);
      }

      $jmlabsen = count($absen);
      for ($i = 0; $i < $jmlabsen; $i++) {
        $abs = $request->absensi[$i];

        $idabsen = DB::select('CALL absensi_gabungan_prodi_kelas(?)', [$abs]);
        $jml_idabsen = count($idabsen);

        for ($j = 0; $j < $jml_idabsen; $j++) {
          $id_absensi = $idabsen[$j];

          Absensi_mahasiswa::where('id_absensi', $id_absensi->id_absensi)->update(['absensi' => 'ABSEN']);
        }
      }
    } elseif ($absen == null) {
      for ($i = 0; $i < $jml_bap_gabungan; $i++) {
        $id_bap_gabungan = $bap_gabungan[$i];
        $get_id_bap = $id_bap_gabungan->id_bap;

        Absensi_mahasiswa::where('id_bap', $get_id_bap)->update(['absensi' => 'HADIR']);
      }
    }

    if ($absr != null) {
      $jml_mhs = count($absr);
      for ($i = 0; $i < $jml_mhs; $i++) {
        $studentrecord = $absr[$i];
        $cek_idstudentrecord = Student_record::where('id_studentrecord', $studentrecord)->first();
        $cek_idkurperiode = $cek_idstudentrecord->id_kurperiode;

        $cek_bap_id = DB::select('CALL kelas_gabungan_prodi_kelas(?,?)', [$cek_idkurperiode, $cek_bap->pertemuan]);
        $jml_bap_id = count($cek_bap_id);
        for ($l = 0; $l < $jml_bap_id; $l++) {
          $bap_fix = $cek_bap_id[$l];

          $abs = new Absensi_mahasiswa();
          $abs->id_bap = $bap_fix->id_bap;
          $abs->id_studentrecord = $studentrecord;
          $abs->absensi = 'ABSEN';
          $abs->save();
        }
      }
    }

    $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$cek_bap->id_kurperiode]);
    $jml_kelas_gabungan = count($cek_kelas_gabungan);

    for ($h = 0; $h < $jml_kelas_gabungan; $h++) {
      $kelas = $cek_kelas_gabungan[$h];

      $id_kurperiode = $kelas->id_kurperiode;

      $cek_idbap_gabungan = Bap::where('id_kurperiode', $kelas->id_kurperiode)
        ->where('pertemuan', $cek_bap->pertemuan)
        ->where('status', 'ACTIVE')
        ->select('id_bap')
        ->first();

      $jml_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
        ->where('absensi', 'ABSEN')
        ->count();
      $jml_tdk_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
        ->where('absensi', 'HADIR')
        ->count();

      $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['hadir' => $jml_hadir]);
      $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['tidak_hadir' => $jml_tdk_hadir]);
    }

    $id_kur = $cek_bap->id_kurperiode;

    Alert::success('', 'Absen berhasil diedit')->autoclose(3500);
    return redirect('entri_bap_kprd/' . $id_kur);
  }

  public function view_bap($id)
  {
    $bp = Bap::where('id_bap', $id)->get();
    foreach ($bp as $dtbp) {
      # code...
    }

    $bap = Kurikulum_periode::join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_kurperiode', $dtbp->id_kurperiode)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
      ->get();
    foreach ($bap as $data) {
      # code...
    }
    $prd = $data->prodi;
    $tipe = $data->periode_tipe;
    $tahun = $data->periode_tahun;


    return view('kaprodi/bap/view_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
  }

  public function cetak($id)
  {
    $bp = Bap::where('id_bap', $id)->get();
    foreach ($bp as $dtbp) {
      # code...
    }

    $bap = Kurikulum_periode::join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_kurperiode', $dtbp->id_kurperiode)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
      ->get();
    foreach ($bap as $data) {
      # code...
    }
    $prd = $data->prodi;
    $tipe = $data->periode_tipe;
    $tahun = $data->periode_tahun;
    $bulan = array(
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    );
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('dosen/cetak_bap', ['d' => $d, 'm' => $m, 'y' => $y, 'prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
  }

  public function edit_bap($id)
  {
    $bap = Bap::where('id_bap', $id)->get();
    foreach ($bap as $key_bap) {
      # code...
    }
    return view('kaprodi/bap/edit_bap', ['id' => $id, 'bap' => $key_bap]);
  }

  public function simpanedit_bap(Request $request, $id)
  {
    $this->validate($request, [
      'pertemuan'               => 'required',
      'tanggal'                 => 'required',
      'jam_mulai'               => 'required',
      'jam_selsai'              => 'required',
      'jenis_kuliah'            => 'required',
      'id_tipekuliah'           => 'required',
      'metode_kuliah'           => 'required',
      'materi_kuliah'           => 'required',
      'file_kuliah_tatapmuka'   => 'mimes:jpg,jpeg|max:2000',
      'file_materi_kuliah' => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG,docx,DOCX,PDF|max:4000',
      'file_materi_tugas'       => 'mimes:jpg,jpeg|max:2000',

    ]);

    $bap                        = Bap::find($id);
    $bap->id_kurperiode         = $request->id_kurperiode;
    $bap->pertemuan             = $request->pertemuan;
    $bap->tanggal               = $request->tanggal;
    $bap->jam_mulai             = $request->jam_mulai;
    $bap->jam_selsai            = $request->jam_selsai;
    $bap->jenis_kuliah          = $request->jenis_kuliah;
    $bap->id_tipekuliah         = $request->id_tipekuliah;
    $bap->metode_kuliah         = $request->metode_kuliah;
    $bap->materi_kuliah         = $request->materi_kuliah;
    $bap->media_pembelajaran    = $request->media_pembelajaran;
    $bap->updated_by            = Auth::user()->name;

    if ($bap->file_kuliah_tatapmuka) {
      if ($request->hasFile('file_kuliah_tatapmuka')) {
        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Kuliah Tatap Muka/' . $bap->file_kuliah_tatapmuka);
        $file                               = $request->file('file_kuliah_tatapmuka');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Kuliah Tatap Muka';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_kuliah_tatapmuka        = $nama_file;
      }
    } else {
      if ($request->hasFile('file_kuliah_tatapmuka')) {
        $file                               = $request->file('file_kuliah_tatapmuka');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Kuliah Tatap Muka';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_kuliah_tatapmuka         = $nama_file;
      }
    }

    if ($bap->file_materi_kuliah) {
      if ($request->hasFile('file_materi_kuliah')) {
        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Materi Kuliah/' . $bap->file_materi_kuliah);
        $file                               = $request->file('file_materi_kuliah');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Materi Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_kuliah        = $nama_file;
      }
    } else {
      if ($request->hasFile('file_materi_kuliah')) {
        $file                               = $request->file('file_materi_kuliah');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Materi Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_kuliah            = $nama_file;
      }
    }

    if ($bap->file_materi_tugas) {
      if ($request->hasFile('file_materi_tugas')) {
        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Tugas Kuliah/' . $bap->file_materi_tugas);
        $file                               = $request->file('file_materi_tugas');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Tugas Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_tugas        = $nama_file;
      }
    } else {
      if ($request->hasFile('file_materi_tugas')) {
        $file                               = $request->file('file_materi_tugas');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Tugas Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_tugas            = $nama_file;
      }
    }


    $bap->save();

    Kuliah_transaction::where('id_bap', $id)
      ->update(['id_tipekuliah' => $request->id_tipekuliah]);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['tanggal' => $request->tanggal]);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['akt_jam_mulai' => $request->jam_mulai]);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['akt_jam_selesai' => $request->jam_selsai]);

    return redirect('entri_bap_kprd/' . $request->id_kurperiode);
  }

  public function delete_bap($id)
  {
    Bap::where('id_bap', $id)
      ->update(['status' => 'NOT ACTIVE']);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['status' => 'NOT ACTIVE']);

    Absensi_mahasiswa::where('id_bap', $id)
      ->update(['status' => 'NOT ACTIVE']);

    $idk = Bap::where('id_bap', $id)
      ->select('id_kurperiode')
      ->get();

    foreach ($idk as $key) {
      # code...
    }

    return redirect('entri_bap_kprd/' . $key->id_kurperiode);
  }

  public function sum_absen($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
      ->orderBy('student.nim', 'asc')
      ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 2)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 1)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 3)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 4)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 5)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 6)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 7)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 8)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 9)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 10)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 11)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 12)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 13)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 14)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 15)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 16)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    return view('kaprodi/bap/absensi_perkuliahan', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function print_absensi($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
      ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 2)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 1)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 3)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 4)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 5)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 6)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 7)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 8)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 9)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 10)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 11)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 12)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 13)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 14)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 15)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 16)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $bulan = array(
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    );
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('kaprodi/bap/cetak_absensi', ['d' => $d, 'm' => $m, 'y' => $y, 'abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function jurnal_bap($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select('kuliah_transaction.val_jam_selesai', 'kuliah_transaction.val_jam_mulai', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
      ->orderBy('bap.tanggal', 'ASC')
      ->get();

    return view('kaprodi/bap/jurnal_perkuliahan', ['bap' => $key, 'data' => $data]);
  }

  public function print_jurnal($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('prodi.prodi', $key->prodi)
      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
      ->first();

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select('kuliah_transaction.val_jam_selesai', 'kuliah_transaction.val_jam_mulai', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
      ->orderBy('bap.tanggal', 'ASC')
      ->get();

    $bulan = array(
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    );
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('kaprodi/bap/cetak_jurnal', ['cekkprd' => $cekkprd, 'd' => $d, 'm' => $m, 'y' => $y, 'bap' => $key, 'data' => $data]);
  }

  public function history_makul_dsn()
  {
    $iddsn = Auth::user()->id_user;

    $mkul = DB::select('CALL history_makul_diampu(?)', [$iddsn]);

    return view('kaprodi/record/history_makul_dsn', ['makul' => $mkul]);
  }

  public function cekmhs_dsn_his($id)
  {
    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absensi_mahasiswa_prodi_kelas(?)', [$id]);

    return view('kaprodi/record/list_mhs_dsn_his', ['ck' => $kelas_gabungan, 'ids' => $id]);
  }

  public function view_bap_his($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
      ->orderBy('bap.id_bap', 'ASC')
      ->get();

    return view('kaprodi/record/view_bap_his', ['bap' => $key, 'data' => $data]);
  }

  public function sum_absen_his($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
      ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 2)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 1)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 3)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 4)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 5)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 6)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 7)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 8)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 9)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 10)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 11)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 12)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 13)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 14)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 15)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 16)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    return view('kaprodi/record/absensi_perkuliahan_his', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function jurnal_bap_his($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select('kuliah_transaction.val_jam_selesai', 'kuliah_transaction.val_jam_mulai', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
      ->orderBy('bap.tanggal', 'ASC')
      ->get();

    return view('kaprodi/record/jurnal_perkuliahan_his', ['bap' => $key, 'data' => $data]);
  }

  public function data_ipk_kprd()
  {
    $ipk = DB::select("CALL getIpkMhs()");

    $angkatan = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student.active', 1)
      ->select(DB::raw('DISTINCT(student.idangkatan)'), 'angkatan.angkatan')
      ->orderBy('angkatan.angkatan', 'ASC')
      ->get();

    $prodi = Prodi::all();

    return view('kaprodi/master/data_ipk', compact('ipk', 'angkatan', 'prodi'));
  }

  public function export_nilai_ipk_kprd()
  {
    $nama_file = 'Nilai IPK Mahasiswa.xlsx';

    return Excel::download(new DataNilaiIpkMhsExport, $nama_file);
  }

  public function filter_ipk_mhs(Request $request)
  {
    $angkatan = $request->id_angkatan;
    $prodi = $request->kodeprodi;
    $ipk = DB::select('CALL filterIpk(?,?)', array($angkatan, $prodi));

    return view('kaprodi/master/filter_ipk_mhs', compact('ipk', 'angkatan', 'prodi'));
  }

  public function export_nilai_ipk_prodi(Request $request)
  {
    $angkatan = Angkatan::where('idangkatan', $request->id_angkatan)
      ->select('angkatan', 'idangkatan')
      ->first();
    $nama_angkatan = $angkatan->angkatan;
    $id_angkatan = $angkatan->idangkatan;

    $prodi = Prodi::where('kodeprodi', $request->kodeprodi)
      ->select('prodi', 'kodeprodi')
      ->first();
    $nama_prodi = $prodi->prodi;
    $id_prodi = $prodi->kodeprodi;

    $nama_file = 'Nilai IPK Mahasiswa Angkatan ' . $nama_angkatan . ' ' . $nama_prodi . '.xlsx';

    return Excel::download(new DataNilaiIpkMhsProdiExport($id_angkatan, $id_prodi), $nama_file);
  }

  public function nilai_mhs_kprd()
  {
    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;

    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
      ->select('id_prodi')
      ->first();
    $prodi = $iddosen->id_prodi;

    $nilai = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->where('kurikulum_periode.id_prodi', $prodi)
      ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', DB::raw('COUNT(student_record.id_student) as jml_mhs'), 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->groupBy('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->get();

    return view('kaprodi/master/rekap_nilai', compact('nilai'));
  }

  public function cek_nilai_mhs_kprd($id)
  {
    $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student_record.id_kurperiode', $id)
      ->where('student_record.status', 'TAKEN')
      ->select('student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
      ->get();

    return view('kaprodi/master/cek_nilai_mhs', compact('data'));
  }

  public function soal_uts_kprd()
  {
    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
      ->select('id_prodi')
      ->first();
    $prodi = $iddosen->id_prodi;

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;

    $soal = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->where('kurikulum_periode.id_prodi', $prodi)
      ->where('bap.jenis_kuliah', 'UTS')
      ->select('bap.id_bap', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'prodi.prodi', 'kelas.kelas', 'dosen.nama', 'bap.file_materi_kuliah', 'dosen.iddosen', 'bap.id_kurperiode')
      ->get();

    return view('kaprodi/master/cek_soal_uts', compact('soal'));
  }

  public function soal_uas_kprd()
  {
    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
      ->select('id_prodi')
      ->first();
    $prodi = $iddosen->id_prodi;

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;

    $soal = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->where('kurikulum_periode.id_prodi', $prodi)
      ->where('bap.jenis_kuliah', 'UAS')
      ->select('bap.id_bap', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'prodi.prodi', 'kelas.kelas', 'dosen.nama', 'bap.file_materi_kuliah', 'dosen.iddosen', 'bap.id_kurperiode')
      ->get();

    return view('kaprodi/master/cek_soal_uas', compact('soal'));
  }

  public function input_kat_kprd($id)
  {
    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

    $kurrr = $id;

    return view('kaprodi/matakuliah/input_kat_dsn', ['kuri' => $kurrr, 'ck' => $kelas_gabungan, 'id' => $id]);
  }

  public function save_nilai_KAT_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_KAT;

    $jml = count($jmlnil);

    for ($i = 0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
        ->where('id_kurtrans', $kur)
        ->select('id_studentrecord')
        ->get();

      $banyak = count($cekid);

      $nilai = $request->nilai_KAT[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_KAT   = 0;
          $entry->save();
        } elseif ($ceknl != null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_KAT   = $nilai;
          $entry->save();
        }
      } elseif ($banyak > 1) {

        if ($ceknl == null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_KAT' => 0]);
        } elseif ($ceknl != null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_KAT' => $nilai]);
        }
      }
    }


    //ke halaman list mahasiswa
    //cek setting nilai
    $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$request->id_kurperiode]);

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;
    $idkur = $request->id_kurperiode;
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function input_uts_kprd($id)
  {
    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

    $mkl = Kurikulum_periode::where('id_kurperiode', $id)->first();

    $kmkl = $mkl->id_makul;
    $kprd = $mkl->id_prodi;
    $kkls = $mkl->id_kelas;
    $kurrr = $id;

    return view('kaprodi/matakuliah/input_uts_dsn', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $kelas_gabungan, 'id' => $id]);
  }

  public function save_nilai_UTS_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_UTS;

    $jml = count($jmlnil);

    for ($i = 0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
        ->where('id_kurtrans', $kur)
        ->select('id_studentrecord')
        ->get();

      $banyak = count($cekid);

      $nilai = $request->nilai_UTS[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UTS   = 0;
          $entry->data_origin = 'eSIAM';
          $entry->save();
        } elseif ($ceknl != null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UTS   = $nilai;
          $entry->data_origin = 'eSIAM';
          $entry->save();
        }
      } elseif ($banyak > 1) {

        if ($ceknl == null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_UTS' => 0, 'data_origin' => 'eSIAM']);
        } elseif ($ceknl != null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_UTS' => $nilai, 'data_origin' => 'eSIAM']);
        }
      }
    }

    $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
    $jml_kelas = count($kelas_gabungan);

    for ($j = 0; $j < $jml_kelas; $j++) {
      $gabungan = $kelas_gabungan[$j];

      $id_kurperiode = Kurikulum_periode::where('id_kurperiode', $gabungan->id_kurperiode)->first();
      Ujian_transaction::where('id_periodetahun', $id_kurperiode->id_periodetahun)
        ->where('id_periodetipe', $id_kurperiode->id_periodetipe)
        ->where('jenis_ujian', 'UTS')
        ->where('id_prodi', $request->id_prodi)
        ->where('id_kelas', $request->id_kelas)
        ->where('id_makul', $request->id_makul)
        ->update(['aktual_pengoreksi' => Auth::user()->name, 'data_origin' => 'eSIAM']);
    }

    //ke halaman list mahasiswa
    //cek setting nilai
    $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$request->id_kurperiode]);

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;
    $idkur = $request->id_kurperiode;

    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function input_uas_kprd($id)
  {
    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

    $mkl = Kurikulum_periode::where('id_kurperiode', $id)->first();

    $kmkl = $mkl->id_makul;
    $kprd = $mkl->id_prodi;
    $kkls = $mkl->id_kelas;
    $kurrr = $id;

    return view('kaprodi/matakuliah/input_uas_dsn', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $kelas_gabungan, 'id' => $id]);
  }

  public function save_nilai_UAS_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_UAS;

    $jml = count($jmlnil);

    for ($i = 0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
        ->where('id_kurtrans', $kur)
        ->select('id_studentrecord')
        ->get();

      $banyak = count($cekid);

      $nilai = $request->nilai_UAS[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UAS   = 0;
          $entry->data_origin = 'eSIAM';
          $entry->save();
        } elseif ($ceknl != null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UAS   = $nilai;
          $entry->data_origin = 'eSIAM';
          $entry->save();
        }
      } elseif ($banyak > 1) {

        if ($ceknl == null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_UAS' => 0, 'data_origin' => 'eSIAM']);
        } elseif ($ceknl != null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_UAS' => $nilai, 'data_origin' => 'eSIAM']);
        }
      }
    }

    $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
    $jml_kelas = count($kelas_gabungan);

    for ($j = 0; $j < $jml_kelas; $j++) {
      $gabungan = $kelas_gabungan[$j];

      $id_kurperiode = Kurikulum_periode::where('id_kurperiode', $gabungan->id_kurperiode)->first();
      Ujian_transaction::where('id_periodetahun', $id_kurperiode->id_periodetahun)
        ->where('id_periodetipe', $id_kurperiode->id_periodetipe)
        ->where('jenis_ujian', 'UAS')
        ->where('id_prodi', $request->id_prodi)
        ->where('id_kelas', $request->id_kelas)
        ->where('id_makul', $request->id_makul)
        ->update(['aktual_pengoreksi' => Auth::user()->name, 'data_origin' => 'eSIAM']);
    }

    //ke halaman list mahasiswa
    //cek setting nilai
    $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
    //cek mahasiswa
    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$request->id_kurperiode]);

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;
    $idkur = $request->id_kurperiode;

    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function input_akhir_kprd($id)
  {
    //cek mahasiswa
    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student_record.id_kurperiode', $id)
      ->where('student_record.status', 'TAKEN')
      ->select(
        'student_record.id_kurtrans',
        'student_record.id_student',
        'student_record.id_studentrecord',
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'student_record.nilai_AKHIR',
        'student_record.nilai_AKHIR_angka'
      )
      ->orderBy('student.nim', 'ASC')
      ->get();

    $kurrr = $id;

    return view('kaprodi/matakuliah/input_akhir_dsn', ['kuri' => $kurrr, 'ck' => $cks, 'id' => $id]);
  }

  public function save_nilai_AKHIR_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_AKHIR_angka;
    $jml = count($jmlnil);
    for ($i = 0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
        ->where('id_kurtrans', $kur)
        ->select('id_studentrecord')
        ->get();
      $banyak = count($cekid);

      $nilai = $request->nilai_AKHIR_angka[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR_angka = 0;
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        } elseif ($ceknl != null) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR_angka = $nilai;
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        }

        if ($ceknl < 50) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'E';
          $ceknilai->nilai_ANGKA = '0';
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        } elseif ($ceknl < 60) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'D';
          $ceknilai->nilai_ANGKA = '1';
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        } elseif ($ceknl < 65) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'C';
          $ceknilai->nilai_ANGKA = '2';
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        } elseif ($ceknl < 70) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'C+';
          $ceknilai->nilai_ANGKA = '2.5';
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        } elseif ($ceknl < 75) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'B';
          $ceknilai->nilai_ANGKA = '3';
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        } elseif ($ceknl < 80) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'B+';
          $ceknilai->nilai_ANGKA = '3.5';
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        } elseif ($ceknl <= 100) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'A';
          $ceknilai->nilai_ANGKA = '4';
          $ceknilai->data_origin = 'eSIAM';
          $ceknilai->save();
        }
      } elseif ($banyak > 1) {
        if ($ceknl == null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR_angka' => 0, 'data_origin' => 'eSIAM']);
        } elseif ($ceknl != null) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR_angka' => $nilai, 'data_origin' => 'eSIAM']);
        }

        if ($ceknl < 50) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR' => 'E', 'data_origin' => 'eSIAM']);

          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_ANGKA' => '0', 'data_origin' => 'eSIAM']);
        } elseif ($ceknl < 60) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR' => 'D', 'data_origin' => 'eSIAM']);

          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_ANGKA' => '1', 'data_origin' => 'eSIAM']);
        } elseif ($ceknl < 65) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR' => 'C', 'data_origin' => 'eSIAM']);

          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_ANGKA' => '2', 'data_origin' => 'eSIAM']);
        } elseif ($ceknl < 70) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR' => 'C+', 'data_origin' => 'eSIAM']);

          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_ANGKA' => '2.5', 'data_origin' => 'eSIAM']);
        } elseif ($ceknl < 75) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR' => 'B', 'data_origin' => 'eSIAM']);

          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_ANGKA' => '3', 'data_origin' => 'eSIAM']);
        } elseif ($ceknl < 80) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR' => 'B+', 'data_origin' => 'eSIAM']);

          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_ANGKA' => '3.5', 'data_origin' => 'eSIAM']);
        } elseif ($ceknl <= 100) {
          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_AKHIR' => 'A', 'data_origin' => 'eSIAM']);

          Student_record::where('id_student', $stu)
            ->where('id_kurtrans', $kur)
            ->update(['nilai_ANGKA' => '4', 'data_origin' => 'eSIAM']);
        }
      }
    }

    //ke halaman list mahasiswa
    //cek setting nilai
    $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
    //cek mahasiswa
    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('student_record.status', 'TAKEN')
      ->select(
        'student_record.id_kurtrans',
        'student_record.id_student',
        'student_record.id_studentrecord',
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'student_record.nilai_KAT',
        'student_record.nilai_UTS',
        'student_record.nilai_UAS',
        'student_record.nilai_AKHIR',
        'student_record.nilai_AKHIR_angka'
      )
      ->orderBy('student.nim', 'ASC')
      ->get();

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;
    $idkur = $request->id_kurperiode;
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $cks, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function rekap_perkuliahan()
  {
    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
      ->select('id_prodi')
      ->first();
    $prodi = $iddosen->id_prodi;

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;


    $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.id_prodi', $prodi)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'kurikulum_periode.id_kurperiode', 'prodi.prodi')
      ->get();

    $jml = Kurikulum_periode::join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->where('bap.status', 'ACTIVE')
      ->select(DB::raw('COUNT(bap.id_kurperiode) as jml_per'), 'bap.id_kurperiode')
      ->groupBy('bap.id_kurperiode')
      ->get();

    return view('kaprodi/perkuliahan/rekap_perkuliahan', compact('data', 'jml'));
  }

  public function cek_rekapan($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select('kuliah_transaction.kurang_jam', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
      ->get();


    return view('kaprodi/perkuliahan/cek_bap', compact('key', 'data'));
  }

  public function cek_sum_absen($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
      ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 2)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 1)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 3)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 4)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 5)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 6)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 7)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 8)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 9)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 10)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 11)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 12)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 13)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 14)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 15)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 16)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    return view('kaprodi/perkuliahan/cek_absensi_perkuliahan', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function cek_print_absensi($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('prodi.prodi', $key->prodi)
      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
      ->first();


    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
      ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 2)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 1)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 3)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 4)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 5)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 6)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 7)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 8)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 9)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 10)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 11)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 12)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 13)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 14)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 15)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
      ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->where('bap.pertemuan', 16)
      ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
      ->get();

    $bulan = array(
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    );
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('kaprodi/perkuliahan/cek_cetak_absensi', ['cekkprd' => $cekkprd, 'd' => $d, 'm' => $m, 'y' => $y, 'abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function cek_jurnal_bap_kprd($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.id_dosen_2', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $dosen2 = Dosen::where('iddosen', $key->id_dosen_2)->get();
    foreach ($dosen2 as $keydsn) {
      // code...
    }
    if (count($dosen2) > 0) {
      $nama_dsn2 = $keydsn->nama . ', ' . $keydsn->akademik;
    } else {
      $nama_dsn2 = '';
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select(
        'kuliah_transaction.val_jam_selesai',
        'kuliah_transaction.val_jam_mulai',
        'kuliah_transaction.tanggal_validasi',
        'kuliah_transaction.payroll_check',
        'bap.id_bap',
        'bap.pertemuan',
        'bap.tanggal',
        'bap.jam_mulai',
        'bap.jam_selsai',
        'bap.materi_kuliah',
        'bap.metode_kuliah',
        'kuliah_tipe.tipe_kuliah',
        'bap.jenis_kuliah',
        'bap.hadir',
        'bap.tidak_hadir'
      )
      ->orderBy('bap.tanggal', 'ASC')
      ->get();

    return view('kaprodi/perkuliahan/cek_jurnal_perkuliahan', ['nama_dosen_2' => $nama_dsn2, 'bap' => $key, 'data' => $data]);
  }

  public function print_jurnal_cek_kprd($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();
    foreach ($bap as $key) {
      # code...
    }

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('prodi.prodi', $key->prodi)
      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
      ->first();

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
      ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
      ->where('bap.id_kurperiode', $id)
      ->where('bap.status', 'ACTIVE')
      ->select(
        'kuliah_transaction.val_jam_selesai',
        'kuliah_transaction.val_jam_mulai',
        'kuliah_transaction.tanggal_validasi',
        'kuliah_transaction.payroll_check',
        'bap.id_bap',
        'bap.pertemuan',
        'bap.tanggal',
        'bap.jam_mulai',
        'bap.jam_selsai',
        'bap.materi_kuliah',
        'bap.metode_kuliah',
        'kuliah_tipe.tipe_kuliah',
        'bap.jenis_kuliah',
        'bap.hadir',
        'bap.tidak_hadir'
      )
      ->orderBy('bap.tanggal', 'ASC')
      ->get();

    $bulan = array(
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    );
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('kaprodi/perkuliahan/cek_cetak_jurnal', ['cekkprd' => $cekkprd, 'd' => $d, 'm' => $m, 'y' => $y, 'bap' => $key, 'data' => $data]);
  }

  public function cek_view_bap($id)
  {
    $bp = Bap::where('id_bap', $id)->get();
    foreach ($bp as $dtbp) {
      # code...
    }

    $bap = Kurikulum_periode::join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_kurperiode', $dtbp->id_kurperiode)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
      ->get();
    foreach ($bap as $data) {
      # code...
    }
    $prd = $data->prodi;
    $tipe = $data->periode_tipe;
    $tahun = $data->periode_tahun;


    return view('kaprodi/perkuliahan/view_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
  }

  public function cek_print_bap($id)
  {
    $bp = Bap::where('id_bap', $id)->get();
    foreach ($bp as $dtbp) {
      # code...
    }

    $bap = Kurikulum_periode::join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_kurperiode', $dtbp->id_kurperiode)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
      ->get();
    foreach ($bap as $data) {
      # code...
    }
    $prd = $data->prodi;
    $tipe = $data->periode_tipe;
    $tahun = $data->periode_tahun;
    $bulan = array(
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember',
    );
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('kaprodi/perkuliahan/cetak_bap', ['d' => $d, 'm' => $m, 'y' => $y, 'prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
  }

  public function pembimbing_pkl()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->where('student.active', 1)
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
      ->select(
        DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
        'student.nim',
        'student.nama',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.validasi_baak'
      )
      ->groupBy(
        'student.nama',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.validasi_baak'
      )
      ->orderBy('student.nim', 'ASC')
      ->get();

    return view('kaprodi/prausta/pembimbing_pkl', compact('data'));
  }

  public function record_bim_pkl($id)
  {
    $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_setting_relasi.acc_seminar_sidang',
        'student.idstudent',
        'student.nim',
        'student.nama',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'kelas.kelas',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.tempat_prausta',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi'
      )
      ->first();

    $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
      ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->whereIn('prausta_master_kode.kode_prausta', ['FA-601', 'TI-601', 'TK-601'])
      ->get();

    return view('kaprodi/prausta/cek_bimbingan_pkl', compact('jdl', 'pkl'));
  }

  public function komentar_bimbingan_kprd(Request $request, $id)
  {
    $prd = Prausta_trans_bimbingan::find($id);
    $prd->komentar_bimbingan = $request->komentar_bimbingan;
    $prd->save();

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function val_bim_pkl($id)
  {
    $val = Prausta_trans_bimbingan::find($id);
    $val->validasi = 'SUDAH';
    $val->save();

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function status_judul(Request $request)
  {
    $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update(['acc_judul_dospem' => $request->acc_judul]);

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function acc_seminar_pkl($id)
  {
    $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['acc_seminar_sidang' => 'TERIMA']);

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function tolak_seminar_pkl($id)
  {
    $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['acc_seminar_sidang' => 'TOLAK']);

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function penguji_pkl()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      //->where('prausta_trans_hasil.status', 'ACTIVE')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [1, 2, 3])
      ->select(
        'prausta_setting_relasi.id_dosen_penguji_1',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'student.nim',
        'student.nama',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'kelas.kelas',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.tempat_prausta',
        'prausta_setting_relasi.acc_seminar_sidang',
        'prausta_trans_hasil.validasi'
      )
      ->get();

    return view('kaprodi/prausta/penguji_pkl', compact('data'));
  }

  public function isi_form_nilai_pkl($id)
  {
    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $form_dosbing = Prausta_master_penilaian::where('kategori', 1)
      ->where('jenis_form', 'Form Pembimbing')
      ->where('status', 'ACTIVE')
      ->get();

    $form_seminar = Prausta_master_penilaian::where('kategori', 1)
      ->where('jenis_form', 'Form Seminar')
      ->where('status', 'ACTIVE')
      ->get();

    return view('kaprodi/prausta/form_nilai_pkl', compact('data', 'id', 'form_dosbing', 'form_seminar'));
  }

  public function simpan_nilai_prakerin(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $nilai_pem_lap = $request->nilai_pembimbing_lapangan;
    $id_penilaian1 = $request->id_penilaian_prausta1;
    $id_penilaian2 = $request->id_penilaian_prausta2;
    $nilai1 = $request->nilai1;
    $nilai2 = $request->nilai2;

    $hitung_id_penilaian1 = count($id_penilaian1);
    $hitung_id_penilaian2 = count($id_penilaian2);

    for ($i = 0; $i < $hitung_id_penilaian1; $i++) {
      $id_nilai1 = $id_penilaian1[$i];
      $n1 = $nilai1[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai1;
      $usta->nilai = $n1;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
      $id_nilai2 = $id_penilaian2[$i];
      $n2 = $nilai2[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai2;
      $usta->nilai = $n2;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    $ceknilai_1 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 1)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $ceknilai_2 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 1)
      ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
      ->first();

    // $id_prausta = $request->id_settingrelasi_prausta;
    // $nilai_1 = $request->nilai_pembimbing_lapangan;
    // $nilai_2 = $request->total;
    // $nilai_3 = $request->totals;

    if ($nilai_pem_lap == null) {

      $huruf = ($ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 2;
    } elseif ($nilai_pem_lap != null) {

      $huruf = (($nilai_pem_lap + $ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 3);
    }

    $hasilavg = round($huruf, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }


    $usta = new Prausta_trans_hasil();
    $usta->id_settingrelasi_prausta = $id_prausta;
    $usta->nilai_1 = $nilai_pem_lap;
    $usta->nilai_2 = $ceknilai_1->nilai1;
    $usta->nilai_3 = $ceknilai_2->nilai2;
    $usta->nilai_huruf = $nilai_huruf;
    $usta->added_by = Auth::user()->name;
    $usta->status = 'ACTIVE';
    $usta->data_origin = 'eSIAM';
    $usta->save();

    Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
    return redirect('penguji_pkl_kprd');
  }

  public function edit_nilai_pkl_by_dosen_kprd($id)
  {
    $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $nilai_pkl = Prausta_trans_hasil::where('prausta_trans_hasil.id_settingrelasi_prausta', $id)->first();
    $nilai_1 = $nilai_pkl->nilai_1;

    $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 1)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
      ->get();

    $nilai_sem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 1)
      ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
      ->get();

    return view('kaprodi/prausta/edit_nilai_prakerin', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
  }

  public function put_nilai_prakerin_dosen_kprd(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $nilai_pem_lap = $request->nilai_pembimbing_lapangan;
    $id_penilaian1 = $request->id_penilaian_prausta1;
    $id_penilaian2 = $request->id_penilaian_prausta2;
    $nilai1 = $request->nilai1;
    $nilai2 = $request->nilai2;

    $hitung_id_penilaian1 = count($id_penilaian1);
    $hitung_id_penilaian2 = count($id_penilaian2);

    for ($i = 0; $i < $hitung_id_penilaian1; $i++) {
      $id_nilai1 = $id_penilaian1[$i];
      $n1 = $nilai1[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)
        ->update([
          'nilai' => $n1,
          'updated_by' => Auth::user()->name
        ]);
    }

    for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
      $id_nilai2 = $id_penilaian2[$i];
      $n2 = $nilai2[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)
        ->update([
          'nilai' => $n2,
          'updated_by' => Auth::user()->name
        ]);
    }

    $ceknilai_1 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 1)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $ceknilai_2 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 1)
      ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
      ->first();

    if ($nilai_pem_lap == null) {

      $huruf = ($ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 2;
    } elseif ($nilai_pem_lap != null) {

      $huruf = (($nilai_pem_lap + $ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 3);
    }

    $hasilavg = round($huruf, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }

    $usta = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
      ->update([
        'nilai_1' => $nilai_pem_lap,
        'nilai_2' => $ceknilai_1->nilai1,
        'nilai_3' => $ceknilai_2->nilai2,
        'nilai_huruf' => $nilai_huruf,
        'updated_by' => Auth::user()->name
      ]);

    Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
    return redirect('penguji_pkl_kprd');
  }

  public function pembimbing_sempro()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
      ->select(
        DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
        'student.nim',
        'student.nama',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.validasi_baak'
      )
      ->groupBy(
        'student.nama',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.validasi_baak'
      )
      ->orderBy('student.nim', 'ASC')
      ->get();

    return view('kaprodi/prausta/pembimbing_sempro', compact('data'));
  }

  public function record_bim_sempro($id)
  {
    $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_setting_relasi.acc_seminar_sidang',
        'student.idstudent',
        'student.nim',
        'student.nama',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'kelas.kelas',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.tempat_prausta',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi'
      )
      ->first();

    $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
      ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
      ->get();

    return view('kaprodi/prausta/cek_bimbingan_sempro', compact('jdl', 'pkl'));
  }

  public function penguji_sempro()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')

      ->where(function ($query)  use ($id) {
        $query->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
      })
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      //->where('prausta_trans_hasil.status', 'ACTIVE')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [4, 5, 6])
      ->select(
        'prausta_setting_relasi.id_dosen_penguji_1',
        'prausta_setting_relasi.id_dosen_penguji_2',
        'prausta_setting_relasi.id_dosen_pembimbing',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'student.nim',
        'student.nama',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'kelas.kelas',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.id_student',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.tempat_prausta',
        'prausta_setting_relasi.acc_seminar_sidang',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi',
        'prausta_setting_relasi.validasi_pembimbing',
        'prausta_setting_relasi.validasi_penguji_1',
        'prausta_setting_relasi.validasi_penguji_2',
        'prausta_trans_hasil.validasi'
      )
      ->get();

    return view('kaprodi/prausta/penguji_sempro', compact('data', 'id'));
  }

  public function isi_form_nilai_proposal_dospem($id)
  {
    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $form_dosbing = Prausta_master_penilaian::where('kategori', 2)
      ->where('jenis_form', 'Form Pembimbing')
      ->where('status', 'ACTIVE')
      ->get();

    return view('kaprodi/prausta/form_nilai_sempro_dospem', compact('data', 'id', 'form_dosbing'));
  }

  public function simpan_nilai_sempro_dospem(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_penilaian_prausta;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai;
      $usta->nilai = $n;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dospem = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    if (($cek_nilai) == null) {
      $hasil = $nilai_dospem / 3;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $usta = new Prausta_trans_hasil();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->nilai_1 = $nilai_dospem;
      $usta->nilai_huruf = $nilai_huruf;
      $usta->added_by = Auth::user()->name;
      $usta->status = 'ACTIVE';
      $usta->data_origin = 'eSIAM';
      $usta->save();
    } elseif (($cek_nilai) != null) {
      $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
        ->update([
          'nilai_1' => $nilai_dospem,
          'nilai_huruf' => $nilai_huruf
        ]);
    }

    Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
    return redirect('penguji_sempro_kprd');
  }

  public function isi_form_nilai_proposal_dosji1($id)
  {
    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $form_peng1 = Prausta_master_penilaian::where('kategori', 2)
      ->where('jenis_form', 'Form Penguji I')
      ->where('status', 'ACTIVE')
      ->get();

    return view('kaprodi/prausta/form_nilai_sempro_dosji1', compact('data', 'id', 'form_peng1'));
  }

  public function simpan_nilai_sempro_dosji1(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_penilaian_prausta;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai;
      $usta->nilai = $n;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dosji1 = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    if (($cek_nilai) == null) {
      $hasil = $nilai_dosji1 / 3;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $usta = new Prausta_trans_hasil();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->nilai_2 = $nilai_dosji1;
      $usta->nilai_huruf = $nilai_huruf;
      $usta->added_by = Auth::user()->name;
      $usta->status = 'ACTIVE';
      $usta->data_origin = 'eSIAM';
      $usta->save();
    } elseif (($cek_nilai) != null) {
      $hasil = ($nilai_dosji1 + $cek_nilai->nilai_1 + $cek_nilai->nilai_3) / 3;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
        ->update([
          'nilai_2' => $nilai_dosji1,
          'nilai_huruf' => $nilai_huruf
        ]);
    }

    Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
    return redirect('penguji_sempro_kprd');
  }

  public function isi_form_nilai_proposal_dosji2($id)
  {
    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $form_peng2 = Prausta_master_penilaian::where('kategori', 2)
      ->where('jenis_form', 'Form Penguji II')
      ->where('status', 'ACTIVE')
      ->get();

    return view('kaprodi/prausta/form_nilai_sempro_dosji2', compact('data', 'id', 'form_peng2'));
  }

  public function simpan_nilai_sempro_dosji2(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_penilaian_prausta;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai;
      $usta->nilai = $n;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dosji2 = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    if (($cek_nilai) == null) {
      $hasil = $nilai_dosji2 / 3;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $usta = new Prausta_trans_hasil();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->nilai_3 = $nilai_dosji2;
      $usta->nilai_huruf = $nilai_huruf;
      $usta->added_by = Auth::user()->name;
      $usta->status = 'ACTIVE';
      $usta->data_origin = 'eSIAM';
      $usta->save();
    } elseif (($cek_nilai) != null) {
      $hasil = ($nilai_dosji2 + $cek_nilai->nilai_1 + $cek_nilai->nilai_2) / 3;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
        ->update([
          'nilai_3' => $nilai_dosji2,
          'nilai_huruf' => $nilai_huruf
        ]);
    }

    Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
    return redirect('penguji_sempro_kprd');
  }

  public function validasi_dospem_kprd($id)
  {
    $date = date('Y-m-d');

    $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
      ->update([
        'validasi_pembimbing' => 'SUDAH',
        'tgl_val_pembimbing' => $date
      ]);

    return redirect()->back();
  }

  public function validasi_dosji1_kprd($id)
  {
    $date = date('Y-m-d');

    $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
      ->update([
        'validasi_penguji_1' => 'SUDAH',
        'tgl_val_penguji_1' => $date
      ]);

    return redirect()->back();
  }

  public function validasi_dosji2_kprd($id)
  {
    $date = date('Y-m-d');

    $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
      ->update([
        'validasi_penguji_2' => 'SUDAH',
        'tgl_val_penguji_2' => $date
      ]);

    return redirect()->back();
  }

  public function edit_nilai_sempro_by_dospem_kprd($id)
  {
    $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(
        'prausta_master_penilaian.komponen',
        'prausta_master_penilaian.bobot',
        'prausta_master_penilaian.acuan',
        'prausta_trans_penilaian.nilai',
        'prausta_trans_penilaian.id_trans_penilaian'
      )
      ->get();

    return view('kaprodi/prausta/edit_nilai_sempro_dospem', compact('nilai_pem', 'datadiri', 'id'));
  }

  public function put_nilai_sempro_dospem_kprd(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_trans_penilaian;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
        ->update([
          'nilai' => $n,
          'updated_by' => Auth::user()->name
        ]);
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dospem = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
    $hasilavg = round($hasil, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }

    $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
      ->update([
        'nilai_1' => $nilai_dospem,
        'nilai_huruf' => $nilai_huruf,
        'updated_by' => Auth::user()->name
      ]);

    Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
    return redirect('penguji_sempro_kprd');
  }

  public function edit_nilai_sempro_by_dospeng1_kprd($id)
  {
    $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(
        'prausta_master_penilaian.komponen',
        'prausta_master_penilaian.bobot',
        'prausta_master_penilaian.acuan',
        'prausta_trans_penilaian.nilai',
        'prausta_trans_penilaian.id_trans_penilaian'
      )
      ->get();

    return view('kaprodi/prausta/edit_nilai_sempro_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
  }

  public function put_nilai_sempro_dospeng1_kprd(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_trans_penilaian;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
        ->update([
          'nilai' => $n,
          'updated_by' => Auth::user()->name
        ]);
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
      ->first();

    $nilai_dospem = $ceknilai->nilai2;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
    $hasilavg = round($hasil, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }

    $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
      ->update([
        'nilai_2' => $nilai_dospem,
        'nilai_huruf' => $nilai_huruf,
        'updated_by' => Auth::user()->name
      ]);

    Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
    return redirect('penguji_sempro_kprd');
  }

  public function edit_nilai_sempro_by_dospeng2_kprd($id)
  {
    $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(
        'prausta_master_penilaian.komponen',
        'prausta_master_penilaian.bobot',
        'prausta_master_penilaian.acuan',
        'prausta_trans_penilaian.nilai',
        'prausta_trans_penilaian.id_trans_penilaian'
      )
      ->get();

    return view('kaprodi/prausta/edit_nilai_sempro_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
  }

  public function put_nilai_sempro_dospeng2_kprd(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_trans_penilaian;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
        ->update([
          'nilai' => $n,
          'updated_by' => Auth::user()->name
        ]);
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
      ->first();

    $nilai_dospem = $ceknilai->nilai3;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
    $hasilavg = round($hasil, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }

    $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
      ->update([
        'nilai_3' => $nilai_dospem,
        'nilai_huruf' => $nilai_huruf,
        'updated_by' => Auth::user()->name
      ]);

    Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
    return redirect('penguji_sempro_kprd');
  }

  public function pembimbing_ta()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
      ->select(
        DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
        'student.nim',
        'student.nama',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.validasi_baak'
      )
      ->groupBy(
        'student.nama',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.validasi_baak'
      )
      ->orderBy('student.nim', 'ASC')
      ->get();

    return view('kaprodi/prausta/pembimbing_ta', compact('data'));
  }

  public function record_bim_ta($id)
  {
    $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_setting_relasi.acc_seminar_sidang',
        'student.idstudent',
        'student.nim',
        'student.nama',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'kelas.kelas',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.tempat_prausta',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi',
        'prausta_setting_relasi.file_plagiarisme'
      )
      ->first();

    $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
      ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
      ->get();

    return view('kaprodi/prausta/cek_bimbingan_ta', compact('jdl', 'pkl'));
  }

  public function penguji_ta()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')

      ->where(function ($query)  use ($id) {
        $query->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
      })
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      //->where('prausta_trans_hasil.status', 'ACTIVE')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [7, 8, 9])
      ->select(
        'prausta_setting_relasi.id_dosen_penguji_1',
        'prausta_setting_relasi.id_dosen_penguji_2',
        'prausta_setting_relasi.id_dosen_pembimbing',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'student.nim',
        'student.nama',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'kelas.kelas',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.tempat_prausta',
        'prausta_setting_relasi.acc_seminar_sidang',
        'prausta_trans_hasil.validasi',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi',
        'prausta_setting_relasi.id_student',
        'prausta_setting_relasi.file_plagiarisme'
      )
      ->get();

    return view('kaprodi/prausta/penguji_ta', compact('data', 'id'));
  }

  public function isi_form_nilai_ta_dospem($id)
  {
    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $form_dosbing = Prausta_master_penilaian::where('kategori', 3)
      ->where('jenis_form', 'Form Pembimbing')
      ->where('status', 'ACTIVE')
      ->get();

    return view('kaprodi/prausta/form_nilai_ta_dospem', compact('data', 'id', 'form_dosbing'));
  }

  public function simpan_nilai_ta_dospem(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_penilaian_prausta;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai;
      $usta->nilai = $n;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dospem = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    if (($cek_nilai) == null) {
      $hasil = $nilai_dospem * 60 / 100;

      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $usta = new Prausta_trans_hasil();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->nilai_1 = $nilai_dospem;
      $usta->nilai_huruf = $nilai_huruf;
      $usta->added_by = Auth::user()->name;
      $usta->status = 'ACTIVE';
      $usta->data_origin = 'eSIAM';
      $usta->save();
    } elseif (($cek_nilai) != null) {
      $hasil = (($nilai_dospem * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100) + ($cek_nilai->nilai_3 * 20 / 100));
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
        ->update([
          'nilai_1' => $nilai_dospem,
          'nilai_huruf' => $nilai_huruf
        ]);
    }

    Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
    return redirect('penguji_ta_kprd');
  }

  public function isi_form_nilai_ta_dosji1($id)
  {
    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $form_peng1 = Prausta_master_penilaian::where('kategori', 3)
      ->where('jenis_form', 'Form Penguji I')
      ->where('status', 'ACTIVE')
      ->get();

    return view('kaprodi/prausta/form_nilai_ta_dosji1', compact('data', 'id', 'form_peng1'));
  }

  public function simpan_nilai_ta_dosji1(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_penilaian_prausta;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai;
      $usta->nilai = $n;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dosji1 = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    if (($cek_nilai) == null) {
      $hasil = $nilai_dosji1 * 20 / 100;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $usta = new Prausta_trans_hasil();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->nilai_2 = $nilai_dosji1;
      $usta->nilai_huruf = $nilai_huruf;
      $usta->added_by = Auth::user()->name;
      $usta->status = 'ACTIVE';
      $usta->data_origin = 'eSIAM';
      $usta->save();
    } elseif (($cek_nilai) != null) {
      $hasil = (($nilai_dosji1 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_3 * 20 / 100));
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
        ->update([
          'nilai_2' => $nilai_dosji1,
          'nilai_huruf' => $nilai_huruf
        ]);
    }

    Alert::success('', 'Nilai berhasil dientri')->autoclose(3500);
    return redirect('penguji_ta_kprd');
  }

  public function isi_form_nilai_ta_dosji2($id)
  {
    $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $form_peng2 = Prausta_master_penilaian::where('kategori', 3)
      ->where('jenis_form', 'Form Penguji II')
      ->where('status', 'ACTIVE')
      ->get();

    return view('kaprodi/prausta/form_nilai_ta_dosji2', compact('data', 'id', 'form_peng2'));
  }

  public function simpan_nilai_ta_dosji2(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_penilaian_prausta;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = new Prausta_trans_penilaian();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->id_penilaian_prausta = $id_nilai;
      $usta->nilai = $n;
      $usta->created_by = Auth::user()->name;
      $usta->save();
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dosji2 = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    if (($cek_nilai) == null) {
      $hasil = $nilai_dosji2 * 20 / 100;
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $usta = new Prausta_trans_hasil();
      $usta->id_settingrelasi_prausta = $id_prausta;
      $usta->nilai_3 = $nilai_dosji2;
      $usta->nilai_huruf = $nilai_huruf;
      $usta->added_by = Auth::user()->name;
      $usta->status = 'ACTIVE';
      $usta->data_origin = 'eSIAM';
      $usta->save();
    } elseif (($cek_nilai) != null) {
      $hasil = (($nilai_dosji2 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100));
      $hasilavg = round($hasil, 2);

      if ($hasilavg >= 80) {
        $nilai_huruf = 'A';
      } elseif ($hasilavg >= 75) {
        $nilai_huruf = 'B+';
      } elseif ($hasilavg >= 70) {
        $nilai_huruf = 'B';
      } elseif ($hasilavg >= 65) {
        $nilai_huruf = 'C+';
      } elseif ($hasilavg >= 60) {
        $nilai_huruf = 'C';
      } elseif ($hasilavg >= 50) {
        $nilai_huruf = 'D';
      } elseif ($hasilavg >= 0) {
        $nilai_huruf = 'E';
      }

      $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
        ->update([
          'nilai_3' => $nilai_dosji2,
          'nilai_huruf' => $nilai_huruf
        ]);
    }

    Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
    return redirect('penguji_ta_kprd');
  }

  public function bimbingan_prakerin()
  {
    $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->where('kaprodi.id_dosen', Auth::user()->id_user)
      ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama', 'prodi.id_prodi', 'prodi.kodeprodi')
      ->first();

    $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
      ->where('student.active', 1)
      ->where('student.kodeprodi', $cek->kodeprodi)
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
      ->get();

    $kode = $cek->kodeprodi;

    return view('kaprodi/monitoring/bimbingan_prakerin', compact('data', 'kode'));
  }

  public function detail_bim_prakerin($id)
  {
    $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_trans_bimbingan.tanggal_bimbingan',
        'prausta_trans_bimbingan.file_bimbingan',
        'prausta_trans_bimbingan.remark_bimbingan',
        'prausta_trans_bimbingan.komentar_bimbingan',
        'prausta_trans_bimbingan.validasi',
        'student.idstudent'
      )
      ->get();

    $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi',
        'student.idstudent',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.dosen_pembimbing',
        'dosen.akademik'
      )
      ->first();

    return view('kaprodi/monitoring/detail_bimbingan_prakerin', compact('data', 'mhs'));
  }

  public function bimbingan_sempro()
  {
    $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->where('kaprodi.id_dosen', Auth::user()->id_user)
      ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama', 'prodi.id_prodi', 'prodi.kodeprodi')
      ->first();


    $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
      ->where('student.active', 1)
      ->where('student.kodeprodi', $cek->kodeprodi)
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
      ->get();

    $kode = $cek->kodeprodi;

    return view('kaprodi/monitoring/bimbingan_sempro', compact('data', 'kode'));
  }

  public function detail_bim_sempro($id)
  {
    $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_trans_bimbingan.tanggal_bimbingan',
        'prausta_trans_bimbingan.file_bimbingan',
        'prausta_trans_bimbingan.remark_bimbingan',
        'prausta_trans_bimbingan.komentar_bimbingan',
        'prausta_trans_bimbingan.validasi',
        'student.idstudent'
      )
      ->get();

    $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi',
        'student.idstudent',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.dosen_pembimbing',
        'dosen.akademik'
      )
      ->first();

    return view('kaprodi/monitoring/detail_bimbingan_sempro', compact('data', 'mhs'));
  }

  public function bimbingan_ta()
  {
    $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->where('kaprodi.id_dosen', Auth::user()->id_user)
      ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama', 'prodi.id_prodi', 'prodi.kodeprodi')
      ->first();

    $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
      ->where('student.active', 1)
      ->where('student.kodeprodi', $cek->kodeprodi)
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
      ->get();

    $kode = $cek->kodeprodi;

    return view('kaprodi/monitoring/bimbingan_ta', compact('data', 'kode'));
  }

  public function detail_bim_ta($id)
  {
    $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_trans_bimbingan.tanggal_bimbingan',
        'prausta_trans_bimbingan.file_bimbingan',
        'prausta_trans_bimbingan.remark_bimbingan',
        'prausta_trans_bimbingan.komentar_bimbingan',
        'prausta_trans_bimbingan.validasi',
        'student.idstudent'
      )
      ->get();

    $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_setting_relasi.file_draft_laporan',
        'prausta_setting_relasi.file_laporan_revisi',
        'student.idstudent',
        'prausta_setting_relasi.id_settingrelasi_prausta',
        'prausta_setting_relasi.dosen_pembimbing',
        'dosen.akademik'
      )
      ->first();

    return view('kaprodi/monitoring/detail_bimbingan_ta', compact('data', 'mhs'));
  }

  public function excel_bimbingan_prakerin(Request $request)
  {
    $kode = $request->kodeprodi;
    $prodi = Prodi::where('kodeprodi', $kode)->first();
    $nama_prd = $prodi->prodi;

    $nama_file = 'Data Bimbingan Prakerin' . ' ' . $nama_prd  . '.xlsx';
    return Excel::download(new DataBimbinganPrakerinExport($kode), $nama_file);
  }

  public function excel_bimbingan_sempro(Request $request)
  {
    $kode = $request->kodeprodi;
    $prodi = Prodi::where('kodeprodi', $kode)->first();
    $nama_prd = $prodi->prodi;

    $nama_file = 'Data Bimbingan Sempro' . ' ' . $nama_prd  . '.xlsx';
    return Excel::download(new DataBimbinganSemproExport($kode), $nama_file);
  }

  public function excel_bimbingan_ta(Request $request)
  {
    $kode = $request->kodeprodi;
    $prodi = Prodi::where('kodeprodi', $kode)->first();
    $nama_prd = $prodi->prodi;

    $nama_file = 'Data Bimbingan TA' . ' ' . $nama_prd  . '.xlsx';
    return Excel::download(new DataBimbinganTaExport($kode), $nama_file);
  }

  public function nilai_prakerin_kaprodi()
  {
    $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->where('kaprodi.id_dosen', Auth::user()->id_user)
      ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama', 'prodi.id_prodi', 'prodi.kodeprodi')
      ->first();

    $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
      ->where('student.active', 1)
      ->where('student.kodeprodi', $cek->kodeprodi)
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'prausta_trans_hasil.id_settingrelasi_prausta'
      )
      ->orderBy('student.nim', 'DESC')
      ->get();

    return view('kaprodi/monitoring/nilai_prakerin', compact('data'));
  }

  public function nilai_sempro_kaprodi()
  {
    $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->where('kaprodi.id_dosen', Auth::user()->id_user)
      ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama', 'prodi.id_prodi', 'prodi.kodeprodi')
      ->first();

    $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
      ->where('student.active', 1)
      ->where('student.kodeprodi', $cek->kodeprodi)
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'prausta_trans_hasil.id_settingrelasi_prausta'
      )
      ->orderBy('student.nim', 'DESC')
      ->get();

    return view('kaprodi/monitoring/nilai_sempro', compact('data'));
  }

  public function nilai_ta_kaprodi()
  {
    $cek = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->where('kaprodi.id_dosen', Auth::user()->id_user)
      ->select('prodi.id_prodi', 'prodi.prodi', 'dosen.nama', 'prodi.id_prodi', 'prodi.kodeprodi')
      ->first();

    $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
      ->where('student.active', 1)
      ->where('student.kodeprodi', $cek->kodeprodi)
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'prausta_trans_hasil.id_settingrelasi_prausta'
      )
      ->orderBy('student.nim', 'DESC')
      ->get();

    return view('kaprodi/monitoring/nilai_ta', compact('data'));
  }

  public function edit_nilai_ta_by_dospem_kprd($id)
  {
    $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(
        'prausta_master_penilaian.komponen',
        'prausta_master_penilaian.bobot',
        'prausta_master_penilaian.acuan',
        'prausta_trans_penilaian.nilai',
        'prausta_trans_penilaian.id_trans_penilaian'
      )
      ->get();

    return view('kaprodi/prausta/edit_nilai_ta', compact('nilai_pem', 'datadiri', 'id'));
  }

  public function put_nilai_ta_dospem_kprd(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_trans_penilaian;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
        ->update([
          'nilai' => $n,
          'updated_by' => Auth::user()->name
        ]);
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
      ->first();

    $nilai_dospem = $ceknilai->nilai1;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();


    $hasil = (($nilai_dospem * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100) + ($cek_nilai->nilai_3 * 20 / 100));
    $hasilavg = round($hasil, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }

    $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
      ->update([
        'nilai_1' => $nilai_dospem,
        'nilai_huruf' => $nilai_huruf,
        'updated_by' => Auth::user()->name
      ]);

    Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
    return redirect('penguji_ta_kprd');
  }

  public function edit_nilai_ta_by_dospeng1_kprd($id)
  {
    $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(
        'prausta_master_penilaian.komponen',
        'prausta_master_penilaian.bobot',
        'prausta_master_penilaian.acuan',
        'prausta_trans_penilaian.nilai',
        'prausta_trans_penilaian.id_trans_penilaian'
      )
      ->get();

    return view('kaprodi/prausta/edit_nilai_ta_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
  }

  public function put_nilai_ta_dospeng1_kprd(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_trans_penilaian;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
        ->update([
          'nilai' => $n,
          'updated_by' => Auth::user()->name
        ]);
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
      ->first();

    $nilai_dospeng1 = $ceknilai->nilai2;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    $hasil = (($nilai_dospeng1 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_3 * 20 / 100));

    $hasilavg = round($hasil, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }

    $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
      ->update([
        'nilai_2' => $nilai_dospeng1,
        'nilai_huruf' => $nilai_huruf,
        'updated_by' => Auth::user()->name
      ]);

    Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
    return redirect('penguji_ta_kprd');
  }

  public function edit_nilai_ta_by_dospeng2_kprd($id)
  {
    $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
      ->first();

    $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
      ->where('prausta_master_penilaian.kategori', 3)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(
        'prausta_master_penilaian.komponen',
        'prausta_master_penilaian.bobot',
        'prausta_master_penilaian.acuan',
        'prausta_trans_penilaian.nilai',
        'prausta_trans_penilaian.id_trans_penilaian'
      )
      ->get();

    return view('kaprodi/prausta/edit_nilai_ta_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
  }

  public function put_nilai_ta_dospeng2_kprd(Request $request)
  {
    $id_prausta = $request->id_settingrelasi_prausta;
    $id_penilaian = $request->id_trans_penilaian;
    $nilai = $request->nilai;

    $hit_jml_nilai = count($id_penilaian);

    for ($i = 0; $i < $hit_jml_nilai; $i++) {
      $id_nilai = $id_penilaian[$i];
      $n = $nilai[$i];

      $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
        ->update([
          'nilai' => $n,
          'updated_by' => Auth::user()->name
        ]);
    }

    $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
      ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
      ->where('prausta_master_penilaian.kategori', 2)
      ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
      ->where('prausta_master_penilaian.status', 'ACTIVE')
      ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
      ->first();

    $nilai_dospeng2 = $ceknilai->nilai3;

    $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    $hasil = (($nilai_dospeng2 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100));

    $hasilavg = round($hasil, 2);

    if ($hasilavg >= 80) {
      $nilai_huruf = 'A';
    } elseif ($hasilavg >= 75) {
      $nilai_huruf = 'B+';
    } elseif ($hasilavg >= 70) {
      $nilai_huruf = 'B';
    } elseif ($hasilavg >= 65) {
      $nilai_huruf = 'C+';
    } elseif ($hasilavg >= 60) {
      $nilai_huruf = 'C';
    } elseif ($hasilavg >= 50) {
      $nilai_huruf = 'D';
    } elseif ($hasilavg >= 0) {
      $nilai_huruf = 'E';
    }

    $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
      ->update([
        'nilai_3' => $nilai_dospeng2,
        'nilai_huruf' => $nilai_huruf,
        'updated_by' => Auth::user()->name
      ]);

    Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
    return redirect('penguji_ta_kprd');
  }

  public function jadwal_seminar_prakerin_kprd()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->where(function ($query)  use ($id) {
        $query->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
      })
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [1, 2, 3])
      ->select(
        'student.nama',
        'student.nim',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'prausta_setting_relasi.dosen_pembimbing',
        'prausta_setting_relasi.dosen_penguji_1',
        'prausta_setting_relasi.tanggal_selesai',
        'prausta_setting_relasi.jam_mulai_sidang',
        'prausta_setting_relasi.jam_selesai_sidang',
        'prausta_setting_relasi.ruangan'
      )
      ->get();

    return view('kaprodi/prausta/jadwal_seminar_prakerin', compact('data'));
  }

  public function jadwal_seminar_proposal_kprd()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
      ->where(function ($query)  use ($id) {
        $query->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
      })
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [4, 5, 6])
      ->select(
        'student.nama',
        'student.nim',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'prausta_setting_relasi.dosen_pembimbing',
        'prausta_setting_relasi.dosen_penguji_1',
        'prausta_setting_relasi.dosen_penguji_2',
        'prausta_setting_relasi.tanggal_selesai',
        'prausta_setting_relasi.jam_mulai_sidang',
        'prausta_setting_relasi.jam_selesai_sidang',
        'prausta_setting_relasi.ruangan'
      )
      ->get();

    return view('kaprodi/prausta/jadwal_seminar_proposal', compact('data'));
  }

  public function jadwal_sidang_ta_kprd()
  {
    $id = Auth::user()->id_user;

    $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
      ->where(function ($query)  use ($id) {
        $query->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
          ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
      })
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [7, 8, 9])
      ->select(
        'student.nama',
        'student.nim',
        'prausta_master_kode.kode_prausta',
        'prausta_master_kode.nama_prausta',
        'prodi.prodi',
        'prausta_setting_relasi.dosen_pembimbing',
        'prausta_setting_relasi.dosen_penguji_1',
        'prausta_setting_relasi.dosen_penguji_2',
        'prausta_setting_relasi.tanggal_selesai',
        'prausta_setting_relasi.jam_mulai_sidang',
        'prausta_setting_relasi.jam_selesai_sidang',
        'prausta_setting_relasi.ruangan'
      )
      ->get();

    return view('kaprodi/prausta/jadwal_sidang_ta', compact('data'));
  }

  public function upload_soal_dsn_kprd()
  {
    $id = Auth::user()->id_user;

    $data = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->leftjoin('soal_ujian', 'kurikulum_periode.id_kurperiode', '=', 'soal_ujian.id_kurperiode')
      ->where('kurikulum_periode.id_dosen', $id)
      ->where('periode_tahun.status', 'ACTIVE')
      ->where('periode_tipe.status', 'ACTIVE')
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select(
        'kurikulum_periode.id_kurperiode',
        'matakuliah.kode',
        'matakuliah.makul',
        'prodi.prodi',
        'kelas.kelas',
        'semester.semester',
        'soal_ujian.soal_uts',
        'soal_ujian.soal_uas'
      )
      ->get();

    return view('kaprodi/soal/soal_ujian', compact('data'));
  }

  public function simpan_soal_uts_dsn_kprd(Request $request)
  {
    $message = [
      'max' => ':attribute harus diisi maksimal :max KB',
      'required' => ':attribute wajib diisi'
    ];
    $this->validate(
      $request,
      [
        'soal_uts' => 'mimes:pdf,docx,DOCX,PDF,doc,DOC|max:4000'
      ],
      $message,
    );

    $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
    $jml_kelas = count($kelas_gabungan);

    for ($i = 0; $i < $jml_kelas; $i++) {
      $gabungan = $kelas_gabungan[$i];
      $cek = Soal_ujian::where('id_kurperiode', $gabungan->id_kurperiode)->first();

      if ($cek == null) {
        $path_soal = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;

        if (!File::exists($path_soal)) {
          File::makeDirectory($path_soal);
        }

        $info = new Soal_ujian();
        $info->id_kurperiode = $gabungan->id_kurperiode;
        $info->created_by = Auth::user()->name;

        if ($i == 0) {
          if ($request->hasFile('soal_uts')) {
            $file = $request->file('soal_uts');
            $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move($tujuan_upload, $nama_file);
            $info->soal_uts = $nama_file;
          }
        } elseif ($i > 0) {
          if ($request->hasFile('soal_uts')) {
            $id_kur = $kelas_gabungan[0];
            $kurperiode = $id_kur->id_kurperiode;
            $file = $request->file('soal_uts');
            $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $kurperiode;
            $nama_file = time() . '_' . $file->getClientOriginalName();

            $id_kur1 = $kelas_gabungan[$i];
            $kurperiode1 = $id_kur1->id_kurperiode;
            $new_path = 'Soal Ujian/' . 'UTS/' . $kurperiode1;
            $new_nama_file = time() . '_' . $file->getClientOriginalName();

            File::copy($tujuan_upload . '/' . $nama_file, $new_path . '/' . $new_nama_file);

            $info->soal_uts = $new_nama_file;
          }
        }

        $info->save();
      } elseif ($cek != null) {
        $path_soal = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;

        if (!File::exists($path_soal)) {
          File::makeDirectory($path_soal);
        }

        $id = $cek->id_soal;
        $info = Soal_ujian::find($id);

        if ($i == 0) {
          if ($info->soal_uts) {
            if ($request->hasFile('soal_uts')) {
              File::delete('Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode . '/' . $info->soal_uts);
              $file = $request->file('soal_uts');
              $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;
              $nama_file = time() . '_' . $file->getClientOriginalName();
              $file->move($tujuan_upload, $nama_file);
              $info->soal_uts = $nama_file;
            }
          } else {
            if ($request->hasFile('soal_uts')) {
              $file = $request->file('soal_uts');
              $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;
              $nama_file = time() . '_' . $file->getClientOriginalName();
              $file->move($tujuan_upload, $nama_file);
              $info->soal_uts = $nama_file;
            }
          }
        } elseif ($i > 0) {
          if ($info->soal_uts) {
            if ($request->hasFile('soal_uts')) {
              $id_kur1 = $kelas_gabungan[0];
              $d1 = $id_kur1->id_kurperiode;
              File::delete('Soal Ujian/' . 'UTS/' . $d1 . '/' . $info->soal_uts);
              $file = $request->file('soal_uts');
              $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $d1;
              $nama_file = time() . '_' . $file->getClientOriginalName();

              $tes2 = $kelas_gabungan[$i];
              $d2 = $tes2->id_kurperiode;
              File::delete('Soal Ujian/' . 'UTS/' . $d2 . '/' . $info->soal_uts);
              $path = 'Soal Ujian/' . 'UTS/' . $d2;
              $nama_file1 = time() . '_' . $file->getClientOriginalName();

              File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

              $info->soal_uts = $nama_file1;
            }
          } else {
            if ($request->hasFile('soal_uts')) {
              $tes1 = $kelas_gabungan[0];
              $d1 = $tes1->id_kurperiode;
              $file = $request->file('soal_uts');
              $nama_file = time() . '_' . $file->getClientOriginalName();
              $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $d1;

              $tes2 = $kelas_gabungan[$i];
              $d2 = $tes2->id_kurperiode;
              $path = 'Soal Ujian/' . 'UTS/' . $d2;
              $nama_file1 = time() . '_' . $file->getClientOriginalName();

              File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

              $info->soal_uts = $nama_file1;
            }
          }
        }

        $info->save();
      }
    }


    Alert::success('', 'Soal berhasil ditambahkan')->autoclose(3500);
    return redirect('makul_diampu_kprd');
  }

  public function simpan_soal_uas_dsn_kprd(Request $request)
  {
    $message = [
      'max' => ':attribute harus diisi maksimal :max KB',
      'required' => ':attribute wajib diisi'
    ];
    $this->validate(
      $request,
      [
        'soal_uas' => 'mimes:pdf,docx,DOCX,PDF,doc,DOC|max:4000'
      ],
      $message,
    );

    $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
    $jml_kelas = count($kelas_gabungan);

    for ($i = 0; $i < $jml_kelas; $i++) {
      $gabungan = $kelas_gabungan[$i];
      $cek = Soal_ujian::where('id_kurperiode', $gabungan->id_kurperiode)->first();

      if ($cek == null) {
        $path_soal = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;

        if (!File::exists($path_soal)) {
          File::makeDirectory($path_soal);
        }

        $info = new Soal_ujian();
        $info->id_kurperiode = $gabungan->id_kurperiode;
        $info->created_by = Auth::user()->name;

        if ($i == 0) {
          if ($request->hasFile('soal_uas')) {
            $file = $request->file('soal_uas');
            $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $file->move($tujuan_upload, $nama_file);
            $info->soal_uas = $nama_file;
          }
        } elseif ($i > 0) {
          if ($request->hasFile('soal_uas')) {
            $id_kur = $kelas_gabungan[0];
            $kurperiode = $id_kur->id_kurperiode;
            $file = $request->file('soal_uas');
            $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $kurperiode;
            $nama_file = time() . '_' . $file->getClientOriginalName();

            $id_kur1 = $kelas_gabungan[$i];
            $kurperiode1 = $id_kur1->id_kurperiode;
            $new_path = 'Soal Ujian/' . 'UAS/' . $kurperiode1;
            $new_nama_file = time() . '_' . $file->getClientOriginalName();

            File::copy($tujuan_upload . '/' . $nama_file, $new_path . '/' . $new_nama_file);

            $info->soal_uas = $new_nama_file;
          }
        }

        $info->save();
      } elseif ($cek != null) {
        $path_soal = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;

        if (!File::exists($path_soal)) {
          File::makeDirectory($path_soal);
        }

        $id = $cek->id_soal;
        $info = Soal_ujian::find($id);

        if ($i == 0) {
          if ($info->soal_uas) {
            if ($request->hasFile('soal_uas')) {
              File::delete('Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode . '/' . $info->soal_uas);
              $file = $request->file('soal_uas');
              $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;
              $nama_file = time() . '_' . $file->getClientOriginalName();
              $file->move($tujuan_upload, $nama_file);
              $info->soal_uas = $nama_file;
            }
          } else {
            if ($request->hasFile('soal_uas')) {
              $file = $request->file('soal_uas');
              $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;
              $nama_file = time() . '_' . $file->getClientOriginalName();
              $file->move($tujuan_upload, $nama_file);
              $info->soal_uas = $nama_file;
            }
          }
        } elseif ($i > 0) {
          if ($info->soal_uas) {
            if ($request->hasFile('soal_uas')) {
              $id_kur1 = $kelas_gabungan[0];
              $d1 = $id_kur1->id_kurperiode;
              File::delete('Soal Ujian/' . 'UAS/' . $d1 . '/' . $info->soal_uas);
              $file = $request->file('soal_uas');
              $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $d1;
              $nama_file = time() . '_' . $file->getClientOriginalName();

              $tes2 = $kelas_gabungan[$i];
              $d2 = $tes2->id_kurperiode;
              File::delete('Soal Ujian/' . 'UAS/' . $d2 . '/' . $info->soal_uas);
              $path = 'Soal Ujian/' . 'UAS/' . $d2;
              $nama_file1 = time() . '_' . $file->getClientOriginalName();

              File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

              $info->soal_uas = $nama_file1;
            }
          } else {
            if ($request->hasFile('soal_uas')) {
              $tes1 = $kelas_gabungan[0];
              $d1 = $tes1->id_kurperiode;
              $file = $request->file('soal_uas');
              $nama_file = time() . '_' . $file->getClientOriginalName();
              $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $d1;

              $tes2 = $kelas_gabungan[$i];
              $d2 = $tes2->id_kurperiode;
              $path = 'Soal Ujian/' . 'UAS/' . $d2;
              $nama_file1 = time() . '_' . $file->getClientOriginalName();

              File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

              $info->soal_uas = $nama_file1;
            }
          }
        }
        $info->save();
      }
    }

    Alert::success('', 'Soal berhasil ditambahkan')->autoclose(3500);
    return redirect('makul_diampu_kprd');
  }

  public function val_kurikulum_kprd()
  {
    $id = Auth::user()->id_user;
    $kprd = Kaprodi::where('id_dosen', $id)->first();
    $prd = Prodi::where('id_prodi', $kprd->id_prodi)->first();

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::where('kodeprodi', $prd->kodeprodi)->get();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    return view('kaprodi/kurikulum/standar_kurikulum_prodi', compact('kurikulum', 'prodi', 'angkatan', 'semester'));
  }

  public function lihat_kurikulum_standar_prodi(Request $request)
  {
    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $idkurikulum = $request->id_kurikulum;
    $idprodi = $request->id_prodi;
    $idangkatan = $request->id_angkatan;
    $idsemester = $request->id_semester;
    $status = $request->status;
    $paket = $request->pelaksanaan_paket;

    $krlm = Kurikulum_master::where('id_kurikulum', $idkurikulum)->first();
    $prd = Prodi::where('id_prodi', $idprodi)->first();
    $angk = Angkatan::where('idangkatan', $idangkatan)->first();
    $smtr = Semester::where('idsemester', $idsemester)->first();
    $mk = Matakuliah::where('active', '1')->get();

    if ($idsemester != null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $idkurikulum)
        ->where('kurikulum_transaction.id_prodi', $idprodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.id_semester', $idsemester)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    } elseif ($idsemester == null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $idkurikulum)
        ->where('kurikulum_transaction.id_prodi', $idprodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    }
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact('data', 'kurikulum', 'prodi', 'angkatan', 'semester', 'krlm', 'prd', 'angk', 'smtr', 'status', 'paket'));
  }

  public function add_setting_kurikulum_kprd()
  {
    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();
    $matakuliah = Matakuliah::where('active', '1')->get();

    return view('kaprodi/kurikulum/add_standar_kurikulum', compact('kurikulum', 'prodi', 'angkatan', 'semester', 'matakuliah'));
  }

  public function save_setting_kurikulum_kprd(Request $request)
  {
    $idmakul = $request->id_makul;
    $id_kurikulum = $request->id_kurikulum;
    $id_prodi = $request->id_prodi;
    $idangkatan = $request->id_angkatan;
    $idsemester = $request->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $cek_mk = count($idmakul);

    for ($i = 0; $i < $cek_mk; $i++) {
      $idmk = $request->id_makul[$i];
      if ($idmk != null) {

        $cekid_kur = Kurikulum_transaction::where('id_kurikulum', $id_kurikulum)
          ->where('id_prodi', $id_prodi)
          ->where('id_angkatan', $idangkatan)
          ->where('id_semester', $idsemester)
          ->where('id_makul', $idmk)
          ->where('status', 'ACTIVE')
          ->where('pelaksanaan_paket', 'OPEN')
          ->count();

        if ($cekid_kur == 0) {
          $bsa = new Kurikulum_transaction();
          $bsa->id_kurikulum = $request->id_kurikulum;
          $bsa->id_prodi = $request->id_prodi;
          $bsa->id_semester = $request->id_semester;
          $bsa->id_angkatan = $request->id_angkatan;
          $bsa->id_makul = $idmk;
          $bsa->save();
        }
      }
    }

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $idangkatan)->first();
    $smtr = Semester::where('idsemester', $idsemester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    if ($idsemester != null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.id_semester', $idsemester)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    } elseif ($idsemester == null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    }

    Alert::success('', 'Berhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function edit_setting_kurikulum_kprd($id)
  {
    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();
    $matakuliah = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    return view('kaprodi/kurikulum/edit_standar_kurikulum', compact('id', 'data', 'kurikulum', 'prodi', 'angkatan', 'semester', 'matakuliah'));
  }

  public function put_setting_kurikulum_kprd(Request $request, $id)
  {
    $bsa = Kurikulum_transaction::find($id);
    $bsa->id_kurikulum = $request->id_kurikulum;
    $bsa->id_prodi = $request->id_prodi;
    $bsa->id_semester = $request->id_semester;
    $bsa->id_angkatan = $request->id_angkatan;
    $bsa->id_makul = $request->id_makul;
    $bsa->status = $request->status;
    $bsa->pelaksanaan_paket = $request->pelaksanaan_paket;
    $bsa->save();

    $id_kurikulum = $request->id_kurikulum;
    $id_prodi = $request->id_prodi;
    $idangkatan = $request->id_angkatan;
    $idsemester = $request->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $request->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $request->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $request->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $request->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    if ($request->id_semester != null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $request->id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $request->id_angkatan)
        ->where('kurikulum_transaction.id_semester', $request->id_semester)
        ->where('kurikulum_transaction.status', $request->status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $request->pelaksanaan_paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    } elseif ($request->id_semester == null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $request->id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $request->id_angkatan)
        ->where('kurikulum_transaction.status', $request->status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $request->pelaksanaan_paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    }

    Alert::success('', 'Berhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function hapus_setting_kurikulum_kprd($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['status' => 'NOT ACTIVE']);

    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Berhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function aktif_setting_kurikulum_kprd($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['status' => 'ACTIVE']);

    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Berhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function closed_setting_kurikulum_kprd($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['pelaksanaan_paket' => 'CLOSED']);

    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Berhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function open_setting_kurikulum_kprd($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['pelaksanaan_paket' => 'OPEN']);

    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Bberhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function validate_setting_kurikulum_kprd($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['validasi' => 'SUDAH']);

    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Berhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function unvalidate_setting_kurikulum_kprd($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['validasi' => 'BELUM']);

    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Berhasil')->autoclose(3500);
    return view('kaprodi/kurikulum/cek_standar_kurikulum', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function record_pembayaran_mhs_kprd($id)
  {
    $maha = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->select('student.idstudent', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.prodi', 'kelas.kelas', 'student.nama', 'student.nim')
      ->where('idstudent', $id)
      ->first();

    $cek_study = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->where('student.idstudent', $id)
      ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
      ->first();

    $cb = Beasiswa::where('idstudent', $id)->first();

    $biaya = Biaya::where('idangkatan', $maha->idangkatan)
      ->where('idstatus', $maha->idstatus)
      ->where('kodeprodi', $maha->kodeprodi)
      ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14', 'prakerin', 'seminar', 'sidang', 'wisuda')
      ->first();

    if ($cek_study->study_year == '3') {
      $itembayar = Itembayar::where('study_year', '3')
        ->orderBy('iditem', 'ASC')
        ->get();

      $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 1)
        ->sum('bayar.bayar');

      $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 2)
        ->sum('bayar.bayar');

      $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 3)
        ->sum('bayar.bayar');

      $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 4)
        ->sum('bayar.bayar');

      $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 5)
        ->sum('bayar.bayar');

      $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 6)
        ->sum('bayar.bayar');

      $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 7)
        ->sum('bayar.bayar');

      $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 8)
        ->sum('bayar.bayar');

      $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 9)
        ->sum('bayar.bayar');

      $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 10)
        ->sum('bayar.bayar');

      $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 11)
        ->sum('bayar.bayar');

      $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 12)
        ->sum('bayar.bayar');

      $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 13)
        ->sum('bayar.bayar');

      $sisaprakerin = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 36)
        ->sum('bayar.bayar');

      $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 14)
        ->sum('bayar.bayar');

      $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 15)
        ->sum('bayar.bayar');

      $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 16)
        ->sum('bayar.bayar');

      return view('kaprodi/master/data_biaya', compact('maha', 'itembayar', 'cb', 'biaya', 'sisadaftar', 'sisaawal', 'sisadsp', 'sisaspp1', 'sisaspp2', 'sisaspp3', 'sisaspp4', 'sisaspp5', 'sisaspp6', 'sisaspp7', 'sisaspp8', 'sisaspp9', 'sisaspp10', 'sisaprakerin', 'sisaseminar', 'sisasidang', 'sisawisuda'));
    } elseif ($cek_study->study_year == '4') {
      $itembayar = Itembayar::where('study_year', '4')
        ->orderBy('iditem', 'ASC')
        ->get();

      $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 18)
        ->sum('bayar.bayar');

      $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 19)
        ->sum('bayar.bayar');

      $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 20)
        ->sum('bayar.bayar');

      $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 21)
        ->sum('bayar.bayar');

      $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 22)
        ->sum('bayar.bayar');

      $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 23)
        ->sum('bayar.bayar');

      $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 24)
        ->sum('bayar.bayar');

      $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 25)
        ->sum('bayar.bayar');

      $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 26)
        ->sum('bayar.bayar');

      $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 27)
        ->sum('bayar.bayar');

      $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 28)
        ->sum('bayar.bayar');

      $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 29)
        ->sum('bayar.bayar');

      $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 30)
        ->sum('bayar.bayar');

      $sisaspp11 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 31)
        ->sum('bayar.bayar');

      $sisaspp12 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 32)
        ->sum('bayar.bayar');

      $sisaspp13 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 33)
        ->sum('bayar.bayar');

      $sisaspp14 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 34)
        ->sum('bayar.bayar');

      $sisaprakerin = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 35)
        ->sum('bayar.bayar');

      $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 37)
        ->sum('bayar.bayar');

      $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 38)
        ->sum('bayar.bayar');

      $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->where('bayar.iditem', 39)
        ->sum('bayar.bayar');

      return view('kaprodi/master/data_biaya', compact('maha', 'itembayar', 'cb', 'biaya', 'sisadaftar', 'sisaawal', 'sisadsp', 'sisaspp1', 'sisaspp2', 'sisaspp3', 'sisaspp4', 'sisaspp5', 'sisaspp6', 'sisaspp7', 'sisaspp8', 'sisaspp9', 'sisaspp10', 'sisaspp11', 'sisaspp12', 'sisaspp13', 'sisaspp14', 'sisaprakerin', 'sisaseminar', 'sisasidang', 'sisawisuda'));
    }
  }

  public function download_bap_pkl_kprd($id)
  {
    $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'prodi.id_prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_trans_hasil.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.tempat_prausta',
        'prausta_setting_relasi.dosen_pembimbing',
        'prausta_setting_relasi.tanggal_selesai',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'dosen.nama as nama_dsn',
        'dosen.nik',
        'dosen.akademik'
      )
      ->first();
    if ($data == null) {
      Alert::warning('', 'Data PKL Belum ada')->autoclose(3500);
      return redirect('pembimbing_pkl_kprd');
    } else {
      $nama = $data->nama;
      $nim = $data->nim;
      $kelas = $data->kelas;
      $idprodi = $data->id_prodi;

      $kaprodi = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
        ->where('kaprodi.id_prodi', $idprodi)
        ->select('dosen.nama', 'dosen.nik', 'dosen.akademik')
        ->first();
      $nama_kaprodi = $kaprodi->nama;
      $akademik_kaprodi = $kaprodi->akademik;
      $nik_kaprodi = $kaprodi->nik;

      $cektgl = date(' d F Y', strtotime($data->tanggal_selesai));
      $cekhari = date('l', strtotime($data->tanggal_selesai));

      switch ($cekhari) {
        case 'Sunday':
          $hari = 'Minggu';
          break;
        case 'Monday':
          $hari = 'Senin';
          break;
        case 'Tuesday':
          $hari = 'Selasa';
          break;
        case 'Wednesday':
          $hari = 'Rabu';
          break;
        case 'Thursday':
          $hari = 'Kamis';
          break;
        case 'Friday':
          $hari = 'Jum\'at';
          break;
        case 'Saturday':
          $hari = 'Sabtu';
          break;
        default:
          $hari = 'Tidak ada';
          break;
      }

      $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
      );

      $pecahkan = explode('-', $data->tanggal_selesai);

      $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

      $pdf = PDF::loadView('prausta/prakerin/unduh_bap_prakerin', compact('data', 'hari', 'tglhasil', 'nama_kaprodi', 'nik_kaprodi', 'akademik_kaprodi'))->setPaper('a4');
      return $pdf->download('BAP Prakerin' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }
  }

  public function download_bap_sempro_kprd($id)
  {
    $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_trans_hasil.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.dosen_pembimbing',
        'prausta_setting_relasi.dosen_penguji_1',
        'prausta_setting_relasi.dosen_penguji_2',
        'prausta_setting_relasi.tanggal_selesai',
        'prausta_setting_relasi.id_dosen_pembimbing',
        'prausta_setting_relasi.id_dosen_penguji_1',
        'prausta_setting_relasi.id_dosen_penguji_2',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'dosen.nama as nama_dsn',
        'dosen.akademik'
      )
      ->first();

    if ($data == null) {
      Alert::warning('', 'Data SEMPRO Belum ada')->autoclose(3500);
      return redirect('pembimbing_sempro_kprd');
    } else {
      $nama = $data->nama;
      $nim = $data->nim;
      $kelas = $data->kelas;


      $cektgl = date(' d F Y', strtotime($data->tanggal_selesai));
      $cekhari = date('l', strtotime($data->tanggal_selesai));

      switch ($cekhari) {
        case 'Sunday':
          $hari = 'Minggu';
          break;
        case 'Monday':
          $hari = 'Senin';
          break;
        case 'Tuesday':
          $hari = 'Selasa';
          break;
        case 'Wednesday':
          $hari = 'Rabu';
          break;
        case 'Thursday':
          $hari = 'Kamis';
          break;
        case 'Friday':
          $hari = 'Jum\'at';
          break;
        case 'Saturday':
          $hari = 'Sabtu';
          break;
        default:
          $hari = 'Tidak ada';
          break;
      }

      $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
      );

      $pecahkan = explode('-', $data->tanggal_selesai);

      $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

      $dospem = Dosen::where('iddosen', $data->id_dosen_pembimbing)->first();

      $dospeng1 = Dosen::where('iddosen', $data->id_dosen_penguji_1)->first();

      $dospeng2 = Dosen::where('iddosen', $data->id_dosen_penguji_2)->first();

      $pdf = PDF::loadView('prausta/sempro/unduh_bap_sempro', compact('data', 'hari', 'tglhasil', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
      return $pdf->download('BAP Sempro' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }
  }

  public function download_bap_ta_kprd($id)
  {
    $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'prausta_trans_hasil.id_settingrelasi_prausta',
        'prausta_setting_relasi.judul_prausta',
        'prausta_setting_relasi.dosen_pembimbing',
        'prausta_setting_relasi.dosen_penguji_1',
        'prausta_setting_relasi.dosen_penguji_2',
        'prausta_setting_relasi.id_dosen_pembimbing',
        'prausta_setting_relasi.id_dosen_penguji_1',
        'prausta_setting_relasi.id_dosen_penguji_2',
        'prausta_setting_relasi.tanggal_selesai',
        'prausta_trans_hasil.nilai_1',
        'prausta_trans_hasil.nilai_2',
        'prausta_trans_hasil.nilai_3',
        'prausta_trans_hasil.nilai_huruf',
        'dosen.nama as nama_dsn',
        'dosen.akademik'
      )
      ->first();

    if ($data == null) {
      Alert::warning('', 'Data TA Belum ada')->autoclose(3500);
      return redirect('pembimbing_ta_kprd');
    } else {
      $nama = $data->nama;
      $nim = $data->nim;
      $kelas = $data->kelas;


      $cektgl = date(' d F Y', strtotime($data->tanggal_selesai));
      $cekhari = date('l', strtotime($data->tanggal_selesai));

      switch ($cekhari) {
        case 'Sunday':
          $hari = 'Minggu';
          break;
        case 'Monday':
          $hari = 'Senin';
          break;
        case 'Tuesday':
          $hari = 'Selasa';
          break;
        case 'Wednesday':
          $hari = 'Rabu';
          break;
        case 'Thursday':
          $hari = 'Kamis';
          break;
        case 'Friday':
          $hari = 'Jum\'at';
          break;
        case 'Saturday':
          $hari = 'Sabtu';
          break;
        default:
          $hari = 'Tidak ada';
          break;
      }

      $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
      );

      $pecahkan = explode('-', $data->tanggal_selesai);

      $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

      $dospem = Dosen::where('iddosen', $data->id_dosen_pembimbing)->first();

      $dospeng1 = Dosen::where('iddosen', $data->id_dosen_penguji_1)->first();

      $dospeng2 = Dosen::where('iddosen', $data->id_dosen_penguji_2)->first();

      $pdf = PDF::loadView('prausta/ta/unduh_bap_ta', compact('data', 'hari', 'tglhasil', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
      return $pdf->download('BAP TA' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }
  }

  public function makul_ulang_kprd()
  {
    $id = Auth::user()->id_user;

    $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
      ->join('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
      ->where('dosen_pembimbing.id_dosen', $id)
      ->where('student_record.status', 'TAKEN')
      ->whereIn('student.active', [1, 5])
      ->select('student_record.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.id_kurtrans', 'matakuliah.makul', 'student_record.nilai_AKHIR')
      ->groupBy('student_record.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.id_kurtrans', 'matakuliah.makul', 'student_record.nilai_AKHIR')
      ->get();

    return view('kaprodi/perkuliahan/makul_mengulang', compact('data'));
  }

  public function cek_makul_mengulang_kprd($id)
  {
    $id_dsn = Auth::user()->id_user;

    $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
      ->join('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
      ->where('dosen_pembimbing.id_dosen', $id_dsn)
      ->where('student.idstudent', $id)
      ->where('student_record.status', 'TAKEN')
      ->whereIn('student.active', [1, 5])
      ->select('student_record.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.id_kurtrans', 'matakuliah.makul', 'student_record.nilai_AKHIR')
      ->groupBy('student_record.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.id_kurtrans', 'matakuliah.makul', 'student_record.nilai_AKHIR')
      ->get();

    if (count($data) > 0) {
      return view('kaprodi/perkuliahan/makul_mengulang', compact('data'));
    } else {
      Alert::warning('Mahasiswa ini tidak ada matakuliah mengulang');
      return redirect('mhs_bim');
    }
  }

  public function record_pembayaran_mahasiswa_kprd()
  {
    $id = Auth::user()->id_user;
    $kode = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('id_dosen', $id)
      ->select('kaprodi.id_kaprodi', 'prodi.kodeprodi')
      ->first();

    $kdprd = $kode->kodeprodi;

    $data1 = Student::leftJoin('prodi', function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->select('student.idstudent', 'student.nama', 'student.nim', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi')
      ->whereIn('student.active', [1, 5])
      ->orderBy('student.nim', 'ASC')
      ->get();

    $data = DB::select('CALL data_pembayaran_mhs_prodi(?)', [$kdprd]);

    return view('kaprodi/pembayaran/data_pembayaran', compact('data'));
  }

  public function detail_pembayaran_mhs_kprd($id)
  {
    $mhs = Student::join('prodi', function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student.idstudent', $id)
      ->select('student.idstudent', 'student.nama', 'student.nim', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi')
      ->first();

    $data = DB::select('CALL detail_pembayaran_mhs(?)', [$id]);

    $detail_beasiswa = DB::select('CALL detail_beasiswa_mhs(?)', [$id]);

    foreach ($detail_beasiswa as $key_beasiswa) {
      # code...
    }

    $total_byr_mhs = DB::select('CALL detail_totalbayar_mhs(?)', [$id]);

    foreach ($total_byr_mhs as $key_total) {
      # code...
    }

    return view('kaprodi/pembayaran/detail_pembayaran', compact('data', 'mhs', 'key_beasiswa', 'key_total'));
  }

  public function generate_nilai_akhir_dsn_kprd(Request $request)
  {
    $idkur = $request->id_kurperiode;

    $set_nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();
    $kat = $set_nilai->kat;
    $uts = $set_nilai->uts;
    $uas = $set_nilai->uas;

    // $data = Student_record::where('id_kurperiode', $idkur)->get();
    $data = DB::select('CALL absen_mahasiswa(?)', [$idkur]);
    $jml_mhs = count($data);

    for ($i = 0; $i < $jml_mhs; $i++) {
      $nilai = $data[$i];

      $id_record = $nilai->id_studentrecord;
      $id_student = $nilai->id_student;
      $n_kat = $nilai->nilai_KAT;
      $n_uts = $nilai->nilai_UTS;
      $n_uas = $nilai->nilai_UAS;
      $id_kurtrans = $nilai->id_kurtrans;

      $cek_id = Student_record::where('id_student', $id_student)
        ->where('id_kurtrans', $id_kurtrans)
        ->get();

      $banyak_id = count($cek_id);

      $hsl_kat = $n_kat * $kat / 100;
      $hsl_uts = $n_uts * $uts / 100;
      $hsl_uas = $n_uas * $uas / 100;

      $n_total = $hsl_kat + $hsl_uts + $hsl_uas;

      if ($banyak_id == 1) {
        $id = $id_record;
        $ceknilai = Student_record::find($id);
        $ceknilai->nilai_AKHIR_angka = $n_total;
        $ceknilai->save();

        if ($n_total < 50) {
          $id = $id_record;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'E';
          $ceknilai->nilai_ANGKA = '0';
          $ceknilai->save();
        } elseif ($n_total < 60) {
          $id = $id_record;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'D';
          $ceknilai->nilai_ANGKA = '1';
          $ceknilai->save();
        } elseif ($n_total < 65) {
          $id = $id_record;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'C';
          $ceknilai->nilai_ANGKA = '2';
          $ceknilai->save();
        } elseif ($n_total < 70) {
          $id = $id_record;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'C+';
          $ceknilai->nilai_ANGKA = '2.5';
          $ceknilai->save();
        } elseif ($n_total < 75) {
          $id = $id_record;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'B';
          $ceknilai->nilai_ANGKA = '3';
          $ceknilai->save();
        } elseif ($n_total < 80) {
          $id = $id_record;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'B+';
          $ceknilai->nilai_ANGKA = '3.5';
          $ceknilai->save();
        } elseif ($n_total <= 100) {
          $id = $id_record;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'A';
          $ceknilai->nilai_ANGKA = '4';
          $ceknilai->save();
        }
      } elseif ($banyak_id > 1) {
        Student_record::where('id_student', $id_student)
          ->where('id_kurtrans', $id_kurtrans)
          ->update(['nilai_AKHIR_angka' => $n_total]);

        if ($n_total < 50) {
          Student_record::where('id_student', $id_student)
            ->where('id_kurtrans', $id_kurtrans)
            ->update([
              'nilai_AKHIR' => 'E',
              'nilai_ANGKA' => '0'
            ]);
        } elseif ($n_total < 60) {
          Student_record::where('id_student', $id_student)
            ->where('id_kurtrans', $id_kurtrans)
            ->update([
              'nilai_AKHIR' => 'D',
              'nilai_ANGKA' => '1'
            ]);
        } elseif ($n_total < 65) {
          Student_record::where('id_student', $id_student)
            ->where('id_kurtrans', $id_kurtrans)
            ->update([
              'nilai_AKHIR' => 'C',
              'nilai_ANGKA' => '2'
            ]);
        } elseif ($n_total < 70) {
          Student_record::where('id_student', $id_student)
            ->where('id_kurtrans', $id_kurtrans)
            ->update([
              'nilai_AKHIR' => 'C+',
              'nilai_ANGKA' => '2.5'
            ]);
        } elseif ($n_total < 75) {
          Student_record::where('id_student', $id_student)
            ->where('id_kurtrans', $id_kurtrans)
            ->update([
              'nilai_AKHIR' => 'B',
              'nilai_ANGKA' => '3'
            ]);
        } elseif ($n_total < 80) {
          Student_record::where('id_student', $id_student)
            ->where('id_kurtrans', $id_kurtrans)
            ->update([
              'nilai_AKHIR' => 'B+',
              'nilai_ANGKA' => '3.5'
            ]);
        } elseif ($n_total <= 100) {
          Student_record::where('id_student', $id_student)
            ->where('id_kurtrans', $id_kurtrans)
            ->update([
              'nilai_AKHIR' => 'A',
              'nilai_ANGKA' => '4'
            ]);
        }
      }
    }
    //cek setting nilai
    $nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();

    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idkur]);

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $idkur)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;
    $idkur = $idkur;

    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $id, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function post_settingnilai_dsn_kprd(Request $request)
  {
    $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
    $jml_id_kur = count($cek_kelas_gabungan);

    for ($i = 0; $i < $jml_id_kur; $i++) {
      $idkurperiode = $cek_kelas_gabungan[$i];

      $kpr = new Setting_nilai();
      $kpr->id_kurperiode = $idkurperiode->id_kurperiode;
      $kpr->kat = $request->kat;
      $kpr->uts = $request->uts;
      $kpr->uas = $request->uas;
      $kpr->created_by = Auth::user()->name;
      $kpr->save();
    }

    //cek setting nilai
    $idkur = $request->id_kurperiode;
    $nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();

    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idkur]);

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $idkur)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;
    $idkur = $idkur;

    Alert::success('Berhasil');
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function put_settingnilai_dsn_kprd(Request $request, $id)
  {
    $id_setting = Setting_nilai::where('id_settingnilai', $id)->first();
    $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$id_setting->id_kurperiode]);
    $jml_id_kur = count($cek_kelas_gabungan);

    for ($i = 0; $i < $jml_id_kur; $i++) {
      $idkurperiode = $cek_kelas_gabungan[$i];

      Setting_nilai::where('id_kurperiode', $idkurperiode->id_kurperiode)->update([
        'kat' => $request->kat,
        'uts' => $request->uts,
        'uas' => $request->uas,
        'updated_by' => Auth::user()->name,
      ]);
    }

    //cek setting nilai
    $idkur = $request->id_kurperiode;
    $nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();

    $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idkur]);

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->where('id_kurperiode', $idkur)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans')
      ->first();

    $kur = $ckstr->id_kurtrans;
    $idkur = $idkur;

    Alert::success('Berhasil');
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
  }

  public function sop_dsn_kprd()
  {
    $data = Standar::where('status', 'ACTIVE')->get();

    return view('kaprodi/sop', compact('data'));
  }

  public function val_sertifikat_kprd()
  {
    $iddosen = Auth::user()->id_user;

    $prodi = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('id_dosen', $iddosen)
      ->select('prodi.kodeprodi')
      ->first();

    if ($prodi->kodeprodi == 25 or $prodi->kodeprodi == 22) {
      $data = Sertifikat::join('student', 'sertifikat.id_student', '=', 'student.idstudent')
        ->leftjoin('jenis_kegiatan', 'sertifikat.id_jeniskegiatan', '=', 'jenis_kegiatan.id_jeniskegiatan')
        ->leftjoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
        ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
        ->whereIn('student.kodeprodi', [25, 22])
        ->where('student.active', 1)
        ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'angkatan.angkatan', DB::raw('COUNT(sertifikat.id_student) as jml_sertifikat'))
        ->groupBy('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'angkatan.angkatan',)
        ->get();
    } else {
      $data = Sertifikat::join('student', 'sertifikat.id_student', '=', 'student.idstudent')
        ->leftjoin('jenis_kegiatan', 'sertifikat.id_jeniskegiatan', '=', 'jenis_kegiatan.id_jeniskegiatan')
        ->leftjoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
        ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
        ->whereIn('prodi.kodeprodi', [$prodi->kodeprodi])
        ->where('student.active', 1)
        ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'angkatan.angkatan', DB::raw('COUNT(sertifikat.id_student) as jml_sertifikat'))
        ->groupBy('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'angkatan.angkatan',)
        ->get();
    }


    return view('kaprodi/skpi/validasi_sertifikat', compact('data'));
  }

  public function cek_sertifikat_kprd($id)
  {
    $mhs = Student::leftjoin('prodi', function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student.idstudent', $id)
      ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi')
      ->first();

    $data = Sertifikat::where('sertifikat.id_student', $id)
      ->leftjoin('jenis_kegiatan', 'sertifikat.id_jeniskegiatan', '=', 'jenis_kegiatan.id_jeniskegiatan')
      ->get();

    return view('kaprodi/skpi/cek_sertifikat', compact('mhs', 'data'));
  }

  public function validasi_sertifikat($id)
  {
    Sertifikat::where('id_sertifikat', $id)->update(['validasi' => 'SUDAH']);

    $idmhs = Sertifikat::where('id_sertifikat', $id)->select('id_student')->first();

    return redirect('cek_sertifikat_kprd/' . $idmhs->id_student);
  }

  public function batal_validasi_sertifikat($id)
  {
    Sertifikat::where('id_sertifikat', $id)->update(['validasi' => 'BELUM']);

    $idmhs = Sertifikat::where('id_sertifikat', $id)->select('id_student')->first();

    return redirect('cek_sertifikat_kprd/' . $idmhs->id_student);
  }

  public function save_validasi_all_sertifikat(Request $request)
  {
    $sertifikat = $request->id_sertifikat;
    $jml_sertifikat = count($sertifikat);

    for ($i = 0; $i < $jml_sertifikat; $i++) {
      $id_sert = $sertifikat[$i];

      Sertifikat::where('id_sertifikat', $id_sert)->update(['validasi' => 'SUDAH']);

      $idmhs = Sertifikat::where('id_sertifikat', $id_sert)->select('id_student')->first();
    }

    return redirect('cek_sertifikat_kprd/' . $idmhs->id_student);
  }

  public function pedoman_akademik_dsn_kprd()
  {
    $pedoman = Pedoman_akademik::join('periode_tahun', 'pedoman_akademik.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->where('pedoman_akademik.status', 'ACTIVE')
      ->get();

    return view('kaprodi/pedoman_akademik', ['pedoman' => $pedoman]);
  }

  public function download_pedoman_dsn_kprd($id)
  {
    $ped = Pedoman_akademik::where('id_pedomanakademik', $id)->get();
    foreach ($ped as $keyped) {
      // code...
    }
    //PDF file is stored under project/public/download/info.pdf
    $file = 'pedoman/' . $keyped->file;
    return Response::download($file);
  }

  public function penangguhan_mhs_dsn_kprd()
  {
    $id = Auth::user()->id_user;

    $data = Dosen_pembimbing::join('penangguhan_master_trans', 'dosen_pembimbing.id_student', '=', 'penangguhan_master_trans.id_student')
      ->join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
      ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
      ->join('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('dosen_pembimbing.id_dosen', $id)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'periode_tahun.periode_tahun',
        'periode_tipe.periode_tipe',
        'penangguhan_master_trans.id_periodetahun',
        'penangguhan_master_trans.id_periodetipe',
        'penangguhan_master_trans.id_student',
        'penangguhan_master_trans.id_penangguhan_kategori',
        'penangguhan_master_kategori.kategori',
        'penangguhan_master_trans.total_tunggakan',
        'penangguhan_master_trans.rencana_bayar',
        'penangguhan_master_trans.alasan',
        'penangguhan_master_trans.validasi_kaprodi',
        'penangguhan_master_trans.validasi_dsn_pa',
        'penangguhan_master_trans.validasi_bauk',
        'penangguhan_master_trans.validasi_baak',
        'penangguhan_master_trans.id_penangguhan_trans'
      )
      ->orderBy('student.nim')
      ->get();

    return view('kaprodi/penangguhan/data_penangguhan', compact('data'));
  }

  public function val_penangguhan_dsn_kprd($id)
  {
    Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_dsn_pa' => 'SUDAH']);

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function batal_val_penangguhan_dsn_kprd($id)
  {
    Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_dsn_pa' => 'BELUM']);

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function penangguhan_mhs_prodi()
  {
    $id = Auth::user()->id_user;

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('dosen.iddosen', $id)
      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik', 'prodi.kodeprodi')
      ->first();

    $data = Student::join('penangguhan_master_trans', 'student.idstudent', '=', 'penangguhan_master_trans.id_student')
      ->join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
      ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student.kodeprodi', $cekkprd->kodeprodi)
      ->select(
        'student.nama',
        'student.nim',
        'prodi.prodi',
        'kelas.kelas',
        'angkatan.angkatan',
        'periode_tahun.periode_tahun',
        'periode_tipe.periode_tipe',
        'penangguhan_master_trans.id_periodetahun',
        'penangguhan_master_trans.id_periodetipe',
        'penangguhan_master_trans.id_student',
        'penangguhan_master_trans.id_penangguhan_kategori',
        'penangguhan_master_kategori.kategori',
        'penangguhan_master_trans.total_tunggakan',
        'penangguhan_master_trans.rencana_bayar',
        'penangguhan_master_trans.alasan',
        'penangguhan_master_trans.validasi_kaprodi',
        'penangguhan_master_trans.validasi_dsn_pa',
        'penangguhan_master_trans.validasi_bauk',
        'penangguhan_master_trans.validasi_baak',
        'penangguhan_master_trans.id_penangguhan_trans'
      )
      ->orderBy('student.nim')
      ->get();

    return view('kaprodi/penangguhan/data_penangguhan_prodi', compact('data'));
  }

  public function val_penangguhan_prodi($id)
  {
    Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_kaprodi' => 'SUDAH']);

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function batal_val_penangguhan_prodi($id)
  {
    Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_kaprodi' => 'BELUM']);

    Alert::success('', 'Berhasil')->autoclose(3500);
    return redirect()->back();
  }

  public function master_yudisium_kprd()
  {
    $iddosen = Auth::user()->id_user;

    $prodi = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('id_dosen', $iddosen)
      ->select('prodi.kodeprodi')
      ->first();

    if ($prodi->kodeprodi == 25 or $prodi->kodeprodi == 22) {
      $data = Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
        ->leftJoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->where('student.active', 1)
        ->whereIn('student.kodeprodi', [25, 22])
        ->select('yudisium.id_yudisium', 'yudisium.nama_lengkap', 'yudisium.tmpt_lahir', 'yudisium.tgl_lahir', 'yudisium.nik', 'student.nim', 'prodi.prodi', 'yudisium.id_student', 'yudisium.file_ijazah', 'yudisium.file_ktp', 'yudisium.file_foto', 'yudisium.validasi')
        ->orderBy('student.nim', 'ASC')
        ->get();
    } else {
      $data = Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
        ->leftJoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->where('student.active', 1)
        ->whereIn('student.kodeprodi', [$prodi->kodeprodi])
        ->select('yudisium.id_yudisium', 'yudisium.nama_lengkap', 'yudisium.tmpt_lahir', 'yudisium.tgl_lahir', 'yudisium.nik', 'student.nim', 'prodi.prodi', 'yudisium.id_student', 'yudisium.file_ijazah', 'yudisium.file_ktp', 'yudisium.file_foto', 'yudisium.validasi')
        ->orderBy('student.nim', 'ASC')
        ->get();
    }


    return view('kaprodi/perkuliahan/master_yudisium', compact('data'));
  }

  public function master_wisuda_kprd()
  {
    $iddosen = Auth::user()->id_user;

    $prodi = Kaprodi::join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
      ->where('id_dosen', $iddosen)
      ->select('prodi.kodeprodi')
      ->first();

    if ($prodi->kodeprodi == 25 or $prodi->kodeprodi == 22) {
      $data = Wisuda::join('student', 'wisuda.id_student', '=', 'student.idstudent')
        ->leftjoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
        ->where('student.active', 1)
        ->whereIn('student.kodeprodi', [25, 22])
        ->select('wisuda.id_wisuda', 'wisuda.nama_lengkap', 'wisuda.nim', 'wisuda.tahun_lulus', 'wisuda.ukuran_toga', 'wisuda.no_hp', 'wisuda.email', 'wisuda.nik', 'wisuda.alamat_ktp', 'wisuda.alamat_domisili', 'wisuda.nama_ayah', 'wisuda.nama_ibu', 'wisuda.no_hp_ayah', 'wisuda.no_hp_ibu', 'wisuda.alamat_ortu', 'wisuda.status_vaksin', 'wisuda.file_vaksin', 'wisuda.npwp', 'wisuda.validasi', 'wisuda.id_student', 'wisuda.id_prodi', 'prodi.prodi', 'kelas.kelas')
        ->get();
    } else {
      $data = Wisuda::join('student', 'wisuda.id_student', '=', 'student.idstudent')
        ->leftjoin('prodi', function ($join) {
          $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
        ->where('student.active', 1)
        ->whereIn('student.kodeprodi', [$prodi->kodeprodi])
        ->select('wisuda.id_wisuda', 'wisuda.nama_lengkap', 'wisuda.nim', 'wisuda.tahun_lulus', 'wisuda.ukuran_toga', 'wisuda.no_hp', 'wisuda.email', 'wisuda.nik', 'wisuda.alamat_ktp', 'wisuda.alamat_domisili', 'wisuda.nama_ayah', 'wisuda.nama_ibu', 'wisuda.no_hp_ayah', 'wisuda.no_hp_ibu', 'wisuda.alamat_ortu', 'wisuda.status_vaksin', 'wisuda.file_vaksin', 'wisuda.npwp', 'wisuda.validasi', 'wisuda.id_student', 'wisuda.id_prodi', 'prodi.prodi', 'kelas.kelas')
        ->get();
    }

    return view('kaprodi/perkuliahan/master_wisuda', compact('data', 'prodi'));
  }
}
