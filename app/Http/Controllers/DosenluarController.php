<?php

namespace App\Http\Controllers;

use PDF;
use File;
use Alert;
use App\Bap;
use App\Absensi_mahasiswa;
use App\Kuliah_transaction;
use App\Kaprodi;
use App\User;
use App\Bayar;
use App\Dosen;
use App\Prodi;
use App\Kelas;
use App\Ruangan;
use App\Student;
use App\Semester;
use App\Angkatan;
use App\Matakuliah;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_master;
use App\Student_record;
use App\Dosen_pembimbing;
use App\Kuitansi;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Ujian_transaction;
use App\Exports\DataNilaiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DosenluarController extends Controller
{
    public function makul_diampu()
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
                                ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
                                ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
                                ->where('kurikulum_periode.id_dosen', $iddsn)
                                ->where('periode_tahun.id_periodetahun', $thn)
                                ->where('kurikulum_periode.status', 'ACTIVE')
                                ->select('kurikulum_hari.hari', 'kurikulum_jam.jam','kurikulum_periode.id_kurperiode','matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                                ->get();

        return view('dosenluar/makul_diampu', ['makul'=>$mkul, 'mk'=>$mk, 'prd'=>$prd, 'kls'=>$kls, 'smt'=>$smt]);
    }

    public function cekmhs($id)
    {
        $mhs = Student::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $angk = Angkatan::all();
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $id)
                            ->where('student_record.status','TAKEN')
                            ->select('student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                            ->get();

        return view('dosenluar/list_mhs', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$id]);

        // $ck = Student_record::join('kuitansi', 'student_record.id_student', '=', 'kuitansi.idstudent')
        //                     ->join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
        //                     ->where('student_record.id_kurperiode', $id)
        //                     ->select(DB::raw('sum(bayar.bayar) as byr'),'kuitansi.idstudent')
        //                     ->groupBy(DB::raw('kuitansi.idstudent'))
        //                     ->get();

        // mencari angkatan dan semester
        // $mk = Student_record::join('student','student_record.id_student', '=', 'student.idstudent')
        //                     ->where('student_record.id_kurperiode',$id)
        //                     ->select('student.idangkatan')
        //                     ->get();

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
                            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
                            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
                            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                            ->where('kurikulum_periode.id_dosen', $iddsn)
                            ->where('kurikulum_periode.status', 'ACTIVE')
                            ->select('periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                            ->orderBy('kurikulum_periode.id_periodetahun', 'DESC')
                            ->get();

      return view('dosenluar/history_makul_dsn', ['prd_tipe'=>$prd_tipe,'prd_tahun'=>$prd_tahun,'makul'=>$mkul, 'mk'=>$mk, 'prd'=>$prd, 'kls'=>$kls, 'smt'=>$smt]);
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

        return view('dosenluar/list_mhs_dsn_his', ['ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'ids'=>$id, 'kur'=>$kur]);
    }

    public function val_ujian()
    {
        $mhs = Student::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $angk = Angkatan::all();
        $thn = Periode_tahun::where('status', 'ACTIVE')->get();
        foreach ($thn as $tahun) {
        // code...
        }

        $tp = Periode_tipe::where('status', 'ACTIVE')->get();
        foreach ($tp as $tipe) {
        // code...
        }

        $uang = Kuitansi::join('student', 'kuitansi.idstudent', '=', 'student.idstudent')
                        ->join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                        ->where('student.active', 1)
                        ->select(DB::raw('sum(bayar.bayar) as byr'), 'student.idstudent', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan')
                        ->groupBy(DB::raw('student.idstudent'), 'student.kodeprodi', 'student.idstatus', 'student.idangkatan')
                        ->get();

        return view('dosenluar/validasi_ujian', ['uang'=>$uang, 'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk]);
    }

    public function input_kat($id)
    {
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('id_kurperiode', $id)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT')
                          ->get();
        $kurrr = $id;

      return view('dosenluar/input_kat', ['kuri' => $kurrr,'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
    }

    public function save_nilai_KAT(Request $request)
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
            $entry->data_origin   = 'eSIAM';
            $entry->save();
          }elseif ($ceknl != null) {
            $id                 = $id_kur;
            $entry              = Student_record::find($id);
            $entry->nilai_KAT   = $nilai;
            $entry->data_origin   = 'eSIAM';
            $entry->save();
          }

        }elseif ($banyak > 1) {

            if ($ceknl == null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_KAT' => 0]);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

            }elseif ($ceknl != null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_KAT' => $nilai]);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

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
                          ->where('student_record.status', 'TAKEN')
                          ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
    foreach ($ckstr as $str) {
      # code...
    }
    $kur = $str->id_kurtrans;
    $idkur = $request->id_kurperiode;
    return view('dosenluar/list_mhs', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
    }

    public function input_uts($id)
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

      return view('dosenluar/input_uts', ['kuri' => $kurrr,'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl,'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
    }

    public function save_nilai_UTS(Request $request)
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
            $entry->data_origin   = 'eSIAM';
            $entry->save();
          }elseif ($ceknl != null) {
            $id                 = $id_kur;
            $entry              = Student_record::find($id);
            $entry->nilai_UTS   = $nilai;
            $entry->data_origin   = 'eSIAM';
            $entry->save();
          }

        }elseif ($banyak > 1) {

            if ($ceknl == null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_UTS' => 0]);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

            }elseif ($ceknl != null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_UTS' => $nilai]);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

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
                          ->where('student_record.status', 'TAKEN')
                          ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
    foreach ($ckstr as $str) {
      # code...
    }
    $kur = $str->id_kurtrans;
    $idkur = $request->id_kurperiode;
    return view('dosenluar/list_mhs', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
    }

    public function input_uas($id)
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

      return view('dosenluar/input_uas', ['kuri' => $kurrr,'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl,'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
    }

    public function save_nilai_UAS(Request $request)
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
            $entry->data_origin = 'eSIAM';

            $entry->save();
          }elseif ($ceknl != null) {
            $id                 = $id_kur;
            $entry              = Student_record::find($id);
            $entry->nilai_UAS   = $nilai;
            $entry->data_origin = 'eSIAM';
            $entry->save();
          }

        }elseif ($banyak > 1) {

            if ($ceknl == null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_UAS' => 0]);

                Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

            }elseif ($ceknl != null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_UAS' => $nilai]);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

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
                          ->where('student_record.status', 'TAKEN')
                          ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
    foreach ($ckstr as $str) {
      # code...
    }
    $kur = $str->id_kurtrans;
    $idkur = $request->id_kurperiode;
    return view('dosenluar/list_mhs', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
    }

    public function input_akhir($id)
    {
      $mhs = Student::all();
      $prd = Prodi::all();
      $kls = Kelas::all();
      $angk = Angkatan::all();
      //cek mahasiswa
      $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                          ->where('student_record.id_kurperiode', $id)
                          ->where('student_record.status','TAKEN')
                          ->select('student_record.id_kurtrans','student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();
      $kurrr = $id;

      return view('dosenluar/input_akhir', ['kuri' => $kurrr, 'ck'=>$cks,'mhs'=>$mhs, 'prd'=>$prd, 'kls'=>$kls, 'angk'=>$angk, 'id'=>$id]);
    }

    public function save_nilai_AKHIR(Request $request)
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
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }elseif ($ceknl != null) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR_angka = $nilai;
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }

          if ($ceknl < 50) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR = 'E';
            $ceknilai->nilai_ANGKA = '0';
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }elseif ($ceknl < 60) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR = 'D';
            $ceknilai->nilai_ANGKA = '1';
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }elseif ($ceknl < 65) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR = 'C';
            $ceknilai->nilai_ANGKA = '2';
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }elseif ($ceknl < 70) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR = 'C+';
            $ceknilai->nilai_ANGKA = '2.5';
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }elseif ($ceknl < 75) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR = 'B';
            $ceknilai->nilai_ANGKA = '3';
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }elseif ($ceknl < 80) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR = 'B+';
            $ceknilai->nilai_ANGKA = '3.5';
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }elseif ($ceknl <= 100) {
            $id = $id_kur;
            $ceknilai = Student_record::find($id);
            $ceknilai->nilai_AKHIR = 'A';
            $ceknilai->nilai_ANGKA = '4';
            $ceknilai->data_origin   = 'eSIAM';
            $ceknilai->save();
          }

        }elseif ($banyak > 1) {
          if ($ceknl == null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_AKHIR_angka' => 0]);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

            }elseif ($ceknl != null) {
              Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['nilai_AKHIR_angka' => $nilai]);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

          }

          if ($ceknl < 50) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR' => 'E']);

            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_ANGKA' => '0']);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

          }elseif ($ceknl < 60) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR' => 'D']);

            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_ANGKA' => '1']);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

          }elseif ($ceknl < 65) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR' => 'C']);

            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_ANGKA' => '2']);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

          }elseif ($ceknl < 70) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR' => 'C+']);

            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_ANGKA' => '2.5']);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

          }elseif ($ceknl < 75) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR' => 'B']);

            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_ANGKA' => '3']);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

          }elseif ($ceknl < 80) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR' => 'B+']);

            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_ANGKA' => '3.5']);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);

          }elseif ($ceknl <= 100) {
            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_AKHIR' => 'A']);

            Student_record::where('id_student', $stu)
                          ->where('id_kurtrans', $kur)
                          ->update(['nilai_ANGKA' => '4']);

            Student_record::where('id_student', $stu)
                            ->where('id_kurtrans', $kur)
                            ->update(['data_origin' => 'eSIAM']);
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
                          ->where('student_record.status', 'TAKEN')
                          ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
                          ->get();

    $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->where('id_kurperiode', $request->id_kurperiode)
                            ->where('student_record.status', 'TAKEN')
                            ->select('student_record.id_kurtrans')
                            ->get();
    foreach ($ckstr as $str) {
      # code...
    }
    $kur = $str->id_kurtrans;
    $idkur = $request->id_kurperiode;
    return view('dosenluar/list_mhs', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
    }

    public function change_dsnluar($id)
    {
      return view ('dosenluar/change_pwd_dsn', ['dsn' => $id]);
    }

    public function store_pwd_dsn_luar(Request $request, $id)
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

  public function entri_bap($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
                ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
                ->get();

    return view('dosenluar/bap', ['bap' => $key, 'data' => $data]);
  }

  public function input_bap($id)
  {

    return view('dosenluar/form_bap', ['id' => $id]);
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
      'file_kuliah_tatapmuka'   => 'image|mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
      'file_materi_kuliah'      => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG|max:2048',
      'file_materi_tugas'       => 'image|mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
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

      return redirect('entri_bap_dsn/'.$request->id_kurperiode)->with('success', 'Data Berhasil diupload');
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
                        ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                        ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                        ->where('student_record.id_kurperiode', $idp)
                        ->where('student_record.status', 'TAKEN')
                        ->select('angkatan.angkatan', 'kelas.kelas', 'prodi.prodi', 'student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim')
                        ->get();

    return view('dosenluar/absensi', ['absen' => $cks, 'idk' => $idp, 'id' => $id]);
  }

  public function save_absensi(Request $request)
  {
    $id_record  = $request->id_studentrecord;
    $jmlrecord  = count($id_record);
    $id_kur     = $request->id_kurperiode;
    $id_bp      = $request->id_bap;
    $absen      = $request->absensi;
    $jmlabsen   = count($request->absensi);

    for ($i = 0; $i < $jmlrecord; $i++) {
      $kurp = $request->id_studentrecord[$i];
      $idr = explode(',', $kurp);
      $tra = $idr[0];

      $cek = Absensi_mahasiswa::where('id_studentrecord', $tra)
                              ->where('id_bap', $id_bp)
                              ->get();

      $hit = count($cek);

      if ($hit == 0) {
        $abs                    = new Absensi_mahasiswa;
        $abs->id_bap            = $id_bp;
        $abs->id_studentrecord  = $tra;
        $abs->save();
      }
    }

    for ($i = 0; $i < $jmlabsen; $i++) {
      $abs    = $request->absensi[$i];
      $idab   = explode(',', $abs, 2);
      $trsen  = $idab[0];
      $trsi   = $idab[1];

      Absensi_mahasiswa::where('id_studentrecord', $trsen)
                        ->where('id_bap', $id_bp)
                        ->update(['absensi' => 'ABSEN']);
    }

    $bp = Bap::where('id_bap', $id_bp)
            ->update(['hadir' => $jmlabsen]);
    $bp = Bap::where('id_bap', $id_bp)
              ->update(['tidak_hadir' => $jmlrecord - $jmlabsen]);

    Alert::success('', 'Absen berhasil ditambahkan')->autoclose(3500);
    return redirect('entri_bap_dsn/' . $id_kur);
  }

  public function edit_absen($id)
  {
    $abs = Absensi_mahasiswa::join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
                            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                            ->where('absensi_mahasiswa.id_bap', $id)
                            ->select('student_record.id_kurperiode', 'absensi_mahasiswa.id_absensi', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'absensi_mahasiswa.absensi')
                            ->get();

    foreach ($abs as $key) {
      # code...
    }
    $idk = $key->id_kurperiode;

    return view('dosenluar/edit_absen', ['idk' => $idk, 'abs' => $abs, 'id' => $id]);
  }

  public function save_edit_absensi(Request $request)
  {
    $id_record  = $request->id_studentrecord;
    $jmlrecord  = count($id_record);

    $id_bp      = $request->id_bap;
    $absen      = $request->absensi;
    $jmlabsen   = count($request->absensi);

    for ($i = 0; $i < $jmlrecord; $i++) {
      $kurp = $request->id_studentrecord[$i];
      $idr = explode(',', $kurp);
      $tra = $idr[0];

      $cek = Absensi_mahasiswa::where('id_studentrecord', $tra)
                              ->where('id_bap', $id_bp)
                              ->update(['absensi' => 'HADIR']);
    }

    for ($i = 0; $i < $jmlabsen; $i++) {
      $abs    = $request->absensi[$i];
      $idab   = explode(',', $abs, 2);
      $trsen  = $idab[0];
      $trsi   = $idab[1];

      Absensi_mahasiswa::where('id_absensi', $trsen)
                      ->update(['absensi' => $trsi]);
    }

    $bp = Bap::where('id_bap', $id_bp)
              ->update(['hadir' => $jmlabsen]);

    $bp = Bap::where('id_bap', $id_bp)
            ->update(['tidak_hadir' => $jmlrecord - $jmlabsen]);

    $kur = Bap::where('id_bap', $id_bp)
              ->select('id_kurperiode')
              ->get();

    foreach ($kur as $kui) {
      # code...
    }
    $id_kur = $kui->id_kurperiode;

    Alert::success('', 'Absen berhasil diedit')->autoclose(3500);

    return redirect('entri_bap_dsn/' . $id_kur);
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
                            ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
                            ->get();
    foreach ($bap as $data) {
      # code...
    }
    $prd = $data->prodi;
    $tipe = $data->periode_tipe;
    $tahun = $data->periode_tahun;


    return view('dosenluar/view_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
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
                            ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
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
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('dosenluar/cetak_bap', ['d' => $d, 'm' => $m, 'y' => $y, 'prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
  }

  public function edit_bap($id)
  {
    $bap = Bap::where('id_bap', $id)->get();
    foreach ($bap as $key_bap) {
      # code...
    }
    return view('dosenluar/edit_bap', ['id' => $id, 'bap' => $key_bap]);
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


    if ($bap->file_kuliah_tatapmuka) {
      if ($request->hasFile('file_kuliah_tatapmuka')) {
        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Kuliah Tatap Muka/' . $bap->file_kuliah_tatapmuka);
        $file                               = $request->file('file_kuliah_tatapmuka');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Kuliah Tatap Muka';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_kuliah_tatapmuka        = $nama_file;
      }
    } else {
      if ($request->hasFile('file_kuliah_tatapmuka')) {
        $file                               = $request->file('file_kuliah_tatapmuka');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Kuliah Tatap Muka';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_kuliah_tatapmuka         = $nama_file;
      }
    }

    if ($bap->file_materi_kuliah) {
      if ($request->hasFile('file_materi_kuliah')) {
        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Materi Kuliah/' . $bap->file_materi_kuliah);
        $file                               = $request->file('file_materi_kuliah');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Materi Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_kuliah        = $nama_file;
      }
    } else {
      if ($request->hasFile('file_materi_kuliah')) {
        $file                               = $request->file('file_materi_kuliah');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Materi Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_kuliah            = $nama_file;
      }
    }

    if ($bap->file_materi_tugas) {
      if ($request->hasFile('file_materi_tugas')) {
        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Tugas Kuliah/' . $bap->file_materi_tugas);
        $file                               = $request->file('file_materi_tugas');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Tugas Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_tugas        = $nama_file;
      }
    } else {
      if ($request->hasFile('file_materi_tugas')) {
        $file                               = $request->file('file_materi_tugas');
        $nama_file                          = 'Pertemuan Ke-' . $request->pertemuan . "_" . $file->getClientOriginalName();
        $tujuan_upload                      = 'File_BAP/' . Auth::user()->id_user . '/' . $request->id_kurperiode . '/' . 'Tugas Kuliah';
        $file->move($tujuan_upload, $nama_file);
        $bap->file_materi_tugas            = $nama_file;
      }
    }

    $bap->save();

    Kuliah_transaction::where('id_bap', $id)
      ->update(['id_tipekuliah' => $request->id_tipekuliah]);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['tanggal' => $request->tanggal]);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['akt_jam_mulai' => $request->jam_mulai]);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['akt_jam_selesai' => $request->jam_selsai]);


    return redirect('entri_bap_dsn/' . $request->id_kurperiode);
  }

  public function delete_bap($id)
  {
    Bap::where('id_bap', $id)
      ->update(['status' => 'NOT ACTIVE']);

    Kuliah_transaction::where('id_bap', $id)
      ->update(['status' => 'NOT ACTIVE']);

    Absensi_mahasiswa::where('id_bap', $id)
      ->update(['status' => 'NOT ACTIVE']);

    $idk = Bap::where('id_bap', $id)
      ->select('id_kurperiode')
      ->get();

    foreach ($idk as $key) {
      # code...
    }

    return redirect('entri_bap_dsn/' . $key->id_kurperiode);
  }

  public function sum_absen($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
                            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
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
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 1)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 3)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 4)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 5)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 6)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 7)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 8)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 9)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 10)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 11)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 12)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 13)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 14)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 15)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 16)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    return view('dosenluar/absensi_perkuliahan', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function print_absensi($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
                            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
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
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 1)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 3)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 4)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 5)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 6)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 7)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 8)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 9)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 10)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 11)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 12)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 13)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 14)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 15)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 16)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    return view('dosenluar/cetak_absensi', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function download_absensi($id)
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

    $makul = $key->makul;
    $tahun = $key->periode_tahun;
    $tipe = $key->periode_tipe;
    $kelas = $key->kelas;

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

    $pdf= PDF::loadView('dosen/download/absensi_perkuliahan_pdf', ['abs16'=>$abs16,'abs15'=>$abs15,'abs14'=>$abs14,'abs13'=>$abs13,'abs12'=>$abs12,'abs11'=>$abs11,'abs10'=>$abs10,'abs9'=>$abs9,'abs8'=>$abs8,'abs7'=>$abs7,'abs6'=>$abs6,'abs5'=>$abs5,'abs4'=>$abs4,'abs'=>$abs,'abs1'=>$abs1,'abs2'=>$abs2,'abs3'=>$abs3, 'bap'=>$key])->setPaper('a4', 'landscape');
    return $pdf->download('Absensi Matakuliah'.' '.$makul.' '.$tahun.' '.$tipe.' '.$kelas.'.pdf');
  }

  public function jurnal_bap($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
                            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                            ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

    return view('dosenluar/jurnal_perkuliahan', ['bap' => $key, 'data' => $data]);
  }

  public function print_jurnal($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
                            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
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
                ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
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
    $m = $bulan[date('m')];
    $y = date('Y');

    return view('dosenluar/cetak_jurnal', ['cekkprd'=>$cekkprd, 'd' => $d, 'm' => $m, 'y' => $y, 'bap' => $key, 'data' => $data]);
  }

  public function download_jurnal($id)
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

    $makul = $key->makul;
    $tahun = $key->periode_tahun;
    $tipe = $key->periode_tipe;
    $kelas = $key->kelas;

    $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
                      ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
                      ->where('prodi.prodi', $key->prodi)
                      ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
                      ->first();

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

    $pdf= PDF::loadView('dosen/download/jurnal_perkuliahan_pdf', ['cekkprd'=>$cekkprd, 'bap'=>$key, 'data'=>$data])->setPaper('a4', 'landscape');
    return $pdf->download('Jurnal Matakuliah'.' '.$makul.' '.$tahun.' '.$tipe.' '.$kelas.'.pdf');
  }

  public function unduh_pdf_nilai(Request $request)
  {
    $id = $request->id_kurperiode;

    $mk = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
    ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
    ->join('kelas', 'kurikulum_periode.id_kelas', 'kelas.idkelas')
    ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
    ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
    ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
    ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'dosen.nama', 'dosen.akademik', 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'prodi.prodi', 'kelas.kelas')
      ->get();

    $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
    ->where('id_kurperiode', $id)
      ->where('student_record.status', 'TAKEN')
      ->select('student.nama', 'student.nim', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
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
    $pdf = PDF::loadView('dosenluar/unduh_nilai_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'data' => $key, 'tb' => $cks]);
    return $pdf->download('Nilai Matakuliah' . ' ' . $makul . ' ' . $tahun . ' ' . $tipe . ' ' . $kelas . '.pdf');
  }

  public function view_bap_his($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
              ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
              ->get();

    return view('dosenluar/view_bap_his', ['bap' => $key, 'data' => $data]);
  }

  public function view_history_bap($id)
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
                            ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
                            ->get();
    foreach ($bap as $data) {
      # code...
    }
    $prd = $data->prodi;
    $tipe = $data->periode_tipe;
    $tahun = $data->periode_tahun;


    return view('dosenluar/view_history_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
  }

  public function sum_absen_his($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
                            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
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
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 1)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 3)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 4)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 5)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 6)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 7)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                            ->where('bap.id_kurperiode', $id)
                            ->where('bap.status', 'ACTIVE')
                            ->where('bap.pertemuan', 8)
                            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                            ->get();

    $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 9)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 10)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 11)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 12)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 13)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 14)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 15)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
                              ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
                              ->where('bap.id_kurperiode', $id)
                              ->where('bap.status', 'ACTIVE')
                              ->where('bap.pertemuan', 16)
                              ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
                              ->get();

    return view('dosenluar/absensi_perkuliahan_his', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
  }

  public function jurnal_bap_his($id)
  {
    $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
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
                            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                            ->get();
    foreach ($bap as $key) {
      # code...
    }

    $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                ->where('bap.id_kurperiode', $id)
                ->where('bap.status', 'ACTIVE')
                ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
                ->orderBy('bap.tanggal', 'ASC')
                ->get();

    return view('dosenluar/jurnal_perkuliahan_his', ['bap' => $key, 'data' => $data]);
  }
}
