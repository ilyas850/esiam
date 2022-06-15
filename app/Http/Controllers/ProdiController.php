<?php

namespace App\Http\Controllers;

use Alert;
use App\Angkatan;
use App\Prodi;
use App\Student;
use App\Dosen;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

  public function dospem_sempro()
  {
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $prodi = Prodi::join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [4, 5, 6, 7, 8, 9])
      ->select('prodi.kodeprodi', 'prodi.id_prodi', 'prodi.prodi', 'prausta_master_kode.id_masterkode_prausta')
      ->get();

    $data = Student::join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
      ->where('student.active', 1)
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->orderBy('student.nim', 'DESC')
      ->get();

    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2])
      ->get();

    return view('adminprodi/dospem/sempro', compact('angkatan', 'prodi', 'data', 'dosen'));
  }

  public function view_mhs_bim_sempro(Request $request)
  {
    dd($request);
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
    dd($id2);
    return view('adminprodi/dospem/lihat_sempro', compact('data', 'dosen', 'id2', 'angkatan', 'id1'));
  }

  public function save_dsn_bim_sempro(Request $request)
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

    Alert::success('', 'Data Pembimbing Seminar Proposal Berhasil Diinput')->autoclose(3500);
    return redirect('dospem_sempro');
  }

  public function put_dospem_sempro(Request $request, $id)
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
    return redirect('dospem_sempro');
  }

  public function dospem_ta()
  {
    $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
    $prodi = Prodi::join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
      ->whereIn('prausta_master_kode.id_masterkode_prausta', [7, 8, 9])
      ->select('prodi.kodeprodi', 'prodi.id_prodi', 'prodi.prodi', 'prausta_master_kode.id_masterkode_prausta')
      ->get();

    $data = Student::join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
      ->where('student.active', 1)
      ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
      ->where('prausta_setting_relasi.status', 'ACTIVE')
      ->orderBy('student.nim', 'DESC')
      ->get();

    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2])
      ->get();

    return view('adminprodi/dospem/ta', compact('angkatan', 'prodi', 'data', 'dosen'));
  }

  public function view_mhs_bim_ta(Request $request)
  {
    $angkatan = $request->idangkatan;
    $prodi = $request->kodeprodi;

    $user = explode(',', $prodi, 2);
    $id1 = $user[0];
    $id2 = $user[1];

    $datas = Student::where('student.idangkatan', $angkatan)
      ->where('student.kodeprodi', $id1)
      ->where('student.active', 1)
      ->orderBy('student.nim', 'ASC')
      ->get();

    $dosen = Dosen::where('active', 1)
      ->whereIn('idstatus', [1, 2])
      ->get();

    return view('adminprodi/dospem/lihat_ta', compact('datas', 'dosen', 'id2', 'angkatan', 'id1'));
  }

  public function save_dsn_bim_ta(Request $request)
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

    Alert::success('', 'Data Pembimbing Tugas Akhir Berhasil Diinput')->autoclose(3500);
    return redirect('dospem_ta');
  }

  public function put_dospem_ta(Request $request, $id)
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
    return redirect('dospem_ta');
  }
}
