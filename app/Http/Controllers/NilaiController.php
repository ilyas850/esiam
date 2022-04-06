<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\Prodi;
use App\Student;
use App\Semester;
use App\Matakuliah;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Student_record;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
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

    $mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

    $mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
}
