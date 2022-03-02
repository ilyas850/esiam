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
      $id = Auth::user()->username;

      $maha = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
        ->where('student.nim', $id)
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
          'seminar',
          'sidang',
          'wisuda'
        )
        ->first();

      $totalbiaya = $biaya->daftar + $biaya->awal + $biaya->dsp + $biaya->spp1 + $biaya->spp2 + $biaya->spp3 + $biaya->spp4 + $biaya->spp5 + $biaya->spp6 + $biaya->spp7 + $biaya->spp8 + $biaya->spp9 + $biaya->spp10 + $biaya->seminar + $biaya->sidang + $biaya->wisuda;

      $cekbeasiswa = Beasiswa::where('idstudent', $ids)->get();

      if (count($cekbeasiswa) > 0) {

        foreach ($cekbeasiswa as $cb) {
          // code...
        }

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
        $seminar = $biaya->seminar - (($biaya->seminar * ($cb->seminar)) / 100);
        $sidang = $biaya->sidang - (($biaya->sidang * ($cb->sidang)) / 100);
        $wisuda = $biaya->wisuda - (($biaya->wisuda * ($cb->wisuda)) / 100);

        $totalall = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $seminar + $sidang + $wisuda;
      } else {
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
        $seminar = $biaya->seminar;
        $sidang = $biaya->sidang;
        $wisuda = $biaya->wisuda;

        $totalall = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $seminar + $sidang + $wisuda;
      }

      $totalbayarmhs = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
        ->where('kuitansi.idstudent', $ids)
        ->sum('bayar.bayar');

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

      $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $ids)
        ->where('bayar.iditem', 14)
        ->sum('bayar.bayar');

      $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $ids)
        ->where('bayar.iditem', 15)
        ->sum('bayar.bayar');

      $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        ->where('kuitansi.idstudent', $ids)
        ->where('bayar.iditem', 16)
        ->sum('bayar.bayar');

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
      $totalsisa = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaseminar + $sisasidang + $sisawisuda;

      if ($c == 1) {
        $cekbyr = ($daftar + $awal) - $tots1;
      } elseif ($c == 2) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1) - $tots2;
      } elseif ($c == 3) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2) - $tots3;
      } elseif ($c == 4) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3) - ($tots4);
      } elseif ($c == 5) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4) - $tots5;
      } elseif ($c == 6) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5) - $tots6;
      } elseif ($c == 7) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6) - $tots7;
      } elseif ($c == 8) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7) - $tots8;
      } elseif ($c == 9) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8) - $tots9;
      } elseif ($c == 10) {
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9) - $tots10;
      }

      //cek status semester mahasiswa
      if ($cekbyr < 1) {

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

        $skst = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->where('student_record.id_student', $ids)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('student_record.status', 'TAKEN')
          ->sum('matakuliah.akt_sks_teori');

        $sksp = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
          ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
          ->where('student_record.id_student', $ids)
          ->where('kurikulum_periode.id_periodetipe', $tipe)
          ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
          ->where('student_record.status', 'TAKEN')
          ->sum('matakuliah.akt_sks_praktek');

        $sks = $skst + $sksp;


        $mhs = $ids;
        $prod = Prodi::where('kodeprodi', $maha->kodeprodi)->get();
        foreach ($prod as $value) {
          // code...
        }
        $kur = Kurikulum_master::where('status', 'ACTIVE')->get();
        foreach ($kur as $krlm) {
          // code...
        }

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
          ->whereNotIn('kurikulum_periode.id_makul', [209, 210]) // ->select('kurikulum_periode.id_makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
          ->union($add_krs)
          ->get();


        return view('mhs/krs/isi_krs', ['b' => $b, 'mhss' => $mhs, 'add' => $final_krs, 'mhs' => $maha, 'thn' => $periodetahun, 'tp' => $periodetipe, 'krs' => $record, 'sks' => $sks]);
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
}
