<?php

namespace App\Http\Controllers;

use Alert;
use App\Angkatan;
use App\Prodi;
use App\Student;
use App\Dosen;
use App\Kurikulum_master;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Matakuliah;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use App\Semester;
use App\Student_record;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Redirect, Response;

class ProdiController extends Controller
{
  public function dospem_pkl()
  {
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $prodi = Prodi::join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [1, 2, 3])
      ->select('prodi.kodeprodi', 'prodi.id_prodi', 'prodi.prodi', 'prausta_master_kode.id_masterkode_prausta')
      ->get();

    $data = Student::join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
      ->where('student.active', 1)
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->orderBy('student.nim', 'DESC')
      ->get();

    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2])
      ->get();

    return view('adminprodi/dospem/pkl', compact('angkatan', 'prodi', 'data', 'dosen'));
  }

  public function view_mhs_bim_pkl(Request $request)
  {
    $angkatan = $request->idangkatan;
    $prodi = $request->kodeprodi;

    $user = explode(',', $prodi, 2);
    $id1 = $user[0];
    $id2 = $user[1];

    $data = Student::where('student.idangkatan', $angkatan)
      ->where('student.kodeprodi', $id1)
      ->where('student.active', 1)
      ->orderBy('student.nim', 'ASC')
      ->get();

    // $datas = Student::leftjoin('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
    //   ->where('student.idangkatan', $angkatan)
    //   ->where('student.kodeprodi', $id1)
    //   ->where('student.active', 1)
    //   ->where(function ($query)  use ($id2) {
    //     $query->where('prausta_setting_relasi.id_masterkode_prausta', $id2)
    //       ->orWhere('prausta_setting_relasi.id_masterkode_prausta', NULL);
    //   })

    //   ->orderBy('student.nim', 'ASC')
    //   ->get();


    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2, 3])
      ->get();

    return view('adminprodi/dospem/lihat_pkl', compact('data', 'dosen', 'id2'));
  }

  public function save_dsn_bim_pkl(Request $request)
  {
    $dosen = $request->iddosen;
    $idms = $request->id_masterkode_prausta;

    $hitdsn = count($dosen);

    for ($i = 0; $i < $hitdsn; $i++) {
      $dta = $request->iddosen[$i];

      if ($dta != null) {

        $user = explode(',', $dta, 3);
        $id1 = $user[0];
        $id2 = $user[1];
        $id3 = $user[2];

        $cekmhs = Prausta_setting_relasi::where('id_student', $id1)
          ->where('id_masterkode_prausta', $idms)
          ->where('status', 'ACTIVE')
          ->get();

        if (count($cekmhs) == 0) {

          $dt = new Prausta_setting_relasi;
          $dt->id_masterkode_prausta = $idms;
          $dt->id_student = $id1;
          $dt->dosen_pembimbing = $id3;
          $dt->id_dosen_pembimbing = $id2;
          $dt->added_by = Auth::user()->name;
          $dt->status = 'ACTIVE';
          $dt->data_origin = 'eSIAM';
          $dt->save();
        } elseif (count($cekmhs) > 0) {

          $akun = Prausta_setting_relasi::where('id_student', $id1)
            ->where('id_masterkode_prausta', $idms)
            ->where('status', 'ACTIVE')
            ->update([
              'id_dosen_pembimbing' => $id2,
              'dosen_pembimbing' => $id3,
              'data_origin' => 'eSIAM'
            ]);
        }
      }
    }

    Alert::success('', 'Data Pembimbing Prakerin Berhasil Diinput')->autoclose(3500);
    return redirect('dospem_pkl');
  }

  public function put_dospem_pkl(Request $request, $id)
  {
    $dosen = $request->id_dosen_pembimbing;

    $user = explode(',', $dosen, 2);
    $id1 = $user[0];
    $id2 = $user[1];

    $prd = Prausta_setting_relasi::find($id);
    $prd->id_dosen_pembimbing = $id1;
    $prd->dosen_pembimbing = $id2;
    $prd->updated_by = $request->updated_by;
    $prd->save();

    Alert::success('', 'Berhasil diedit')->autoclose(3500);
    return redirect('dospem_pkl');
  }

  public function dospem_sempro_ta()
  {
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();

    $prodi1 = Prodi::select('prodi.kodeprodi', 'prodi.id_prodi', 'prodi.prodi')
      ->get();

    $prodi = Prodi::groupBy('kodeprodi', 'prodi')
      ->select('kodeprodi', 'prodi')
      ->get();

    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2])
      ->get();

    $data = DB::select('CALL dospem_sempro_ta()');

    return view('adminprodi/dospem/sempro_ta', compact('data', 'dosen', 'angkatan', 'prodi'));
  }

  public function edit_dospem_sempro_ta(Request $request)
  {
    $id_sempro = $request->id_sempro;
    $id_ta = $request->id_ta;

    $dsn_sempro = $request->id_dosen_pembimbing_sempro;
    $user = explode(',', $dsn_sempro, 2);
    $id1 = $user[0];
    $id2 = $user[1];

    $dsn_ta = $request->id_dosen_pembimbing_ta;
    $user = explode(',', $dsn_ta, 2);
    $id3 = $user[0];
    $id4 = $user[1];

    $updated = $request->updated_by;

    Prausta_setting_relasi::where('id_settingrelasi_prausta', $id_sempro)
      ->update(['id_dosen_pembimbing' => $id1, 'dosen_pembimbing' => $id2, 'updated_by' => $updated]);

    Prausta_setting_relasi::where('id_settingrelasi_prausta', $id_ta)
      ->update(['id_dosen_pembimbing' => $id3, 'dosen_pembimbing' => $id4, 'updated_by' => $updated]);

    Alert::success('', 'Berhasil diedit')->autoclose(3500);
    return redirect('dospem_sempro_ta');
  }

  public function view_mhs_bim_sempro_ta(Request $request)
  {
    $angkatan = $request->idangkatan;
    $prodi = $request->kodeprodi;

    // $user = explode(',', $prodi, 2);
    // $id1 = $user[0];
    // $id2 = $user[1];

    $data = Student::join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->where('student.idangkatan', $angkatan)
      ->where('student.kodeprodi', $prodi)
      ->where('student.active', 1)
      ->select('student.idstudent', 'student.nim', 'student.nama', 'prodi.id_prodi', 'kelas.kelas', 'prodi.konsentrasi')
      ->orderBy('student.nim', 'ASC')
      ->get();

    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2])
      ->get();

    // $kode = Prausta_master_kode::where('id_prodi', $id2)
    //   ->whereIn('prausta_master_kode.tipe_prausta', ['Seminar', 'TugasAkhir'])
    //   ->select('prausta_master_kode.id_masterkode_prausta')
    //   ->get();

    // $kode1 = $kode[0]->id_masterkode_prausta;
    // $kode2 = $kode[1]->id_masterkode_prausta;

    return view('adminprodi/dospem/lihat_sempro_ta', compact('data', 'dosen', 'angkatan'));
    // return view('adminprodi/dospem/lihat_sempro_ta', compact('data', 'dosen', 'kode1', 'kode2', 'angkatan'));
  }

  public function save_dsn_bim_sempro_ta(Request $request)
  {dd($request);
    $dosen = $request->iddosen;

    // $idms1 = $request->id_masterkode_prausta1;
    // $idms2 = $request->id_masterkode_prausta2;

    $hitdsn = count($dosen);

    for ($i = 0; $i < $hitdsn; $i++) {
      $dta = $request->iddosen[$i];

      if ($dta != null) {

        $user = explode(',', $dta, 4);
        $id1 = $user[0]; #id student
        $id2 = $user[1]; #id dosen
        $id3 = $user[2]; #nama dosen
        $id4 = $user[3]; #id prodi

        $kode = Prausta_master_kode::where('id_prodi', $id4)
          ->where('prausta_master_kode.tipe_prausta', 'Seminar')
          ->whereIn('prausta_master_kode.kode_prausta', ['FA-6001', 'TI-6001'])
          ->select('prausta_master_kode.id_masterkode_prausta')
          ->first();

        $cekmhs = Prausta_setting_relasi::where('id_student', $id1)
          ->where('id_masterkode_prausta', $kode->id_masterkode_prausta)
          ->where('status', 'ACTIVE')
          ->get();

        if (count($cekmhs) == 0) {

          $dt = new Prausta_setting_relasi;
          $dt->id_masterkode_prausta = $kode->id_masterkode_prausta;
          $dt->id_student = $id1;
          $dt->dosen_pembimbing = $id3;
          $dt->id_dosen_pembimbing = $id2;
          $dt->added_by = Auth::user()->name;
          $dt->status = 'ACTIVE';
          $dt->data_origin = 'eSIAM';
          $dt->save();
        } elseif (count($cekmhs) > 0) {

          Prausta_setting_relasi::where('id_student', $id1)
            ->where('id_masterkode_prausta', $kode->id_masterkode_prausta)
            ->where('status', 'ACTIVE')
            ->update([
              'id_dosen_pembimbing' => $id2,
              'dosen_pembimbing' => $id3
            ]);
        }
      }
    }

    for ($i = 0; $i < $hitdsn; $i++) {
      $dta = $request->iddosen[$i];

      if ($dta != null) {

        $user = explode(',', $dta, 4);
        $id1 = $user[0]; #id student
        $id2 = $user[1]; #id dosen
        $id3 = $user[2]; #nama dosen
        $id4 = $user[3]; #id prodi

        $kode1 = Prausta_master_kode::where('id_prodi', $id4)
          ->where('prausta_master_kode.tipe_prausta', 'TugasAkhir')
          ->whereIn('prausta_master_kode.kode_prausta', ['FA6003', 'TI-6001'])
          ->select('prausta_master_kode.id_masterkode_prausta')
          ->first();

        $cekmhs = Prausta_setting_relasi::where('id_student', $id1)
          ->where('id_masterkode_prausta', $kode1->id_masterkode_prausta)
          ->where('status', 'ACTIVE')
          ->get();

        if (count($cekmhs) == 0) {

          $dt = new Prausta_setting_relasi;
          $dt->id_masterkode_prausta = $kode1->id_masterkode_prausta;
          $dt->id_student = $id1;
          $dt->dosen_pembimbing = $id3;
          $dt->id_dosen_pembimbing = $id2;
          $dt->added_by = Auth::user()->name;
          $dt->status = 'ACTIVE';
          $dt->data_origin = 'eSIAM';
          $dt->save();
        } elseif (count($cekmhs) > 0) {

          Prausta_setting_relasi::where('id_student', $id1)
            ->where('id_masterkode_prausta', $kode1->id_masterkode_prausta)
            ->where('status', 'ACTIVE')
            ->update([
              'id_dosen_pembimbing' => $id2,
              'dosen_pembimbing' => $id3
            ]);
        }
      }
    }

    Alert::success('', 'Data Pembimbing Seminar Proposal dan Tugas Akhir Berhasil Diinput')->autoclose(3500);
    return redirect('dospem_sempro_ta');
  }

  public function setting_standar_kurikulum()
  {
    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();
    $data = Kurikulum_transaction::all();

    return view('adminprodi/kurikulum/setting_standar', compact('data', 'kurikulum', 'prodi', 'angkatan', 'semester'));
  }

  public function view_kurikulum_standar(Request $request)
  {
    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $idkurikulum = $request->id_kurikulum;
    $idprodi = $request->id_prodi;
    $idangkatan = $request->id_angkatan;
    $idsemester = $request->id_semester;
    $status = $request->status;
    $paket = $request->pelaksanaan_paket;

    $krlm = Kurikulum_master::where('id_kurikulum', $idkurikulum)->first();
    $prd = Prodi::where('id_prodi', $idprodi)->first();
    $angk = Angkatan::where('idangkatan', $idangkatan)->first();
    $smtr = Semester::where('idsemester', $idsemester)->first();
    $mk = Matakuliah::where('active', '1')->get();

    if ($idsemester != null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $idkurikulum)
        ->where('kurikulum_transaction.id_prodi', $idprodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.id_semester', $idsemester)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    } elseif ($idsemester == null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $idkurikulum)
        ->where('kurikulum_transaction.id_prodi', $idprodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    }

    return view('adminprodi/kurikulum/view_kurikulum_standar', compact('mk', 'data', 'kurikulum', 'prodi', 'angkatan', 'semester', 'krlm', 'prd', 'angk', 'smtr', 'status', 'paket'));
  }

  public function add_setting_kurikulum()
  {
    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();
    $matakuliah = Matakuliah::where('active', '1')->get();

    return view('adminprodi/kurikulum/add_setting_kurikulum', compact('kurikulum', 'prodi', 'angkatan', 'semester', 'matakuliah'));
  }

  public function save_setting_kurikulum(Request $request)
  {
    $idmakul = $request->id_makul;
    $id_kurikulum = $request->id_kurikulum;
    $id_prodi = $request->id_prodi;
    $idangkatan = $request->id_angkatan;
    $idsemester = $request->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $cek_mk = count($idmakul);

    for ($i = 0; $i < $cek_mk; $i++) {
      $idmk = $request->id_makul[$i];
      if ($idmk != null) {

        $cekid_kur = Kurikulum_transaction::where('id_kurikulum', $id_kurikulum)
          ->where('id_prodi', $id_prodi)
          ->where('id_angkatan', $idangkatan)
          ->where('id_semester', $idsemester)
          ->where('id_makul', $idmk)
          ->where('status', 'ACTIVE')
          ->where('pelaksanaan_paket', 'OPEN')
          ->count();

        if ($cekid_kur == 0) {
          $bsa = new Kurikulum_transaction();
          $bsa->id_kurikulum = $request->id_kurikulum;
          $bsa->id_prodi = $request->id_prodi;
          $bsa->id_semester = $request->id_semester;
          $bsa->id_angkatan = $request->id_angkatan;
          $bsa->id_makul = $idmk;
          $bsa->save();
        }
      }
    }



    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $idangkatan)->first();
    $smtr = Semester::where('idsemester', $idsemester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    if ($idsemester != null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.id_semester', $idsemester)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    } elseif ($idsemester == null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
        ->where('kurikulum_transaction.status', $status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    }

    Alert::success('', 'Kurikulum berhasil ditambahkan')->autoclose(3500);
    return view('adminprodi/kurikulum/view_kurikulum_standar', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function edit_setting_kurikulum($id)
  {
    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();
    $matakuliah = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    return view('adminprodi/kurikulum/edit_setting_kurikulum', compact('id', 'data', 'kurikulum', 'prodi', 'angkatan', 'semester', 'matakuliah'));
  }

  public function put_setting_kurikulum(Request $request, $id)
  {
    $bsa = Kurikulum_transaction::find($id);
    $bsa->id_kurikulum = $request->id_kurikulum;
    $bsa->id_prodi = $request->id_prodi;
    $bsa->id_semester = $request->id_semester;
    $bsa->id_angkatan = $request->id_angkatan;
    $bsa->id_makul = $request->id_makul;
    $bsa->status = $request->status;
    $bsa->pelaksanaan_paket = $request->pelaksanaan_paket;
    $bsa->save();

    $id_kurikulum = $request->id_kurikulum;
    $id_prodi = $request->id_prodi;
    $idangkatan = $request->id_angkatan;
    $idsemester = $request->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $request->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $request->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $request->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $request->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    if ($request->id_semester != null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $request->id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $request->id_angkatan)
        ->where('kurikulum_transaction.id_semester', $request->id_semester)
        ->where('kurikulum_transaction.status', $request->status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $request->pelaksanaan_paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    } elseif ($request->id_semester == null) {
      $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
        ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
        ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        ->where('kurikulum_transaction.id_kurikulum', $request->id_kurikulum)
        ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
        ->where('kurikulum_transaction.id_angkatan', $request->id_angkatan)
        ->where('kurikulum_transaction.status', $request->status)
        ->where('kurikulum_transaction.pelaksanaan_paket', $request->pelaksanaan_paket)
        ->orderBy('semester.semester', 'ASC')
        ->orderBy('matakuliah.kode', 'ASC')
        ->select(
          'kurikulum_transaction.idkurtrans',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_prodi',
          'kurikulum_transaction.id_kurikulum',
          'kurikulum_transaction.id_semester',
          'kurikulum_transaction.id_angkatan',
          'kurikulum_transaction.id_makul',
          'kurikulum_transaction.pelaksanaan_paket',
          'kurikulum_transaction.validasi',
          'kurikulum_transaction.status',
          'kurikulum_master.nama_kurikulum',
          'prodi.prodi',
          'angkatan.angkatan',
          'semester.semester',
          'matakuliah.makul',
          'matakuliah.kode',
          'matakuliah.akt_sks_teori',
          'matakuliah.akt_sks_praktek'
        )
        ->get();
    }

    Alert::success('', 'Setting kurikulum berhasil diedit')->autoclose(3500);
    return view('adminprodi/kurikulum/view_kurikulum_standar', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function hapus_setting_kurikulum($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['status' => 'NOT ACTIVE']);


    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Setting kurikulum berhasil dihapus')->autoclose(3500);
    return view('adminprodi/kurikulum/view_kurikulum_standar', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function aktif_setting_kurikulum($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['status' => 'ACTIVE']);


    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Setting kurikulum berhasil dihapus')->autoclose(3500);
    return view('adminprodi/kurikulum/view_kurikulum_standar', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function closed_setting_kurikulum($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['pelaksanaan_paket' => 'CLOSED']);


    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Setting kurikulum berhasil dihapus')->autoclose(3500);
    return view('adminprodi/kurikulum/view_kurikulum_standar', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function open_setting_kurikulum($id)
  {
    $data_kur = Kurikulum_transaction::where('kurikulum_transaction.idkurtrans', $id)
      ->first();

    $id_kurikulum = $data_kur->id_kurikulum;
    $id_prodi = $data_kur->id_prodi;
    $idangkatan = $data_kur->id_angkatan;
    $idsemester = $data_kur->id_semester;
    $status = 'ACTIVE';
    $paket = 'OPEN';

    $kurikulum = Kurikulum_master::all();
    $prodi = Prodi::all();
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $semester = Semester::all();

    $krlm = Kurikulum_master::where('id_kurikulum', $data_kur->id_kurikulum)->first();
    $prd = Prodi::where('id_prodi', $data_kur->id_prodi)->first();
    $angk = Angkatan::where('idangkatan', $data_kur->id_angkatan)->first();
    $smtr = Semester::where('idsemester', $data_kur->id_semester)->first();
    $mk = Matakuliah::where('active', '1')
      ->orderBy('kode', 'asc')
      ->get();

    Kurikulum_transaction::where('idkurtrans', $id)->update(['pelaksanaan_paket' => 'OPEN']);


    $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
      ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
      ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
      ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
      ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
      ->where('kurikulum_transaction.id_kurikulum', $data_kur->id_kurikulum)
      ->where('kurikulum_transaction.id_prodi', $data_kur->id_prodi)
      ->where('kurikulum_transaction.id_angkatan', $data_kur->id_angkatan)
      ->where('kurikulum_transaction.id_semester', $data_kur->id_semester)
      ->where('kurikulum_transaction.status', 'ACTIVE')
      ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
      ->orderBy('semester.semester', 'ASC')
      ->orderBy('matakuliah.kode', 'ASC')
      ->select(
        'kurikulum_transaction.idkurtrans',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_prodi',
        'kurikulum_transaction.id_kurikulum',
        'kurikulum_transaction.id_semester',
        'kurikulum_transaction.id_angkatan',
        'kurikulum_transaction.id_makul',
        'kurikulum_transaction.pelaksanaan_paket',
        'kurikulum_transaction.validasi',
        'kurikulum_transaction.status',
        'kurikulum_master.nama_kurikulum',
        'prodi.prodi',
        'angkatan.angkatan',
        'semester.semester',
        'matakuliah.makul',
        'matakuliah.kode',
        'matakuliah.akt_sks_teori',
        'matakuliah.akt_sks_praktek'
      )
      ->get();


    Alert::success('', 'Setting kurikulum berhasil dihapus')->autoclose(3500);
    return view('adminprodi/kurikulum/view_kurikulum_standar', compact(
      'id_kurikulum',
      'id_prodi',
      'idangkatan',
      'idsemester',
      'status',
      'paket',
      'kurikulum',
      'prodi',
      'angkatan',
      'semester',
      'krlm',
      'prd',
      'angk',
      'smtr',
      'mk',
      'data'
    ));
  }

  public function rekap_nilai_mhs()
  {
    $periode_tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();

    $periode_tipe = Periode_tipe::all();

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;
    $nama_tipe = $tp->periode_tipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;
    $nama_tahun = $thn->periode_tahun;

    $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', DB::raw('COUNT(student_record.id_student) as jml_mhs'), 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->groupBy('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->get();

    return view('adminprodi/nilai/rekap_nilai_mhs', compact('periode_tahun', 'periode_tipe', 'data', 'nama_tipe', 'nama_tahun'));
  }

  public function filter_rekap_nilai_mhs(Request $request)
  {
    $periode_tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();

    $periode_tipe = Periode_tipe::all();

    $tp = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
    $tipe = $tp->id_periodetipe;
    $nama_tipe = $tp->periode_tipe;

    $thn = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
    $tahun = $thn->id_periodetahun;
    $nama_tahun = $thn->periode_tahun;

    $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', DB::raw('COUNT(student_record.id_student) as jml_mhs'), 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->groupBy('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->get();

    return view('adminprodi/nilai/rekap_nilai_mhs', compact('periode_tahun', 'periode_tipe', 'data', 'nama_tipe', 'nama_tahun'));
  }

  public function cek_rekap_nilai_mhs($id)
  {
    $nama = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('matakuliah.kode', 'matakuliah.makul', 'kelas.kelas', 'prodi.prodi')
      ->first();

    $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student_record.id_kurperiode', $id)
      ->where('student_record.status', 'TAKEN')
      ->select('student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
      ->get();

    return view('adminprodi/nilai/cek_rekap_nilai_mhs', compact('data', 'nama'));
  }

  public function jadwal_kuliah_prodi()
  {
    $tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
    $tipe = Periode_tipe::all();

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $idtipe = $tp->id_periodetipe;
    $namaperiodetipe = $tp->periode_tipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $idtahun = $thn->id_periodetahun;
    $namaperiodetahun = $thn->periode_tahun;

    $data = DB::select('CALL jadwal_perkuliahan(?,?)', [$idtahun, $idtipe]);

    return view('adminprodi/perkuliahan/jadwal_perkuliahan', compact('data', 'tahun', 'tipe', 'namaperiodetahun', 'namaperiodetipe'));
  }

  public function filter_jadwal_perkuliahan_prodi(Request $request)
  {
    $tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
    $tipe = Periode_tipe::all();

    $tp = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
    $idtipe = $tp->id_periodetipe;
    $namaperiodetipe = $tp->periode_tipe;

    $thn = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
    $idtahun = $thn->id_periodetahun;
    $namaperiodetahun = $thn->periode_tahun;

    $data = DB::select('CALL jadwal_perkuliahan(?,?)', [$idtahun, $idtipe]);

    return view('adminprodi/perkuliahan/jadwal_perkuliahan', compact('data', 'tahun', 'tipe', 'namaperiodetahun', 'namaperiodetipe'));
  }
}
