<?php

namespace App\Http\Controllers;

use Alert;
use App\User;
use App\Dosen;
use App\Student;
use App\Semester;
use App\Matakuliah;
use App\Kurikulum_master;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Student_record;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Waktu_edom;
use App\Edom_master;
use App\Edom_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EdomController extends Controller
{
  public function edom()
  {
    $tahun = Periode_tahun::where('status', 'ACTIVE')->get();

    $tipe = Periode_tipe::where('status', 'ACTIVE')->get();

    $edom = Waktu_edom::all();
    foreach ($edom as $keyedom) {
      // code...
    }

    $ldate = date('m/d/Y');

    return view('sadmin/edom', ['now' => $ldate, 'edom' => $keyedom, 'tahun' => $tahun, 'tipe' => $tipe]);
  }

  public function simpanedom(Request $request)
  {
    $cektgl = strtotime($request->waktu_akhir);
    $cektglawal = strtotime('now');

    if ($cektgl < $cektglawal) {

      Alert::error('maaf waktu salah', 'maaf');
      return redirect()->back();
    } else {
      $id = $request->id;
      $time_nya = Waktu_edom::find($id);
      $time_nya->waktu_awal = $request->waktu_awal;
      $time_nya->waktu_akhir = $request->waktu_akhir;
      $time_nya->status = $request->status;
      $time_nya->save();

      Alert::success('Pembukaan Edom', 'Berhasil')->autoclose(3500);
      return redirect()->back();
    }
  }

  public function edit_edom(Request $request)
  {
    $this->validate($request, [
      'status' => 'required',
      'id' => 'required',
    ]);

    $id = $request->id;
    $edom = Waktu_edom::find($id);
    $edom->waktu_awal = $request->waktu_awal;
    $edom->waktu_akhir = $request->waktu_akhir;
    $edom->status = $request->status;
    $edom->save();

    Alert::success('Penutupan Edom', 'Berhasil');
    return redirect('edom');
  }

  public function isi_edom()
  {
    $waktu_edom = Waktu_edom::all();
    foreach ($waktu_edom as $edom) {
      // code...
    }

    if ($edom->status == 1) {

      $id = Auth::user()->username;
      $maha = Student::where('nim', $id)->get();
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

      $sub_thn = substr($tahun->periode_tahun, 6, 2);
      $tp = $tipe->id_periodetipe;
      $smt = $sub_thn . $tp;
      $angk = $key->idangkatan;

      if ($smt % 2 != 0) {
        $a = (($smt + 10) - 1) / 10;
        $b = $a - $angk;
        $c = ($b * 2) - 1;
      } else {
        $a = (($smt + 10) - 2) / 10;
        $b = $a - $angk;
        $c = $b * 2;
      }

      $semester = Semester::all();
      $makul = Matakuliah::all();
      $dosen = Dosen::all();

      $latestPosts = DB::table('student_record')
        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        ->select('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen', 'student_record.id_student', DB::raw('MAX(student_record.id_kurtrans) as id_kurtrans'), DB::raw('MAX(student_record.id_kurperiode) as id_kurperiode'))
        ->where('student_record.id_student', $key->idstudent)
        // ->where('kurikulum_periode.id_semester', $c)
        ->where('kurikulum_periode.id_periodetipe', $tp)
        ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
        ->where('student_record.status', 'TAKEN')
        ->groupBy('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen', 'student_record.id_student')
        ->get();


      return view('mhs/edom/isi_edom', ['edom' => $latestPosts, 'mk' => $makul, 'dsn' => $dosen]);
    } else {

      alert()->error('Pengisian EDOM Belum dibuka', 'Maaf silahkan menghubungi bagian akademik');
      return redirect('home');
    }
  }

  public function form_edom(Request $request)
  {
    $id = $request->id_student;
    $kurper = $request->id_kurperiode;
    $kurtr = $request->id_kurtrans;
    $mk = $request->id_makul;
    $dsn = $request->id_dosen;
    $makul = Matakuliah::all();
    $dosen = Dosen::all();

    $edm = Edom_master::where('id_edom', 1)->get();

    foreach ($edm as $keydm) {
      // code...
    }



    $edom = Edom_master::orderBy('type', 'ASC')
      ->where('status', 'ACTIVE')
      ->orderBy('description', 'ASC')
      ->paginate(30);

    return view('mhs/edom/form_edom', ['keydm' => $keydm, 'dsn' => $dsn, 'edom' => $edom, 'dosen' => $dosen, 'makul' => $makul, 'mk' => $mk, 'kurtr' => $kurtr, 'kurper' => $kurper, 'ids' => $id]);
  }

  public function save_edom(Request $request)
  {
    $this->validate($request, [
      'id_student' => 'required',
      'id_kurperiode' => 'required',
      'id_kurtrans' => 'required',
      'nilai_edom' => 'required',
    ]);
    $name = Student::where('idstudent', $request->id_student)->get();
    foreach ($name as $value) {
      // code...
    }

    $nama = $value->nama;
    $nama_ok = str_replace("'", "", $nama);

    $jml = count($request->nilai_edom);
    for ($i = 0; $i < $jml; $i++) {
      $nilai = $request->nilai_edom[$i];
      $edom = explode(',', $nilai, 2);
      $idom = $edom[0];
      $nidom = $edom[1];

      $cekedom = Edom_transaction::where('id_edom', $idom)
        ->where('id_student', $request->id_student)
        ->where('id_kurperiode', $request->id_kurperiode)
        ->where('id_kurtrans', $request->id_kurtrans)
        ->get();

      if (count($cekedom) > 0) {

        Alert::warning('maaf edom mata kuliah sudah dipilih', 'MAAF !!');
        return redirect('isi_edom');
      } else {
        $isi = new Edom_transaction;
        $isi->id_edom = $idom;
        $isi->id_student = $request->id_student;
        $isi->id_kurperiode = $request->id_kurperiode;
        $isi->id_kurtrans = $request->id_kurtrans;
        $isi->nilai_edom = $nidom;

        $isi->created_by = $nama_ok;
        $isi->created_date   = date("Y-m-d h:i:s");
        $isi->save();
      }
    }
    Alert::success('', 'Pengisian EDOM anda berhasil ')->autoclose(3500);
    return redirect('isi_edom');
  }

  public function edom_kom(Request $request)
  {
    $id = $request->id_student;
    $kurper = $request->id_kurperiode;
    $kurtr = $request->id_kurtrans;
    $mk = $request->id_makul;
    $dsn = $request->id_dosen;
    $makul = Matakuliah::all();
    $dosen = Dosen::all();

    $edom_com = Edom_master::orderBy('id_edom', 'DESC')
      ->paginate(1);

    return view('mhs/edom/komentar', ['edom_com' => $edom_com, 'dsn' => $dsn, 'dosen' => $dosen, 'makul' => $makul, 'mk' => $mk, 'kurtr' => $kurtr, 'kurper' => $kurper, 'ids' => $id]);
  }

  public function save_com(Request $request)
  {
    $this->validate($request, [
      'id_student' => 'required',
      'id_kurperiode' => 'required',
      'id_kurtrans' => 'required',
    ]);

    $name = Student::where('idstudent', $request->id_student)->get();
    foreach ($name as $value) {
      // code...
    }

    $nama = $value->nama;
    $nama_ok = str_replace("'", "", $nama);

    $cekedom = Edom_transaction::where('id_edom', $request->id_edom)
      ->where('id_student', $request->id_student)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->get();

    if (count($cekedom) > 0) {

      Alert::warning('maaf komentar di edom mata kuliah ini sudah diisi', 'MAAF !!');
      return redirect('isi_edom');
    } else {
      $isi = new Edom_transaction;
      $isi->id_edom = $request->id_edom;
      $isi->id_student = $request->id_student;
      $isi->id_kurperiode = $request->id_kurperiode;
      $isi->id_kurtrans = $request->id_kurtrans;
      $isi->nilai_edom = $request->nilai_edom;

      $isi->created_by = $nama_ok;
      $isi->created_date   = date("Y-m-d h:i:s");
      $isi->save();
      Alert::success('', 'Pengisian Komentar di EDOM anda berhasil ')->autoclose(3500);
      return redirect('isi_edom');
    }
  }

  public function data_edom()
  {


    return view('sadmin/data_edom');
  }
}
