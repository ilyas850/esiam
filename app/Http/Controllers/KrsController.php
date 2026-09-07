<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah_bom;
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
use App\Models\Kelas;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

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
      // dd($cb->toArray());
      //list biaya kuliah mahasiswa
      if (($cb) != null) {

        $daftar = (float) $biaya->daftar - (((float) $biaya->daftar * ((float) $cb->daftar)) / 100);
        $awal = (float) $biaya->awal - (((float) $biaya->awal * ((float) $cb->awal)) / 100);
        $dsp = (float) $biaya->dsp - (((float) $biaya->dsp * ((float) $cb->dsp)) / 100);
        $spp1 = (float) trim($biaya->spp1) - (((float) trim($biaya->spp1) * ((float) $cb->spp1)) / 100);
        $spp2 = (float) $biaya->spp2 - (((float) $biaya->spp2 * ((float) $cb->spp2)) / 100);
        $spp3 = (float) $biaya->spp3 - (((float) $biaya->spp3 * ((float) $cb->spp3)) / 100);
        $spp4 = (float) $biaya->spp4 - (((float) $biaya->spp4 * ((float) $cb->spp4)) / 100);
        $spp5 = (float) $biaya->spp5 - (((float) $biaya->spp5 * ((float) $cb->spp5)) / 100);
        $spp6 = (float) $biaya->spp6 - (((float) $biaya->spp6 * ((float) $cb->spp6)) / 100);
        $spp7 = (float) $biaya->spp7 - (((float) $biaya->spp7 * ((float) $cb->spp7)) / 100);
        $spp8 = (float) $biaya->spp8 - (((float) $biaya->spp8 * ((float) $cb->spp8)) / 100);
        $spp9 = (float) $biaya->spp9 - (((float) $biaya->spp9 * ((float) $cb->spp9)) / 100);
        $spp10 = (float) $biaya->spp10 - (((float) $biaya->spp10 * ((float) $cb->spp10)) / 100);
        $spp11 = (float) $biaya->spp11 - (((float) $biaya->spp11 * ((float) $cb->spp11)) / 100);
        $spp12 = (float) $biaya->spp12 - (((float) $biaya->spp12 * ((float) $cb->spp12)) / 100);
        $spp13 = (float) $biaya->spp13 - (((float) $biaya->spp13 * ((float) $cb->spp13)) / 100);
        $spp14 = (float) $biaya->spp14 - (((float) $biaya->spp14 * ((float) $cb->spp14)) / 100);
        $prakerin = (float) $biaya->prakerin - (((float) $biaya->prakerin * ((float) $cb->prakerin)) / 100);
      } elseif (($cb) == null) {

        $daftar = (float) $biaya->daftar;
        $awal = (float) $biaya->awal;
        $dsp = (float) $biaya->dsp;
        $spp1 = (float) $biaya->spp1;
        $spp2 = (float) $biaya->spp2;
        $spp3 = (float) $biaya->spp3;
        $spp4 = (float) $biaya->spp4;
        $spp5 = (float) $biaya->spp5;
        $spp6 = (float) $biaya->spp6;
        $spp7 = (float) $biaya->spp7;
        $spp8 = (float) $biaya->spp8;
        $spp9 = (float) $biaya->spp9;
        $spp10 = (float) $biaya->spp10;
        $spp11 = (float) $biaya->spp11;
        $spp12 = (float) $biaya->spp12;
        $spp13 = (float) $biaya->spp13;
        $spp14 = (float) $biaya->spp14;
        $prakerin = (float) $biaya->prakerin;
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
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '801') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8) - $total_semua_dibayar;
      } elseif ($c == 9) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + ($spp9 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == 10) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == '1001') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10) - $total_semua_dibayar;
      } elseif ($c == 11) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + ($spp11 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == 12) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + ($spp12 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == 13) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + ($spp13 * 10 / 100)) - $total_semua_dibayar;
      } elseif ($c == 14) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + ($spp14 * 10 / 100)) - $total_semua_dibayar;
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
      $sks += (float) $keysks->akt_sks_teori + (float) $keysks->akt_sks_praktek;
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

    if ($kodeprodi == 23 or $kodeprodi == 25 or $kodeprodi == 22 or $kodeprodi == 26) {
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
      $k += (float) $tra1;
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
          $ks = (float) $g->akt_sks_teori + (float) $g->akt_sks_praktek;
          $sk += $ks;
        }
        $hasil_sks_sama = ($sk / count($as));
      } elseif (count($as) == 1) {
        $sk = 0;
        for ($z = 0; $z < count($as); $z++) {
          $g = $as[$z];
          $ks = (float) $g->akt_sks_teori + (float) $g->akt_sks_praktek;
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
            $krs->tanggal_krs = date("Y-m-d");
            $krs->id_student = $id;
            $krs->data_origin = 'eSIAM';
            $krs->id_kurperiode = $tra;
            $krs->id_kurtrans = $trs;
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
            $krs->tanggal_krs = date("Y-m-d");
            $krs->id_student = $id;
            $krs->data_origin = 'eSIAM';
            $krs->id_kurperiode = $tra;
            $krs->id_kurtrans = $trs;
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
    $student_id = Auth::user()->id_user;

    // Temukan record KRS dan pastikan record tersebut milik user yang sedang login
    $record = Student_record::where('id_studentrecord', $id)
      ->where('id_student', $student_id)
      ->first();

    // Jika record tidak ditemukan atau bukan milik user, tolak aksi
    if (!$record) {
      Alert::error('Gagal', 'Mata kuliah tidak ditemukan atau Anda tidak berhak mengubahnya.');
      return redirect('krs');
    }
    $record->status = 'CANCELLED'; // Sebaiknya gunakan status yang jelas, bukan dari request
    $record->save();

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
      $sks += (float) $keysks->akt_sks_teori + (float) $keysks->akt_sks_praktek;
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

  public function krs_manual(Request $request)
  {
    $tahunActive = Periode_tahun::where('status', 'ACTIVE')->first();
    $tipeActive = Periode_tipe::where('status', 'ACTIVE')->first();

    if ($request->ajax()) {
      $query = Student::with([
        'student_records' => function ($q) use ($tahunActive, $tipeActive) {
          $q->select('id_studentrecord', 'tanggal_krs', 'id_student', 'id_kurperiode', 'id_kurtrans', 'status', 'remark')
            ->where('status', 'TAKEN');
          if ($tahunActive && $tipeActive) {
            $q->whereHas('kurperiode', function ($kp) use ($tahunActive, $tipeActive) {
              $kp->where('id_periodetahun', $tahunActive->id_periodetahun)
                ->where('id_periodetipe', $tipeActive->id_periodetipe);
            });
          }
          $q->with([
            'kurperiode' => function ($kp2) {
              $kp2->select('id_kurperiode', 'id_periodetahun', 'id_periodetipe', 'id_makul')
                ->with([
                  'makul:idmakul,kode,makul,akt_sks_teori,akt_sks_praktek',
                ]);
            }
          ]);
        },
        'kelas:idkelas,kelas',
        'angkatan:idangkatan,angkatan',
        'dosenPembimbing' => function ($q) {
          $q->select('id', 'id_dosen', 'id_student', 'status')
            ->with([
              'dosen' => function ($q2) {
                $q2->select('iddosen', 'nama', 'akademik');
              }
            ]);
        }
      ])
        ->join('prodi', function ($join) {
          $join->on('student.kodeprodi', '=', 'prodi.kodeprodi')
            ->on('student.kodekonsentrasi', '=', 'prodi.kodekonsentrasi');
        })
        ->select(
          'student.idstudent',
          'student.idangkatan',
          'student.idstatus',
          'student.nim',
          'student.nama',
          'student.kodeprodi',
          'student.kodekonsentrasi',
          'student.intake',
          'prodi.prodi',
          'prodi.konsentrasi'
        )
        ->whereIn('student.active', [1, 5]);

      // Filter by Program Studi
      if ($request->filled('filter_prodi')) {
        $query->where('student.kodeprodi', $request->filter_prodi);
      }

      // Filter by Kelas
      if ($request->filled('filter_kelas')) {
        $query->where('student.idstatus', $request->filter_kelas);
      }

      // Filter by Angkatan
      if ($request->filled('filter_angkatan')) {
        $query->where('student.idangkatan', $request->filter_angkatan);
      }

      // Filter by Status KRS
      if ($request->filled('filter_status_krs')) {
        if ($request->filter_status_krs == 'sudah') {
          $query->whereHas('student_records', function ($q) use ($tahunActive, $tipeActive) {
            $q->where('status', 'TAKEN');
            if ($tahunActive && $tipeActive) {
              $q->whereHas('kurperiode', function ($kp) use ($tahunActive, $tipeActive) {
                $kp->where('id_periodetahun', $tahunActive->id_periodetahun)
                  ->where('id_periodetipe', $tipeActive->id_periodetipe);
              });
            }
          });
        } elseif ($request->filter_status_krs == 'belum') {
          $query->whereDoesntHave('student_records', function ($q) use ($tahunActive, $tipeActive) {
            $q->where('status', 'TAKEN');
            if ($tahunActive && $tipeActive) {
              $q->whereHas('kurperiode', function ($kp) use ($tahunActive, $tipeActive) {
                $kp->where('id_periodetahun', $tahunActive->id_periodetahun)
                  ->where('id_periodetipe', $tipeActive->id_periodetipe);
              });
            }
          });
        }
      }

      // Search keyword
      if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
          $q->where('student.nim', 'like', "%{$search}%")
            ->orWhere('student.nama', 'like', "%{$search}%")
            ->orWhere('prodi.prodi', 'like', "%{$search}%")
            ->orWhereHas('kelas', function ($q2) use ($search) {
              $q2->where('kelas', 'like', "%{$search}%");
            });
        });
      }

      $recordsFiltered = $query->count();
      $itemPerPage = $request->input('length', 10);
      $start = $request->input('start', 0);
      $order = $request->input('order.0.column');
      $dir = $request->input('order.0.dir', 'desc');

      $columns = [
        1 => 'student.nim',
        2 => 'prodi.prodi',
        3 => 'student.idstatus',
        4 => 'student.idangkatan',
      ];

      if (isset($columns[$order])) {
        $query->orderBy($columns[$order], $dir);
      } else {
        $query->orderBy('student.kodeprodi', 'DESC')
          ->orderBy('student.nim', 'DESC');
      }

      $data = $query->skip($start)->take($itemPerPage)->get();
      $recordsTotal = Student::whereIn('active', [1, 5])->count();

      $formattedData = [];
      $no = $start + 1;
      foreach ($data as $item) {
        $totalSKS = 0;
        $totalMakul = 0;
        if (isset($item->student_records)) {
          foreach ($item->student_records as $record) {
            if ($record->status == 'TAKEN' && $record->kurperiode && $record->kurperiode->makul) {
              $makul = $record->kurperiode->makul;
              $totalSKS += ($makul->akt_sks_teori ?? 0) + ($makul->akt_sks_praktek ?? 0);
              $totalMakul++;
            }
          }
        }

        $sksBadge = $totalSKS > 0
          ? '<span class="badge bg-green" style="font-size: 11px; padding: 4px 8px;"><i class="fa fa-check-circle"></i> ' . $totalSKS . ' SKS (' . $totalMakul . ' MK)</span>'
          : '<span class="badge bg-red" style="font-size: 11px; padding: 4px 8px;"><i class="fa fa-exclamation-circle"></i> 0 SKS</span>';

        $formattedData[] = [
          'no' => $no++,
          'nim_nama' => '<strong>' . e($item->nim) . '</strong><br><span style="color: #333;">' . e($item->nama) . '</span>',
          'prodi' => '<strong>' . e($item->prodi) . '</strong>' . ($item->konsentrasi && $item->konsentrasi != '-' ? '<br><small class="text-muted"><i class="fa fa-tag"></i> ' . e($item->konsentrasi) . '</small>' : ''),
          'kelas' => '<span class="label label-info" style="font-size: 11px;">' . e(optional($item->kelas)->kelas ?? '-') . '</span>',
          'angkatan' => '<span class="label label-default" style="font-size: 11px;">' . e(optional($item->angkatan)->angkatan ?? '-') . '</span> <br><small class="text-muted">' . ($item->intake == '1' ? 'Ganjil' : 'Genap') . '</small>',
          'dosen_pembimbing' => optional(optional($item->dosenPembimbing)->dosen)->nama ? ('<i class="fa fa-user-circle text-muted"></i> ' . e($item->dosenPembimbing->dosen->nama)) : '<span class="text-muted">-</span>',
          'jml_sks' => $sksBadge,
          'aksi' => '<div class="btn-group" role="group">' .
            '<a href="' . url('/krs-manual/detail/' . $item->idstudent) . '" class="btn btn-info btn-xs btn-flat" title="Lihat Detail KRS"><i class="fa fa-eye"></i> Detail</a> ' .
            '<a href="' . url('/krs-manual/create/' . $item->idstudent) . '" class="btn btn-primary btn-xs btn-flat" title="Kelola / Tambah KRS"><i class="fa fa-pencil"></i> Kelola</a>' .
            '</div>'
        ];
      }

      return response()->json([
        'draw' => $request->input('draw'),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $formattedData
      ]);
    }

    // Hitung statistik untuk info-boxes
    $totalMahasiswaAktif = Student::whereIn('active', [1, 5])->count();
    $sudahKrsCount = Student::whereIn('active', [1, 5])
      ->whereHas('student_records', function ($q) use ($tahunActive, $tipeActive) {
        $q->where('status', 'TAKEN');
        if ($tahunActive && $tipeActive) {
          $q->whereHas('kurperiode', function ($kp) use ($tahunActive, $tipeActive) {
            $kp->where('id_periodetahun', $tahunActive->id_periodetahun)
              ->where('id_periodetipe', $tipeActive->id_periodetipe);
          });
        }
      })->count();
    $belumKrsCount = max(0, $totalMahasiswaAktif - $sudahKrsCount);

    // List master untuk filter dropdown
    $listProdi = Prodi::select('kodeprodi', DB::raw('MAX(prodi) as prodi'))->groupBy('kodeprodi')->orderBy('prodi', 'ASC')->get();
    $listKelas = Kelas::orderBy('kelas', 'ASC')->get();
    $listAngkatan = Angkatan::orderBy('angkatan', 'DESC')->get();

    return view('sadmin.krs.krs-manual', compact(
      'tahunActive',
      'tipeActive',
      'totalMahasiswaAktif',
      'sudahKrsCount',
      'belumKrsCount',
      'listProdi',
      'listKelas',
      'listAngkatan'
    ));
  }

  public function detailKrsManual($id)
  {
    $dataMhs = Student::with([
      'angkatan:idangkatan,angkatan',
      'kelas:idkelas,kelas',
      'dosenPembimbing' => function ($q) {
        $q->select('id', 'id_dosen', 'id_student', 'status')
          ->with([
            'dosen' => function ($q2) {
              $q2->select('iddosen', 'nama', 'akademik');
            }
          ]);
      }
    ])
      ->join('prodi', function ($join) {
        $join->on('student.kodeprodi', '=', 'prodi.kodeprodi')
          ->on('student.kodekonsentrasi', '=', 'prodi.kodekonsentrasi');
      })
      ->select(
        'student.idstudent',
        'student.idangkatan',
        'student.idstatus',
        'student.nim',
        'student.nama',
        'student.kodeprodi',
        'student.kodekonsentrasi',
        'student.intake',
        'student.active',
        'prodi.id_prodi',
        'prodi.prodi',
        'prodi.konsentrasi'
      )
      ->where('student.idstudent', $id)
      ->firstOrFail();

    $tahunActive = Periode_tahun::where('status', 'ACTIVE')->first();
    $tipeActive = Periode_tipe::where('status', 'ACTIVE')->first();

    $dataKrsMhs = Student_record::where('id_student', $id)
      ->where('status', 'TAKEN')
      ->whereHas('kurperiode', function ($q) use ($tahunActive, $tipeActive) {
        if ($tahunActive && $tipeActive) {
          $q->where('id_periodetahun', $tahunActive->id_periodetahun)
            ->where('id_periodetipe', $tipeActive->id_periodetipe);
        }
      })
      ->with([
        'kurperiode' => function ($q) {
          $q->with([
            'makul:idmakul,kode,makul,akt_sks_teori,akt_sks_praktek',
            'dosen:iddosen,nama,akademik',
            'semester:idsemester,semester',
            'kelas:idkelas,kelas',
            'hari:id_hari,hari',
            'jam:id_jam,jam',
            'ruangan:id_ruangan,nama_ruangan'
          ]);
        }
      ])
      ->get();

    $totalSksTeori = 0;
    $totalSksPraktek = 0;
    $totalSks = 0;

    foreach ($dataKrsMhs as $krs) {
      if ($krs->kurperiode && $krs->kurperiode->makul) {
        $sksT = (int) ($krs->kurperiode->makul->akt_sks_teori ?? 0);
        $sksP = (int) ($krs->kurperiode->makul->akt_sks_praktek ?? 0);
        $totalSksTeori += $sksT;
        $totalSksPraktek += $sksP;
        $totalSks += ($sksT + $sksP);
      }
    }

    return view('sadmin.krs.krs-manual-detail', compact(
      'dataMhs',
      'tahunActive',
      'tipeActive',
      'dataKrsMhs',
      'totalSksTeori',
      'totalSksPraktek',
      'totalSks'
    ));
  }

  public function createKrsManual($id)
  {
    $dataMhs = Student::with([
      'angkatan:idangkatan,angkatan',
      'kelas:idkelas,kelas'
    ])
      ->join('prodi', function ($join) {
        $join->on('student.kodeprodi', '=', 'prodi.kodeprodi')
          ->on('student.kodekonsentrasi', '=', 'prodi.kodekonsentrasi');
      })
      ->select(
        'student.idstudent',
        'student.idangkatan',
        'student.idstatus',
        'student.nim',
        'student.nama',
        'student.kodeprodi',
        'student.kodekonsentrasi',
        'student.intake',
        'prodi.id_prodi',
        'prodi.prodi',
        'prodi.konsentrasi'
      )
      ->where('student.idstudent', $id)
      ->firstOrFail();

    $tahunActive = Periode_tahun::where('status', 'ACTIVE')->first();
    $tipeActive = Periode_tipe::where('status', 'ACTIVE')->first();
    $kurikulumMhs = Kurikulum_master::where('remark', $dataMhs->intake)->first();

    $dataKrsMhs = Student_record::where('id_student', $id)
      ->where('status', 'TAKEN')
      ->whereHas('kurperiode', function ($q) use ($tahunActive, $tipeActive) {
        if ($tahunActive && $tipeActive) {
          $q->where('id_periodetahun', $tahunActive->id_periodetahun)
            ->where('id_periodetipe', $tipeActive->id_periodetipe);
        }
      })
      ->with([
        'kurperiode' => function ($q) use ($tahunActive, $tipeActive) {
          $q->select('id_kurperiode', 'id_periodetahun', 'id_periodetipe', 'id_makul', 'id_dosen', 'id_semester', 'id_kelas')
            ->with([
              'makul:idmakul,kode,makul,akt_sks_teori,akt_sks_praktek',
              'tahun:id_periodetahun,periode_tahun,status',
              'tipe:id_periodetipe,periode_tipe,status',
              'dosen:iddosen,nama,akademik'
            ]);
        }
      ])
      ->select('id_studentrecord', 'tanggal_krs', 'id_student', 'id_kurperiode', 'id_kurtrans', 'status', 'remark')
      ->get();

    // Hitung total SKS yang saat ini sudah diambil
    $totalSksDiambil = 0;
    foreach ($dataKrsMhs as $krs) {
      if ($krs->kurperiode && $krs->kurperiode->makul) {
        $totalSksDiambil += ($krs->kurperiode->makul->akt_sks_teori ?? 0) + ($krs->kurperiode->makul->akt_sks_praktek ?? 0);
      }
    }

    // Filter katalog kelas & semester
    $kelasList = Kelas::orderBy('kelas', 'ASC')->get();
    $semesterList = Semester::orderBy('idsemester', 'ASC')->get();
    $selectedKelas = request()->get('filter_kelas', optional($dataMhs->kelas)->idkelas);
    $selectedSemester = request()->get('filter_semester', '');

    $dataKrsQuery = Kurikulum_periode::with([
      'tahun:id_periodetahun,periode_tahun',
      'tipe:id_periodetipe,periode_tipe',
      'makul:idmakul,kode,makul,akt_sks_teori,akt_sks_praktek,active',
      'dosen:iddosen,nama,akademik',
      'semester:idsemester,semester',
      'kelas:idkelas,kelas',
      'hari:id_hari,hari',
      'jam:id_jam,jam',
      'ruangan:id_ruangan,nama_ruangan'
    ])
      ->where('id_periodetahun', $tahunActive->id_periodetahun)
      ->where('id_periodetipe', $tipeActive->id_periodetipe)
      ->where('id_prodi', $dataMhs->id_prodi)
      ->where('status', 'ACTIVE');

    if ($selectedKelas && $selectedKelas != 'all') {
      $dataKrsQuery->where('id_kelas', $selectedKelas);
    }
    if ($selectedSemester && $selectedSemester != 'all') {
      $dataKrsQuery->where('id_semester', $selectedSemester);
    }

    $dataKrsCollection = $dataKrsQuery->orderBy('id_semester', 'ASC')
      ->orderBy('id_makul', 'ASC')
      ->get();

    if ($dataKrsCollection->isNotEmpty() && $kurikulumMhs) {
      $periodeMakulIds = $dataKrsCollection->pluck('id_makul');

      $bomMap = Matakuliah_bom::whereIn('slave_idmakul', $periodeMakulIds)
        ->get()
        ->keyBy('slave_idmakul');

      $masterIds = $bomMap->pluck('master_idmakul');
      $allPossibleMakulIds = $periodeMakulIds->merge($masterIds)->unique();

      $kurtransactions = Kurikulum_transaction::whereIn('id_makul', $allPossibleMakulIds)
        ->where('id_kurikulum', $kurikulumMhs->id_kurikulum)
        ->where('id_prodi', $dataMhs->id_prodi)
        ->where('status', 'ACTIVE')
        ->get()
        ->keyBy('id_makul');

      $dataKrsCollection->each(function ($item) use ($kurtransactions, $bomMap) {
        $item->kurtrans = null;
        if (isset($kurtransactions[$item->id_makul])) {
          $item->kurtrans = $kurtransactions[$item->id_makul];
        } else if (isset($bomMap[$item->id_makul])) {
          $masterId = $bomMap[$item->id_makul]->master_idmakul;
          if (isset($kurtransactions[$masterId])) {
            $item->kurtrans = $kurtransactions[$masterId];
          }
        }
      });
    }

    $dataKrs = $dataKrsCollection;

    return view('sadmin.krs.krs-manual-create', compact(
      'id',
      'dataMhs',
      'dataKrsMhs',
      'dataKrs',
      'tahunActive',
      'tipeActive',
      'totalSksDiambil',
      'kelasList',
      'semesterList',
      'selectedKelas',
      'selectedSemester'
    ));
  }

  public function saveKrsManual(Request $request)
  {
    $request->validate([
      'id_student' => 'required',
      'id_kurperiode' => 'required',
    ]);

    try {
      $idStudent = $request->id_student;
      $idKurperiode = $request->id_kurperiode;
      $idKurtrans = $request->id_kurtrans;

      $kurperiode = Kurikulum_periode::with(['makul', 'dosen'])->find($idKurperiode);
      if (!$kurperiode) {
        return response()->json(['success' => false, 'message' => 'Mata kuliah periode tidak ditemukan.'], 404);
      }

      $tahunActive = Periode_tahun::where('status', 'ACTIVE')->first();
      $tipeActive = Periode_tipe::where('status', 'ACTIVE')->first();

      // Cek apakah mahasiswa sudah mengambil mata kuliah ini pada semester aktif
      $cekDuplicate = Student_record::where('id_student', $idStudent)
        ->where('status', 'TAKEN')
        ->whereHas('kurperiode', function ($q) use ($kurperiode, $tahunActive, $tipeActive) {
          $q->where('id_makul', $kurperiode->id_makul);
          if ($tahunActive && $tipeActive) {
            $q->where('id_periodetahun', $tahunActive->id_periodetahun)
              ->where('id_periodetipe', $tipeActive->id_periodetipe);
          }
        })
        ->first();

      if ($cekDuplicate) {
        return response()->json([
          'success' => false,
          'message' => 'Mata kuliah "' . ($kurperiode->makul->makul ?? '') . '" sudah diambil pada semester ini.'
        ]);
      }

      // Jika id_kurtrans kosong, cari kurtrans yang cocok
      if (empty($idKurtrans)) {
        $student = Student::find($idStudent);
        if ($student) {
          $kurikulumMhs = Kurikulum_master::where('remark', $student->intake)->first();
          if ($kurikulumMhs) {
            $kt = Kurikulum_transaction::where('id_makul', $kurperiode->id_makul)
              ->where('id_kurikulum', $kurikulumMhs->id_kurikulum)
              ->where('status', 'ACTIVE')
              ->first();
            if ($kt) {
              $idKurtrans = $kt->idkurtrans;
            }
          }
        }
      }

      // Simpan Student Record baru
      $krs = new Student_record;
      $krs->id_student = $idStudent;
      $krs->id_kurperiode = $idKurperiode;
      $krs->id_kurtrans = $idKurtrans ?? 0;
      $krs->tanggal_krs = date('Y-m-d');
      $krs->status = 'TAKEN';
      $krs->remark = 0;
      $krs->data_origin = 'eSIAM-Manual';
      $krs->save();

      // Hitung total SKS terkini mahasiswa di periode aktif
      $currentRecords = Student_record::where('id_student', $idStudent)
        ->where('status', 'TAKEN')
        ->whereHas('kurperiode', function ($q) use ($tahunActive, $tipeActive) {
          if ($tahunActive && $tipeActive) {
            $q->where('id_periodetahun', $tahunActive->id_periodetahun)
              ->where('id_periodetipe', $tipeActive->id_periodetipe);
          }
        })
        ->with('kurperiode.makul')
        ->get();

      $totalSksNow = 0;
      foreach ($currentRecords as $rec) {
        if ($rec->kurperiode && $rec->kurperiode->makul) {
          $totalSksNow += ($rec->kurperiode->makul->akt_sks_teori ?? 0) + ($rec->kurperiode->makul->akt_sks_praktek ?? 0);
        }
      }

      $makul = $kurperiode->makul;
      $dosen = $kurperiode->dosen;
      $sksItem = ($makul->akt_sks_teori ?? 0) + ($makul->akt_sks_praktek ?? 0);

      return response()->json([
        'success' => true,
        'message' => 'Mata kuliah ' . ($makul->makul ?? '') . ' berhasil ditambahkan.',
        'id_studentrecord' => $krs->id_studentrecord,
        'id_kurperiode' => $idKurperiode,
        'kode_makul' => $makul->kode ?? '',
        'nama_makul' => $makul->makul ?? '',
        'sks' => $sksItem,
        'nama_dosen' => $dosen ? $dosen->nama : '-',
        'remark' => 'belum',
        'total_sks_now' => $totalSksNow,
        'total_makul_now' => $currentRecords->count()
      ]);
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
      ], 500);
    }
  }

  public function cancelKrsManual($id)
  {
    try {
      $record = Student_record::with('kurperiode.makul')->find($id);
      if (!$record) {
        return response()->json(['success' => false, 'message' => 'Data KRS tidak ditemukan.'], 404);
      }

      $idStudent = $record->id_student;
      $idKurperiode = $record->id_kurperiode;
      $makulName = optional(optional($record->kurperiode)->makul)->makul ?? 'Mata kuliah';

      $record->status = 'DROPPED';
      $record->save();

      $tahunActive = Periode_tahun::where('status', 'ACTIVE')->first();
      $tipeActive = Periode_tipe::where('status', 'ACTIVE')->first();

      $currentRecords = Student_record::where('id_student', $idStudent)
        ->where('status', 'TAKEN')
        ->whereHas('kurperiode', function ($q) use ($tahunActive, $tipeActive) {
          if ($tahunActive && $tipeActive) {
            $q->where('id_periodetahun', $tahunActive->id_periodetahun)
              ->where('id_periodetipe', $tipeActive->id_periodetipe);
          }
        })
        ->with('kurperiode.makul')
        ->get();

      $totalSksNow = 0;
      foreach ($currentRecords as $rec) {
        if ($rec->kurperiode && $rec->kurperiode->makul) {
          $totalSksNow += ($rec->kurperiode->makul->akt_sks_teori ?? 0) + ($rec->kurperiode->makul->akt_sks_praktek ?? 0);
        }
      }

      return response()->json([
        'success' => true,
        'message' => $makulName . ' berhasil dibatalkan dari KRS.',
        'id_studentrecord' => $id,
        'id_kurperiode' => $idKurperiode,
        'total_sks_now' => $totalSksNow,
        'total_makul_now' => $currentRecords->count()
      ]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => 'Gagal membatalkan KRS: ' . $e->getMessage()], 500);
    }
  }
}
