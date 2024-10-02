<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\Models\Prodi;
use App\Models\Biaya;
use App\Models\Kuitansi;
use App\Models\Beasiswa;
use App\Models\Student;
use App\Models\Semester;
use App\Models\Matakuliah;
use App\Models\Periode_tipe;
use App\Models\Periode_tahun;
use App\Models\Student_record;
use App\Models\Kurikulum_periode;
use App\Models\Kurikulum_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
  public function nilai()
  {
    $id = Auth::user()->id_user;
    $maha = Student::where('idstudent', $id)->first();

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tp = Periode_tipe::where('status', 'ACTIVE')->first();

    $tpee = Periode_tipe::all();

    $sub_thn = substr($thn->periode_tahun, 6, 2);
    $idtp = $tp->id_periodetipe;
    $smt = $sub_thn . $idtp;
    $angk = $maha->idangkatan;

    if ($smt % 2 != 0) {
      $a = (($smt + 10) - 1) / 10;
      $b = $a - $angk;
      $c = ($b * 2) - 1;
    } else {
      $a = (($smt + 10) - 2) / 10;
      $b = $a - $angk;
      $c = $b * 2;
    }

    $record = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->where('student_record.id_student', $id)
      ->where('student_record.status', 'TAKEN')
      ->whereNotIn('kurikulum_periode.id_semester', [$c])
      ->select('kurikulum_periode.id_periodetahun', 'periode_tahun.periode_tahun')
      ->groupBy('kurikulum_periode.id_periodetahun', 'periode_tahun.periode_tahun')
      ->orderBy('kurikulum_periode.id_periodetahun', 'ASC')
      ->get();

    $hitung = count($record);

    return view('mhs/nilai/cek', ['tpe' => $tpee, 'add' => $record,  'idmhs' => $maha]);
  }

  public function view_nilai(Request $request)
  {
    $tahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)
      ->select('periode_tahun', 'id_periodetahun')
      ->first();

    $periodetahun = $tahun->periode_tahun;
    $idperiodetahun = $tahun->id_periodetahun;

    $tipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)
      ->select('periode_tipe', 'id_periodetipe')
      ->first();

    $periodetipe = $tipe->periode_tipe;
    $idperiodetipe = $tipe->id_periodetipe;

    $id = Auth::user()->id_user;

    $mhs = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->where('student.idstudent', $id)
      ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'student.idstudent')
      ->first();

    $iduser = $mhs->idstudent;

    //cek nilai tahun akademik 
    $cekrecord = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->where('student_record.id_student', $iduser)
      ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
      ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
      ->where('student_record.status', 'TAKEN')
      ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul', 'student_record.nilai_ANGKA', 'kurikulum_periode.id_periodetahun', 'kurikulum_periode.id_periodetipe')
      ->groupBy('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul', 'student_record.nilai_ANGKA', 'kurikulum_periode.id_periodetahun', 'kurikulum_periode.id_periodetipe')
      ->get();

    $cn = count($cekrecord);

    if ($cn == 0) {
      Alert::error('Tahun Akademik yang anda pilih belum ada', 'MAAF !!');
      return redirect()->back();
    } elseif ($cn > 0) {

      $record = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
        ->where('student_record.id_student', $iduser)
        ->where('kurikulum_periode.id_periodetipe',  $request->id_periodetipe)
        ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
        ->where('student_record.status', 'TAKEN')
        ->select('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA',  'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
        ->groupBy('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA',  'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
        ->get();

      //jumlah sks
      $sks = 0;
      foreach ($record as $keysks) {
        $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
      }

      //cek nilai x sks
      $nxsks = 0;
      foreach ($record as $totsks) {
        $nxsks += ($totsks->akt_sks_teori + $totsks->akt_sks_praktek) * $totsks->nilai_ANGKA;
      }

      return view('mhs/nilai/nilai_khs', ['periodetahun' => $periodetahun, 'periodetipe' => $periodetipe, 'idperiodetipe' => $idperiodetipe, 'idperiodetahun' => $idperiodetahun, 'nxsks' => $nxsks, 'sks' => $sks, 'mhs' => $mhs, 'data' => $record, 'iduser' => $iduser]);
    }
  }

  public function unduh_khs_nilaipdf(Request $request)
  {
    $thns = $request->id_periodetahun;
    $tps = $request->id_periodetipe;
    $iduser = $request->id_student;

    $tahun = Periode_tahun::where('id_periodetahun', $thns)
      ->select('periode_tahun', 'id_periodetahun')
      ->first();

    $periodetahun = $tahun->periode_tahun;
    $idperiodetahun = $tahun->id_periodetahun;

    $tipe = Periode_tipe::where('id_periodetipe', $tps)
      ->select('periode_tipe', 'id_periodetipe')
      ->first();

    $periodetipe = $tipe->periode_tipe;
    $idperiodetipe = $tipe->id_periodetipe;

    $id = Auth::user()->id_user;

    $mhs = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->where('student.idstudent', $id)
      ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'student.idstudent')
      ->first();

    $ds = $mhs->idstudent;

    $data = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('student_record.id_student', $ds)
      ->where('kurikulum_periode.id_periodetipe', $tps)
      ->where('kurikulum_periode.id_periodetahun', $thns)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->groupBy('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->get();

    //jumlah sks
    $sks = 0;
    foreach ($data as $keysks) {
      $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
    }

    //cek nilai x sks
    $nxsks = 0;
    foreach ($data as $totsks) {
      $nxsks += ($totsks->akt_sks_teori + $totsks->akt_sks_praktek) * $totsks->nilai_ANGKA;
    }

    $pdf = PDF::loadView('mhs/nilai/khs_nilai_pdf', compact('periodetahun', 'periodetipe', 'nxsks', 'sks', 'mhs', 'data', 'iduser'));
    return $pdf->download('KHS-' . Auth::user()->name . '-' . date("d-m-Y") . '.pdf');
  }

  public function khs()
  {
    $id = Auth::user()->id_user;

    $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->where('student_record.id_student', $id)
      ->where('student_record.status', 'TAKEN')
      // ->whereNotIn('kurikulum_periode.id_semester', [$c])
      ->select('kurikulum_periode.id_periodetahun', 'periode_tahun.periode_tahun', 'periode_tipe.id_periodetipe', 'periode_tipe.periode_tipe')
      ->groupBy('kurikulum_periode.id_periodetahun', 'periode_tahun.periode_tahun', 'periode_tipe.id_periodetipe', 'periode_tipe.periode_tipe')
      ->orderBy('kurikulum_periode.id_periodetahun', 'ASC')
      ->get();

    return view('mhs/khs/filter_khs', compact('data'));
  }

  public function filter_khs(Request $request)
  {
    $id = Auth::user()->id_user;

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
        'student.kodeprodi',
        'student.intake'
      )
      ->first();

    $periode_khs = $request->id_periodetahun;
    $pisah = explode(',', $periode_khs, 2);
    $idthn = $pisah[0];
    $idtp = $pisah[1];
    $tipe_khs = $request->tipe_khs;

    $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
    $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

    $prd_thn = Periode_tahun::where('id_periodetahun', $idthn)->first();
    $prd_tp = Periode_tipe::where('id_periodetipe', $idtp)->first();

    $nama_periodetahun = $prd_thn->periode_tahun;
    $nama_periodetipe = $prd_tp->periode_tipe;

    $idangkatan = $mhs->idangkatan;
    $idstatus = $mhs->idstatus;
    $kodeprodi = $mhs->kodeprodi;
    $intake = $mhs->intake;

    $sub_thn = substr($prd_thn->periode_tahun, 6, 2);
    $tp = $prd_tp->id_periodetipe;
    $smt = $sub_thn . $tp;
    $angk = $mhs->idangkatan;

    if ($smt % 2 != 0) {
      if ($tp == 1) {
        //ganjil
        $a = (($smt + 10) - 1) / 10; // ( 211 + 10 - 1 ) / 10 = 22
        $b = $a - $idangkatan; // 22 - 20 = 2
        if ($intake == 2) {
          $c = ($b * 2) - 1 - 1;
        } elseif ($intake == 1) {
          $c = ($b * 2) - 1;
        } // 2 * 2 - 1 = 3
      } elseif ($tp == 3) {
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

    $biaya = Biaya::where('idangkatan', $idangkatan)
      ->where('idstatus', $idstatus)
      ->where('kodeprodi', $kodeprodi)
      ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14')
      ->first();

    $cb = Beasiswa::where('idstudent', $id)->first();

    if ($cb != null) {
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
      $spp11 = $biaya->spp11 - ($biaya->spp11 * $cb->spp11) / 100;
      $spp12 = $biaya->spp12 - ($biaya->spp12 * $cb->spp12) / 100;
      $spp13 = $biaya->spp13 - ($biaya->spp13 * $cb->spp13) / 100;
      $spp14 = $biaya->spp14 - ($biaya->spp14 * $cb->spp14) / 100;
    } elseif ($cb == null) {
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

    //total pembayaran kuliah
    $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
      ->where('kuitansi.idstudent', $id)
      ->whereNotIn('bayar.iditem', [14, 15, 16, 35, 36, 37, 38, 39])
      ->sum('bayar.bayar');

    if ($tipe_khs == 'UTS') {

      if ($c == 1) {
        $cekbyr = $daftar + $awal + $spp1 / 2 - $total_semua_dibayar;
      } elseif ($c == 2) {
        $cekbyr = $daftar + $awal + ($dsp * 75) / 100 + $spp1 + $spp2 / 2 - $total_semua_dibayar;
      } elseif ($c == '201') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + ($spp2 * 82 / 100) - $total_semua_dibayar;
      } elseif ($c == 3) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 / 2 - $total_semua_dibayar;
      } elseif ($c == 4) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 33) / 100 - $total_semua_dibayar;
      } elseif ($c == '401') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82 / 100) - $total_semua_dibayar;
      } elseif ($c == 5) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 / 2 - $total_semua_dibayar;
      } elseif ($c == 6) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 / 2 - $total_semua_dibayar;
      } elseif ($c == '601') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82 / 100) - $total_semua_dibayar;
      } elseif ($c == 7) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 / 2 - $total_semua_dibayar;
      } elseif ($c == 8) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 / 2 - $total_semua_dibayar;
      } elseif ($c == '801') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82 / 100) - $total_semua_dibayar;
      } elseif ($c == 9) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 / 2 - $total_semua_dibayar;
      } elseif ($c == 10) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 / 2 - $total_semua_dibayar;
      } elseif ($c == '1001') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82 / 100) - $total_semua_dibayar;
      } elseif ($c == 11) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 / 2 - $total_semua_dibayar;
      } elseif ($c == 12) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 / 2 - $total_semua_dibayar;
      } elseif ($c == 13) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 / 2 - $total_semua_dibayar;
      } elseif ($c == 14) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + $spp14 / 2 - $total_semua_dibayar;
      }

      if ($cekbyr == 0 or $cekbyr < 1) {
        $record = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->where('student_record.id_student', $id)
          ->where('kurikulum_periode.id_periodetipe', $idtp)
          ->where('kurikulum_periode.id_periodetahun', $idthn)
          ->where('student_record.status', 'TAKEN')
          ->select('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
          ->groupBy('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
          ->orderBy('matakuliah.kode', 'ASC')
          ->get();

        $sks = 0;
        foreach ($record as $keysks) {
          $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
        }

        return view('mhs/khs/filter_khs_mid', compact('nama_periodetahun', 'nama_periodetipe', 'sks', 'mhs', 'record', 'idthn', 'idtp', 'id'));
      } else {
        Alert::warning('Maaf anda tidak dapat melihat KHS karena keuangan Anda belum memenuhi syarat');
        return redirect('home');
      }
    } elseif ($tipe_khs == 'FINAL') {

      if ($c == 1) {
        $cekbyr = $daftar + $awal + $spp1 - $total_semua_dibayar;
      } elseif ($c == 2) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 - $total_semua_dibayar;
      } elseif ($c == '201') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 - $total_semua_dibayar;
      } elseif ($c == 3) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 - $total_semua_dibayar;
      } elseif ($c == 4) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
      } elseif ($c == '401') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
      } elseif ($c == 5) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 - $total_semua_dibayar;
      } elseif ($c == 6) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
      } elseif ($c == '601') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
      } elseif ($c == 7) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 - $total_semua_dibayar;
      } elseif ($c == 8) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
      } elseif ($c == '801') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
      } elseif ($c == 9) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 - $total_semua_dibayar;
      } elseif ($c == 10) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
      } elseif ($c == '1001') {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
      } elseif ($c == 11) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 - $total_semua_dibayar;
      } elseif ($c == 12) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 - $total_semua_dibayar;
      } elseif ($c == 13) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 - $total_semua_dibayar;
      } elseif ($c == 14) {
        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + $spp14 - $total_semua_dibayar;
      }

      if ($cekbyr == 0 or $cekbyr < 1) {
        $recordas = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->where('student_record.id_student', $id)
          ->where('kurikulum_periode.id_periodetipe', $idtp)
          ->where('kurikulum_periode.id_periodetahun', $idthn)
          ->where('student_record.status', 'TAKEN')
          ->select('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_ANGKA', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
          ->groupBy('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_ANGKA', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
          ->orderBy('matakuliah.kode', 'ASC')
          ->get();

        //jumlah SKS
        $sks = 0;
        foreach ($recordas as $keysks) {
          $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
        }

        //cek nilai x sks
        $nxsks = 0;
        foreach ($recordas as $totsks) {
          $nxsks += ($totsks->akt_sks_teori + $totsks->akt_sks_praktek) * $totsks->nilai_ANGKA;
        }

        return view('mhs/khs/filter_khs_final', compact('nama_periodetahun', 'nama_periodetipe', 'sks', 'mhs', 'recordas', 'idthn', 'idtp', 'id', 'nxsks'));
      } else {
        Alert::error('Maaf anda tidak dapat melihat KHS karena keuangan Anda belum memenuhi syarat', 'MAAF !!');
        return redirect('home');
      }
    }
  }

  public function unduh_khs_mid_term(Request $request)
  {
    $id = $request->id_student;
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;

    $mhs = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->where('student.idstudent', $id)
      ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
      ->first();

    $periode_tahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periode_tipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();

    $nama = $mhs->nama;
    $prodi = $mhs->prodi;
    $kelas = $mhs->kelas;

    $record = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
      ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->groupBy('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->get();

    $sks = 0;
    foreach ($record as $keysks) {
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

    $pdf = PDF::loadView('mhs/khs/khs_mid_pdf', ['periode_tahun' => $periode_tahun, 'periode_tipe' => $periode_tipe, 'd' => $d, 'm' => $m, 'y' => $y, 'mhs' => $mhs, 'krs' => $record, 'sks' => $sks])->setPaper('a4', 'portrait');
    return $pdf->download('KHS UTS' . ' ' . $nama . ' ' . $prodi . ' ' . $kelas . ' ' . '(' . $periode_tahun->periode_tahun . ' ' . $periode_tipe->periode_tipe . ')' . '.pdf');
  }

  public function unduh_khs_final_term(Request $request)
  {
    $id = $request->id_student;
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;

    $mhs = Student::leftJoin('prodi', (function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    }))
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->where('student.idstudent', $id)
      ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
      ->first();

    $nama = $mhs->nama;
    $prodi = $mhs->prodi;
    $kelas = $mhs->kelas;

    $periode_tahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periode_tipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();

    $nama_periodetahun = $periode_tahun->periode_tahun;
    $nama_periodetipe = $periode_tipe->periode_tipe;

    $data = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('student_record.id_student', $id)
      ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
      ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
      ->where('student_record.status', 'TAKEN')
      ->select('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->groupBy('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
      ->get();

    //jumlah sks
    $sks = 0;
    foreach ($data as $keysks) {
      $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
    }

    //cek nilai x sks
    $nxsks = 0;
    foreach ($data as $totsks) {
      $nxsks += ($totsks->akt_sks_teori + $totsks->akt_sks_praktek) * $totsks->nilai_ANGKA;
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

    $pdf = PDF::loadView('mhs/khs/khs_final_pdf', compact('nama_periodetahun', 'nama_periodetipe', 'nxsks', 'sks', 'mhs', 'data', 'd', 'm', 'y'))->setPaper('a4', 'portrait');
    return $pdf->download('KHS FINAL' . ' ' . $nama . ' ' . $prodi . ' ' . $kelas . ' ' . '(' . $nama_periodetahun . ' ' . $nama_periodetipe . ')' . '.pdf');
  }

  public function input_nilai_by_admin($id)
  {
    $idKurperiode = $id;

    $cekMatkul = Kurikulum_periode::with('makul')->where('id_kurperiode', $idKurperiode)->first();

    $data = DB::table('student_record as b')
      ->join('kurikulum_periode as a', 'b.id_kurperiode', '=', 'a.id_kurperiode')
      ->join('student as c', 'b.id_student', '=', 'c.idstudent')
      ->join('prodi as d', function ($join) {
        $join->on('d.kodeprodi', '=', 'c.kodeprodi')
          ->on('d.kodekonsentrasi', '=', 'c.kodekonsentrasi');
      })
      ->join('kelas as e', 'c.idstatus', '=', 'e.idkelas')
      ->join('angkatan as f', 'c.idangkatan', '=', 'f.idangkatan')
      ->where('b.status', 'TAKEN')
      ->where('c.active', 1)
      ->where('a.status', 'ACTIVE')
      ->whereIn(DB::raw('(a.id_periodetahun, a.id_periodetipe, a.id_semester, a.id_kelas, a.id_makul, a.id_hari, a.id_jam, a.id_dosen)'), function ($query) use ($idKurperiode) {
        $query->select(DB::raw('id_periodetahun, id_periodetipe, id_semester, id_kelas, id_makul, id_hari, id_jam, id_dosen'))
          ->from('kurikulum_periode')
          ->where('id_kurperiode', $idKurperiode);
      })
      ->select(
        'b.id_studentrecord',
        'b.id_kurperiode',
        'b.id_kurtrans',
        'b.id_student',
        'c.nim',
        'c.nama',
        'd.prodi',
        'e.kelas',
        'f.angkatan',
        'b.nilai_KAT',
        'b.nilai_UTS',
        'b.nilai_UAS',
        'b.nilai_AKHIR',
        'b.nilai_AKHIR_angka'
      )
      ->orderBy('d.prodi', 'ASC')
      ->orderBy('e.kelas', 'ASC')
      ->orderBy('c.nim', 'ASC')
      ->get();

    return view('sadmin/nilai/input_nilai_by_admin', compact('data', 'idKurperiode', 'cekMatkul'));
  }

  public function save_nilai_by_admin(Request $request)
  {
    // Data input dari request
    $idStudentRecords = $request->input('id_studentrecord', []);
    $nilaiAkhirAngka = $request->input('nilai_AKHIR_angka', []);
    $nilaiAkhir = $request->input('nilai_AKHIR', []);
    $nilaiAngka = $request->input('nilai_ANGKA', []);

    DB::beginTransaction();

    try {
      foreach ($idStudentRecords as $key => $id) {

        $finalNilaiAkhirAngka = isset($nilaiAkhirAngka[$key]) ? $nilaiAkhirAngka[$key] : 0;
        $finalNilaiAkhir = isset($nilaiAkhir[$key]) ? $nilaiAkhir[$key] : '0'; // Set ke '0' atau nilai lain sesuai kebutuhan
        $finalNilaiAngka = isset($nilaiAngka[$key]) ? $nilaiAngka[$key] : 0;

        DB::table('student_record')
          ->where('id_studentrecord', $id)
          ->update([
            'nilai_AKHIR_angka' => $finalNilaiAkhirAngka,
            'nilai_AKHIR' => $finalNilaiAkhir,
            'nilai_ANGKA' => $finalNilaiAngka,
            'updated_at' => now(),
          ]);
      }

      // Commit transaksi jika semua berhasil
      DB::commit();

      return redirect('list_mahasiswa_makul/' . $request->id_kurperiode)->with('success', 'Data Nilai Berhasil disimpan');
    } catch (\Exception $e) {
      // Rollback transaksi jika terjadi error
      DB::rollBack();
      // Tangani error sesuai kebutuhan
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }
}
