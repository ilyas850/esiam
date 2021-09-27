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
      $id = Auth::user()->username;
      $maha = Student::where('nim', Auth::user()->username)->get();

      foreach ($maha as $key) {
        # code...
      }
      $thn = Periode_tahun::where('status', 'ACTIVE')->get();
      foreach ($thn as $tahun) {
        // code...
      }

      $tp = Periode_tipe::where('status', 'ACTIVE')->get();
      foreach ($tp as $tipe) {
        // code...
      }

      $semester = Semester::all();
      $thunn = Periode_tahun::all();
      $tpee = Periode_tipe::all();

      $sub_thn = substr($tahun->periode_tahun,6,2);
      $tp = $tipe->id_periodetipe;
      $smt = $sub_thn.$tp;
      $angk = $key->idangkatan;

      if ($smt %2 != 0){
      $a = (($smt + 10)-1)/10;
      $b = $a - $angk;
      $c = ($b*2)-1;
      }else{
        $a = (($smt + 10)-2)/10;
        $b = $a - $angk;
        $c = $b * 2;
      }

      $record = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                              ->where('student_record.id_student', $key->idstudent)
                              ->where('student_record.status', 'TAKEN')
                              ->whereNotIn('kurikulum_periode.id_semester', [$c])
                              // ->whereNotIn('kurikulum_periode.id_periodetahun', [$tahun->id_periodetahun])
                              // ->whereNotIn('kurikulum_periode.id_periodetipe', [$tipe->id_periodetipe])
                              ->select( 'kurikulum_periode.id_periodetahun')
                              ->groupBy( 'kurikulum_periode.id_periodetahun')
                              ->orderBy('kurikulum_periode.id_periodetahun', 'ASC')
                              ->get();

      $hitung = count($record);

      return view('mhs/nilai/cek', ['tpe'=>$tpee,'thun'=>$thunn,'add'=>$record,  'idmhs'=>$key]);
    }

    public function view_nilai(Request $request)
    {
      $tahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)
                            ->select('periode_tahun','id_periodetahun')
                            ->get();
      foreach ($tahun as $keytahun) {
        // code...
      }
      $periodetahun = $keytahun->periode_tahun;
      $idperiodetahun = $keytahun->id_periodetahun;

      $tipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)
                          ->select('periode_tipe','id_periodetipe')
                          ->get();
      foreach ($tipe as $keytipe) {
        // code...
      }
      $periodetipe = $keytipe->periode_tipe;
      $idperiodetipe = $keytipe->id_periodetipe;

      $id = Auth::user()->username;
      $maha = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                      ->where('student.nim', $id)
                      ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas','student.idstudent')
                      ->get();
      foreach ($maha as $mhs) {
        # code...
      }

      $makul = Matakuliah::all();
      $iduser = $mhs->idstudent;

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
      }elseif ($cn > 0) {

        $record = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $iduser)
                                ->where('kurikulum_periode.id_periodetipe',  $request->id_periodetipe)
                                ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA','kurikulum_transaction.id_makul', 'kurikulum_periode.id_periodetahun', 'kurikulum_periode.id_periodetipe', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                                ->groupBy('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'kurikulum_transaction.id_makul', 'kurikulum_periode.id_periodetahun', 'kurikulum_periode.id_periodetipe', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                                ->get();

        $record1 = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $iduser)
                                ->where('kurikulum_periode.id_periodetipe',  $request->id_periodetipe)
                                ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('student_record.id_kurtrans', DB::raw('COUNT(student_record.id_kurtrans) as products_count'), 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                                ->groupBy('student_record.id_kurtrans','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek')
                                ->having('products_count', '>' , 1)
                                ->get();

      foreach ($record1 as $key1) {
        // code...
      }

      $kurangi = $key1->akt_sks_teori + $key1->akt_sks_praktek;

      $jml_mkl = count($record1);

      $skst = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                            ->where('student_record.id_student', $iduser)
                            ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
                            ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                            ->where('student_record.status', 'TAKEN')
                            ->sum('matakuliah.akt_sks_teori');


      $sksp = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                            ->where('student_record.id_student', $iduser)
                            ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
                            ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                            ->where('student_record.status', 'TAKEN')
                            ->sum('matakuliah.akt_sks_praktek');

      if ($jml_mkl == 0) {
        $sks = $skst + $sksp;
      }elseif ($jml_mkl > 0) {
        $sks = $skst + $sksp - $kurangi;
      }

      $ceknilaisksd1 = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                              ->where('student_record.id_student', $iduser)
                              ->where('kurikulum_periode.id_periodetipe',  $request->id_periodetipe)
                              ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                              ->where('student_record.status', 'TAKEN')
                              ->select('student_record.id_kurtrans', DB::raw('COUNT(student_record.id_kurtrans) as products_count'), 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'student_record.nilai_ANGKA')
                              ->groupBy('student_record.id_kurtrans','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek', 'student_record.nilai_ANGKA')
                              ->having('products_count', '>' , 1)
                              ->get();

      foreach ($ceknilaisksd1 as $keysks) {
        // code...
      }
      $hslsks = ($keysks->akt_sks_teori + $keysks->akt_sks_praktek) * $keysks->nilai_ANGKA;

      $ceknilaisksd = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                    ->where('student_record.id_student', $iduser)
                                    ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
                                    ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                                    ->where('student_record.status', 'TAKEN')
                                    ->select(DB::raw('sum((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as akt_sks'))
                                    ->get();

        foreach ($ceknilaisksd as $keynia) {
          // code...
        }
        $nia = ($keynia->akt_sks - $hslsks);

      // return view('mhs/nilai/nilai_khs', compact('periodetahun','periodetipe','mhs'));
      return view('mhs/nilai/nilai_khs', ['periodetahun'=>$periodetahun,'periodetipe'=>$periodetipe, 'idperiodetipe'=>$idperiodetipe, 'idperiodetahun' => $idperiodetahun, 'nia' => $nia, 'mk' => $makul, 'sks' => $sks, 'mhs' => $mhs, 'data' => $record, 'iduser' => $iduser]);
      }

    }

    public function unduh_khs_nilaipdf(Request $request)
    {
      $thns = $request->id_periodetahun;
      $tps = $request->id_periodetipe;
      $iduser = $request->id_student;

      $tahun = Periode_tahun::where('id_periodetahun', $thns)
                            ->select('periode_tahun','id_periodetahun')
                            ->get();
      foreach ($tahun as $keytahun) {
        // code...
      }
      $periodetahun = $keytahun->periode_tahun;
      $idperiodetahun = $keytahun->id_periodetahun;

      $tipe = Periode_tipe::where('id_periodetipe', $tps)
                          ->select('periode_tipe','id_periodetipe')
                          ->get();
      foreach ($tipe as $keytipe) {
        // code...
      }
      $periodetipe = $keytipe->periode_tipe;
      $idperiodetipe = $keytipe->id_periodetipe;

      $id = Auth::user()->username;

      $maha = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                      ->where('student.nim', $id)
                      ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas','student.idstudent')
                      ->get();
      foreach ($maha as $mhs) {
        # code...
      }

      $ds = $mhs->idstudent;

      $mk = Matakuliah::all();

      $data = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                              ->where('student_record.id_student', $ds)
                              ->where('kurikulum_periode.id_periodetipe', $tps)
                              ->where('kurikulum_periode.id_periodetahun', $thns)
                              ->where('student_record.status', 'TAKEN')
                              ->select('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA','kurikulum_transaction.id_makul', 'kurikulum_periode.id_periodetahun', 'kurikulum_periode.id_periodetipe', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                              ->groupBy('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'kurikulum_transaction.id_makul', 'kurikulum_periode.id_periodetahun', 'kurikulum_periode.id_periodetipe', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                              ->get();

      $record1 = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $iduser)
                                ->where('kurikulum_periode.id_periodetipe',  $request->id_periodetipe)
                                ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('student_record.id_kurtrans', DB::raw('COUNT(student_record.id_kurtrans) as products_count'), 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                                ->groupBy('student_record.id_kurtrans','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek')
                                ->having('products_count', '>' , 1)
                                ->get();

        foreach ($record1 as $key1) {
        // code...
        }

        $kurangi = $key1->akt_sks_teori + $key1->akt_sks_praktek;

        $jml_mkl = count($record1);

        $skst = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $ds)
                                ->where('kurikulum_periode.id_periodetipe', $tps)
                                ->where('kurikulum_periode.id_periodetahun', $thns)
                                ->where('student_record.status', 'TAKEN')
                                ->sum('matakuliah.akt_sks_teori');

        $sksp = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $ds)
                                ->where('kurikulum_periode.id_periodetipe', $tps)
                                ->where('kurikulum_periode.id_periodetahun', $thns)
                                ->where('student_record.status', 'TAKEN')
                                ->sum('matakuliah.akt_sks_praktek');

        if ($jml_mkl == 0) {
          $sks = $skst + $sksp;
        }elseif ($jml_mkl > 0) {
          $sks = $skst + $sksp - $kurangi;
        }

        $ceknilaisksd1 = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $iduser)
                                ->where('kurikulum_periode.id_periodetipe',  $request->id_periodetipe)
                                ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('student_record.id_kurtrans', DB::raw('COUNT(student_record.id_kurtrans) as products_count'), 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'student_record.nilai_ANGKA')
                                ->groupBy('student_record.id_kurtrans','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek', 'student_record.nilai_ANGKA')
                                ->having('products_count', '>' , 1)
                                ->get();

        foreach ($ceknilaisksd1 as $keysks) {
          // code...
        }
        $hslsks = ($keysks->akt_sks_teori + $keysks->akt_sks_praktek) * $keysks->nilai_ANGKA;

        $ceknilaisksd=Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                    ->where('student_record.id_student', $ds)
                                    ->where('kurikulum_periode.id_periodetipe', $tps)
                                    ->where('kurikulum_periode.id_periodetahun', $thns)
                                    ->where('student_record.status', 'TAKEN')
                                    ->select(DB::raw('sum((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as akt_sks'))
                                    ->get();



        foreach ($ceknilaisksd as $keynia) {
          // code...
        }
        $nia = ($keynia->akt_sks - $hslsks);

      //return view('mhs/nilai/khs_nilai_pdf', ['periodetahun'=>$periodetahun,'periodetipe'=>$periodetipe, 'idperiodetipe'=>$idperiodetipe, 'idperiodetahun' => $idperiodetahun, 'nia' => $nia, 'mk' => $makul, 'sks' => $sks, 'mhs' => $mhs, 'data' => $record, 'iduser' => $iduser]);
      $pdf= PDF::loadView('mhs/nilai/khs_nilai_pdf',compact('periodetahun','periodetipe','idperiodetipe','idperiodetahun','nia','mk','sks','mhs','data','iduser'));
      return $pdf->download('KHS-'.Auth::user()->name.'-'.date("d-m-Y").'.pdf');
    }
}
