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
  public function isi_krs()
  {
    $time = Waktu_krs::where('status', 1)->first();

    if ($time == null) {
      alert()->error('KRS Belum dibuka', 'Maaf silahkan menghubungi bagian akademik');
      return redirect('home');
    } elseif ($time->status == 1) {
      $id = Auth::user()->id_user;

      $maha = Student::leftJoin('prodi', (function ($join) {
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

      $ids = $maha->idstudent;
      $idangkatan = $maha->idangkatan;
      $idstatus = $maha->idstatus;
      $kodeprodi = $maha->kodeprodi;

      $thn = Periode_tahun::where('status', 'ACTIVE')->first();

      $tp = Periode_tipe::where('status', 'ACTIVE')->first();

      $periodetahun = $thn->periode_tahun;
      $periodetipe = $tp->periode_tipe;

      //cek semester
      $sub_thn = substr($thn->periode_tahun, 6, 2);
      $tipe = $tp->id_periodetipe;
      $smt = $sub_thn . $tipe;

      if ($smt % 2 != 0) {
        $a = (($smt + 10) - 1) / 10;
        $b = $a - $idangkatan;
        $c = ($b * 2) - 1;
      } else {
        $a = (($smt + 10) - 2) / 10;
        $b = $a - $idangkatan;
        $c = $b * 2;
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
          'prakerin',
          'seminar',
          'sidang',
          'wisuda'
        )
        ->first();

      $totalbiaya = $biaya->daftar + $biaya->awal + $biaya->dsp + $biaya->spp1 + $biaya->spp2 + $biaya->spp3 + $biaya->spp4 + $biaya->spp5 +
        $biaya->spp6 + $biaya->spp7 + $biaya->spp8 + $biaya->spp9 + $biaya->spp10 + $biaya->spp11 + $biaya->spp12 + $biaya->spp13 + $biaya->spp14;

      $cb = Beasiswa::where('idstudent', $ids)->first();

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
        ->where('student.idstudent', $ids)
        ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
        ->first();

      if ($cek_study->study_year == 3) {
        $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 1)
          ->sum('bayar.bayar');

        $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 2)
          ->sum('bayar.bayar');

        $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 3)
          ->sum('bayar.bayar');

        $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 4)
          ->sum('bayar.bayar');

        $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 5)
          ->sum('bayar.bayar');

        $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 6)
          ->sum('bayar.bayar');

        $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 7)
          ->sum('bayar.bayar');

        $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 8)
          ->sum('bayar.bayar');

        $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 9)
          ->sum('bayar.bayar');

        $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 10)
          ->sum('bayar.bayar');

        $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 11)
          ->sum('bayar.bayar');

        $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 12)
          ->sum('bayar.bayar');

        $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 13)
          ->sum('bayar.bayar');
      } elseif ($cek_study->study_year == 4) {

        $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 18)
          ->sum('bayar.bayar');

        $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 19)
          ->sum('bayar.bayar');

        $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 20)
          ->sum('bayar.bayar');

        $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 21)
          ->sum('bayar.bayar');

        $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 22)
          ->sum('bayar.bayar');

        $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 23)
          ->sum('bayar.bayar');

        $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 24)
          ->sum('bayar.bayar');

        $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 25)
          ->sum('bayar.bayar');

        $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 26)
          ->sum('bayar.bayar');

        $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 27)
          ->sum('bayar.bayar');

        $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 28)
          ->sum('bayar.bayar');

        $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 29)
          ->sum('bayar.bayar');

        $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 30)
          ->sum('bayar.bayar');

        $sisaspp11 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 31)
          ->sum('bayar.bayar');

        $sisaspp12 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 32)
          ->sum('bayar.bayar');

        $sisaspp13 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 33)
          ->sum('bayar.bayar');

        $sisaspp14 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
          ->where('kuitansi.idstudent', $ids)
          ->where('bayar.iditem', 34)
          ->sum('bayar.bayar');
      }

      if ($cek_study->study_year == 3) {
        $tots1 = $sisadaftar + $sisaawal;
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
        $tots1 = $sisadaftar + $sisaawal;
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
        $cekbyr = ($daftar + $awal) - $tots1;
      } elseif ($c = 1.4) {
        $cekbyr = ($daftar + $awal + ($dsp * 91 / 100) + ($spp1 * 82 / 100)) - $tots2;
      } elseif ($c == 2) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1) - $tots2;
      } elseif ($c == 3) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2) - $tots3;
      } elseif ($c = 3.4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82 / 100)) - $tots4;
      } elseif ($c == 4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3) - ($tots4);
      } elseif ($c == 5) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4) - $tots5;
      } elseif ($c = 5.4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82 / 100)) - $tots6;
      } elseif ($c == 6) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5) - $tots6;
      } elseif ($c == 7) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6) - $tots7;
      } elseif ($c = 7.4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82 / 100)) - $tots8;
      } elseif ($c == 8) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7) - $tots8;
      } elseif ($c == 9) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8) - $tots9;
      } elseif ($c = 9.4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82 / 100)) - $tots10;
      } elseif ($c == 10) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9) - $tots10;
      } elseif ($c == 11) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10) - $tots11;
      } elseif ($c == 12) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11) - $tots12;
      } elseif ($c == 13) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12) - $tots13;
      } elseif ($c == 14) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13) - $tots14;
      }

      //cek status semester mahasiswa
      if ($cekbyr < 1) {

        //data KRS yang diambil
        $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
          ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
          ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
          ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
          ->where('student_record.id_student', $ids)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('student_record.status', 'TAKEN')
          ->select('student_record.remark', 'student_record.id_studentrecord', 'student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
          ->get();

        //cek sks dari KRS
        $recordas = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->where('student_record.id_student', $ids)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
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

        $mhs = $ids;
        $value = Prodi::where('kodeprodi', $maha->kodeprodi)->first();

        $krlm = Kurikulum_master::where('status', 'ACTIVE')->first();

        $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
          ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
          ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
          ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_kelas', $maha->idstatus)
          ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_prodi', $value->id_prodi)
          ->where('kurikulum_transaction.id_semester', $c)
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
          ->where('kurikulum_periode.id_kelas', $maha->idstatus)
          ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
          ->where('kurikulum_periode.id_prodi', $value->id_prodi)
          ->where('kurikulum_transaction.id_semester', $c)
          ->where('kurikulum_transaction.id_angkatan', $idangkatan)
          ->where('kurikulum_periode.status', 'ACTIVE')
          ->where('kurikulum_transaction.status', 'ACTIVE')
          ->whereNotIn('kurikulum_periode.id_makul', [209, 210])
          ->union($add_krs)
          ->get();

        return view('mhs/krs/isi_krs', ['mhss' => $mhs, 'add' => $final_krs, 'mhs' => $maha, 'thn' => $periodetahun, 'tp' => $periodetipe, 'krs' => $record, 'sks' => $sks]);
      } else {
        alert()->warning('Anda tidak dapat melakukan KRS karena keuangan Anda belum memenuhi syarat', 'Hubungi BAAK untuk KRS manual')->autoclose(5000);
        return redirect('home');
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
    return redirect('isi_krs');
  }

  public function add_krs()
  {
    $time = Waktu_krs::where('status', 1)->first();

    if ($time == null) {
      alert()->error('KRS Belum dibuka', 'Maaf silahkan menghubungi bagian akademik');
      return redirect('home');
    } elseif ($time->status == 1) {
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
          'student.kodeprodi'
        )
        ->first();

      $idangkatan = $data_mhs->idangkatan;
      $idstatus = $data_mhs->idstatus;
      $kodeprodi = $data_mhs->kodeprodi;

      $thn = Periode_tahun::where('status', 'ACTIVE')->first();
      $tp = Periode_tipe::where('status', 'ACTIVE')->first();

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
          $c = ($b * 2) - 1;
        } elseif ($tipe == 3) {
          //pendek
          $a = (($smt + 10) - 3) / 10;
          $b = $a - $idangkatan;
          $c = ($b * 2) . '0' . '1';
        }
      } else {
        //genap
        $a = (($smt + 10) - 2) / 10;
        $b = $a - $idangkatan;
        $c = $b * 2;
      }
      // dd($c);
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
          'spp14'
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

      //total pembayaran kuliah
      $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $id)
        ->sum('bayar.bayar');

      if ($c == 1) {
        $cekbyr = ($daftar + $awal) - $total_semua_dibayar;
      } elseif ($c == 2) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1) - $total_semua_dibayar;
      } elseif ($c == '201') {
        $cekbyr = ($daftar + $awal + ($dsp * 91 / 100) + $spp1 + ($spp2 * 82 / 100)) - $total_semua_dibayar;
      } elseif ($c == 3) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2) - $total_semua_dibayar;
      } elseif ($c == 4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3) - $total_semua_dibayar;
      } elseif ($c == '401') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82 / 100)) - $total_semua_dibayar;
      } elseif ($c == 5) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4) - $total_semua_dibayar;
      } elseif ($c == 6) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5) - $total_semua_dibayar;
      } elseif ($c == '601') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82 / 100)) - $total_semua_dibayar;
      } elseif ($c == 7) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6) - $total_semua_dibayar;
      } elseif ($c == 8) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7) - $total_semua_dibayar;
      } elseif ($c == '801') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82 / 100)) - $total_semua_dibayar;
      } elseif ($c == 9) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8) - $total_semua_dibayar;
      } elseif ($c == 10) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9) - $total_semua_dibayar;
      } elseif ($c == '1001') {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82 / 100)) - $total_semua_dibayar;
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
          ->select('student_record.remark', 'student_record.id_studentrecord', 'student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
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

        return view('mhs/krs/add_krs', compact('periodetahun', 'periodetipe', 'data_mhs', 'sks', 'record'));
      } else {
        alert()->warning('Anda tidak dapat melakukan KRS karena keuangan Anda belum memenuhi syarat', 'Hubungi BAAK untuk KRS manual')->autoclose(5000);
        return redirect('home');
      }
    }
  }

  public function input_krs()
  {
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
        'student.kodeprodi'
      )
      ->first();

    $idangkatan = $data_mhs->idangkatan;
    $idstatus = $data_mhs->idstatus;
    $kodeprodi = $data_mhs->kodeprodi;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tp = Periode_tipe::where('status', 'ACTIVE')->first();

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
        $c = ($b * 2) - 1; // 2 * 2 - 1 = 3
      } elseif ($tipe == 3) {
        //pendek
        $a = (($smt + 10) - 3) / 10; // ( 213 + 10 - 3 ) / 10  = 22
        $b = $a - $idangkatan; // 22 - 20 = 2
        $c = ($b * 2);
      }
    } else {
      //genap
      $a = (($smt + 10) - 2) / 10; // (212 + 10 - 2) / 10 = 22
      $b = $a - $idangkatan; // 22 - 20 = 2
      $c = $b * 2; // 2 * 2 = 4
    }

    $value = Prodi::where('kodeprodi', $data_mhs->kodeprodi)->first();

    $krlm = Kurikulum_master::where('status', 'ACTIVE')->first();

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
        ->whereNotIn('kurikulum_periode.id_makul', [209, 210])
        ->union($add_krs)
        ->get();
      return view('mhs/krs/form_krs', compact('final_krs'));
    } else {
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
        ->where('kurikulum_transaction.id_semester', $c)
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
        ->where('kurikulum_transaction.id_semester', $c)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_periode.status', 'ACTIVE')
        ->where('kurikulum_transaction.status', 'ACTIVE')
        ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
        ->whereNotIn('kurikulum_periode.id_makul', [209, 210])
        ->union($add_krs)
        ->get();
      return view('mhs/krs/form_krs', compact('final_krs'));
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
    return redirect('isi_krs');
  }
}
