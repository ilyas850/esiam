<?php

namespace App\Http\Controllers;

use Alert;
use App\Angkatan;
use App\Prodi;
use App\Student;
use App\Dosen;
use App\Kurikulum_master;
use App\Kurikulum_transaction;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use App\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
          $dt->save();
        } elseif (count($cekmhs) > 0) {

          $akun = Prausta_setting_relasi::where('id_student', $id1)
            ->where('id_masterkode_prausta', $idms)
            ->where('status', 'ACTIVE')
            ->update([
              'id_dosen_pembimbing' => $id2,
              'dosen_pembimbing' => $id3
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

    $prodi = Prodi::select('prodi.kodeprodi', 'prodi.id_prodi', 'prodi.prodi')
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

    $user = explode(',', $prodi, 2);
    $id1 = $user[0];
    $id2 = $user[1];

    $data = Student::where('student.idangkatan', $angkatan)
      ->where('student.kodeprodi', $id1)
      ->where('student.active', 1)
      ->orderBy('student.nim', 'ASC')
      ->get();

    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2])
      ->get();

    $kode = Prausta_master_kode::where('id_prodi', $id2)
      ->whereIn('prausta_master_kode.tipe_prausta', ['Seminar', 'TugasAkhir'])
      ->select('prausta_master_kode.id_masterkode_prausta')
      ->get();

    $kode1 = $kode[0]->id_masterkode_prausta;
    $kode2 = $kode[1]->id_masterkode_prausta;

    return view('adminprodi/dospem/lihat_sempro_ta', compact('data', 'dosen', 'kode1', 'kode2', 'angkatan'));
  }

  public function save_dsn_bim_sempro_ta(Request $request)
  {
    $dosen = $request->iddosen;
    $idms1 = $request->id_masterkode_prausta1;
    $idms2 = $request->id_masterkode_prausta2;

    $hitdsn = count($dosen);

    for ($i = 0; $i < $hitdsn; $i++) {
      $dta = $request->iddosen[$i];

      if ($dta != null) {

        $user = explode(',', $dta, 3);
        $id1 = $user[0];
        $id2 = $user[1];
        $id3 = $user[2];

        $cekmhs = Prausta_setting_relasi::where('id_student', $id1)
          ->where('id_masterkode_prausta', $idms1)
          ->where('status', 'ACTIVE')
          ->get();

        if (count($cekmhs) == 0) {

          $dt = new Prausta_setting_relasi;
          $dt->id_masterkode_prausta = $idms1;
          $dt->id_student = $id1;
          $dt->dosen_pembimbing = $id3;
          $dt->id_dosen_pembimbing = $id2;
          $dt->added_by = Auth::user()->name;
          $dt->status = 'ACTIVE';
          $dt->data_origin = 'eSIAM';
          $dt->save();
        } elseif (count($cekmhs) > 0) {

          $akun = Prausta_setting_relasi::where('id_student', $id1)
            ->where('id_masterkode_prausta', $idms1)
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

        $user = explode(',', $dta, 3);
        $id1 = $user[0];
        $id2 = $user[1];
        $id3 = $user[2];

        $cekmhs = Prausta_setting_relasi::where('id_student', $id1)
          ->where('id_masterkode_prausta', $idms2)
          ->where('status', 'ACTIVE')
          ->get();

        if (count($cekmhs) == 0) {

          $dt = new Prausta_setting_relasi;
          $dt->id_masterkode_prausta = $idms2;
          $dt->id_student = $id1;
          $dt->dosen_pembimbing = $id3;
          $dt->id_dosen_pembimbing = $id2;
          $dt->added_by = Auth::user()->name;
          $dt->status = 'ACTIVE';
          $dt->data_origin = 'eSIAM';
          $dt->save();
        } elseif (count($cekmhs) > 0) {

          $akun = Prausta_setting_relasi::where('id_student', $id1)
            ->where('id_masterkode_prausta', $idms2)
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
    $idangkatan = $request->idangkatan;
    $idsemester = $request->idsemester;
    $status = $request->status;
    $paket = $request->pelaksanaan_paket;

    $krlm = Kurikulum_master::where('id_kurikulum', $idkurikulum)->first();
    $prd = Prodi::where('id_prodi', $idprodi)->first();
    $angk = Angkatan::where('idangkatan', $idangkatan)->first();

    $smtr = Semester::where('idsemester', $idsemester)->first();

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
        ->get();
    }

    $cdata = count($data);
    return view('adminprodi/kurikulum/view_kurikulum_standar', compact('cdata', 'data', 'kurikulum', 'prodi', 'angkatan', 'semester', 'krlm', 'prd', 'angk', 'smtr', 'status', 'paket'));
  }
}
