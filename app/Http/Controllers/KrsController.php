<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\Mhs;
use App\User;
use App\Dosen;
use App\Prodi;
use App\Student;
use App\Informasi;
use App\Ruangan;
use App\Semester;
use App\Waktu_krs;
use App\Matakuliah;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Update_Mahasiswa;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_master;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Student_record;
use App\Bayar;
use App\Beasiswa;
use App\Biaya;
use App\Itembayar;
use App\Kuitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KrsController extends Controller
{
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
          'prodi.konsentrasi',
          'student.idangkatan',
          'student.idstatus',
          'student.kodeprodi',
          'student.intake'
        )
        ->first();

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

      //cek semester
      $sub_thn = substr($thn->periode_tahun, 6, 2);
      $tipe = $tp->id_periodetipe;
      $smt = $sub_thn . $tipe;

      if ($smt % 2 != 0) {
        if ($tipe == 1) {
          //ganjil
          $a = (($smt + 10) - 1) / 10;
          $b = $a - $idangkatan;

          if ($intake == 2) {
            $c = ($b * 2) - 1 - 1;
          } elseif ($intake == 1) {
            $c = ($b * 2) - 1;
          }
        } elseif ($tipe == 3) {
          //pendek
          $a = (($smt + 10) - 3) / 10;
          $b = $a - $idangkatan;
          if ($intake == 2) {
            $c = ($b * 2) - 1 . '0' . '1';
          } elseif ($intake == 1) {
            $c = ($b * 2) . '0' . '1';
          }
        }
      } else {
        //genap
        $a = (($smt + 10) - 2) / 10;
        $b = $a - $idangkatan;
        if ($intake == 2) {
          $c = $b * 2 - 1;
        } elseif ($intake == 1) {
          $c = $b * 2;
        }
      }

      $biaya = Biaya::where('idangkatan', $idangkatan)
        ->where('idstatus', $idstatus)
        ->where('kodeprodi', $kodeprodi)
        ->select(
          'daftar',
          'awal',
          'dsp',
          'spp1',
          'spp2',
          'spp3',
          'spp4',
          'spp5',
          'spp6',
          'spp7',
          'spp8',
          'spp9',
          'spp10',
          'spp11',
          'spp12',
          'spp13',
          'spp14',
          'prakerin'
        )
        ->first();

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
      
      //test lagi
      if ($c == 1) {
        $cekbyr = ($daftar + $awal + ($spp1 * 20 / 100)) - $total_semua_dibayar;
      } elseif ($c == '101') {
        $cekbyr = ($daftar + $awal + ($dsp * 50 / 100) + $spp1) - $total_semua_dibayar;
      } elseif ($c == 2) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1) - $total_semua_dibayar;
      } elseif ($c == '201') {
        $cekbyr = ($daftar + $awal + ($dsp * 91 / 100) + $spp1 + $spp2) - $total_semua_dibayar;
      } elseif ($c == 3) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2) - $total_semua_dibayar;
      } elseif ($c == '301') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3) - $total_semua_dibayar;
      } elseif ($c == 4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3) - $total_semua_dibayar;
      } elseif ($c == '401') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4) - $total_semua_dibayar;
      } elseif ($c == 5) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4) + ($prakerin * 75 / 100) - $total_semua_dibayar;
      } elseif ($c == 6) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5) - $total_semua_dibayar;
      } elseif ($c == '601') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6) - $total_semua_dibayar;
      } elseif ($c == 7) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6) - $total_semua_dibayar;
      } elseif ($c == 8) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7) - $total_semua_dibayar;
      } elseif ($c == '801') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8) - $total_semua_dibayar;
      } elseif ($c == 9) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8) - $total_semua_dibayar;
      } elseif ($c == 10) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9) - $total_semua_dibayar;
      } elseif ($c == '1001') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10) - $total_semua_dibayar;
      } elseif ($c == 11) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10) - $total_semua_dibayar;
      } elseif ($c == 12) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11) - $total_semua_dibayar;
      } elseif ($c == 13) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12) - $total_semua_dibayar;
      } elseif ($c == 14) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13) - $total_semua_dibayar;
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
          $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
            ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_kelas', $idstatus)
            ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
            ->where('kurikulum_periode.id_prodi', $value->id_prodi)
            ->where('kurikulum_transaction.id_angkatan', $idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
            ->where('matakuliah_bom.status', 'ACTIVE');

          $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
            ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_kelas', $idstatus)
            ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
            ->where('kurikulum_periode.id_prodi', $value->id_prodi)
            ->where('kurikulum_transaction.id_angkatan', $idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('kurikulum_transaction.status', 'ACTIVE')
            ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
            ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
            ->union($add_krs)
            ->get();

          return view('mhs/krs/form_krs', compact('final_krs'));
        } else {
          $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
            ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
            ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_kelas', $idstatus)
            ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
            ->where('kurikulum_periode.id_prodi', $value->id_prodi)
            ->where('kurikulum_periode.id_semester', $c)
            ->where('kurikulum_transaction.id_semester', $c)
            ->where('kurikulum_transaction.id_angkatan', $idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
            ->where('matakuliah_bom.status', 'ACTIVE');

          $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
            ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
            ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_kelas', $idstatus)
            ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
            ->where('kurikulum_periode.id_prodi', $value->id_prodi)
            ->where('kurikulum_periode.id_semester', $c)
            ->where('kurikulum_transaction.id_semester', $c)
            ->where('kurikulum_transaction.id_angkatan', $idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('kurikulum_transaction.status', 'ACTIVE')
            ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
            ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
            ->union($add_krs)
            ->get();

          return view('mhs/krs/form_krs', compact('final_krs'));
        }
      }
    } elseif ($kodeprodi == 24) {
      if ($tipe == 3) {
        $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
          ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
          ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
          ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_kelas', $idstatus)
          ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_prodi', $value->id_prodi)
          ->where('kurikulum_transaction.id_angkatan', $idangkatan)
          ->where('kurikulum_periode.status', 'ACTIVE')
          ->where('matakuliah_bom.status', 'ACTIVE');

        $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
          ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
          ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
          ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('kurikulum_periode.id_kelas', $idstatus)
          ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_prodi', $value->id_prodi)
          ->where('kurikulum_transaction.id_angkatan', $idangkatan)
          ->where('kurikulum_periode.status', 'ACTIVE')
          ->where('kurikulum_transaction.status', 'ACTIVE')
          ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
          ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
          ->union($add_krs)
          ->get();

        return view('mhs/krs/form_krs', compact('final_krs'));
      } else {
        $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
          ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
          ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
          ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_kelas', $idstatus)
          ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_semester', $c)
          ->where('kurikulum_transaction.id_semester', $c)
          ->where('kurikulum_transaction.id_angkatan', $idangkatan)
          ->where('kurikulum_periode.status', 'ACTIVE')
          ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
          ->where('matakuliah_bom.status', 'ACTIVE');

        $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
          ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
          ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
          ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('kurikulum_periode.id_kelas', $idstatus)
          ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_semester', $c)
          ->where('kurikulum_transaction.id_semester', $c)
          ->where('kurikulum_transaction.id_angkatan', $idangkatan)
          ->where('kurikulum_periode.status', 'ACTIVE')
          ->where('kurikulum_transaction.status', 'ACTIVE')
          ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
          ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
          ->union($add_krs)
          ->get();

        return view('mhs/krs/form_krs', compact('final_krs'));
      }
    }
  }

  public function save_krs(Request $request)
  {
    $id = Auth::user()->id_user;

    $jml = count($request->id_kurperiode);

    for ($i = 0; $i < $jml; $i++) {
      $kurp = $request->id_kurperiode[$i];
      $idr = explode(',', $kurp, 2);
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
}
