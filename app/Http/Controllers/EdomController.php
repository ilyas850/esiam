<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\User;
use App\Dosen;
use App\Student;
use App\Semester;
use App\Matakuliah;
use App\Prodi;
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

      $id = Auth::user()->id_user;

      $thn = Periode_tahun::where('status', 'ACTIVE')->first();

      $tp = Periode_tipe::where('status', 'ACTIVE')->first();

      $latestPosts = DB::table('student_record')
        ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
        ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
        ->where('student_record.id_student', $id)
        ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
        ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
        ->where('student_record.status', 'TAKEN')
        ->select('matakuliah.makul', 'matakuliah.kode', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen', 'student_record.id_student', DB::raw('MAX(student_record.id_kurtrans) as id_kurtrans'), DB::raw('MAX(student_record.id_kurperiode) as id_kurperiode'), 'dosen.nama')
        ->groupBy('matakuliah.makul', 'matakuliah.kode', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen', 'student_record.id_student', 'dosen.nama')
        ->get();

      return view('mhs/edom/isi_edom', ['edom' => $latestPosts]);
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



    $cekedom = Edom_transaction::where('id_student', $request->id_student)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->get();

    if (count($cekedom) > 0) {
      Alert::warning('maaf edom mata kuliah isi sudah diisi', 'MAAF !!');
      return redirect('isi_edom');
    } elseif (count($cekedom) == 0) {

      $makul = Matakuliah::where('idmakul', $mk)->first();

      if ($dsn == 0) {
        $dosen = '';

        $nama_dsn = '';
        $akademik = '';
      } else {
        $dosen = Dosen::where('iddosen', $dsn)->first();
        $nama_dsn = $dosen->nama;
        $akademik = $dosen->akademik;
      }



      $edm = Edom_master::where('id_edom', 1)->get();

      foreach ($edm as $keydm) {
        // code...
      }

      $edom = Edom_master::orderBy('type', 'ASC')
        ->where('status', 'ACTIVE')
        ->orderBy('description', 'ASC')
        ->paginate(30);

      return view('mhs/edom/form_edom', ['keydm' => $keydm, 'edom' => $edom, 'akademik' => $akademik, 'nama_dsn' => $nama_dsn, 'makul' => $makul, 'mk' => $mk, 'kurtr' => $kurtr, 'kurper' => $kurper, 'ids' => $id]);
    }
  }

  public function save_edom(Request $request)
  {
    $this->validate($request, [
      'id_student' => 'required',
      'id_kurperiode' => 'required',
      'id_kurtrans' => 'required',
      'nilai_edom' => 'required',
    ]);
    $mhs = Student::where('idstudent', $request->id_student)->first();

    $nama = $mhs->nama;
    $nama_ok = str_replace("'", "", $nama);

    $cekedom = Edom_transaction::where('id_student', $request->id_student)
      ->where('id_kurperiode', $request->id_kurperiode)
      ->where('id_kurtrans', $request->id_kurtrans)
      ->get();

    if (count($cekedom) > 0) {
      Alert::warning('maaf edom mata kuliah sudah dipilih', 'MAAF !!');
      return redirect('isi_edom');
    } elseif (count($cekedom) == 0) {
      $jml = count($request->nilai_edom);
      for ($i = 0; $i < $jml; $i++) {
        $nilai = $request->nilai_edom[$i];
        $edom = explode(',', $nilai, 2);
        $idom = $edom[0];
        $nidom = $edom[1];

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
    $makul = Matakuliah::where('idmakul', $mk)->first();
    $dosen = Dosen::where('iddosen', $dsn)->first();

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

  public function master_edom()
  {
    $periodetahun = Periode_tahun::orderBy('id_periodetahun', 'DESC')->get();
    $periodetipe = Periode_tipe::orderBy('id_periodetipe', 'DESC')->get();
    $prodi = Prodi::orderBy('id_prodi', 'DESC')->get();

    return view('sadmin/edom/master_edom', compact('periodetahun', 'periodetipe', 'prodi'));
  }

  public function report_edom(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $idprodi = $request->id_prodi;
    $tipe = $request->tipe_laporan;

    $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
    $prodi = Prodi::where('id_prodi', $idprodi)->first();

    $thn = $periodetahun->periode_tahun;
    $tp = $periodetipe->periode_tipe;
    $prd = $prodi->prodi;

    if ($tipe == 'by_makul') {

      $data = DB::select('CALL edom_by_makul(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));

      return view('sadmin/edom/report_edom_by_makul', compact('data', 'thn', 'tp', 'prd', 'idperiodetahun', 'idperiodetipe', 'idprodi'));
    } elseif ($tipe == 'by_dosen') {

      $data = DB::select('CALL edom_by_dosen(?,?)', array($idperiodetahun, $idperiodetipe));

      return view('sadmin/edom/report_edom_by_dosen', compact('data', 'thn', 'tp', 'idperiodetahun', 'idperiodetipe'));
    }
  }

  public function detail_edom_dosen(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $iddosen = $request->id_dosen;
    $periodetahun = $request->periodetahun;
    $periodetipe = $request->periodetipe;
    $nama = $request->nama;

    $data = DB::select('CALL detail_edom_dosen(?,?,?)', array($iddosen, $idperiodetahun, $idperiodetipe));

    return view('sadmin/edom/detail_edom_dosen', compact('data', 'nama', 'periodetahun', 'periodetipe'));
  }

  public function detail_edom_makul(Request $request)
  {
    $idkurperiode = $request->id_kurperiode;

    $data = DB::select('CALL detail_edom_makul(?)', array($idkurperiode));

    $data_mk = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_periode.id_kurperiode', $idkurperiode)
      ->select('dosen.nama', 'periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'matakuliah.makul')
      ->first();

    return view('sadmin/edom/detail_edom_makul', compact('data', 'data_mk'));
  }

  public function download_report_edom_by_makul(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $idprodi = $request->id_prodi;

    $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
    $prodi = Prodi::where('id_prodi', $idprodi)->first();

    $thn = $periodetahun->periode_tahun;
    $tp = $periodetipe->periode_tipe;
    $prd = $prodi->prodi;

    $data = DB::select('CALL edom_by_makul(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));

    $pdf = PDF::loadView('sadmin/edom/pdf_report_edom_makul', compact('data', 'thn', 'tp', 'prd'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Matakuliah' . ' ' . $thn . ' ' . $tp . ' ' . $prd . '.pdf');
  }

  public function download_report_edom_by_dosen(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;

    $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
    $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();

    $thn = $periodetahun->periode_tahun;
    $tp = $periodetipe->periode_tipe;

    $data = DB::select('CALL edom_by_dosen(?,?)', array($idperiodetahun, $idperiodetipe));

    $pdf = PDF::loadView('sadmin/edom/pdf_report_edom_dosen', compact('data', 'thn', 'tp'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Dosen' . ' ' . $thn . ' ' . $tp . '.pdf');
  }

  public function download_detail_edom_makul(Request $request)
  {
    $idkurperiode = $request->id_kurperiode;

    $data = DB::select('CALL detail_edom_makul(?)', array($idkurperiode));

    $data_mk = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
      ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_kurperiode', $idkurperiode)
      ->select('dosen.nama', 'periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'matakuliah.makul', 'kelas.kelas', 'prodi.prodi')
      ->first();

    $thn = $data_mk->periode_tahun;
    $tp = $data_mk->periode_tipe;
    $nama_mk = $data_mk->makul;
    $nama_dsn = $data_mk->nama;
    $nama_kls = $data_mk->kelas;
    $nama_prd = $data_mk->prodi;

    $pdf = PDF::loadView('sadmin/edom/pdf_detail_report_edom_makul', compact('data', 'thn', 'tp', 'nama_prd', 'nama_dsn', 'nama_mk', 'nama_kls'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Matakuliah' . ' ' . $nama_mk . ' ' . $nama_kls . '.pdf');
  }

  public function download_detail_edom_dosen(Request $request)
  {
    $idperiodetahun = $request->id_periodetahun;
    $idperiodetipe = $request->id_periodetipe;
    $iddosen = $request->id_dosen;
    $periodetahun = $request->periodetahun;
    $periodetipe = $request->periodetipe;
    $nama = $request->nama;

    $data = DB::select('CALL detail_edom_dosen(?,?,?)', array($iddosen, $idperiodetahun, $idperiodetipe));

    $pdf = PDF::loadView('sadmin/edom/pdf_detail_report_edom_dosen', compact('data', 'periodetahun', 'periodetipe', 'nama'))->setPaper('a4', 'landscape');
    return $pdf->download('Report EDOM Dosen' . ' ' . $nama . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
  }
}
