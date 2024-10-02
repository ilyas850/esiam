<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\Models\Mhs;
use App\Helpers\Helper;
use App\User;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Student;
use App\Models\Informasi;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\Waktu_krs;
use App\Models\Matakuliah;
use App\Models\Periode_tipe;
use App\Models\Periode_tahun;
use App\Models\Update_mahasiswa;
use App\Models\Kurikulum_hari;
use App\Models\Kurikulum_jam;
use App\Models\Kurikulum_master;
use App\Models\Kurikulum_periode;
use App\Models\Kurikulum_transaction;
use App\Models\Student_record;
use App\Models\Bayar;
use App\Models\Beasiswa;
use App\Models\Biaya;
use App\Models\Itembayar;
use App\Models\Kuitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
  public function getDataMhs($id)
  {
    return Student::leftJoin('prodi', (function ($join) {
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
        'prodi.konsentrasi',
        'student.idangkatan',
        'student.idstatus',
        'student.kodeprodi',
        'student.intake'
      )
      ->first();
  }
  public function krs()
  {
    $waktu_krs = Waktu_krs::where('status', 1)->first();

    if ($waktu_krs == null) {

      alert()->error('KRS Belum dibuka', 'Maaf silahkan menghubungi bagian akademik');
      return redirect('home');
    } elseif ($waktu_krs->status == 1) {

      $periodetahun_all = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
      $periodetipe_all = Periode_tipe::all();

      $id = Auth::user()->id_user;

      $data_mhs = $this->getDataMhs($id);

      $idangkatan = $data_mhs->idangkatan;
      $idstatus = $data_mhs->idstatus;
      $kodeprodi = $data_mhs->kodeprodi;
      $intake = $data_mhs->intake;

      $thn = Periode_tahun::where('status', 'ACTIVE')->first();
      $tp = Periode_tipe::where('status', 'ACTIVE')->first();

      $idperiodetahun = $thn->id_periodetahun;
      $idperiodetipe = $tp->id_periodetipe;
      $periodetahun = $thn->periode_tahun;
      $periodetipe = $tp->periode_tipe;

      $c = Helper::cekSemesterMhs($periodetahun, $idperiodetipe, $idangkatan, $intake);

      $biaya = Helper::cekBiayaKuliah($idangkatan, $idstatus, $kodeprodi);

      $cb = Beasiswa::where('idstudent', $id)->first();

      //list biaya kuliah mahasiswa
      if (($cb) != null) {

        $daftar = $biaya->daftar - (($biaya->daftar * ($cb->daftar)) / 100);
        $awal = $biaya->awal - (($biaya->awal * ($cb->awal)) / 100);
        $dsp = $biaya->dsp - (($biaya->dsp * ($cb->dsp)) / 100);
        $spp1 = $biaya->spp1 - (($biaya->spp1 * ($cb->spp1)) / 100);
        $spp2 = $biaya->spp2 - (($biaya->spp2 * ($cb->spp2)) / 100);
        $spp3 = $biaya->spp3 - (($biaya->spp3 * ($cb->spp3)) / 100);
        $spp4 = $biaya->spp4 - (($biaya->spp4 * ($cb->spp4)) / 100);
        $spp5 = $biaya->spp5 - (($biaya->spp5 * ($cb->spp5)) / 100);
        $spp6 = $biaya->spp6 - (($biaya->spp6 * ($cb->spp6)) / 100);
        $spp7 = $biaya->spp7 - (($biaya->spp7 * ($cb->spp7)) / 100);
        $spp8 = $biaya->spp8 - (($biaya->spp8 * ($cb->spp8)) / 100);
        $spp9 = $biaya->spp9 - (($biaya->spp9 * ($cb->spp9)) / 100);
        $spp10 = $biaya->spp10 - (($biaya->spp10 * ($cb->spp10)) / 100);
        $spp11 = $biaya->spp11 - (($biaya->spp11 * ($cb->spp11)) / 100);
        $spp12 = $biaya->spp12 - (($biaya->spp12 * ($cb->spp12)) / 100);
        $spp13 = $biaya->spp13 - (($biaya->spp13 * ($cb->spp13)) / 100);
        $spp14 = $biaya->spp14 - (($biaya->spp14 * ($cb->spp14)) / 100);
        $prakerin = $biaya->prakerin - (($biaya->prakerin * ($cb->prakerin)) / 100);
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
        $prakerin = $biaya->prakerin;
      }

      //total pembayaran kuliah
      $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->sum('bayar.bayar');


      if ($c == 1) {
        $cekbyr = ($daftar + $awal + ($spp1 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '101') {
        $cekbyr = ($daftar + $awal + ($dsp * 50 / 100) + $spp1) - $total_semua_dibayar;
      } elseif ($c == 2) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + ($spp2 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '201') {
        $cekbyr = ($daftar + $awal + ($dsp * 91 / 100) + $spp1 + $spp2) - $total_semua_dibayar;
      } elseif ($c == 3) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + ($spp3 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '301') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3) - $total_semua_dibayar;
      } elseif ($c == 4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '401') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4) - $total_semua_dibayar;
      } elseif ($c == 5) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '501') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5)) - $total_semua_dibayar;
      } elseif ($c == 6) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '601') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6) - $total_semua_dibayar;
      } elseif ($c == 7) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + ($spp7 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == 8) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 / 6)) - $total_semua_dibayar;
      } elseif ($c == '801') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8) - $total_semua_dibayar;
      } elseif ($c == 9) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + ($spp9 / 6)) - $total_semua_dibayar;
      } elseif ($c == 10) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 / 6)) - $total_semua_dibayar;
      } elseif ($c == '1001') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10) - $total_semua_dibayar;
      } elseif ($c == 11) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + ($spp11 / 6)) - $total_semua_dibayar;
      } elseif ($c == 12) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + ($spp12 / 6)) - $total_semua_dibayar;
      } elseif ($c == 13) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + ($spp13 / 6)) - $total_semua_dibayar;
      } elseif ($c == 14) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + ($spp14 / 6)) - $total_semua_dibayar;
      }

      if ($cekbyr < 0 or $cekbyr == 0) {

        //data KRS yang diambil
        $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
          ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
          ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
          ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
          ->where('student_record.id_student', $id)
          ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('student_record.status', 'TAKEN')
          ->where('kurikulum_periode.status', 'ACTIVE')
          ->select('student_record.remark', 'student_record.id_studentrecord', 'student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
          ->orderBy('kurikulum_periode.id_hari', 'ASC')
          ->orderBy('kurikulum_periode.id_jam', 'ASC')
          ->get();

        //cek sks dari KRS
        $recordas = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->where('student_record.id_student', $id)
          ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('student_record.status', 'TAKEN')
          ->select('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
          ->groupBy('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
          ->get();

        //jumlah SKS
        $sks = 0;
        foreach ($recordas as $keysks) {
          $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
        }

        return view('mhs/krs/filter_krs', compact('idperiodetahun', 'idperiodetipe', 'periodetahun_all', 'periodetipe_all', 'periodetahun', 'periodetipe', 'data_mhs', 'sks', 'record'));
      } else {
        alert()->warning('Anda tidak dapat melakukan KRS karena keuangan Anda belum memenuhi syarat', 'Hubungi BAAK untuk KRS manual')->autoclose(5000);
        return redirect('home');
      }
    }
  }

  public function filter_krs(Request $request)
  {
    $periodetahun_all = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
    $periodetipe_all = Periode_tipe::all();

    $id = Auth::user()->id_user;

    $data_mhs = Student::leftJoin('prodi', (function ($join) {
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
        'student.kodeprodi',
        'student.kodekonsentrasi',
        'student.intake'
      )
      ->first();


    $thn = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
    $tp = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

    $idperiodetahun = $thn->id_periodetahun;
    $idperiodetipe = $tp->id_periodetipe;
    $periodetahun = $thn->periode_tahun;
    $periodetipe = $tp->periode_tipe;

    //data KRS yang diambil
    $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
      ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
      ->where('student_record.status', 'TAKEN')
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('student_record.remark', 'student_record.id_studentrecord', 'student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
      ->get();

    //cek sks dari KRS
    $recordas = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
      ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->groupBy('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->get();

    //jumlah SKS
    $sks = 0;
    foreach ($recordas as $keysks) {
      $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
    }

    return view('mhs/krs/filter_krs', compact('idperiodetahun', 'idperiodetipe', 'periodetahun_all', 'periodetipe_all', 'periodetahun', 'periodetipe', 'data_mhs', 'sks', 'record'));
  }

  public function entri_krs(Request $request)
  {
    $id = Auth::user()->id_user;
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;

    $data_mhs = Student::leftJoin('prodi', (function ($join) {
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
        'student.kodeprodi',
        'student.kodekonsentrasi',
        'student.intake'
      )
      ->first();

    $idangkatan = $data_mhs->idangkatan;
    $idstatus = $data_mhs->idstatus;
    $kodeprodi = $data_mhs->kodeprodi;
    $kodekonsentrasi = $data_mhs->kodekonsentrasi;
    $intake = $data_mhs->intake;

    $thn = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $tp = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();

    $periodetahun = $thn->periode_tahun;
    $periodetipe = $tp->periode_tipe;

    //cek semester
    $sub_thn = substr($thn->periode_tahun, 6, 2);
    $tipe = $tp->id_periodetipe;
    $smt = $sub_thn . $tipe;

    if ($smt % 2 != 0) {
      if ($tipe == 1) {
        //ganjil
        $a = (($smt + 10) - 1) / 10; // ( 211 + 10 - 1 ) / 10 = 22
        $b = $a - $idangkatan; // 22 - 20 = 2
        if ($intake == 2) {
          $c = ($b * 2) - 1 - 1;
        } elseif ($intake == 1) {
          $c = ($b * 2) - 1;
        } // 2 * 2 - 1 = 3
      } elseif ($tipe == 3) {
        //pendek
        $a = (($smt + 10) - 3) / 10; // ( 213 + 10 - 3 ) / 10  = 22
        $b = $a - $idangkatan; // 22 - 20 = 2
        // $c = ($b * 2);
        if ($intake == 2) {
          $c = $b * 2 - 1;
        } elseif ($intake == 1) {
          $c = $b * 2;
        }
      }
    } else {
      //genap
      $a = (($smt + 10) - 2) / 10; // (212 + 10 - 2) / 10 = 22
      $b = $a - $idangkatan; // 22 - 20 = 2
      // 2 * 2 = 4
      if ($intake == 2) {
        $c = $b * 2 - 1;
      } elseif ($intake == 1) {
        $c = $b * 2;
      }
    }

    if ($kodeprodi == 24) {
      $value = Prodi::where('kodeprodi', $kodeprodi)->first();
    } else {
      $value = Prodi::where('kodeprodi', $kodeprodi)
        ->where('kodekonsentrasi', $kodekonsentrasi)
        ->first();
    }

    $krlm = Kurikulum_master::where('remark', $intake)->first();

    if ($kodeprodi == 23 or $kodeprodi == 25 or $kodeprodi == 22) {
      if ($kodekonsentrasi == null) {
        alert()->warning('Anda tidak dapat melakukan KRS karena Anda belum memiliki konsentrasi', 'Hubungi Prodi masing-masing')->autoclose(5000);
        return redirect()->back();
      } else {

        if ($tipe == 3) {

          $final_krs = DB::select('CALL krs_smt_pendek(?,?,?,?,?,?)', [$krlm->id_kurikulum, $value->id_prodi, $idangkatan, $thn->id_periodetahun, $tipe, $idstatus]);

          return view('mhs/krs/form_krs', compact('final_krs'));
        } else {

          $final_krs = DB::select('CALL krs_smt_normal(?,?,?,?,?,?,?)', [$krlm->id_kurikulum, $value->id_prodi, $idangkatan, $c, $thn->id_periodetahun, $tipe, $idstatus]);

          return view('mhs/krs/form_krs', compact('final_krs'));
        }
      }
    } elseif ($kodeprodi == 24) {
      if ($tipe == 3) {

        // dd($krlm->id_kurikulum, $value->id_prodi, $idangkatan, $thn->id_periodetahun, $tipe, $idstatus);
        $final_krs = DB::select('CALL krs_smt_pendek(?,?,?,?,?,?)', [$krlm->id_kurikulum, $value->id_prodi, $idangkatan, $thn->id_periodetahun, $tipe, $idstatus]);
        return view('mhs/krs/form_krs', compact('final_krs'));
      } else {

        $final_krs = DB::select('CALL krs_smt_normal(?,?,?,?,?,?,?)', [$krlm->id_kurikulum, $value->id_prodi, $idangkatan, $c, $thn->id_periodetahun, $tipe, $idstatus]);

        return view('mhs/krs/form_krs', compact('final_krs'));
      }
    }
  }

  public function save_krs(Request $request)
  {
    $id = Auth::user()->id_user;
    $jml = count($request->id_kurperiode);
    $tipe = Periode_tipe::where('status', 'ACTIVE')->first();
    $idtipe = $tipe->id_periodetipe;

    #cek jumlah SKS
    $k = 0;
    for ($s = 0; $s < $jml; $s++) {
      $kurp1 = $request->id_kurperiode[$s];
      $idr1 = explode(',', $kurp1, 3);
      $tra1 = $idr1[2];
      $k += $tra1;
    }

    #cek sks sama
    for ($p = 0; $p < $jml; $p++) {
      $kurp2 = $request->id_kurperiode[$p];
      $idr2 = explode(',', $kurp2, 3);
      $trs2 = $idr2[0];

      $as = DB::select('CALL hitung_mk_sama(?)', [$trs2]);

      if (count($as) > 1) {
        $sk = 0;
        for ($z = 0; $z < count($as); $z++) {
          $g = $as[$z];
          $ks = $g->akt_sks_teori + $g->akt_sks_praktek;
          $sk += $ks;
        }
        $hasil_sks_sama = ($sk / count($as));
      } elseif (count($as) == 1) {
        $sk = 0;
        for ($z = 0; $z < count($as); $z++) {
          $g = $as[$z];
          $ks = $g->akt_sks_teori + $g->akt_sks_praktek;
          $sk += $ks;
        }
        $hasil_sks_sama = ($sk / count($as));
      }
    }


    $hasil_dari_sks = $k - $hasil_sks_sama;

    if ($idtipe == 1 or $idtipe == 2) {
      if ($hasil_dari_sks > 24) {
        Alert::error('Maaf SKS yang anda ambil melebihi 24 SKS', 'MAAF !!');
        return redirect('krs');
      } elseif ($hasil_dari_sks <= 24) {
        for ($i = 0; $i < $jml; $i++) {
          $kurp = $request->id_kurperiode[$i];
          $idr = explode(',', $kurp, 3);
          $tra = $idr[0];
          $trs = $idr[1];
          $cekkrs = Student_record::where('id_student', $id)
            ->where('id_kurperiode', $tra)
            ->where('id_kurtrans', $trs)
            ->where('status', 'TAKEN')
            ->get();

          if (count($cekkrs) == 0) {
            $krs = new Student_record;
            $krs->tanggal_krs   = date("Y-m-d");
            $krs->id_student    = $id;
            $krs->data_origin   = 'eSIAM';
            $krs->id_kurperiode = $tra;
            $krs->id_kurtrans   = $trs;
            $krs->save();
          }
        }
        Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
        return redirect('krs');
      }
    } elseif ($idtipe == 3) {
      if ($hasil_dari_sks > 9) {
        Alert::error('Maaf SKS yang anda ambil melebihi 9 SKS', 'MAAF !!');
        return redirect('krs');
      } elseif ($hasil_dari_sks <= 9) {
        for ($i = 0; $i < $jml; $i++) {
          $kurp = $request->id_kurperiode[$i];
          $idr = explode(',', $kurp, 3);
          $tra = $idr[0];
          $trs = $idr[1];
          $cekkrs = Student_record::where('id_student', $id)
            ->where('id_kurperiode', $tra)
            ->where('id_kurtrans', $trs)
            ->where('status', 'TAKEN')
            ->get();

          if (count($cekkrs) == 0) {
            $krs = new Student_record;
            $krs->tanggal_krs   = date("Y-m-d");
            $krs->id_student    = $id;
            $krs->data_origin   = 'eSIAM';
            $krs->id_kurperiode = $tra;
            $krs->id_kurtrans   = $trs;
            $krs->save();
          }
        }
        Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
        return redirect('krs');
      }
    }
  }

  public function batalkrs(Request $request)
  {
    $id = $request->id_studentrecord;
    $cek = Student_record::find($id);
    $cek->status = $request->status;
    $cek->save();

    Alert::success('', 'Matakuliah berhasil dihapus')->autoclose(3500);
    return redirect('krs');
  }

  public function unduh_krs(Request $request)
  {
    $id = Auth::user()->id_user;

    $idthn = $request->id_periodetahun;
    $idtp = $request->id_periodetipe;

    $maha = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->where('student.idstudent', $id)
      ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
      ->first();

    $thn = Periode_tahun::where('id_periodetahun', $idthn)->first();

    $tp = Periode_tipe::where('id_periodetipe', $idtp)->first();

    $nama = $maha->nama;
    $prodi = $maha->prodi;
    $kelas = $maha->kelas;

    $record = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
      ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
      ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
      ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.id_periodetipe', $idtp)
      ->where('kurikulum_periode.id_periodetahun', $idthn)
      ->where('student_record.status', 'TAKEN')
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->select('student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
      ->orderBy('kurikulum_periode.id_hari', 'ASC')
      ->orderBy('kurikulum_periode.id_jam', 'ASC')
      ->get();

    $recordas = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.id_periodetipe', $idtp)
      ->where('kurikulum_periode.id_periodetahun', $idthn)
      ->where('student_record.status', 'TAKEN')
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->groupBy('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->get();

    //jumlah SKS
    $sks = 0;
    foreach ($recordas as $keysks) {
      $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
    }

    $bulan = [
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
    ];
    $d = date('d');
    $m = $bulan[date('m')];
    $y = date('Y');

    $pdf = PDF::loadView('mhs/krs_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'mhs' => $maha, 'tp' => $tp, 'thn' => $thn, 'krs' => $record, 'sks' => $sks])->setPaper('a4', 'portrait');
    return $pdf->download('KRS' . ' ' . $nama . ' ' . $prodi . ' ' . $kelas . ' ' . '(' . $thn->periode_tahun . ' ' . $tp->periode_tipe . ')' . '.pdf');
  }

  public function krs_manual()
  {
    $data = Student::with([
      'student_records' => function ($q) {
        $q->select('id_studentrecord', 'tanggal_krs', 'id_student', 'id_kurperiode', 'id_kurtrans', 'status', 'remark')
          ->where('status', 'TAKEN')
          ->with(['kurperiode' => function ($q) {
            $q->select('id_kurperiode', 'id_periodetahun', 'id_periodetipe', 'id_makul')
              ->with([
                'tahun' => function ($q) {
                  $q->select('id_periodetahun', 'periode_tahun', 'status')
                    ->where('status', 'ACTIVE');
                },
                'tipe' => function ($q) {
                  $q->select('id_periodetipe', 'periode_tipe', 'status')
                    ->where('status', 'ACTIVE');
                },
                'makul:idmakul,kode,makul,akt_sks_teori,akt_sks_praktek',
              ])
              ->where('status', 'ACTIVE');
          }]);
      },
      'prodi' => function ($q) {
        $q->select('id_prodi', 'prodi', 'kodeprodi', 'konsentrasi', 'kodekonsentrasi');
      },
      'kelas:idkelas,kelas',
      'angkatan:idangkatan,angkatan',
      'dosenPembimbing' => function ($q) {
        $q->select('id', 'id_dosen', 'id_student', 'status')
          ->with(['dosen' => function ($q) {
            $q->select('iddosen', 'nama', 'akademik');
          }]);
      }
    ])
      ->select('idstudent', 'idangkatan', 'idstatus', 'nim', 'nama', 'kodeprodi', 'kodekonsentrasi', 'intake')
      ->whereIn('active', [1, 5])
      ->orderBy('kodeprodi', 'DESC')
      ->orderBy('nim', 'DESC')
      ->get();

    // dd($data->toArray());
    return view('sadmin.krs.krs-manual', compact('data'));
  }

  public function createKrsManual($id)
  {
    $dataMhs = Student::with(([
      'prodi:id_prodi,prodi,kodeprodi,konsentrasi,kodekonsentrasi',
      'angkatan:idangkatan,angkatan',
      'kelas:idkelas,kelas'
    ]))
      ->select('idstudent', 'idangkatan', 'idstatus', 'nim', 'nama', 'kodeprodi', 'kodekonsentrasi', 'intake')
      ->where('idstudent', $id)
      ->first();

    $tahunActive = Periode_tahun::where('status', 'ACTIVE')->first();
    $tipeActive = Periode_tipe::where('status', 'ACTIVE')->first();
    $kurikulumMhs = Kurikulum_master::where('remark', $dataMhs->intake)->first();

    $dataKrsMhs = Student_record::whereHas('kurperiode', function ($q) use ($tahunActive, $tipeActive) {
      $q->where('id_periodetahun', $tahunActive->id_periodetahun)
        ->where('id_periodetipe', $tipeActive->id_periodetipe);
    })
      ->with(['kurperiode' => function ($q) use ($tahunActive, $tipeActive) {
        $q->select('id_kurperiode', 'id_periodetahun', 'id_periodetipe', 'id_makul', 'id_dosen')
          ->with([
            'makul:idmakul,kode,makul,akt_sks_teori,akt_sks_praktek',
            'tahun' => function ($q) {
              $q->select('id_periodetahun', 'periode_tahun', 'status');
            },
            'tipe' => function ($q) {
              $q->select('id_periodetipe', 'periode_tipe', 'status');
            },
            'dosen' => function ($q) {
              $q->select('iddosen', 'nama', 'akademik');
            }
          ])
          ->where('id_periodetahun', $tahunActive->id_periodetahun)
          ->where('id_periodetipe', $tipeActive->id_periodetipe);
      }])
      ->select('id_studentrecord', 'tanggal_krs', 'id_student', 'id_kurperiode', 'id_kurtrans', 'status', 'remark')
      ->where('id_student', $id)
      ->where('status', 'TAKEN')
      ->get();

    $dataKrs = Kurikulum_periode::whereHas('kurtrans', function ($q) use ($kurikulumMhs, $dataMhs) {
      $q->where('id_kurikulum', $kurikulumMhs->id_kurikulum)
        ->where('id_prodi', $dataMhs->prodi->id_prodi)
        ->where('id_angkatan', $dataMhs->angkatan->idangkatan)
        ->where('status', 'ACTIVE');
    })
      ->with([
        'tahun:id_periodetahun,periode_tahun',
        'tipe:id_periodetipe,periode_tipe',
        'makul' => function ($q) {
          $q->select('idmakul', 'kode', 'makul', 'akt_sks_teori', 'akt_sks_praktek', 'active')
            ->where('active', 1);
        },
        'dosen:iddosen,nama',
        'kurtrans' => function ($q) use ($kurikulumMhs, $dataMhs) {
          $q->select('idkurtrans', 'id_kurikulum', 'id_prodi', 'id_semester', 'id_angkatan', 'id_makul', 'status')
            ->where('id_kurikulum', $kurikulumMhs->id_kurikulum)
            ->where('id_prodi', $dataMhs->prodi->id_prodi)
            ->where('id_angkatan', $dataMhs->angkatan->idangkatan)
            ->where('status', 'ACTIVE');
        },
        'semester:idsemester,semester',
      ])
      ->where('id_periodetahun', $tahunActive->id_periodetahun)
      ->where('id_periodetipe', $tipeActive->id_periodetipe)
      ->where('id_prodi', $dataMhs->prodi->id_prodi)
      ->where('id_kelas', $dataMhs->kelas->idkelas)
      ->where('status', 'ACTIVE')
      ->orderBy('id_semester', 'ASC')
      ->orderBy('id_makul', 'ASC')
      ->get();

    return view('sadmin.krs.krs-manual-create', compact('id', 'dataMhs', 'dataKrsMhs', 'dataKrs', 'tahunActive', 'tipeActive'));
  }

  public function saveKrsManual(Request $request)
  {
    try {
      $cekKrs = Student_record::where('id_student', $request->id_student)
        ->where('id_kurperiode', $request->id_kurperiode)
        ->where('id_kurtrans', $request->id_kurtrans)
        ->where('status', 'TAKEN')
        ->first();

      if (empty($cekKrs)) {
        // Simpan KRS baru
        $krs = new Student_record;
        $krs->id_student = $request->id_student;
        $krs->id_kurperiode = $request->id_kurperiode;
        $krs->id_kurtrans = $request->id_kurtrans;
        $krs->status = 'TAKEN';
        $krs->save();

        // Mengambil data untuk respons JSON
        $kurperiode = $krs->kurperiode;
        $makul = $kurperiode->makul;
        $dosen = $kurperiode->dosen;

        // Kembalikan respons sukses dalam format JSON dengan data tambahan
        return response()->json([
          'success' => true,
          'message' => 'Matakuliah berhasil ditambahkan.',
          'id_studentrecord' => $krs->id_studentrecord,
          'kode_makul' => $makul->kode,
          'nama_makul' => $makul->makul,
          'sks' => $makul->akt_sks_teori + $makul->akt_sks_praktek,
          'nama_dosen' => $dosen ? $dosen->nama : '',
        ]);
      } else {
        return response()->json(['success' => false, 'message' => 'Maaf, mata kuliah sudah dipilih.']);
      }
    } catch (\Throwable $e) {
      return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
    }
  }



  public function cancelKrsManual($id)
  {
    try {
      // Logika pembatalan KRS di sini
      Student_record::where('id_studentrecord', $id)
        ->update([
          'status' => 'DROPPED'
        ]);

      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Gagal membatalkan KRS.'], 500);
    }
  }
}
