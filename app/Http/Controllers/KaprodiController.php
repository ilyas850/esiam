<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\Bap;
use App\User;
use App\Dosen;
use App\Kelas;
use App\Prodi;
use App\Biaya;
use App\Kaprodi;
use App\Ruangan;
use App\Semester;
use App\Beasiswa;
use App\Kuitansi;
use App\Student;
use App\Angkatan;
use App\Informasi;
use App\Matakuliah;
use App\Ujian_transaction;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Student_record;
use App\Dosen_pembimbing;
use App\Edom_transaction;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_master;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Kuliah_transaction;
use App\Absensi_mahasiswa;
use App\Exports\DataNilaiIpkMhsExport;
use App\Exports\DataNilaiIpkMhsProdiExport;
use App\Exports\DataNilaiExport;
use App\Exports\DataMhsExport;
use App\Exports\DataMhsAllExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class KaprodiController extends Controller
{
  public function change_pass_kaprodi($id)
  {
    return view ('kaprodi/change_pwd_kaprodi', ['dsn' => $id]);
  }

  public function store_pwd_kaprodi(Request $request, $id)
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

  public function lihat_semua_kprd()
  {
    $info = Informasi::orderBy('created_at', 'DESC')->get();

    return view('kaprodi/all_info', ['info'=>$info]);
  }

  public function lihat_kprd($id)
  {
    $info = Informasi::find($id);

    return view ('kaprodi/lihatinfo', ['info'=>$info]);
  }

  public function mhs_aktif()
  {
    $tahun = Periode_tahun::whereNotIn('id_periodetahun', [1,3,4])
                          ->orderBy('periode_tahun', 'ASC')
                          ->get();
    $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();
    $prodi = Prodi::all();

    $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                        ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                        ->join('dosen_pembimbing', 'student_record.id_student', 'dosen_pembimbing.id_student')
                        ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                        ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                        ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                        ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                        ->where('periode_tahun.status', 'ACTIVE')
                        ->where('periode_tipe.status', 'ACTIVE')
                        ->where('student_record.status', 'TAKEN')
                        ->where('student.active', 1)
                        ->select(DB::raw('DISTINCT(student_record.id_student)'), 'kelas.kelas','student.nim','angkatan.angkatan', 'prodi.prodi', 'student.nama')
                        ->orderBy('student.nim', 'ASC')
                        ->orderBy('student.idangkatan', 'ASC')
                        ->get();

    return view ('kaprodi/master/mhs_aktif', ['aktif'=>$val, 'thn' => $tahun, 'tp' => $tipe, 'prd' => $prodi]);
  }

  public function cari_mhs_aktif(Request $request)
  {
    $thn = $request->id_periodetahun;
    $tp = $request->id_periodetipe;
    $kd = $request->kodeprodi;

    $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                        ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                        ->join('dosen_pembimbing', 'student_record.id_student', 'dosen_pembimbing.id_student')
                        ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                        ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                        ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                        ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                        ->where('periode_tahun.id_periodetahun', $request->id_periodetahun)
                        ->where('periode_tipe.id_periodetipe', $request->id_periodetipe)
                        ->where('student_record.status', 'TAKEN')
                        ->where('student.active', 1)
                        ->where('student.kodeprodi', $request->kodeprodi)
                        ->select(DB::raw('DISTINCT(student_record.id_student)'), 'kelas.kelas','student.nim','angkatan.angkatan', 'prodi.prodi', 'student.nama','prodi.kodeprodi')
                        ->orderBy('student.nim', 'ASC')
                        ->orderBy('student.idangkatan', 'ASC')
                        ->get();

    $tahun = Periode_tahun::whereNotIn('id_periodetahun', [1,3,4])
                          ->orderBy('periode_tahun', 'ASC')
                          ->get();
    $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();
    $prodi = Prodi::all();

    $thun = Periode_tahun::where('id_periodetahun', $thn)->get();
    foreach ($thun as $keythn) {
      # code...
    }
    $ta = $keythn->periode_tahun;

    $tip = Periode_tipe::where('id_periodetipe', $tp)->get();
    foreach ($tip as $keytp) {
      # code...
    }
    $tpe = $keytp->periode_tipe;

    $prdi = Prodi::where('kodeprodi', $kd)->get();
    foreach ($prdi as $keyprd) {
      # code...
    }
    $prod = $keyprd->prodi;


    return view('kaprodi/master/mhs_aktif_new', ['ta'=>$ta,'tpe'=>$tpe,'prod'=>$prod,'data'=>$val, 'idthn'=>$thn, 'idtp'=>$tp, 'idkd'=>$kd, 'thn' => $tahun, 'tp' => $tipe, 'prd' => $prodi]);
  }

  public function export_data_mhs_aktif()
  {
    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->periode_tahun;

    $ganti = str_replace("/","_",$tahun);

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->periode_tipe;

    $nama_file = 'Data Mahasiswa Aktif '.' '.$ganti.' '.$tipe.'.xlsx';
    return Excel::download(new DataMhsAllExport, $nama_file);
  }

  public function export_xls_mhs_aktif(Request $request)
  {
    $prodi = Prodi::where('kodeprodi', $request->kodeprodi)->first();
    $prd = $prodi->prodi;
    $nmprd = $request->kodeprodi;

    $thn = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
    $tahun = $thn->periode_tahun;
    $nmthun = $request->id_periodetahun;

    $ganti = str_replace("/","_",$tahun);

    $tp = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
    $tipe = $tp->periode_tipe;
    $nmtp = $request->id_periodetipe;

    $nama_file = 'Data Mahasiswa Aktif '.' '.$prd.' '.$ganti.' '.$tipe.'.xlsx';
    return Excel::download(new DataMhsExport($nmprd, $nmthun, $nmtp), $nama_file);
  }

  public function mhs_bim()
  {
    $angk = Angkatan::all();
    $id = Auth::user()->username;
    $dsn = Dosen::where('nik', $id)->get();
    foreach ($dsn as $value) {
      // code...
    }
    $mhs = Dosen_pembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
                          ->where('dosen_pembimbing.id_dosen', $value->iddosen)
                          ->where('student.active', 1)
                          ->select('student.nama', 'student.idangkatan', 'student.idstatus', 'student.idstudent', 'student.kodeprodi', 'student.nim')
                          ->orderBy('student.nim', 'ASC')
                          ->orderBy('student.idangkatan', 'ASC')
                          ->get();

    return view('kaprodi/master/mhs_bim', ['mhs'=>$mhs, 'angk'=>$angk]);
  }

  public function record_nilai($id)
  {
    $maha = Student::where('idstudent', $id)->get();
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
    }elseif ($c==6) {
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
                            ->where('kurikulum_periode.id_periodetipe', $tp)
                            ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                            ->where('student_record.status', 'TAKEN')
                            ->select('kurikulum_periode.id_makul')
                            ->groupBy('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen')
                            ->get();
      $hit = count($records);

      $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                  ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                                  ->where('edom_transaction.id_student', $key->idstudent)
                                  ->where('kurikulum_periode.id_periodetipe', $tp)
                                  ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                  ->select((DB::raw('DISTINCT(edom_transaction.id_kurperiode)')))
                                  ->get();
      $sekhit = count($cekedom);

      if ($hit == $sekhit) {

        $makul = Matakuliah::all();
        $cek = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->where('student_record.id_student', $id)
                              ->where('student_record.status', 'TAKEN')
                              ->select('kurikulum_periode.id_makul','student.nama', 'student.nim', 'student.idstatus', 'student.kodeprodi', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA')
                              ->groupBy('kurikulum_periode.id_makul','student.nama', 'student.nim', 'student.idstatus', 'student.kodeprodi', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA')
                              ->get();
        foreach ($cek as $key) {
          // code...
        }

        return view('kaprodi/master/record_nilai', ['cek'=>$cek, 'key'=>$key, 'mk'=>$makul]);

      }else {

        Alert::error('maaf mahasiswa tersebut belum melakukan pengisian edom', 'MAAF !!');
        return redirect('mhs_bim_kprd');
      }

    }else {

      Alert::warning('Maaf anda tidak dapat melihat nilai mahasiswa ini karena keuangannya belum memenuhi syarat');
      return redirect('mhs_bim_kprd');
    }
  }

  public function val_krs()
  {
    $angk = Angkatan::all();
    $id = Auth::user()->username;
    $dsn = Dosen::where('nik', $id)->get();
    foreach ($dsn as $value) {
      // code...
    }
    $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                        ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                        ->join('dosen_pembimbing', 'student_record.id_student', 'dosen_pembimbing.id_student')
                        ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                        ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                        ->where('periode_tahun.status', 'ACTIVE')
                        ->where('periode_tipe.status', 'ACTIVE')
                        ->where('dosen_pembimbing.id_dosen', $value->iddosen)
                        ->where('student_record.status', 'TAKEN')
                        ->where('student.active', 1)
                        ->select(DB::raw('DISTINCT(student_record.id_student)'),'student_record.remark', 'student.idstatus','student.nim','student.idangkatan', 'student.kodeprodi', 'student.nama')
                        ->orderBy('student.nim', 'ASC')
                        ->orderBy('student.idangkatan', 'ASC')
                        ->get();

    return view ('kaprodi/validasi_krs',['val'=>$val, 'angk'=>$angk]);
  }

  public function krs_validasi(Request $request)
  {
    $id = $request->id_student;

    $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                        ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                        ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                        ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                        ->where('periode_tahun.status', 'ACTIVE')
                        ->where('periode_tipe.status', 'ACTIVE')
                        ->where('student_record.status', 'TAKEN')
                        ->where('student_record.id_student', $id)
                        ->update(['student_record.remark' => $request->remark]);


      Alert::success('', 'Berhasil ')->autoclose(3500);
      return redirect()->back();
  }

  public function cek_krs($id)
  {
    $semester = Semester::all();
    $dosen = Dosen::all();
    $makul = Matakuliah::all();
    $hari = Kurikulum_hari::all();
    $jam = Kurikulum_jam::all();
    $ruang = Ruangan::all();
    $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                        ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                        ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                        ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                        ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                        ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                        ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
                        ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
                        ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
                        ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                        ->where('periode_tahun.status', 'ACTIVE')
                        ->where('periode_tipe.status', 'ACTIVE')
                        ->where('student_record.status', 'TAKEN')
                        ->where('id_student', $id)
                        ->select('student_record.remark', 'student.idstudent', 'student_record.id_studentrecord', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'matakuliah.kode','matakuliah.makul', 'student_record.remark', 'student.idstatus','student.nim','student.idangkatan', 'student.kodeprodi', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'semester.semester')
                        ->get();

      foreach ($val as $key) {
        // code...
      }

      $valkrs = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                          ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                          ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                          ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                          ->where('periode_tahun.status', 'ACTIVE')
                          ->where('periode_tipe.status', 'ACTIVE')
                          ->where('student_record.status', 'TAKEN')
                          ->where('id_student', $id)
                          ->select(DB::raw('DISTINCT(student_record.remark)'), 'student.idstudent' )
                          ->get();

      foreach ($valkrs as $valuekrs) {
        // code...
      }

      $b = $valuekrs->remark;

      $kur = Kurikulum_master::where('status', 'ACTIVE')->get();
      foreach ($kur as $krlm) {
        // code...
      }

      $tp = Periode_tipe::where('status', 'ACTIVE')->get();
      foreach ($tp as $tipe) {
        // code...
      }
      $tp = $tipe->id_periodetipe;

      $thn = Periode_tahun::where('status', 'ACTIVE')->get();

      foreach ($thn as $tahun) {
        // code...
      }

      $maha = Student::where('idstudent', $id)->get();

      foreach ($maha as $key) {
        # code...
      }
      $mhs = $key->kodeprodi;
      $prod = Prodi::where('kodeprodi', $key->kodeprodi)->get();
      foreach ($prod as $value) {
        // code...
      }
      $mhs = $key->idstudent;
      $krs = Kurikulum_transaction::join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                                  ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                                  ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                                  ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                                  ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                                  ->where('kurikulum_periode.id_periodetipe', $tp)
                                  ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                                  ->where('kurikulum_periode.id_kelas', $key->idstatus)
                                  ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                                  ->where('kurikulum_transaction.id_angkatan', $key->idangkatan)
                                  ->where('kurikulum_periode.status', 'ACTIVE')
                                  ->where('kurikulum_transaction.status', 'ACTIVE')
                                  ->select('matakuliah.kode','matakuliah.makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'semester.semester', 'dosen.nama')
                                  ->get();

      return view('kaprodi/cek_krs', ['b'=>$b, 'hr'=>$hari, 'jm'=>$jam,  'rng'=>$ruang, 'smt'=>$semester,'mhss'=>$mhs,'add'=>$krs, 'val'=>$val, 'key'=>$key, 'mk'=>$makul, 'dsn'=>$dosen]);
  }

  public function savekrs_new(Request $request)
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
      return redirect()->back();
    }elseif (count($cekkrs) == 0) {
      $krs = new Student_record;
      $krs->tanggal_krs   = date("Y-m-d");
      $krs->id_student    = $request->id_student;
      $krs->id_kurperiode = $tra;
      $krs->id_kurtrans   = $trs;
      $krs->save();

      Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
      return redirect()->back();
    }
  }

  public function hapuskrsmhs(Request $request)
  {
    $id = $request->id_studentrecord;
    $cek = Student_record::find($id);
    $cek->status = $request->status;
    $cek->save();

    Alert::success('', 'Matakuliah berhasil dihapus')->autoclose(3500);
    return redirect()->back();
  }

  public function makul_diampu_dsn()
  {
    $id = Auth::user()->username;
    $dsn = Dosen::where('nik', $id)->get();
    foreach ($dsn as $keydsn) {
        # code...
    }
    $iddsn = $keydsn->iddosen;
    $tp = Periode_tipe::where('status', 'ACTIVE')->get();
    foreach ($tp as $tipe) {
      // code...
    }
    $tp = $tipe->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->get();

    foreach ($thn as $tahun) {
      // code...
    }
    $thn = $tahun->id_periodetahun;

    $kur = Kurikulum_master::where('status', 'ACTIVE')->get();
    foreach ($kur as $krlm) {
        // code...
    }
    $mkul = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun','=', 'periode_tahun.id_periodetahun')
                            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                            ->where('kurikulum_periode.id_dosen', $iddsn)
                            ->where('periode_tahun.id_periodetahun', $thn)
                            // ->where('periode_tipe.id_periodetipe', $tp)
                            ->select('kurikulum_periode.id_kurperiode','matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                            ->orderBy('semester.semester', 'ASC')
                            ->get();

    return view('kaprodi/matakuliah/makul_diampu_dsn', ['makul'=>$mkul]);
  }

  public function cekmhs_dsn($id)
  {
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $id)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

      $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $id)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
      foreach ($ckstr as $str) {
        # code...
      }
      $kur =$str->id_kurtrans;

      return view('kaprodi/matakuliah/list_mhs_dsn', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$id, 'kur'=>$kur]);
  }

  public function export_xlsnilai(Request $request)
  {
    $id=$request->id_kurperiode;

    $mk = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', 'kelas.idkelas')
                            ->where('kurikulum_periode.id_kurperiode', $id)
                            ->select('matakuliah.makul', 'prodi.prodi', 'kelas.kelas')
                            ->get();
    foreach ($mk as $keymk) {
      # code...
    }

    $mkul = $keymk->makul;
    $prdi = $keymk->prodi;
    $klas = $keymk->kelas;

    $nama_file = 'Nilai Matakuliah' .' '. $mkul .' '. $prdi .' '. $klas .'.xlsx';
    return Excel::download(new DataNilaiExport($id), $nama_file);
  }

  public function unduh_pdf_nilai(Request $request)
  {
    $id=$request->id_kurperiode;

    $mk = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', 'kelas.idkelas')
                            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                            ->where('kurikulum_periode.id_kurperiode', $id)
                            ->select('periode_tahun.periode_tahun','periode_tipe.periode_tipe','dosen.nama','dosen.akademik','matakuliah.kode','matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'prodi.prodi', 'kelas.kelas')
                            ->get();

    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $id)
                          ->where('student_record.status','TAKEN')
                          ->select( 'student.nama', 'student.nim', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

    foreach ($mk as $key) {
      # code...
    }

      $makul = $key->makul;
      $tahun = $key->periode_tahun;
      $tipe = $key->periode_tipe;
      $kelas = $key->kelas;
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
    $m = $bulan[date('m')];
    $y = date('Y');

    // return view('dosen/unduh_nilai_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'data'=>$key,'tb'=>$cks]);
    $pdf= PDF::loadView('kaprodi/matakuliah/unduh_nilai_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'data'=>$key,'tb'=>$cks]);
    return $pdf->download('Nilai Matakuliah'.' '.$makul.' '.$tahun.' '.$tipe.' '.$kelas.'.pdf');
  }

  public function entri_bap($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul','=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                            ->where('kurikulum_periode.id_kurperiode', $id)
                            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                            ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.kurang_jam','kuliah_transaction.tanggal_validasi','kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->get();

    return view ('kaprodi/bap/bap',['bap'=>$key, 'data'=>$data]);
  }

  public function input_bap($id)
  {
    return view('kaprodi/bap/form_bap', ['id'=>$id]);
  }

  public function save_bap(Request $request)
  {
    $message = [
      'max'       => ':attribute harus diisi maksimal :max KB',
      'required'  => ':attribute wajib diisi',
      'unique'    => ':attribute sudah terdaftar',
    ];
    $this->validate($request, [
      'pertemuan'               => 'required',
      'tanggal'                 => 'required',
      'jam_mulai'               => 'required',
      'jam_selsai'              => 'required',
      'jenis_kuliah'            => 'required',
      'id_tipekuliah'           => 'required',
      'metode_kuliah'           => 'required',
      'materi_kuliah'           => 'required',
      'file_kuliah_tatapmuka'   => 'image|mimes:jpg,jpeg,JPG,JPEG|max:2048',
      'file_materi_kuliah'      => 'mimes:jpg,jpeg,JPG,JPEG,pdf|max:2048',
      'file_materi_tugas'       => 'image|mimes:jpg,jpeg,JPG,JPEG|max:2048',
    ], $message);

    $cek_bap = Bap::where('id_kurperiode', $request->id_kurperiode)
                  ->where('id_dosen', Auth::user()->id_user)
                  ->where('pertemuan', $request->pertemuan)
                  ->where('status', 'ACTIVE')
                  ->get();
    $jml_bap = count($cek_bap);
    if ($jml_bap > 0) {

      Alert::error('Maaf pertemuan yang diinput sudah ada', 'maaf');
      return redirect()->back();

    }elseif ($jml_bap == 0) {
      $bap                        = new Bap;
      $bap->id_kurperiode         = $request->id_kurperiode;
      $bap->id_dosen              = Auth::user()->id_user;
      $bap->pertemuan             = $request->pertemuan;
      $bap->tanggal               = $request->tanggal;
      $bap->jam_mulai             = $request->jam_mulai;
      $bap->jam_selsai            = $request->jam_selsai;
      $bap->jenis_kuliah          = $request->jenis_kuliah;
      $bap->id_tipekuliah         = $request->id_tipekuliah;
      $bap->metode_kuliah         = $request->metode_kuliah;
      $bap->materi_kuliah         = $request->materi_kuliah;
      $bap->media_pembelajaran    = $request->media_pembelajaran;

      if($request->hasFile('file_kuliah_tatapmuka'))
      {
        $file                         = $request->file('file_kuliah_tatapmuka');
        $nama_file                    = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
        $tujuan_upload                = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Kuliah Tatap Muka';
        $file->move($tujuan_upload,$nama_file);
        $bap->file_kuliah_tatapmuka   = $nama_file;
      }

      if($request->hasFile('file_materi_kuliah'))
      {
        $file                         = $request->file('file_materi_kuliah');
        $nama_file                    = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
        $tujuan_upload                = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Materi Kuliah';
        $file->move($tujuan_upload,$nama_file);
        $bap->file_materi_kuliah      = $nama_file;
      }

      if($request->hasFile('file_materi_tugas'))
      {
        $file                         = $request->file('file_materi_tugas');
        $nama_file                    = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
        $tujuan_upload                = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Tugas Kuliah';
        $file->move($tujuan_upload,$nama_file);
        $bap->file_materi_tugas       = $nama_file;
      }

      $bap->save();

      $users = DB::table('bap')
                ->limit(1)
                ->orderByDesc('id_bap')
                ->first();

      $kuliah = new Kuliah_transaction;
      $kuliah->id_kurperiode      = $request->id_kurperiode;
      $kuliah->id_dosen           = Auth::user()->id_user;
      $kuliah->id_tipekuliah      = $request->id_tipekuliah;
      $kuliah->tanggal            = $request->tanggal;
      $kuliah->akt_jam_mulai      = $request->jam_mulai;
      $kuliah->akt_jam_selesai    = $request->jam_selsai;
      $kuliah->id_bap             = $users->id_bap;
      $kuliah->save();

      return redirect('entri_bap_kprd/'.$request->id_kurperiode)->with('success', 'Data Berhasil diupload');
    }
  }

  public function entri_absen($id)
  {
    $idbap = Bap::where('id_bap', $id)->get();
    foreach ($idbap as $keybap) {
      # code...
    }
    $idp = $keybap->id_kurperiode;

    //cek mahasiswa
    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                        ->join('kelas','student.idstatus', '=', 'kelas.idkelas')
                        ->join('angkatan','student.idangkatan', '=', 'angkatan.idangkatan')
                        ->where('student_record.id_kurperiode', $idp)
                        ->where('student_record.status','TAKEN')
                        ->select('angkatan.angkatan','kelas.kelas','prodi.prodi','student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim')
                        ->get();

    return view('kaprodi/bap/absensi', ['absen'=>$cks, 'idk'=>$idp, 'id'=>$id]);
  }

  public function save_absensi(Request $request)
  {
    $id_record  = $request->id_studentrecord;
    $jmlrecord  = count($id_record);
    $id_kur     = $request->id_kurperiode;
    $id_bp      = $request->id_bap;
    $absen      = $request->absensi;
    $jmlabsen   = count($request->absensi);

    for ($i=0; $i < $jmlrecord; $i++) {
      $kurp = $request->id_studentrecord[$i];
      $idr = explode(',',$kurp);
      $tra = $idr[0];

      $cek = Absensi_mahasiswa::where('id_studentrecord', $tra)
                              ->where('id_bap', $id_bp)
                              ->get();

      $hit = count($cek);

      if ($hit == 0) {
        $abs                    = new Absensi_mahasiswa;
        $abs->id_bap            = $id_bp;
        $abs->id_studentrecord  = $tra;
        $abs->created_by        = Auth::user()->name;
        $abs->save();
      }
    }

    for ($i=0; $i < $jmlabsen; $i++) {
      $abs    = $request->absensi[$i];
      $idab   = explode(',',$abs, 2);
      $trsen  = $idab[0];
      $trsi   = $idab[1];

      Absensi_mahasiswa::where('id_studentrecord', $trsen)
                      ->where('id_bap', $id_bp)
                      ->update(['absensi' => 'ABSEN']);
    }

    $bp = Bap::where('id_bap', $id_bp)
                  ->update(['hadir'=>$jmlabsen]);
    $bp = Bap::where('id_bap', $id_bp)
                  ->update(['tidak_hadir'=>$jmlrecord-$jmlabsen]);

    return redirect('entri_bap_kprd/'.$id_kur);
  }

  public function edit_absen($id)
  {
    $abs = Absensi_mahasiswa::join('student_record','absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                            ->join('kelas','student.idstatus', '=', 'kelas.idkelas')
                            ->join('angkatan','student.idangkatan', '=', 'angkatan.idangkatan')
                            ->where('absensi_mahasiswa.id_bap', $id)
                            ->select('student_record.id_kurperiode','absensi_mahasiswa.id_absensi','angkatan.angkatan','kelas.kelas','prodi.prodi', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'absensi_mahasiswa.absensi')
                            ->get();

    foreach ($abs as $key) {
      # code...
    }
    $idk = $key->id_kurperiode;

    return view('kaprodi/bap/edit_absen', ['idk'=>$idk,'abs'=>$abs, 'id'=>$id]);
  }

  public function save_edit_absensi(Request $request)
  {
    $id_record  = $request->id_studentrecord;
    $jmlrecord  = count($id_record);

    $id_bp      = $request->id_bap;
    $absen      = $request->absensi;
    $jmlabsen   = count($request->absensi);

    for ($i=0; $i < $jmlrecord; $i++) {
      $kurp = $request->id_studentrecord[$i];
      $idr = explode(',',$kurp);
      $tra = $idr[0];

      $cek = Absensi_mahasiswa::where('id_studentrecord', $tra)
                              ->where('id_bap', $id_bp)
                              ->update(['absensi'=>'HADIR', 'updated_by'=>Auth::user()->name]);


    }

    for ($i=0; $i < $jmlabsen; $i++) {
      $abs    = $request->absensi[$i];
      $idab   = explode(',',$abs, 2);
      $trsen  = $idab[0];
      $trsi   = $idab[1];

      Absensi_mahasiswa::where('id_absensi', $trsen)
                      ->update(['absensi' => $trsi, 'updated_by'=>Auth::user()->name]);
    }

    $bp = Bap::where('id_bap', $id_bp)
                  ->update(['hadir'=>$jmlabsen]);

    $bp = Bap::where('id_bap', $id_bp)
                  ->update(['tidak_hadir'=>$jmlrecord-$jmlabsen]);

    $kur = Bap::where('id_bap', $id_bp)
                ->select('id_kurperiode')
                ->get();

    foreach ($kur as $kui) {
      # code...
    }
    $id_kur = $kui->id_kurperiode;

    Alert::success('', 'Absen berhasil diedit')->autoclose(3500);

    return redirect('entri_bap_kprd/'.$id_kur);
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


    return view('kaprodi/bap/view_bap', ['prd'=>$prd, 'tipe'=>$tipe, 'tahun'=>$tahun, 'data'=>$data, 'dtbp'=>$dtbp]);
  }

  public function cetak($id)
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

    return view('dosen/cetak_bap', ['d' => $d,'m' => $m,'y' => $y,'prd'=>$prd, 'tipe'=>$tipe, 'tahun'=>$tahun, 'data'=>$data, 'dtbp'=>$dtbp]);
  }

  public function edit_bap($id)
  {
    $bap = Bap::where('id_bap', $id)->get();
    foreach ($bap as $key_bap) {
      # code...
    }
    return view('kaprodi/bap/edit_bap', ['id'=>$id, 'bap'=>$key_bap]);
  }

  public function simpanedit_bap(Request $request, $id)
  {
    $this->validate($request, [
      'pertemuan'               => 'required',
      'tanggal'                 => 'required',
      'jam_mulai'               => 'required',
      'jam_selsai'              => 'required',
      'jenis_kuliah'            => 'required',
      'id_tipekuliah'           => 'required',
      'metode_kuliah'           => 'required',
      'materi_kuliah'           => 'required',
      'file_kuliah_tatapmuka'   => 'mimes:jpg,jpeg|max:2000',
      'file_materi_kuliah'      => 'mimes:jpg,jpeg,pdf|max:2000',
      'file_materi_tugas'       => 'mimes:jpg,jpeg|max:2000',

      ]);

      $bap                        = Bap::find($id);
      $bap->id_kurperiode         = $request->id_kurperiode;
      $bap->pertemuan             = $request->pertemuan;
      $bap->tanggal               = $request->tanggal;
      $bap->jam_mulai             = $request->jam_mulai;
      $bap->jam_selsai            = $request->jam_selsai;
      $bap->jenis_kuliah          = $request->jenis_kuliah;
      $bap->id_tipekuliah         = $request->id_tipekuliah;
      $bap->metode_kuliah         = $request->metode_kuliah;
      $bap->materi_kuliah         = $request->materi_kuliah;
      $bap->media_pembelajaran    = $request->media_pembelajaran;
      $bap->updated_by            = Auth::user()->name;

      if ($bap->file_kuliah_tatapmuka) {
        if ($request->hasFile('file_kuliah_tatapmuka')) {
          File::delete('File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Kuliah Tatap Muka/'.$bap->file_kuliah_tatapmuka);
          $file                               = $request->file('file_kuliah_tatapmuka');
          $nama_file                          = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
          $tujuan_upload                      = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Kuliah Tatap Muka';
          $file->move($tujuan_upload,$nama_file);
          $bap->file_kuliah_tatapmuka        = $nama_file;
        }
      }else {
        if ($request->hasFile('file_kuliah_tatapmuka')) {
          $file                               = $request->file('file_kuliah_tatapmuka');
          $nama_file                          = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
          $tujuan_upload                      = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Kuliah Tatap Muka';
          $file->move($tujuan_upload,$nama_file);
          $bap->file_kuliah_tatapmuka         = $nama_file;
        }
      }

      if ($bap->file_materi_kuliah) {
        if ($request->hasFile('file_materi_kuliah')) {
          File::delete('File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Materi Kuliah/'.$bap->file_materi_kuliah);
          $file                               = $request->file('file_materi_kuliah');
          $nama_file                          = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
          $tujuan_upload                      = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Materi Kuliah';
          $file->move($tujuan_upload,$nama_file);
          $bap->file_materi_kuliah        = $nama_file;
        }
      }else {
        if ($request->hasFile('file_materi_kuliah')) {
          $file                               = $request->file('file_materi_kuliah');
          $nama_file                          = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
          $tujuan_upload                      = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Materi Kuliah';
          $file->move($tujuan_upload,$nama_file);
          $bap->file_materi_kuliah            = $nama_file;
        }
      }

      if ($bap->file_materi_tugas) {
        if ($request->hasFile('file_materi_tugas')) {
          File::delete('File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Tugas Kuliah/'.$bap->file_materi_tugas);
          $file                               = $request->file('file_materi_tugas');
          $nama_file                          = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
          $tujuan_upload                      = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Tugas Kuliah';
          $file->move($tujuan_upload,$nama_file);
          $bap->file_materi_tugas        = $nama_file;
        }
      }else {
        if ($request->hasFile('file_materi_tugas')) {
          $file                               = $request->file('file_materi_tugas');
          $nama_file                          = 'Pertemuan Ke-'.$request->pertemuan."_".$file->getClientOriginalName();
          $tujuan_upload                      = 'File_BAP/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Tugas Kuliah';
          $file->move($tujuan_upload,$nama_file);
          $bap->file_materi_tugas            = $nama_file;
        }
      }


      $bap->save();

      Kuliah_transaction::where('id_bap', $id)
                        ->update(['id_tipekuliah'=>$request->id_tipekuliah]);

      Kuliah_transaction::where('id_bap', $id)
                        ->update(['tanggal'=>$request->tanggal]);

      Kuliah_transaction::where('id_bap', $id)
                        ->update(['akt_jam_mulai'=>$request->jam_mulai]);

      Kuliah_transaction::where('id_bap', $id)
                        ->update(['akt_jam_selesai'=>$request->jam_selsai]);

      return redirect('entri_bap_kprd/'.$request->id_kurperiode);
  }

  public function delete_bap($id)
  {
    Bap::where('id_bap', $id)
        ->update(['status'=>'NOT ACTIVE']);

    Kuliah_transaction::where('id_bap', $id)
                      ->update(['status'=>'NOT ACTIVE']);

    Absensi_mahasiswa::where('id_bap', $id)
                    ->update(['status'=>'NOT ACTIVE']);

    $idk = Bap::where('id_bap', $id)
              ->select('id_kurperiode')
              ->get();

    foreach ($idk as $key) {
      # code...
    }

    return redirect('entri_bap_kprd/'.$key->id_kurperiode);
  }

  public function sum_absen($id)
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

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
                            ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 2)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 1)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 3)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 4)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 5)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 6)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 7)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 8)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 9)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 10)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 11)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 12)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 13)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 14)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 15)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 16)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    return view ('kaprodi/bap/absensi_perkuliahan', ['abs16'=>$abs16,'abs15'=>$abs15,'abs14'=>$abs14,'abs13'=>$abs13,'abs12'=>$abs12,'abs11'=>$abs11,'abs10'=>$abs10,'abs9'=>$abs9,'abs8'=>$abs8,'abs7'=>$abs7,'abs6'=>$abs6,'abs5'=>$abs5,'abs4'=>$abs4,'abs'=>$abs,'abs1'=>$abs1,'abs2'=>$abs2,'abs3'=>$abs3, 'bap'=>$key]);
  }

  public function print_absensi($id)
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

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
                            ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 2)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 1)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 3)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 4)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 5)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 6)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 7)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 8)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 9)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 10)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 11)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 12)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 13)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 14)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 15)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 16)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

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
    $m = $bulan[date('m')];
    $y = date('Y');

    return view ('kaprodi/bap/cetak_absensi', ['d' => $d, 'm' => $m, 'y' => $y, 'abs16'=>$abs16,'abs15'=>$abs15,'abs14'=>$abs14,'abs13'=>$abs13,'abs12'=>$abs12,'abs11'=>$abs11,'abs10'=>$abs10,'abs9'=>$abs9,'abs8'=>$abs8,'abs7'=>$abs7,'abs6'=>$abs6,'abs5'=>$abs5,'abs4'=>$abs4,'abs'=>$abs,'abs1'=>$abs1,'abs2'=>$abs2,'abs3'=>$abs3, 'bap'=>$key]);
  }

  public function jurnal_bap($id)
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
                ->select('kuliah_transaction.val_jam_selesai','kuliah_transaction.val_jam_mulai','kuliah_transaction.tanggal_validasi','kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

    return view ('kaprodi/bap/jurnal_perkuliahan', ['bap'=>$key, 'data'=>$data]);
  }

  public function print_jurnal($id)
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

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
                      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
                      ->where('prodi.prodi', $key->prodi)
                      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
                      ->first();

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.val_jam_selesai','kuliah_transaction.val_jam_mulai','kuliah_transaction.tanggal_validasi','kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

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

    return view ('kaprodi/bap/cetak_jurnal', ['cekkprd'=>$cekkprd, 'd' => $d,'m' => $m,'y' => $y,'bap'=>$key, 'data'=>$data]);
  }

  public function history_makul_dsn()
  {
    $id = Auth::user()->username;
    $dsn = Dosen::where('nik', $id)->get();
    foreach ($dsn as $keydsn) {
        # code...
    }
    $iddsn = $keydsn->iddosen;
    $tp = Periode_tipe::where('status', 'ACTIVE')->get();
    foreach ($tp as $tipe) {
      // code...
    }
    $tp = $tipe->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->get();

    foreach ($thn as $tahun) {
      // code...
    }
    $thn = $tahun->id_periodetahun;
    $mk = Matakuliah::all();
    $prd = Prodi::all();
    $kls = Kelas::all();
    $smt = Semester::all();
    $prd_tahun = Periode_tahun::all();
    $prd_tipe = Periode_tipe::all();
    $kur = Kurikulum_master::where('status', 'ACTIVE')->get();
    foreach ($kur as $krlm) {
        // code...
    }


    $mkul = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun','=', 'periode_tahun.id_periodetahun')
                            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                            ->where('kurikulum_periode.id_dosen', $iddsn)
                            ->where('kurikulum_periode.status','ACTIVE')
                            ->select('periode_tipe.periode_tipe','periode_tahun.periode_tahun','kurikulum_periode.id_kurperiode','matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek')
                            ->orderBy('kurikulum_periode.id_periodetahun', 'DESC')
                            ->get();

    return view('kaprodi/record/history_makul_dsn', ['prd_tipe'=>$prd_tipe,'prd_tahun'=>$prd_tahun,'makul'=>$mkul, 'mk'=>$mk, 'prd'=>$prd, 'kls'=>$kls, 'smt'=>$smt]);
  }

  public function cekmhs_dsn_his($id)
  {
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $id)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

      $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $id)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
      foreach ($ckstr as $str) {
        # code...
      }
      $kur =$str->id_kurtrans;

      return view('kaprodi/record/list_mhs_dsn_his', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$id, 'kur'=>$kur]);
  }

  public function view_bap_his($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul','=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                            ->where('kurikulum_periode.id_kurperiode', $id)
                            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                            ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->get();

    return view ('kaprodi/record/view_bap_his',['bap'=>$key, 'data'=>$data]);
  }

  public function sum_absen_his($id)
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

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
                            ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 2)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 1)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 3)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 4)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 5)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 6)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 7)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 8)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 9)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 10)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 11)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 12)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 13)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 14)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 15)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 16)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    return view ('kaprodi/record/absensi_perkuliahan_his', ['abs16'=>$abs16,'abs15'=>$abs15,'abs14'=>$abs14,'abs13'=>$abs13,'abs12'=>$abs12,'abs11'=>$abs11,'abs10'=>$abs10,'abs9'=>$abs9,'abs8'=>$abs8,'abs7'=>$abs7,'abs6'=>$abs6,'abs5'=>$abs5,'abs4'=>$abs4,'abs'=>$abs,'abs1'=>$abs1,'abs2'=>$abs2,'abs3'=>$abs3, 'bap'=>$key]);
  }

  public function jurnal_bap_his($id)
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
                ->select('kuliah_transaction.val_jam_selesai','kuliah_transaction.val_jam_mulai','kuliah_transaction.tanggal_validasi','kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

    return view ('kaprodi/record/jurnal_perkuliahan_his', ['bap'=>$key, 'data'=>$data]);
  }

  public function data_ipk_kprd()
  {
    $ipk = DB::select("CALL getIpkMhs()");

    $angkatan = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                              ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                              ->where('student.active', 1)
                              ->select( DB::raw('DISTINCT(student.idangkatan)'), 'angkatan.angkatan')
                              ->orderBy('angkatan.angkatan', 'ASC')
                              ->get();

    $prodi = Prodi::all();

    return view ('kaprodi/master/data_ipk', compact('ipk', 'angkatan','prodi'));
  }

  public function export_nilai_ipk_kprd()
  {
    $nama_file = 'Nilai IPK Mahasiswa.xlsx';

    return Excel::download(new DataNilaiIpkMhsExport,$nama_file);
  }

  public function filter_ipk_mhs(Request $request)
  {
    $angkatan = $request->id_angkatan;
    $prodi = $request->kodeprodi;
    $ipk = DB::select('CALL filterIpk(?,?)',array($angkatan,$prodi));

    return view('kaprodi/master/filter_ipk_mhs', compact('ipk', 'angkatan', 'prodi'));
  }

  public function export_nilai_ipk_prodi(Request $request)
  {
    $angkatan = Angkatan::where('idangkatan', $request->id_angkatan)
                        ->select('angkatan', 'idangkatan')
                        ->first();
    $nama_angkatan = $angkatan->angkatan;
    $id_angkatan = $angkatan->idangkatan;

    $prodi = Prodi::where('kodeprodi', $request->kodeprodi)
                  ->select('prodi', 'kodeprodi')
                  ->first();
    $nama_prodi = $prodi->prodi;
    $id_prodi = $prodi->kodeprodi;

    $nama_file = 'Nilai IPK Mahasiswa Angkatan '.$nama_angkatan.' '.$nama_prodi.'.xlsx';

    return Excel::download(new DataNilaiIpkMhsProdiExport($id_angkatan,$id_prodi),$nama_file);
  }

  public function nilai_mhs_kprd()
  {
    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;

    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
                      ->select('id_prodi')
                      ->first();
    $prodi = $iddosen->id_prodi;

    $nilai = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                              ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
                              ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                              ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                              ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                              ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                              ->where('kurikulum_periode.id_periodetipe', $tipe)
                              ->where('kurikulum_periode.id_periodetahun', $tahun)
                              ->where('kurikulum_periode.status', 'ACTIVE')
                              ->where('kurikulum_periode.id_prodi', $prodi)
                              ->select('matakuliah.kode','matakuliah.makul','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek',DB::raw('COUNT(student_record.id_student) as jml_mhs'),'dosen.nama', 'kelas.kelas','student_record.id_kurperiode','prodi.prodi')
                              ->groupBy('matakuliah.kode','matakuliah.makul','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek','dosen.nama', 'kelas.kelas','student_record.id_kurperiode','prodi.prodi')
                              ->get();

    return view('kaprodi/master/rekap_nilai', compact('nilai'));
  }

  public function cek_nilai_mhs_kprd($id)
  {
    $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                          ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                          ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                          ->where('student_record.id_kurperiode', $id)
                          ->where('student_record.status','TAKEN')
                          ->select('student.nim','student.nama','prodi.prodi','kelas.kelas','angkatan.angkatan','student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

    return view('kaprodi/master/cek_nilai_mhs', compact('data'));
  }

  public function soal_uts_kprd()
  {
    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
                      ->select('id_prodi')
                      ->first();
    $prodi = $iddosen->id_prodi;

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;

    $soal = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
              ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
              ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
              ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
              ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
              ->where('kurikulum_periode.id_periodetipe', $tipe)
              ->where('kurikulum_periode.id_periodetahun', $tahun)
              ->where('kurikulum_periode.status', 'ACTIVE')
              ->where('kurikulum_periode.id_prodi', $prodi)
              ->where('bap.jenis_kuliah', 'UTS')
              ->select('bap.id_bap','matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'prodi.prodi', 'kelas.kelas', 'dosen.nama', 'bap.file_materi_kuliah', 'dosen.iddosen', 'bap.id_kurperiode')
              ->get();

    return view('kaprodi/master/cek_soal_uts', compact('soal'));
  }

  public function soal_uas_kprd()
  {
    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
                      ->select('id_prodi')
                      ->first();
    $prodi = $iddosen->id_prodi;

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;

    $soal = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
              ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
              ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
              ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
              ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
              ->where('kurikulum_periode.id_periodetipe', $tipe)
              ->where('kurikulum_periode.id_periodetahun', $tahun)
              ->where('kurikulum_periode.status', 'ACTIVE')
              ->where('kurikulum_periode.id_prodi', $prodi)
              ->where('bap.jenis_kuliah', 'UAS')
              ->select('bap.id_bap','matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'prodi.prodi', 'kelas.kelas', 'dosen.nama', 'bap.file_materi_kuliah', 'dosen.iddosen', 'bap.id_kurperiode')
              ->get();

    return view('kaprodi/master/cek_soal_uas', compact('soal'));
  }

  public function input_kat_kprd($id)
  {
    $mhs = Student::all();
    $prd = Prodi::all();
    $kls = Kelas::all();
    $angk = Angkatan::all();
    //cek mahasiswa
    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->where('id_kurperiode', $id)
                        ->where('student_record.status','TAKEN')
                        ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT')
                        ->get();
    $kurrr = $id;
    return view('kaprodi/matakuliah/input_kat_dsn', ['kuri'=>$kurrr,'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
  }

  public function save_nilai_KAT_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_KAT;

    $jml = count($jmlnil);

    for ($i=0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
                              ->where('id_kurtrans', $kur)
                              ->select('id_studentrecord')
                              ->get();

      $banyak = count($cekid);

      $nilai = $request->nilai_KAT[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_KAT   = 0;
          $entry->save();
        }elseif ($ceknl != null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_KAT   = $nilai;
          $entry->save();
        }

      }elseif ($banyak > 1) {

          if ($ceknl == null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_KAT' => 0]);

          }elseif ($ceknl != null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_KAT' => $nilai]);

          }
      }
    }


    //ke halaman list mahasiswa
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $request->id_kurperiode)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

      $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
      foreach ($ckstr as $str) {
        # code...
      }
      $kur =$str->id_kurtrans;
      $idkur = $request->id_kurperiode;
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$idkur, 'kur'=>$kur]);
  }

  public function input_uts_kprd($id)
  {
    $mhs = Student::all();
    $prd = Prodi::all();
    $kls = Kelas::all();
    $angk = Angkatan::all();
    //cek mahasiswa
    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->where('id_kurperiode', $id)
                        ->where('student_record.status','TAKEN')
                        ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_UTS')
                        ->get();

    $mkl = Kurikulum_periode::where('id_kurperiode', $id)->get();

      foreach ($mkl as $keymkl) {
        # code...
      }
      $kmkl = $keymkl->id_makul;
      $kprd = $keymkl->id_prodi;
      $kkls = $keymkl->id_kelas;
      $kurrr = $id;

    return view('kaprodi/matakuliah/input_uts_dsn', ['kuri'=>$kurrr,'kkls'=>$kkls,'kprd'=>$kprd,'mkl'=>$kmkl,'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
  }

  public function save_nilai_UTS_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_UTS;

    $jml = count($jmlnil);

    for ($i=0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
                              ->where('id_kurtrans', $kur)
                              ->select('id_studentrecord')
                              ->get();

      $banyak = count($cekid);

      $nilai = $request->nilai_UTS[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UTS   = 0;
          $entry->save();
        }elseif ($ceknl != null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UTS   = $nilai;
          $entry->save();
        }

      }elseif ($banyak > 1) {

          if ($ceknl == null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_UTS' => 0]);

          }elseif ($ceknl != null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_UTS' => $nilai]);

          }
      }
    }

    $thn = Periode_tahun::where('status', 'ACTIVE')->get();
      foreach ($thn as $tahun) {
        // code...
      }

    $tp = Periode_tipe::where('status', 'ACTIVE')->get();
      foreach ($tp as $tipe) {
        // code...
      }

      Ujian_transaction::where('id_periodetahun', $tahun->id_periodetahun)
                        ->where('id_periodetipe', $tipe->id_periodetipe)
                        ->where('jenis_ujian', 'UTS')
                        ->where('id_prodi', $request->id_prodi)
                        ->where('id_kelas', $request->id_kelas)
                        ->where('id_makul', $request->id_makul)
                        ->update(['aktual_pengoreksi' => Auth::user()->name]);

    //ke halaman list mahasiswa
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $request->id_kurperiode)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

      $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
      foreach ($ckstr as $str) {
        # code...
      }
      $kur =$str->id_kurtrans;
      $idkur = $request->id_kurperiode;
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$idkur, 'kur'=>$kur]);
  }

  public function input_uas_kprd($id)
  {
    $mhs = Student::all();
    $prd = Prodi::all();
    $kls = Kelas::all();
    $angk = Angkatan::all();
    //cek mahasiswa
    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->where('id_kurperiode', $id)
                        ->where('student_record.status','TAKEN')
                        ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_UAS')
                        ->get();

    $mkl = Kurikulum_periode::where('id_kurperiode', $id)->get();

    foreach ($mkl as $keymkl) {
      # code...
    }
    $kmkl = $keymkl->id_makul;
    $kprd = $keymkl->id_prodi;
    $kkls = $keymkl->id_kelas;
    $kurrr = $id;

    return view('kaprodi/matakuliah/input_uas_dsn', ['kuri'=>$kurrr,'kkls'=>$kkls,'kprd'=>$kprd,'mkl'=>$kmkl, 'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
  }

  public function save_nilai_UAS_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_UAS;

    $jml = count($jmlnil);

    for ($i=0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
                              ->where('id_kurtrans', $kur)
                              ->select('id_studentrecord')
                              ->get();

      $banyak = count($cekid);

      $nilai = $request->nilai_UAS[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UAS   = 0;
          $entry->save();

        }elseif ($ceknl != null) {
          $id                 = $id_kur;
          $entry              = Student_record::find($id);
          $entry->nilai_UAS   = $nilai;
          $entry->save();
        }

      }elseif ($banyak > 1) {

          if ($ceknl == null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_UAS' => 0]);

          }elseif ($ceknl != null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_UAS' => $nilai]);

          }
      }
    }
    $thn = Periode_tahun::where('status', 'ACTIVE')->get();
      foreach ($thn as $tahun) {
        // code...
      }

    $tp = Periode_tipe::where('status', 'ACTIVE')->get();
      foreach ($tp as $tipe) {
        // code...
      }

       Ujian_transaction::where('id_periodetahun', $tahun->id_periodetahun)
                        ->where('id_periodetipe', $tipe->id_periodetipe)
                        ->where('jenis_ujian', 'UAS')
                        ->where('id_prodi', $request->id_prodi)
                        ->where('id_kelas', $request->id_kelas)
                        ->where('id_makul', $request->id_makul)
                        ->update(['aktual_pengoreksi' => Auth::user()->name]);


    //ke halaman list mahasiswa
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $request->id_kurperiode)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

      $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
      foreach ($ckstr as $str) {
        # code...
      }
      $kur =$str->id_kurtrans;
      $idkur = $request->id_kurperiode;
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$idkur, 'kur'=>$kur]);
  }

  public function input_akhir_kprd($id)
  {
    $mhs = Student::all();
    $prd = Prodi::all();
    $kls = Kelas::all();
    $angk = Angkatan::all();
    //cek mahasiswa
    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                        ->where('id_kurperiode', $id)
                        ->where('student_record.status','TAKEN')
                        ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                        ->get();
    $kurrr = $id;

    return view('kaprodi/matakuliah/input_akhir_dsn', ['kuri'=>$kurrr,'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
  }

  public function save_nilai_AKHIR_kprd(Request $request)
  {
    $jumlahid = $request->id_student;
    $jmlids = $request->id_studentrecord;
    $jmlnil = $request->nilai_AKHIR_angka;
    $jml = count($jmlnil);
    for ($i=0; $i < $jml; $i++) {
      $idstu = $request->id_student[$i];
      $pisah = explode(',', $idstu, 2);
      $stu = $pisah[0];
      $kur = $pisah[1];

      $cekid = Student_record::where('id_student', $stu)
                              ->where('id_kurtrans', $kur)
                              ->select('id_studentrecord')
                              ->get();
      $banyak = count($cekid);

      $nilai = $request->nilai_AKHIR_angka[$i];
      $id_kur = $request->id_studentrecord[$i];
      $ceknl = $nilai;

      if ($banyak == 1) {

        if ($ceknl == null) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR_angka = 0;
          $ceknilai->save();
        }elseif ($ceknl != null) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR_angka = $nilai;
          $ceknilai->save();
        }

        if ($ceknl < 50) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'E';
          $ceknilai->nilai_ANGKA = '0';
          $ceknilai->save();
        }elseif ($ceknl < 60) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'D';
          $ceknilai->nilai_ANGKA = '1';
          $ceknilai->save();
        }elseif ($ceknl < 65) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'C';
          $ceknilai->nilai_ANGKA = '2';
          $ceknilai->save();
        }elseif ($ceknl < 70) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'C+';
          $ceknilai->nilai_ANGKA = '2.5';
          $ceknilai->save();
        }elseif ($ceknl < 75) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'B';
          $ceknilai->nilai_ANGKA = '3';
          $ceknilai->save();
        }elseif ($ceknl < 80) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'B+';
          $ceknilai->nilai_ANGKA = '3.5';
          $ceknilai->save();
        }elseif ($ceknl <= 100) {
          $id = $id_kur;
          $ceknilai = Student_record::find($id);
          $ceknilai->nilai_AKHIR = 'A';
          $ceknilai->nilai_ANGKA = '4';
          $ceknilai->save();
        }

      }elseif ($banyak > 1) {
        if ($ceknl == null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR_angka' => 0]);

          }elseif ($ceknl != null) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR_angka' => $nilai]);

        }

        if ($ceknl < 50) {
          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'E']);

          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '0']);

        }elseif ($ceknl < 60) {
          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'D']);

          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '1']);
        }elseif ($ceknl < 65) {
          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'C']);

          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '2']);
        }elseif ($ceknl < 70) {
          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'C+']);

          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '2.5']);
        }elseif ($ceknl < 75) {
          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'B']);

          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '3']);
        }elseif ($ceknl < 80) {
          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'B+']);

          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '3.5']);
        }elseif ($ceknl <= 100) {
          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'A']);

          Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '4']);
        }
      }
    }

    //ke halaman list mahasiswa
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $request->id_kurperiode)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

      $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
      foreach ($ckstr as $str) {
        # code...
      }
      $kur =$str->id_kurtrans;
      $idkur = $request->id_kurperiode;
    return view('kaprodi/matakuliah/list_mhs_dsn', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$idkur, 'kur'=>$kur]);
  }

  public function rekap_perkuliahan()
  {
    $iduser = Auth::user()->id_user;
    $iddosen = Kaprodi::where('id_dosen', $iduser)
                      ->select('id_prodi')
                      ->first();
    $prodi = $iddosen->id_prodi;

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;


    $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                              ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                              ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                              ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                              ->where('kurikulum_periode.id_periodetipe', $tipe)
                              ->where('kurikulum_periode.id_periodetahun', $tahun)
                              ->where('kurikulum_periode.id_prodi', $prodi)
                              ->where('kurikulum_periode.status', 'ACTIVE')
                              ->select('matakuliah.kode','matakuliah.makul','matakuliah.akt_sks_teori','matakuliah.akt_sks_praktek','dosen.nama', 'kelas.kelas','kurikulum_periode.id_kurperiode','prodi.prodi')
                              ->get();

    $jml = Kurikulum_periode::join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
                            ->where('kurikulum_periode.id_periodetipe', $tipe)
                            ->where('kurikulum_periode.id_periodetahun', $tahun)
                            ->where('kurikulum_periode.status', 'ACTIVE')
                            ->where('bap.status', 'ACTIVE')
                            ->select(DB::raw('COUNT(bap.id_kurperiode) as jml_per'), 'bap.id_kurperiode')
                            ->groupBy('bap.id_kurperiode')
                            ->get();

    return view ('kaprodi/perkuliahan/rekap_perkuliahan', compact('data', 'jml'));
  }

  public function cek_rekapan($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul','=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                            ->where('kurikulum_periode.id_kurperiode', $id)
                            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                            ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.kurang_jam','kuliah_transaction.tanggal_validasi','kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->get();


    return view ('kaprodi/perkuliahan/cek_bap', compact('key', 'data'));
  }

  public function cek_sum_absen($id)
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

    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
                            ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 2)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 1)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 3)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 4)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 5)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 6)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 7)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 8)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 9)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 10)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 11)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 12)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 13)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 14)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 15)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 16)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    return view ('kaprodi/perkuliahan/cek_absensi_perkuliahan', ['abs16'=>$abs16,'abs15'=>$abs15,'abs14'=>$abs14,'abs13'=>$abs13,'abs12'=>$abs12,'abs11'=>$abs11,'abs10'=>$abs10,'abs9'=>$abs9,'abs8'=>$abs8,'abs7'=>$abs7,'abs6'=>$abs6,'abs5'=>$abs5,'abs4'=>$abs4,'abs'=>$abs,'abs1'=>$abs1,'abs2'=>$abs2,'abs3'=>$abs3, 'bap'=>$key]);
  }

  public function cek_print_absensi($id)
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

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
                      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
                      ->where('prodi.prodi', $key->prodi)
                      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
                      ->first();


    $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
                            ->get();

    $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 2)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 1)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 3)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 4)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 5)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 6)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 7)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 8)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 9)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 10)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 11)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 12)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 13)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 14)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 15)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 16)
                            ->select('absensi_mahasiswa.absensi','absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

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
    $m = $bulan[date('m')];
    $y = date('Y');

    return view ('kaprodi/perkuliahan/cek_cetak_absensi', ['cekkprd'=>$cekkprd,'d' => $d, 'm' => $m, 'y' => $y, 'abs16'=>$abs16,'abs15'=>$abs15,'abs14'=>$abs14,'abs13'=>$abs13,'abs12'=>$abs12,'abs11'=>$abs11,'abs10'=>$abs10,'abs9'=>$abs9,'abs8'=>$abs8,'abs7'=>$abs7,'abs6'=>$abs6,'abs5'=>$abs5,'abs4'=>$abs4,'abs'=>$abs,'abs1'=>$abs1,'abs2'=>$abs2,'abs3'=>$abs3, 'bap'=>$key]);
  }

  public function cek_jurnal_bap_kprd($id)
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
                            ->select('kurikulum_periode.id_dosen_2','kurikulum_periode.akt_sks_praktek','kurikulum_periode.akt_sks_teori','kurikulum_periode.id_kelas','periode_tipe.periode_tipe','periode_tahun.periode_tahun','dosen.akademik','dosen.nama','ruangan.nama_ruangan','kurikulum_jam.jam','kurikulum_hari.hari',DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'),'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                            ->get();
    foreach ($bap as $key) {
      # code...
    }

    $dosen2 = Dosen::where('iddosen', $key->id_dosen_2)->get();
    foreach ($dosen2 as $keydsn) {
      // code...
    }
    if (count($dosen2) > 0) {
      $nama_dsn2 = $keydsn->nama.', '.$keydsn->akademik;
    }else {
      $nama_dsn2 = '';
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.val_jam_selesai','kuliah_transaction.val_jam_mulai','kuliah_transaction.tanggal_validasi','kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

    return view ('kaprodi/perkuliahan/cek_jurnal_perkuliahan', ['nama_dosen_2'=>$nama_dsn2,'bap'=>$key, 'data'=>$data]);
  }

  public function print_jurnal_cek_kprd($id)
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

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
                      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
                      ->where('prodi.prodi', $key->prodi)
                      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
                      ->first();

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.val_jam_selesai','kuliah_transaction.val_jam_mulai','kuliah_transaction.tanggal_validasi','kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

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

    return view ('kaprodi/perkuliahan/cek_cetak_jurnal', ['cekkprd'=>$cekkprd, 'd' => $d,'m' => $m,'y' => $y,'bap'=>$key, 'data'=>$data]);
  }

  public function cek_view_bap($id)
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


    return view('kaprodi/perkuliahan/view_bap', ['prd'=>$prd, 'tipe'=>$tipe, 'tahun'=>$tahun, 'data'=>$data, 'dtbp'=>$dtbp]);
  }

  public function cek_print_bap($id)
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

    return view('kaprodi/perkuliahan/cetak_bap', ['d' => $d,'m' => $m,'y' => $y,'prd'=>$prd, 'tipe'=>$tipe, 'tahun'=>$tahun, 'data'=>$data, 'dtbp'=>$dtbp]);
  }
}
