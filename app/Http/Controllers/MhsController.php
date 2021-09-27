<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Alert;
use App\Bap;
use App\Absensi_mahasiswa;
use App\Pedoman_akademik;
use App\Kuliah_tipe;
use App\User;
use App\Dosen;
use App\Kelas;
use App\Prodi;
use App\Student;
use App\Informasi;
use App\Edom_transaction;
use App\Edom_master;
use App\Ruangan;
use App\Semester;
use App\Waktu_krs;
use App\Matakuliah;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Update_mahasiswa;
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
use App\Ujian_menit;
use App\Ujian_tipe;
use App\Ujian_transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MhsController extends Controller
{
  public function change($id)
  {
    return view ('mhs/change_pwd', ['mhs' => $id]);
  }

  public function store_new_pwd(Request $request, $id)
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
          }
          else
          {
            Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
            return redirect('home');
          }
    }

    public function store_new_user(Request $request, $id)
    {
      $this->validate($request, [
          'role' => 'required',
          'oldpassword' => 'required',
          'password' => 'required|min:7|confirmed',
          ]);

          $sandi = bcrypt($request->password);

          $user = User::find($id);

          $pass = password_verify($request->oldpassword, $user->password);

          if ($pass) {
            $user->password = $sandi;
            $user->role = $request->role;
            $user->save();

            Alert::success('', 'Password anda berhasil dirubah')->autoclose(3500);
            return redirect('home');
          }
          else
          {
            Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
            return redirect('home');
          }
    }

    public function update($id)
    {

      $cek = Student::where('idstudent', $id)->get();
      //$maha = Student::find($id);
      foreach ($cek as $user) {

      }

      return view ('mhs/update', ['mhs' => $user]);
    }

    public function store_update(Request $request)
    {
      $this->validate($request, [
        'id_mhs' => 'required',
        'nim_mhs' => 'required',
        'hp_baru' => 'required',
        'email_baru' => 'required',
      ]);

      $users = new Update_mahasiswa;
      $users->id_mhs = $request->id_mhs;
      $users->nim_mhs = $request->nim_mhs;
      $users->hp_baru = $request->hp_baru;
      $users->email_baru = $request->email_baru;
      $users->save();

      return redirect ('home');
    }

    public function change_update($id)
    {
      $user = Update_Mahasiswa::find($id);

      return view ('mhs/change', ['mhs' => $user]);
    }

    public function store_change(Request $request, $id)
    {
      $this->validate($request, [
        'id_mhs' => 'required',
        'hp_baru' => 'required',
        'email_baru' => 'required',
      ]);

      $user = Update_Mahasiswa::find($id);
      $user->id_mhs       = $request->id_mhs;
      $user->hp_baru      = $request->hp_baru;
      $user->email_baru   = $request->email_baru;
      $user->save();

      return redirect ('home');
    }

    public function krs()
    {dd('ini');
      $cek_waktu = Waktu_krs::all();
      foreach ($cek_waktu as $time) {

      }

      if ($time->status == 1) {
          $id = Auth::user()->username;

          $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

          $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();

          $maha = Student::where('nim', Auth::user()->username)->get();

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
                        ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'seminar', 'sidang', 'wisuda')
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

          $kuitansi = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                              ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
                              ->where('kuitansi.idstudent', $ky)
                              ->select('bayar.bayar', 'kuitansi.tanggal', 'kuitansi.nokuit', 'bayar.iditem')
                              ->orderBy('kuitansi.tanggal', 'ASC')
                              ->get();

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

          $tots1 = $sisadaftar + $sisaawal + $sisadsp;
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
              $cekbyr=($daftar+$awal+$dsp)-$tots1;
            }elseif ($c==4) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3)-($tots4);
            }elseif ($c==2) {
              $cekbyr=($daftar+$awal+$dsp+$spp1)-$tots2;
            }elseif ($c==3) {
              $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2)-$tots3;
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
          $semester = Semester::all();
          $makul = Matakuliah::all();
          $hari = Kurikulum_hari::all();
          $jam = Kurikulum_jam::all();
          $ruang = Ruangan::all();
          $dosen = Dosen::all();

          if ($cekbyr == 0) {
            $record = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->where('student_record.id_student', $key->idstudent)
                                    ->where('kurikulum_periode.id_semester', $c)
                                    ->select('student_record.tanggal_krs', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
                                    ->get();

            $skst = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->where('student_record.id_student', $key->idstudent)
                                    ->where('kurikulum_periode.id_semester', $c)
                                    ->sum('kurikulum_periode.akt_sks_teori');

            $sksp = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->where('student_record.id_student', $key->idstudent)
                                    ->where('kurikulum_periode.id_semester', $c)
                                    ->sum('kurikulum_periode.akt_sks_praktek');

            $sks = $skst + $sksp;

            return view('mhs/krs',['mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$record, 'smt'=>$semester, 'mk'=>$makul, 'hr'=>$hari, 'jm'=>$jam,  'rng'=>$ruang, 'dsn'=>$dosen, 'sks' => $sks]);
          }else {
            alert()->warning('Anda tidak dapat melakukan KRS karena keuangan Anda belum memenuhi syarat', 'Hubungi BAAK untuk KRS manual')->autoclose(5000);
            return redirect('home');
          }

      }else {
        alert()->error('KRS Belum dibuka','Maaf silahkan menghubungi bagian akademik');
        return redirect('home');

      }
    }

    public function add_krs(Request $request)
    {
      $this->validate($request, [
        'id_periodetipe' => 'required',
        'id_periodetahun' => 'required',
        'id_kelas' => 'required',
        'id_prodi' => 'required',
        'idangkatan' => 'required'
      ]);

      $kur = Kurikulum_master::where('status', 'ACTIVE')->get();

      foreach ($kur as $krlm) {
        // code...
      }

      $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

      foreach ($prd_thn as $thn) {
        // code...
      }
      $sub_thn = substr($thn->periode_tahun,6,2);
      $tp = $request->id_periodetipe;
      $smt = $sub_thn.$tp;
      $angk = $request->idangkatan;

      if ($smt %2 != 0){
    	$a = (($smt + 10)-1)/10;
    	$b = $a - $angk;
    	$c = ($b*2)-1;
    	}else{
    		$a = (($smt + 10)-2)/10;
    		$b = $a - $angk;
    		$c = $b * 2;
    	}
      $semester = Semester::all();
      $makul = Matakuliah::all();
      $hari = Kurikulum_hari::all();
      $jam = Kurikulum_jam::all();
      $ruang = Ruangan::all();
      $dosen = Dosen::all();
      $mhs = $request->id_student;

      $krs = Kurikulum_transaction::join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                              ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                              ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
                              ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                              ->where('kurikulum_periode.id_kelas', $request->id_kelas)
                              ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
                              ->where('kurikulum_transaction.id_semester', $c)
                              ->where('kurikulum_transaction.id_angkatan', $request->idangkatan)
                              ->where('kurikulum_periode.status', 'ACTIVE')
                              ->select('kurikulum_periode.id_makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
                              ->get();

      // $recordkrs = student_record::join('students', 'student_record.id_student', '=', 'students.idstudent')
      //                         ->join('kurikulum_periode', 'student_record.id_kurperiod', '=', 'kurikulum_periode.id_kurperiode')
      //                         ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      //                         ->where('student_record.id_student', $mhs)
      //                         ->where('kurikulum_periode.id_semester', $c)
      //                         ->select('kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'student_record.tanggal_krs', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
      //                         ->get();

      // $users = DB::table('users')
      //             ->wherenotExists(function ($query) {
      //                 $query->select(DB::raw(1))
      //                       ->from('students')
      //                       ->whereRaw('students.idstudent = users.id_mahasiswa');
      //      })
      //      ->get();

      // $sk = DB::table('kurikulum_transaction')
      //         ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
      //         ->join('student_record', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      //         ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
      //         ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
      //         ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
      //         ->where('kurikulum_periode.id_kelas', $request->id_kelas)
      //         ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
      //         ->where('kurikulum_transaction.id_semester', $c)
      //         ->where('kurikulum_transaction.id_angkatan', $request->idangkatan)
      //         ->where('kurikulum_periode.status', 'ACTIVE')
      //         ->where('student_record.id_student', $mhs)
      //         ->wherenotExists(function ($query) {
      //           $maha = Student::where('nim', Auth::user()->username)->get();
      //
      //           foreach ($maha as $key) {
      //             # code...
      //           }
      //
      //           $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();
      //           foreach ($prd_thn as $thn) {
      //             // code...
      //           }
      //
      //           $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();
      //           foreach ($prd_tp as $tpe) {
      //             // code...
      //           }
      //           $sub_thn = substr($thn->periode_tahun,6,2);
      //           $tp = $tpe->id_periodetipe;
      //           $smt = $sub_thn.$tp;
      //           $angk = $key->idangkatan;
      //
      //           if ($smt %2 != 0){
      //         	$a = (($smt + 10)-1)/10;
      //         	$b = $a - $angk;
      //         	$c = ($b*2)-1;
      //         	}else{
      //         		$a = (($smt + 10)-2)/10;
      //         		$b = $a - $angk;
      //         		$c = $b * 2;
      //         	}
      //           $query->select(DB::raw(1))
      //                 ->from('student_record')
      //                 ->whereRaw('student_record.id_kurtrans = kurikulum_transaction.idkurtrans');
      //                 // ->where('student_record.id_student', $key->idstudent);
      //
      //         })
      //         ->get();
              // dd($sk);
      return view('mhs/add_krs', ['mhs'=>$mhs, 'add' => $krs, 'smt' => $semester, 'mk' => $makul, 'hr' => $hari, 'jm' => $jam, 'rg' => $ruang, 'dsn' => $dosen]);
    }

    public function save_krs(Request $request)
    {
      $this->validate($request, [
        'id_student' => 'required',
        'id_kurperiode' => 'required',
        'id_kurtrans',
      ]);

      $jml = count($request->id_kurperiode);
      for ($i=0; $i < $jml; $i++) {
        $kurp = $request->id_kurperiode[$i];
        $idr = explode(',',$kurp, 2 );
        $tra = $idr[0];
        $trs = $idr[1];
        $cekkrs = Student_record::where('id_student', $request->id_student)
                                ->where('id_kurperiode', $tra)
                                ->where('id_kurtrans', $trs)
                                ->get();
      }

      if (count($cekkrs) > 0) {

        Alert::warning('maaf mata kuliah sudah dipilih', 'MAAF !!');
        return view('mhs/input_krs');
      }else {
        if (count($request->id_kurperiode) > 0) {
          $jml_kur = count($request->id_kurperiode);
          for ($i=0; $i < $jml_kur; $i++) {
            $kur = $request->id_kurperiode[$i];
            // $tra = substr($request->id_kurperiod[$i],5,3);
            $kurs = explode(',',$kur, 2 );

            $tra = $kurs[0];
            $trs = $kurs[1];
            // $trs = substr($request->id_kurperiod[$i],0,3);

            $krs = new Student_record;
            $krs->tanggal_krs   = date("Y-m-d");
            $krs->id_student    = $request->id_student;
            $krs->id_kurperiode = $tra;
            $krs->id_kurtrans   = $trs;
            $krs->save();
          }
        }
      return redirect('krs');
      }
    }

    public function input_krs(Request $request)
    {
      $this->validate($request, [
        'id_periodetipe' => 'required',
        'id_periodetahun' => 'required',
        'id_kelas' => 'required',
        'id_prodi' => 'required',
        'idangkatan' => 'required',
        'id_student' => 'required'
      ]);
      $mhs = $request->id_student;
      $kur = Kurikulum_master::where('status', 'ACTIVE')->get();

      foreach ($kur as $krlm) {
        // code...
      }

      $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

      foreach ($prd_thn as $thn) {
        // code...
      }
      $sub_thn = substr($thn->periode_tahun,6,2);
      $tp = $request->id_periodetipe;
      $smt = $sub_thn.$tp;
      $angk = $request->idangkatan;

      if ($smt %2 != 0){
    	$a = (($smt + 10)-1)/10;
    	$b = $a - $angk;
    	$c = ($b*2)-1;
    	}else{
    		$a = (($smt + 10)-2)/10;
    		$b = $a - $angk;
    		$c = $b * 2;
    	}
      $semester = Semester::all();
      $makul = Matakuliah::all();
      $hari = Kurikulum_hari::all();
      $jam = Kurikulum_jam::all();
      $ruang = Ruangan::all();
      $dosen = Dosen::all();

      $krs = Kurikulum_transaction::join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                              ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                              ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
                              ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                              ->where('kurikulum_periode.id_kelas', $request->id_kelas)
                              ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
                              ->where('kurikulum_transaction.id_semester', $c)
                              ->where('kurikulum_transaction.id_angkatan', $request->idangkatan)
                              ->where('kurikulum_periode.status', 'ACTIVE')
                              ->select('kurikulum_periode.id_makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
                              ->get();
      return view ('mhs/input_krs', ['mhs'=>$mhs, 'add' => $krs, 'smt' => $semester, 'mk' => $makul, 'hr' => $hari, 'jm' => $jam, 'rg' => $ruang, 'dsn' => $dosen]);
    }

    public function post_krs(Request $request)
    {
      $this->validate($request, [
        'id_student' => 'required',
        'id_kurperiode' => 'required',
        'id_kurtrans' => 'required'
      ]);
      $cekkrs = Student_record::where('id_student', $request->id_student)
                              ->where('id_kurperiode', $request->id_kurperiode)
                              ->where('id_kurtrans', $request->id_kurtrans)
                              ->get();

      if (count($cekkrs) > 0) {

        Alert::warning('maaf mata kuliah sudah dipilih', 'MAAF !!');
        return redirect('krs');
      }else {
        // code...
      }
    }

    public function simpan_krs(Request $request)
    {
      $this->validate($request, [
        'id_student' => 'required',
        'id_kurperiode' => 'required',
      ]);

      $jml = count($request->id_kurperiode);
      for ($i=0; $i < $jml; $i++) {
        $kurp = $request->id_kurperiode[$i];
        $idr = explode(',',$kurp, 2 );
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
        return redirect('isi_krs');
      }elseif (count($cekkrs) == 0) {
        $krs = new Student_record;
        $krs->tanggal_krs   = date("Y-m-d");
        $krs->id_student    = $request->id_student;
        $krs->data_origin   = 'eSIAM';
        $krs->id_kurperiode = $tra;
        $krs->id_kurtrans   = $trs;
        $krs->save();

        Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
        return redirect('isi_krs');
      }

    }


    public function pdf_krs()
    {
      $id = Auth::user()->username;

      $maha = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                    ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                    ->where('nim', Auth::user()->username)
                    ->select('student.idstudent','student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
                    ->get();

      foreach ($maha as $key) {
        # code...
      }

      $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();
      $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();

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
                              ->select('student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
                              ->orderBy('kurikulum_periode.id_hari', 'ASC')
                              ->orderBy('kurikulum_periode.id_jam', 'ASC')
                              ->get();

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

      $sks = $skst + $sksp;

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
      $m =$bulan[date('m')];
      $y = date('Y');

       // return view('mhs/krs_pdf',['d' => $d,'m' => $m,'y' => $y, 'mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$record, 'sks'=>$sks, 'smt'=>$semester, 'mk'=>$makul, 'hr'=>$hari, 'jm'=>$jam,  'rng'=>$ruang, 'dsn'=>$dosen ]);
      $pdf= PDF::loadView('mhs/krs_pdf',['d' => $d,'m' => $m,'y' => $y, 'mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$record, 'sks'=>$sks])->setPaper('a4', 'portrait');
      return $pdf->download('KRS '.Auth::user()->name.'_'.date("d-m-Y").'.pdf');
    }

    public function unduh_khs_mid()
    {
      $id = Auth::user()->username;

      $maha = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                    ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                    ->where('nim', Auth::user()->username)
                    ->select('student.idstudent','student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
                    ->get();

      foreach ($maha as $key) {
        # code...
      }

      $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();
      $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();
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

      $record = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->where('student_record.id_student', $key->idstudent)
                              // ->where('kurikulum_periode.id_semester', $c)
                              ->where('kurikulum_periode.id_periodetipe', $tp)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('student_record.status', 'TAKEN')
                              ->select('student_record.nilai_UTS', 'kurikulum_periode.id_makul', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek')
                              ->orderBy('kurikulum_periode.id_hari', 'ASC')
                              ->orderBy('kurikulum_periode.id_jam', 'ASC')
                              ->get();

      $skst = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->where('student_record.id_student', $key->idstudent)
                              // ->where('kurikulum_periode.id_semester', $c)
                              ->where('kurikulum_periode.id_periodetipe', $tp)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('student_record.status', 'TAKEN')
                              ->sum('kurikulum_periode.akt_sks_teori');

      $sksp = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->where('student_record.id_student', $key->idstudent)
                              // ->where('kurikulum_periode.id_semester', $c)
                              ->where('kurikulum_periode.id_periodetipe', $tp)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('student_record.status', 'TAKEN')
                              ->sum('kurikulum_periode.akt_sks_praktek');

      $sks = $skst + $sksp;

      $semester = Semester::all();
      $makul = Matakuliah::all();
      $hari = Kurikulum_hari::all();
      $jam = Kurikulum_jam::all();
      $ruang = Ruangan::all();
      $dosen = Dosen::all();

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
      $m =$bulan[date('m')];
      $y = date('Y');

       // return view('mhs/khs/khs_mid_pdf',['d' => $d,'m' => $m,'y' => $y, 'mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$record, 'sks'=>$sks, 'smt'=>$semester, 'mk'=>$makul, 'hr'=>$hari, 'jm'=>$jam,  'rng'=>$ruang, 'dsn'=>$dosen ]);
      $pdf= PDF::loadView('mhs/khs/khs_mid_pdf',['d' => $d,'m' => $m,'y' => $y, 'mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$record, 'sks'=>$sks, 'smt'=>$semester, 'mk'=>$makul, 'hr'=>$hari, 'jm'=>$jam,  'rng'=>$ruang, 'dsn'=>$dosen ])->setPaper('a4', 'portrait');
      return $pdf->download('KHS_MID_TERM'.Auth::user()->name.'_'.date("d-m-Y").'.pdf');
    }
    public function jadwal()
    {
      $cek_waktu = Waktu_krs::all();
      foreach ($cek_waktu as $time) {

      }
        $id = Auth::user()->username;

        $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

        $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();

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

          $semester = Semester::all();
          $ruang = Ruangan::all();
          $dosen = Dosen::all();


          $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                  ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                  ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                  ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
                                  ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
                                  ->join('matakuliah', 'kurikulum_periode.id_makul','=', 'matakuliah.idmakul')
                                  ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
                                  ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                                  ->where('student_record.id_student', $key->idstudent)
                                  ->where('kurikulum_periode.id_periodetipe', $tp)
                                  ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                  ->where('student_record.status', 'TAKEN')
                                  ->select('kurikulum_periode.id_kurperiode','student_record.tanggal_krs', 'kurikulum_periode.id_semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'dosen.nama')
                                  ->orderBy('kurikulum_periode.id_hari', 'ASC')
                                  ->orderBy('kurikulum_periode.id_jam', 'ASC')
                                  ->get();

          return view('mhs/jadwal',['mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'jadwal'=>$record, 'smt'=>$semester,'rng'=>$ruang, 'dsn'=>$dosen]);

    }

    public function lihatabsen($id)
    {
      $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul','=', 'matakuliah.idmakul')
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
                              ->select('kurikulum_periode.akt_sks_praktek','kurikulum_periode.akt_sks_teori','kurikulum_periode.id_kelas','periode_tipe.periode_tipe','periode_tahun.periode_tahun','dosen.akademik','dosen.nama','ruangan.nama_ruangan','kurikulum_jam.jam','kurikulum_hari.hari',DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'),'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                              ->get();
      foreach ($bap as $key) {
        # code...
      }

      $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                  ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                  ->where('bap.id_kurperiode', $id)
                  ->where('bap.status', 'ACTIVE')
                  ->select('bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                  ->get();
      $d = count($data);
      if (($d > 0)) {
        foreach ($data as $keybap) {
        # code...
      }

      $idb = $keybap->id_bap;

      return view('mhs/lihatabsen', ['idb'=>$idb,'data'=>$key, 'bap'=>$data]);
      }elseif (($d == 0)) {
        Alert::warning('maaf mata kuliah belum ada absensi', 'MAAF !!');
        return redirect('jadwal');
      }

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
                              ->select('dosen.iddosen','semester.semester','kelas.kelas','prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
                              ->get();
      foreach ($bap as $data) {
        # code...
      }
      $prd = $data->prodi;
      $tipe = $data->periode_tipe;
      $tahun = $data->periode_tahun;


      return view('mhs/view_bap', ['prd'=>$prd, 'tipe'=>$tipe, 'tahun'=>$tahun, 'data'=>$data, 'dtbp'=>$dtbp]);
    }

    public function view_abs($id)
    {
      $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul','=', 'matakuliah.idmakul')
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
                              ->select('kurikulum_periode.akt_sks_praktek','kurikulum_periode.akt_sks_teori','kurikulum_periode.id_kelas','periode_tipe.periode_tipe','periode_tahun.periode_tahun','dosen.akademik','dosen.nama','ruangan.nama_ruangan','kurikulum_jam.jam','kurikulum_hari.hari',DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'),'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                              ->get();
      foreach ($bap as $key) {
        # code...
      }

      $ids = Auth::user()->id_user;
      $kur = Student_record::where('id_kurperiode', $id)
                            ->where('id_student', $ids)
                            ->where('status', 'TAKEN')
                            ->first();

      $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('absensi_mahasiswa.id_studentrecord', $kur->id_studentrecord)
                              ->select('student_record.id_studentrecord','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai','student.nama', 'student.nim', 'absensi_mahasiswa.absensi')
                              ->get();

      return view('mhs/rekap_absen', ['data'=>$key, 'abs'=>$abs]);
    }

    public function tambah_krs(Request $request)
    {
      $this->validate($request, [
        'id_periodetipe' => 'required',
        'id_periodetahun' => 'required',
        'id_kelas' => 'required',
        'id_prodi' => 'required',
        'idangkatan' => 'required'
      ]);

      $kur = Kurikulum_master::where('status', 'ACTIVE')->get();

      foreach ($kur as $krlm) {
        // code...
      }

      $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

      foreach ($prd_thn as $thn) {
        // code...
      }
      $sub_thn = substr($thn->periode_tahun,6,2);
      $tp = $request->id_periodetipe;
      $smt = $sub_thn.$tp;
      $angk = $request->idangkatan;

      if ($smt %2 != 0){
    	$a = (($smt + 10)-1)/10;
    	$b = $a - $angk;
    	$c = ($b*2)-1;
    	}else{
    		$a = (($smt + 10)-2)/10;
    		$b = $a - $angk;
    		$c = $b * 2;
    	}
      $semester = Semester::all();
      $makul = Matakuliah::all();
      $hari = Kurikulum_hari::all();
      $jam = Kurikulum_jam::all();
      $ruang = Ruangan::all();
      $dosen = Dosen::all();
      $mhs = $request->id_student;

      $krs = Kurikulum_transaction::join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                                  ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                                  ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
                                  ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                                  ->where('kurikulum_periode.id_kelas', $request->id_kelas)
                                  ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
                                  ->where('kurikulum_transaction.id_semester', $c)
                                  ->where('kurikulum_transaction.id_angkatan', $request->idangkatan)
                                  ->where('kurikulum_periode.status', 'ACTIVE')
                                  ->select('kurikulum_periode.id_makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
                                  ->get();

      $cek_krs = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->leftjoin('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->leftjoin('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->where('student_record.id_student', $mhs)
                              ->where('kurikulum_periode.id_semester', $c)
                              ->whereNull('kurikulum_periode.id_kurperiode')
                              // ->select('student_record.tanggal_krs', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
                              ->get();

      $cek_kul = DB::table('student_record as sr')
                    ->select('sr.*')
                    ->leftJoin('kurikulum_periode as kp', function ($join) {
                        $join->on('kp.id_kurperiode', '=', 'student_record.id_kurperiode');

                    })
                    ->whereNull('kp.id_kurperiode')
                    ->get();
                    dd($cek_kul);

      DB::table('item as i')
          ->select('i.*')
          ->leftJoin('qualifications as q', function ($join) {
              $join->on('q.item_id', '=', 'i.id')
                   ->on('q.user_id', '=', $user_id);
          })
          ->whereNull('q.item_id')
          ->get();

      return view('mhs/add_krs', ['mhs'=>$mhs, 'add' => $krs, 'smt' => $semester, 'mk' => $makul, 'hr' => $hari, 'jm' => $jam, 'rg' => $ruang, 'dsn' => $dosen]);
    }

    public function khs_mid()
    {
      $id = Auth::user()->username;
      $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();
      $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();
      $maha = Student::where('nim', $id)->get();
      foreach ($maha as $key) {
        // code...
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
                    ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6','spp7', 'spp8', 'spp9', 'spp10', 'seminar', 'sidang', 'wisuda')
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
      $kuitansi = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                          ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
                          ->where('kuitansi.idstudent', $ky)
                          ->select('bayar.bayar', 'kuitansi.tanggal', 'kuitansi.nokuit', 'bayar.iditem')
                          ->orderBy('kuitansi.tanggal', 'ASC')
                          ->get();

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

      $tots1 = $sisadaftar + $sisaawal + ($sisaspp1);
      $tots2 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + ($sisaspp2);
      $tots3 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + ($sisaspp3);
      $tots4 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + ($sisaspp4);
      $tots5 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + ($sisaspp5);
      $tots6 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + ($sisaspp6);
      $tots7 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + ($sisaspp7);
      $tots8 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + ($sisaspp8);
      $tots9 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + ($sisaspp9);
      $tots10 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + ($sisaspp10);
      $totalsisa = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaseminar + $sisasidang + $sisawisuda;
      $beasdsp = count( $cekbeasiswa);
      if ($beasdsp == 0) {
        if ($c==1) {
          $cekbyr=($daftar+$awal+($spp1/2)-150000)-$tots1;
        }elseif ($c==4) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+($spp4/2)-150000)-$tots4;
        }elseif ($c==2) {
          $cekbyr=($daftar+$awal+($dsp*75/100)+$spp1+($spp2/2)-150000)-$tots2;
        }elseif ($c==3) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+($spp3/2)-150000)-$tots3;
        }elseif ($c==5) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+($spp5/2)-150000)-$tots5;
        }elseif ($c==5.4 or 6) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+($spp6/2)-150000)-$tots6;
        }elseif ($c==7) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+($spp7/2)-150000)-$tots7;
        }elseif ($c==8) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+($spp8/2)-150000)-$tots8;
        }elseif ($c==9) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+($spp9/2)-150000)-$tots9;
        }elseif ($c==10) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+$spp9+($spp10/2)-150000)-$tots10;
        }
      }else {
        if ($c==1) {
          $cekbyr=($daftar+$awal+($spp1/2)-150000)-$tots1;
        }elseif ($c==4) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+($spp4/2)-150000)-$tots4;
        }elseif ($c==2) {
          $cekbyr=($daftar+$awal+$spp1+($spp2/2)-150000)-$tots2;
        }elseif ($c==3) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+($spp3/2)-150000)-$tots3;
        }elseif ($c==5) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+($spp5/2)-150000)-$tots5;
        }elseif ($c==5.4 or 6) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+($spp6/2)-150000)-$tots6;
        }elseif ($c==7) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+($spp7/2)-150000)-$tots7;
        }elseif ($c==8) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+($spp8/2)-150000)-$tots8;
        }elseif ($c==9) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+($spp9/2)-150000)-$tots9;
        }elseif ($c==10) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+$spp9+($spp10/2)-150000)-$tots10;
        }
      }

      if ($cekbyr == 0 or $cekbyr < 1) {

        $semester = Semester::all();
        $makul = Matakuliah::all();
        $hari = Kurikulum_hari::all();
        $jam = Kurikulum_jam::all();
        $ruang = Ruangan::all();
        $dosen = Dosen::all();

        $record = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->where('student_record.id_student', $key->idstudent)
                                ->where('kurikulum_periode.id_periodetipe', $tp)
                                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('student_record.id_kurtrans','kurikulum_transaction.id_makul', 'student_record.nilai_UTS')
                                ->groupBy('student_record.id_kurtrans','kurikulum_transaction.id_makul', 'student_record.nilai_UTS')
                                ->get();


          $users = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                  ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                  ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                  ->where('student_record.id_student', $key->idstudent)
                                  // ->where('kurikulum_transaction.id_semester', $c)
                                  ->where('kurikulum_periode.id_periodetipe', $tp)
                                  ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                  ->where('student_record.status', 'TAKEN')
                                  ->select('student_record.id_kurtrans', DB::raw('COUNT(student_record.id_kurtrans) as products_count'), 'student.nama')
                                  ->groupBy('student_record.id_kurtrans', 'student.nama')
                                  ->having('products_count', '>' , 1)
                                  ->get();

      $skst = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                              ->where('student_record.id_student', $key->idstudent)
                              // ->where('kurikulum_transaction.id_semester', $c)
                              ->where('kurikulum_periode.id_periodetipe', $tp)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('kurikulum_transaction.id_angkatan', $angk)
                              ->where('student_record.status', 'TAKEN')
                              ->sum('kurikulum_periode.akt_sks_teori');

      $sksp = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                              ->where('student_record.id_student', $key->idstudent)
                              // ->where('kurikulum_transaction.id_semester', $c)
                              ->where('kurikulum_periode.id_periodetipe', $tp)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('kurikulum_transaction.id_angkatan', $angk)
                              ->where('student_record.status', 'TAKEN')
                              ->sum('kurikulum_periode.akt_sks_praktek');


      $sks = $skst + $sksp;

      return view('mhs/khs_mid',['mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$record, 'smt'=>$semester, 'mk'=>$makul, 'hr'=>$hari, 'jm'=>$jam,  'rng'=>$ruang, 'dsn'=>$dosen, 'sks' => $sks]);

      }else{

          Alert::warning('Maaf anda tidak dapat melihat KHS karena keuangan Anda belum memenuhi syarat');
        return redirect('home');


      }
    }

    public function khs_final()
    {
      $id = Auth::user()->username;

      $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

      $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();

      $maha = Student::where('nim', Auth::user()->username)->get();

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

      $kuitansi = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                          ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
                          ->where('kuitansi.idstudent', $ky)
                          ->select('bayar.bayar', 'kuitansi.tanggal', 'kuitansi.nokuit', 'bayar.iditem')
                          ->orderBy('kuitansi.tanggal', 'ASC')
                          ->get();

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

      $tots1 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1;
      $tots2 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2;
      $tots3 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3;
      $tots4 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4;
      $tots5 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5;
      $tots6 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6;
      $tots7 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7;
      $tots8 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8;
      $tots9 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9;
      $tots10 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10;
      $totalsisa = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaseminar + $sisasidang + $sisawisuda;

        if ($c==1) {
          $cekbyr=($daftar+$awal+$dsp+$spp1)-$tots1;
        }elseif ($c==4) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4)-$tots4;
        }elseif ($c==2) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2)-$tots2;
        }elseif ($c==3) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3)-$tots3;
        }elseif ($c==5) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5)-$tots5;
        }elseif ($c==5.4 or 6) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6)-$tots6;
        }elseif ($c==7) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7)-$tots7;
        }elseif ($c==8) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8)-$tots8;
        }elseif ($c==9) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+$spp9)-$tots9;
        }elseif ($c==10) {
          $cekbyr=($daftar+$awal+$dsp+$spp1+$spp2+$spp3+$spp4+$spp5+$spp6+$spp7+$spp8+$spp9+$spp10)-$tots10;
        }


      if ($cekbyr < 1) {

          $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->where('student_record.id_student', $key->idstudent)
                                // ->where('kurikulum_periode.id_semester', $c)
                                ->where('kurikulum_periode.id_periodetipe', $tp)
                                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('kurikulum_periode.id_makul')
                                ->groupBy('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen')
                                ->get();
        $hit = count($records);
// dd($hit);
        $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                    ->where('edom_transaction.id_student', $key->idstudent)
                                    // ->where('kurikulum_periode.id_semester', $c)
                                    ->where('kurikulum_periode.id_periodetipe', $tp)
                                    ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)

                                    ->select((DB::raw('DISTINCT(edom_transaction.id_kurperiode)')))
                                    ->get();

      $sekhit = count($cekedom);
      if ($hit == $sekhit) {
        $semester = Semester::all();
        $makul = Matakuliah::all();
        $hari = Kurikulum_hari::all();
        $jam = Kurikulum_jam::all();
        $ruang = Ruangan::all();
        $dosen = Dosen::all();

        $recordas = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->where('student_record.id_student', $key->idstudent)
                                // ->where('kurikulum_transaction.id_semester', $c)
                                ->where('kurikulum_periode.id_periodetipe', $tp)
                                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul', 'student_record.nilai_ANGKA', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek')
                                ->groupBy('kurikulum_transaction.idkurtrans', 'student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul', 'student_record.nilai_ANGKA', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek')
                                ->get();

        $record = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->where('student_record.id_student', $key->idstudent)
                                // ->where('kurikulum_transaction.id_semester', $c)
                                ->where('kurikulum_periode.id_periodetipe', $tp)
                                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->select('student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul', 'student_record.nilai_ANGKA')
                                ->groupBy('kurikulum_transaction.idkurtrans', 'student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul', 'student_record.nilai_ANGKA')
                                ->get();

        $skst = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')

                                ->where('student_record.id_student', $key->idstudent)
                                // ->where('kurikulum_periode.id_semester', $c)
                                ->where('kurikulum_periode.id_periodetipe', $tp)
                                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')

                                ->sum('kurikulum_periode.akt_sks_teori');

        $skstt = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $key->idstudent)
                                // ->where('kurikulum_periode.id_semester', $smt)
                                ->where('kurikulum_periode.id_periodetipe', $tp)
                                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->sum('matakuliah.akt_sks_teori');

        $sksp = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                                ->where('student_record.id_student', $key->idstudent)
                                // ->where('kurikulum_periode.id_semester', $c)
                                ->where('kurikulum_periode.id_periodetipe', $tp)
                                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                ->where('student_record.status', 'TAKEN')
                                ->sum('matakuliah.akt_sks_praktek');


        $sks = $skstt + $sksp;

        $nilai = array(
          'A' => 4,
          'B+' => 3.5,
          'B' => 3,
          'C+' => 2.5,
          'C' => 2,
          'D' => 1,
          'E' => 0,
        );

        $ceknilaiA=Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->where('student_record.id_student', $key->idstudent)
                                ->where('kurikulum_transaction.id_semester', $c)
                                // ->select('kurikulum_transaction.id_makul', DB::raw('sum(kurikulum_periode.akt_sks_teori+kurikulum_periode.akt_sks_teori) as total)'))
                                ->select('kurikulum_transaction.id_makul', 'student_record.nilai_AKHIR', DB::raw('MAX(kurikulum_periode.akt_sks_teori) as akt_sks_teori'), DB::raw('MAX(kurikulum_periode.akt_sks_praktek) as akt_sks_praktek'))
                                ->groupBy('student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul')
                                ->get();

        $ceknilaimhs=Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                  ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                  ->where('student_record.id_student', $key->idstudent)
                                  ->where('kurikulum_transaction.id_semester', $c)
                                  ->select('student_record.nilai_AKHIR')
                                  ->groupBy('student_record.nilai_AKHIR', 'kurikulum_transaction.id_makul')
                                  ->get();

      $ceknilaisks=Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->where('student_record.id_student', $key->idstudent)
                              ->where('kurikulum_transaction.id_semester', $c)
                              ->select(DB::raw('sum(kurikulum_periode.akt_sks_teori+kurikulum_periode.akt_sks_praktek) as akt_sks'))
                              ->groupBy('kurikulum_transaction.id_makul', 'student_record.nilai_AKHIR')
                              ->get();

      $ceknilaisksd=Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                              ->where('student_record.id_student', $key->idstudent)
                              // ->where('kurikulum_transaction.id_semester', $c)
                              ->where('kurikulum_periode.id_periodetipe', $tp)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('student_record.status', 'TAKEN')
                              ->select(DB::raw('sum((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as akt_sks'))
                              // ->groupBy('kurikulum_transaction.id_makul', 'student_record.nilai_AKHIR')
                              ->first();

        $sks_nilai = $ceknilaisksd->akt_sks;

        $d = date('d');
        // $m =$bulan[date('m')];
        $y = date('Y');

        return view('mhs/khs_final',['sks_nilai'=>$sks_nilai,'nia'=>$ceknilaisksd,'ceknilai'=>$ceknilaiA,'mhs'=>$key, 'tp'=>$prd_tp, 'thn'=>$prd_thn, 'krs'=>$recordas, 'smt'=>$semester, 'mk'=>$makul, 'hr'=>$hari, 'jm'=>$jam,  'rng'=>$ruang, 'dsn'=>$dosen, 'sks' => $sks]);
      }else {
        Alert::error('maaf anda belum melakukan pengisian edom', 'MAAF !!');
        return redirect('home');
      }

      }else {

          Alert::warning('Maaf anda tidak dapat melihat KHS karena keuangan Anda belum memenuhi syarat');
        return redirect('home');

      }
    }

    public function uang()
    {
      $id = Auth::user()->username;

      $maha = Student::where('nim', $id)->get();

      foreach ($maha as $key) {
        # code...
      }

      $ky = $key->idstudent;
      $idangkatan = $key->idangkatan;
      $idstatus = $key->idstatus;
      $kodeprodi = $key->kodeprodi;

      $itembayar = Itembayar::all();

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

        $bswsdftr=$value->daftar-(($value->daftar*($cb->daftar))/100);
        $bswsawal=$value->awal-(($value->awal*($cb->awal))/100);
        $bswsdsp=$value->dsp-(($value->dsp*($cb->dsp))/100);
        $bswspp1=$value->spp1-(($value->spp1*($cb->spp1))/100);
        $bswspp2=$value->spp2-(($value->spp2*($cb->spp2))/100);
        $bswspp3=$value->spp3-(($value->spp3*($cb->spp3))/100);
        $bswspp4=$value->spp4-(($value->spp4*($cb->spp4))/100);
        $bswspp5=$value->spp5-(($value->spp5*($cb->spp5))/100);
        $bswspp6=$value->spp6-(($value->spp6*($cb->spp6))/100);
        $bswspp7=$value->spp7-(($value->spp7*($cb->spp7))/100);
        $bswspp8=$value->spp8-(($value->spp8*($cb->spp8))/100);
        $bswspp9=$value->spp9-(($value->spp9*($cb->spp9))/100);
        $bswspp10=$value->spp10-(($value->spp10*($cb->spp10))/100);
        $bswssmn=$value->seminar-(($value->seminar*($cb->seminar))/100);
        $bswssdg=$value->sidang-(($value->sidang*($cb->sidang))/100);
        $bswswsd=$value->wisuda-(($value->wisuda*($cb->wisuda))/100);

        $totalall = $bswsdftr+$bswsawal+$bswsdsp+$bswspp1+$bswspp2+$bswspp3+$bswspp4+$bswspp5+$bswspp6+$bswspp7+$bswspp8+$bswspp9+$bswspp10+$bswssmn+$bswssdg+$bswswsd;
      }else {
        $bswsdftr=$value->daftar;
        $bswsawal=$value->awal;
        $bswsdsp=$value->dsp;
        $bswspp1=$value->spp1;
        $bswspp2=$value->spp2;
        $bswspp3=$value->spp3;
        $bswspp4=$value->spp4;
        $bswspp5=$value->spp5;
        $bswspp6=$value->spp6;
        $bswspp7=$value->spp7;
        $bswspp8=$value->spp8;
        $bswspp9=$value->spp9;
        $bswspp10=$value->spp10;
        $bswssmn=$value->seminar;
        $bswssdg=$value->sidang;
        $bswswsd=$value->wisuda;

        $totalall = $bswsdftr+$bswsawal+$bswsdsp+$bswspp1+$bswspp2+$bswspp3+$bswspp4+$bswspp5+$bswspp6+$bswspp7+$bswspp8+$bswspp9+$bswspp10+$bswssmn+$bswssdg+$bswswsd;
      }

      $kuitansi = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                          ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
                          ->where('kuitansi.idstudent', $ky)
                          ->select('bayar.bayar', 'kuitansi.tanggal', 'kuitansi.nokuit', 'bayar.iditem')
                          ->orderBy('kuitansi.tanggal', 'ASC')
                          ->get();

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

      $totalsisa = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaseminar + $sisasidang + $sisawisuda;

      $kurangdaftar = $bswsdftr - $sisadaftar;
      $kurangawal = $bswsawal - $sisaawal;
      $kurangdsp = $bswsdsp - $sisadsp;
      $kurangspp1 = $bswspp1 - $sisaspp1;
      $kurangspp2 = $bswspp2 - $sisaspp2;
      $kurangspp3 = $bswspp3 - $sisaspp3;
      $kurangspp4 = $bswspp4 - $sisaspp4;
      $kurangspp5 = $bswspp5 - $sisaspp5;
      $kurangspp6 = $bswspp6 - $sisaspp6;
      $kurangspp7 = $bswspp7 - $sisaspp7;
      $kurangspp8 = $bswspp8 - $sisaspp8;
      $kurangspp9 = $bswspp9 - $sisaspp9;
      $kurangspp10 = $bswspp10 - $sisaspp10;
      $kurangseminar = $bswssmn - $sisaseminar;
      $kurangsidang = $bswssdg - $sisasidang;
      $kurangwisuda = $bswswsd - $sisawisuda;

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

      $item = Itembayar::all();

      $mhsbea = Beasiswa::where('idstudent', $ky)->get();

      if (count($mhsbea) > 0) {

        foreach ($mhsbea as $keybea) {
          // code...
        }

        $beadaftar = $keybea->daftar;
        $beaawal = $keybea->awal;
        $beadsp = $keybea->dsp;
        $beaspp1 = $keybea->spp1;
        $beaspp2 = $keybea->spp2;
        $beaspp3 = $keybea->spp3;
        $beaspp4 = $keybea->spp4;
        $beaspp5 = $keybea->spp5;
        $beaspp6 = $keybea->spp6;
        $beaspp7 = $keybea->spp7;
        $beaspp8 = $keybea->spp8;
        $beaspp9 = $keybea->spp9;
        $beaspp10 = $keybea->spp10;
        $beasmn = $keybea->seminar;
        $beasdg = $keybea->sidang;
        $beawsd = $keybea->wisuda;
      }else {
        $beadaftar = '0';
        $beaawal = '0';
        $beadsp = '0';
        $beaspp1 = '0';
        $beaspp2 = '0';
        $beaspp3 = '0';
        $beaspp4 = '0';
        $beaspp5 = '0';
        $beaspp6 = '0';
        $beaspp7 = '0';
        $beaspp8 = '0';
        $beaspp9 = '0';
        $beaspp10 = '0';
        $beasmn = '0';
        $beasdg = '0';
        $beawsd = '0';
      }

      return view('mhs/keuangan', ['beawsd'=>$beawsd, 'beasdg'=>$beasdg, 'beasmn'=>$beasmn, 'beaspp10'=>$beaspp10, 'beaspp9'=>$beaspp9, 'beaspp8'=>$beaspp8, 'beaspp7'=>$beaspp7, 'beaspp6'=>$beaspp6, 'beaspp5'=>$beaspp5, 'beaspp4'=>$beaspp4, 'beaspp3'=>$beaspp3, 'beaspp2'=>$beaspp2, 'beaspp1'=>$beaspp1, 'beadsp'=>$beadsp, 'beaawal'=>$beaawal, 'beadaftar'=>$beadaftar,
                                  'totalbayarmhs'=>$totalbayarmhs, 'c'=>$c, 'items'=>$item,'kurangwisuda'=>$kurangwisuda, 'kurangsidang'=>$kurangsidang, 'kurangseminar'=>$kurangseminar, 'kurangspp6'=>$kurangspp6, 'kurangspp5'=>$kurangspp5,'kurangspp4'=>$kurangspp1, 'kurangspp3'=>$kurangspp1, 'kurangspp2'=>$kurangspp2, 'kurangspp1'=>$kurangspp1, 'kurangdsp'=>$kurangdsp,
                                  '$kurangdaftar'=>$kurangdaftar, 'kurangawal'=>$kurangawal, 'totalsisa'=>$totalsisa, 'sisadsp'=>$sisadsp, 'sisaspp1'=>$sisaspp1, 'sisaspp2'=>$sisaspp2, 'sisaspp3'=>$sisaspp3, 'sisaspp4'=>$sisaspp4, 'sisaspp5'=>$sisaspp5, 'sisaspp6'=>$sisaspp6, 'sisaspp7'=>$sisaspp7, 'sisaspp8'=>$sisaspp8, 'sisaspp9'=>$sisaspp9,
                                  'sisaspp10'=>$sisaspp10, 'sisaseminar'=>$sisaseminar, 'sisasidang'=>$sisasidang, 'sisawisuda'=>$sisawisuda, 'sisaawal'=>$sisaawal,'sisadaftar'=>$sisadaftar,'total'=>$totalall,'daftar'=>$bswsdftr, 'awal'=>$bswsawal, 'dsp'=>$bswsdsp, 'spp1'=>$bswspp1, 'spp2'=>$bswspp2, 'spp3'=>$bswspp3,
                                  'spp4'=>$bswspp4, 'spp5'=>$bswspp5, 'spp6'=>$bswspp6, 'spp7'=>$bswspp7, 'spp8'=>$bswspp8, 'spp9'=>$bswspp9, 'spp10'=>$bswspp10, 'seminar'=>$bswssmn, 'sidang'=>$bswssdg, 'wisuda'=>$bswswsd, 'biaya' => $value, 'kuit' => $kuitansi,
                                  'itembayar' => $itembayar, 'totalbiaya' => $totalbiaya]);
    }

    public function lihat_semua()
    {
      $info = Informasi::orderBy('created_at', 'DESC')->get();

      return view('mhs/all_info', ['info'=>$info]);
    }

    public function lihat($id)
    {
      $info = Informasi::find($id);

      return view ('mhs/lihatinfo', ['info'=>$info]);
    }

    public function ganti_foto($id)
    {
      $id = Auth::user()->username;
      $mhs = Student::where('nim', $id)->get();
      foreach ($mhs as $maha) {
        // code...
      }

      return view('mhs/ganti_foto', ['mhs'=>$maha]);
    }

    public function simpanfoto(Request $request, $id)
    {

      $this->validate($request, [
          'foto'          => 'required|mimes:jpeg,jpg|max:500',
        ]);

        $foto                 = Student::find($id);

        if ($foto->foto) {
          if ($request->hasFile('foto')) {
            File::delete('foto_mhs/'.$foto->foto);
            $file = $request->file('foto');
            $nama_file = Auth::user()->username.'.jpg';
            $tujuan_upload = 'foto_mhs';
            $file->move($tujuan_upload,$nama_file);
            $foto->foto        = $nama_file;
            $foto->save();
            Alert::success('', 'Foto berhasil disimpan')->autoclose(3500);
            return redirect('home');
          }

        }else {
          if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_file = Auth::user()->username.'.jpg';
            $tujuan_upload = 'foto_mhs';
            $file->move($tujuan_upload,$nama_file);
            $foto->foto        = $nama_file;
            $foto->save();
            Alert::success('', 'Foto berhasil disimpan')->autoclose(3500);
            return redirect('home');
          }

        }

    }

    public function jdl_uts()
    {
      $thn = Periode_tahun::where('status', 'ACTIVE')->get();
      foreach ($thn as $tahun) {
        // code...
      }

      $tp = Periode_tipe::where('status', 'ACTIVE')->get();
      foreach ($tp as $tipe) {
        // code...
      }

      $id_prd = Auth::user()->username;
      $mhs = Student::where('nim', $id_prd)->get();
      foreach ($mhs as $keymhs) {
        // code...
      }
      $prodi = $keymhs->kodeprodi;
      $prd = Prodi::where('kodeprodi', $prodi)->get();
      foreach ($prd as $keyprd) {
        // code...
      }
      $makul = Matakuliah::all();
      $ruang = Ruangan::all();
      $jam = Kurikulum_jam::all();
      $uts = Ujian_transaction::where('ujian_transaction.id_periodetahun', $tahun->id_periodetahun)
                              ->where('ujian_transaction.id_periodetipe', $tipe->id_periodetipe)
                              ->where('ujian_transaction.id_prodi', $keyprd->id_prodi)
                              ->where('ujian_transaction.id_kelas', $keymhs->idstatus)
                              ->where('ujian_transaction.jenis_ujian', 'UTS')
                              //->where('student_record.id_student', $keymhs->idstudent)
                              // ->where('student_record.status', 'TAKEN')
                              // ->select('ujian_transaction.tanggal_ujian')
                              ->get();

      $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->where('student_record.id_student', $keymhs->idstudent)
                              ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('student_record.status', 'TAKEN')
                              //->join('ujian_transaction', 'kurikulum_periode.id_makul', '=', 'ujian_transaction.id_makul')
                              ->select('kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan')
                              ->orderBy('kurikulum_periode.id_hari', 'ASC')
                              ->get();

      return view('mhs/jadwal_uts', ['record'=>$record, 'uts'=>$uts, 'mk'=>$makul, 'rng'=>$ruang, 'jam'=>$jam]);
    }

    public function jdl_uas()
    {
      $thn = Periode_tahun::where('status', 'ACTIVE')->get();
      foreach ($thn as $tahun) {
        // code...
      }

      $tp = Periode_tipe::where('status', 'ACTIVE')->get();
      foreach ($tp as $tipe) {
        // code...
      }

      $id_prd = Auth::user()->username;
      $mhs = Student::where('nim', $id_prd)->get();
      foreach ($mhs as $keymhs) {
        // code...
      }
      $prodi = $keymhs->kodeprodi;
      $prd = Prodi::where('kodeprodi', $prodi)->get();
      foreach ($prd as $keyprd) {
        // code...
      }
      $makul = Matakuliah::all();
      $ruang = Ruangan::all();
      $jam = Kurikulum_jam::all();
      $uts = Ujian_transaction::where('ujian_transaction.id_periodetahun', $tahun->id_periodetahun)
                              ->where('ujian_transaction.id_periodetipe', $tipe->id_periodetipe)
                              ->where('ujian_transaction.id_prodi', $keyprd->id_prodi)
                              ->where('ujian_transaction.id_kelas', $keymhs->idstatus)
                              ->where('ujian_transaction.jenis_ujian', 'UAS')
                              ->get();

      $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->where('student_record.id_student', $keymhs->idstudent)
                              ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
                              ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                              ->where('student_record.status', 'TAKEN')
                              //->join('ujian_transaction', 'kurikulum_periode.id_makul', '=', 'ujian_transaction.id_makul')
                              ->select('kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan')
                              ->orderBy('kurikulum_periode.id_hari', 'ASC')
                              ->get();

      return view('mhs/jadwal_uas', ['record' => $record, 'uts' => $uts, 'mk' => $makul, 'rng' => $ruang, 'jam' => $jam]);
    }

    public function pedoman_akademik()
    {
      $thn = Periode_tahun::all();
      $pedoman = Pedoman_akademik::all();

      return view('mhs/pedoman_akademik', ['pedoman'=>$pedoman, 'idhn'=>$thn]);
    }

    public function download_pedoman($id)
    {
        $ped = Pedoman_akademik::where('id_pedomanakademik', $id)->get();
        foreach ($ped as $keyped) {
          // code...
        }
        //PDF file is stored under project/public/download/info.pdf
        $file="pedoman/".$keyped->file;
        return Response::download($file);

    }

    
}
