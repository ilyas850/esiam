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
      $cek_waktu = Waktu_krs::all();
      foreach ($cek_waktu as $time) {

      }

      if ($time->status == 1) {

          $id = Auth::user()->username;

          $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

          $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();

          $maha = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                        ->where('nim', Auth::user()->username)
                        ->select('student.idstudent','student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
                        ->get();

          foreach ($maha as $key) {
            # code...
          }

          $ky = $key->idstudent;
          $idangkatan = $key->idangkatan;
          $idstatus = $key->idstatus;
          $kodeprodi = $key->kodeprodi;

          $thn = Periode_tahun::where('status', 'ACTIVE')->get();

          foreach ($thn as $tahun) {
            // code...
          }

          $tp = Periode_tipe::where('status', 'ACTIVE')->get();

          foreach ($tp as $tipe) {
            // code...
          }

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

          $biaya = Biaya::where('idangkatan', $idangkatan)
                        ->where('idstatus', $idstatus)
                        ->where('kodeprodi', $kodeprodi)
                        ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6','spp7','spp8','spp9','spp10', 'seminar', 'sidang', 'wisuda')
                        ->get();

          foreach ($biaya as $value) {
            // code...
          }

          $totalbiaya = $value->daftar + $value->awal + $value->dsp + $value->spp1 + $value->spp2 + $value->spp3 + $value->spp4 + $value->spp5 + $value->spp6 + $value->spp7 + $value->spp8 + $value->spp9 + $value->spp10 + $value->seminar + $value->sidang + $value->wisuda;

          $cekbeasiswa = Beasiswa::where('idstudent', $ky)->get();

          if (count($cekbeasiswa) > 0) {

            foreach ($cekbeasiswa as $cb) {
              // code...
            }

            $daftar=$value->daftar-(($value->daftar*($cb->daftar))/100);
            $awal=$value->awal-(($value->awal*($cb->awal))/100);
            $dsp=$value->dsp-(($value->dsp*($cb->dsp))/100);
            $spp1=$value->spp1-(($value->spp1*($cb->spp1))/100);
            $spp2=$value->spp2-(($value->spp2*($cb->spp2))/100);
            $spp3=$value->spp3-(($value->spp3*($cb->spp3))/100);
            $spp4=$value->spp4-(($value->spp4*($cb->spp4))/100);
            $spp5=$value->spp5-(($value->spp5*($cb->spp5))/100);
            $spp6=$value->spp6-(($value->spp6*($cb->spp6))/100);
            $spp7=$value->spp7-(($value->spp7*($cb->spp7))/100);
            $spp8=$value->spp8-(($value->spp8*($cb->spp8))/100);
            $spp9=$value->spp9-(($value->spp9*($cb->spp9))/100);
            $spp10=$value->spp10-(($value->spp10*($cb->spp10))/100);
            $seminar=$value->seminar-(($value->seminar*($cb->seminar))/100);
            $sidang=$value->sidang-(($value->sidang*($cb->sidang))/100);
            $wisuda=$value->wisuda-(($value->wisuda*($cb->wisuda))/100);

            $totalall = $daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+$spp9+$spp10+$seminar+$sidang+$wisuda;
          }else {
            $daftar=$value->daftar;
            $awal=$value->awal;
            $dsp=$value->dsp;
            $spp1=$value->spp1;
            $spp2=$value->spp2;
            $spp3=$value->spp3;
            $spp4=$value->spp4;
            $spp5=$value->spp5;
            $spp6=$value->spp6;
            $spp7=$value->spp7;
            $spp8=$value->spp8;
            $spp9=$value->spp9;
            $spp10=$value->spp10;
            $seminar=$value->seminar;
            $sidang=$value->sidang;
            $wisuda=$value->wisuda;

            $totalall = $daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+$spp9+$spp10+$seminar+$sidang+$wisuda;
          }

          $totalbayarmhs = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                  ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
                                  ->where('kuitansi.idstudent', $ky)
                                  ->sum('bayar.bayar');

          $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 1)
                                ->sum('bayar.bayar');

          $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 2)
                                ->sum('bayar.bayar');

          $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 3)
                                ->sum('bayar.bayar');

          $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 4)
                                ->sum('bayar.bayar');

          $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 5)
                                ->sum('bayar.bayar');

          $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 6)
                                ->sum('bayar.bayar');

          $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 7)
                                ->sum('bayar.bayar');

          $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 8)
                                ->sum('bayar.bayar');

          $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 9)
                                ->sum('bayar.bayar');

          $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 10)
                                ->sum('bayar.bayar');

          $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 11)
                                ->sum('bayar.bayar');

          $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 12)
                                ->sum('bayar.bayar');

          $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 13)
                                ->sum('bayar.bayar');

          $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 14)
                                ->sum('bayar.bayar');

          $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 15)
                                ->sum('bayar.bayar');

          $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                                ->where('kuitansi.idstudent', $ky)
                                ->where('bayar.iditem', 16)
                                ->sum('bayar.bayar');

          $tots1 = $sisadaftar + $sisaawal ;
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

            if ($c==1) {
              $cekbyr=($daftar+$awal)-$tots1;
            }elseif ($c==2) {
              $cekbyr=($daftar+$awal+$dsp+$spp1)-$tots2;
            }elseif ($c==3) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2)-$tots3;
            }elseif ($c==4) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3)-($tots4);
            }elseif ($c==5) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4)-$tots5;
            }elseif ($c==6) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5)-$tots6;
            }elseif ($c==7) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6)-$tots7;
            }elseif ($c==8) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7)-$tots8;
            }elseif ($c==9) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8)-$tots9;
            }elseif ($c==10) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+$spp9)-$tots10;
            }


          if ($cekbyr < 1) {

            $record = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                    ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
                                    ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
                                    ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
                                    ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                                    ->where('student_record.id_student', $key->idstudent)
                                    ->where('kurikulum_periode.id_periodetipe', $tp)
                                    ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                    ->where('student_record.status', 'TAKEN')
                                    ->select('student_record.remark', 'student_record.id_studentrecord', 'student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
                                    ->get();

            $record1 = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                    ->where('student_record.id_student', $key->idstudent)
                                    ->where('kurikulum_periode.id_periodetipe', $tp)
                                    ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                    ->where('student_record.status', 'TAKEN')
                                    ->select('student_record.id_kurtrans', DB::raw('COUNT(student_record.id_kurtrans) as products_count'), 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek','kurikulum_transaction.id_makul')
                                    ->groupBy('student_record.id_kurtrans','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek','kurikulum_transaction.id_makul')
                                    ->having('products_count', '>' , 1)
                                    ->get();

            $f = count($record1);

            foreach ($record1 as $key1) {
                    // code...
            }

            if(($f > 0)){
                $kurangi = $key1->akt_sks_teori + $key1->akt_sks_praktek;
            }else{
                $kurangi = 0;
            }

            $jml_mkl = count($record1);

            $skst = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                    ->where('student_record.id_student', $key->idstudent)
                                    ->where('kurikulum_periode.id_periodetipe', $tp)
                                    ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                    ->where('student_record.status', 'TAKEN')
                                    ->sum('matakuliah.akt_sks_teori');

            $sksp = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                    ->where('student_record.id_student', $key->idstudent)
                                    ->where('kurikulum_periode.id_periodetipe', $tp)
                                    ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                    ->where('student_record.status', 'TAKEN')
                                    ->sum('matakuliah.akt_sks_praktek');

              if ($jml_mkl == 0) {
                $sks = $skst + $sksp;
              }elseif ($jml_mkl == 1) {
                $sks = $skst + $sksp - $kurangi;
              }

        $mhs = $key->idstudent;
        $prod = Prodi::where('kodeprodi', $key->kodeprodi)->get();
        foreach ($prod as $value) {
          // code...
        }
        $kur = Kurikulum_master::where('status', 'ACTIVE')->get();
        foreach ($kur as $krlm) {
          // code...
        }

        $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=','matakuliah_bom.master_idmakul' )
                                        ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
                                        ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                                        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                        ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                                        ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                                        ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                        ->where('kurikulum_periode.id_periodetipe', $tp)
                                        ->where('kurikulum_periode.id_kelas', $key->idstatus)
                                        ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                                        ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                                        ->where('kurikulum_transaction.id_semester', $c)
                                        ->where('kurikulum_transaction.id_angkatan', $key->idangkatan)
                                        ->where('kurikulum_periode.status', 'ACTIVE')
                                        ->where('matakuliah_bom.status', 'ACTIVE');

        $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                                          ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                                          ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                                          ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                                          ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                                          ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                                          ->where('kurikulum_periode.id_periodetipe', $tp)
                                          ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                          ->where('kurikulum_periode.id_kelas', $key->idstatus)
                                          ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                                          ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                                          ->where('kurikulum_transaction.id_semester', $c)
                                          ->where('kurikulum_transaction.id_angkatan', $key->idangkatan)
                                          ->where('kurikulum_periode.status', 'ACTIVE')
                                          ->where('kurikulum_transaction.status', 'ACTIVE')
                                          ->whereNotIn('kurikulum_periode.id_makul', [209,210])// ->select('kurikulum_periode.id_makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
                                          ->union($add_krs)
                                          ->get();


            return view('mhs/krs/isi_krs',['b'=>$b, 'mhss'=>$mhs, 'add'=>$final_krs, 'mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$record, 'sks' => $sks]);
          }else {
            alert()->warning('Anda tidak dapat melakukan KRS karena keuangan Anda belum memenuhi syarat', 'Hubungi BAAK untuk KRS manual')->autoclose(5000);
            return redirect('home');
          }

      }else {
        alert()->error('KRS Belum dibuka','Maaf silahkan menghubungi bagian akademik');
        return redirect('home');

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
