<?php

namespace App\Http\Controllers;

use PDF;
use File;
use Alert;
use Session;
use Storage;
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
use App\Prausta_setting_relasi;
use App\Prausta_trans_bimbingan;
use App\Prausta_trans_hasil;
use App\Prausta_master_penilaian;
use App\Prausta_trans_penilaian;
use App\Soal_ujian;
use App\Setting_nilai;
use App\Standar;
use App\Exports\DataNilaiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Null_;

class DosenluarController extends Controller
{
    public function makul_diampu()
    {
        $iddsn = Auth::user()->id_user;

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();
        $nama_periodetahun = $periodetahun->periode_tahun;
        $nama_periodetipe = $periodetipe->periode_tipe;
        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;

        $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tp = Periode_tipe::all();

        $makul = DB::select('CALL matakuliah_diampu_dosen(?,?,?)', [$idperiodetahun, $idperiodetipe, $iddsn]);

        $makul1 = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->leftjoin('soal_ujian', 'kurikulum_periode.id_kurperiode', '=', 'soal_ujian.id_kurperiode')
            ->where('kurikulum_periode.id_dosen', $iddsn)
            ->where('periode_tahun.id_periodetahun', $idperiodetahun)
            ->where('periode_tipe.id_periodetipe', $idperiodetipe)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_hari.hari', 'kurikulum_jam.jam', 'kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'soal_ujian.soal_uts', 'soal_ujian.soal_uas')
            ->orderBy('semester.semester', 'ASC')
            ->orderBy('kelas.kelas', 'ASC')
            ->orderBy('matakuliah.kode', 'ASC')
            ->get();

        return view('dosenluar/makul_diampu', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
    }

    public function filter_makul_diampu_dsn_luar(Request $request)
    {
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
        $nama_periodetahun = $periodetahun->periode_tahun;
        $nama_periodetipe = $periodetipe->periode_tipe;
        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;

        $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tp = Periode_tipe::all();

        $id = Auth::user()->id_user;

        $makul = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->leftjoin('soal_ujian', 'kurikulum_periode.id_kurperiode', '=', 'soal_ujian.id_kurperiode')
            ->where('kurikulum_periode.id_dosen', $id)
            ->where('periode_tahun.id_periodetahun', $idperiodetahun)
            ->where('periode_tipe.id_periodetipe', $idperiodetipe)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_hari.hari', 'kurikulum_jam.jam', 'kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'soal_ujian.soal_uts', 'soal_ujian.soal_uas')
            ->get();

        return view('dosenluar/makul_diampu', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
    }

    public function cekmhs($id)
    {
        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $id)->first();

        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        return view('dosenluar/list_mhs', ['ck' => $kelas_gabungan, 'ids' => $id, 'nilai' => $nilai]);
    }

    public function export_xlsnilai(Request $request)
    {
        $id = $request->id_kurperiode;

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

        $nama_file = 'Nilai Matakuliah' . ' ' . $mkul . ' ' . $prdi . ' ' . $klas . '.xlsx';
        return Excel::download(new DataNilaiExport($id), $nama_file);
    }

    public function history_makul_dsn()
    {
        $iddsn = Auth::user()->id_user;

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
            ->orderBy('semester.semester', 'ASC')
            ->orderBy('kelas.kelas', 'ASC')
            ->orderBy('matakuliah.kode', 'ASC')
            ->get();

        return view('dosenluar/history_makul_dsn', ['makul' => $mkul]);
    }

    public function cekmhs_dsn_his($id)
    {
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
            ->orderBy('student.nim', 'asc')
            ->get();

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;

        return view('dosenluar/list_mhs_dsn_his', ['ck' => $cks, 'ids' => $id, 'kur' => $kur]);
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

        return view('dosenluar/validasi_ujian', ['uang' => $uang, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk]);
    }

    public function input_kat($id)
    {
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        $kurrr = $id;

        return view('dosenluar/input_kat', ['kuri' => $kurrr, 'ck' => $kelas_gabungan, 'id' => $id]);
    }

    public function save_nilai_KAT(Request $request)
    {
        $jumlahid = $request->id_student;
        $jmlids = $request->id_studentrecord;
        $jmlnil = $request->nilai_KAT;

        $jml = count($jmlnil);

        for ($i = 0; $i < $jml; $i++) {
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
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_KAT = 0;
                    $entry->data_origin = 'eSIAM';
                    $entry->save();
                } elseif ($ceknl != null) {
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_KAT = $nilai;
                    $entry->data_origin = 'eSIAM';
                    $entry->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_KAT' => 0]);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl != null) {
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
        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$request->id_kurperiode]);

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $request->id_kurperiode)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;
        $idkur = $request->id_kurperiode;

        return view('dosenluar/list_mhs', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function input_uts($id)
    {
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        $keymkl = Kurikulum_periode::where('id_kurperiode', $id)->first();

        $kmkl = $keymkl->id_makul;
        $kprd = $keymkl->id_prodi;
        $kkls = $keymkl->id_kelas;
        $kurrr = $id;

        return view('dosenluar/input_uts', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $kelas_gabungan, 'id' => $id]);
    }

    public function save_nilai_UTS(Request $request)
    {
        $jumlahid = $request->id_student;
        $jmlids = $request->id_studentrecord;
        $jmlnil = $request->nilai_UTS;

        $jml = count($jmlnil);

        for ($i = 0; $i < $jml; $i++) {
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
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_UTS = 0;
                    $entry->data_origin = 'eSIAM';
                    $entry->save();
                } elseif ($ceknl != null) {
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_UTS = $nilai;
                    $entry->data_origin = 'eSIAM';
                    $entry->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UTS' => 0]);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl != null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UTS' => $nilai]);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                }
            }
        }

        $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
        $jml_kelas = count($kelas_gabungan);

        for ($j = 0; $j < $jml_kelas; $j++) {
            $gabungan = $kelas_gabungan[$j];

            $id_kurperiode = Kurikulum_periode::where('id_kurperiode', $gabungan->id_kurperiode)->first();
            Ujian_transaction::where('id_periodetahun', $id_kurperiode->id_periodetahun)
                ->where('id_periodetipe', $id_kurperiode->id_periodetipe)
                ->where('jenis_ujian', 'UTS')
                ->where('id_prodi', $request->id_prodi)
                ->where('id_kelas', $request->id_kelas)
                ->where('id_makul', $request->id_makul)
                ->update(['aktual_pengoreksi' => Auth::user()->name, 'data_origin' => 'eSIAM']);
        }

        //ke halaman list mahasiswa

        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$request->id_kurperiode]);

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $request->id_kurperiode)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;
        $idkur = $request->id_kurperiode;
        return view('dosenluar/list_mhs', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function input_uas($id)
    {
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        $keymkl = Kurikulum_periode::where('id_kurperiode', $id)->first();

        $kmkl = $keymkl->id_makul;
        $kprd = $keymkl->id_prodi;
        $kkls = $keymkl->id_kelas;
        $kurrr = $id;

        return view('dosenluar/input_uas', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $kelas_gabungan, 'id' => $id]);
    }

    public function save_nilai_UAS(Request $request)
    {
        $jumlahid = $request->id_student;
        $jmlids = $request->id_studentrecord;
        $jmlnil = $request->nilai_UAS;

        $jml = count($jmlnil);

        for ($i = 0; $i < $jml; $i++) {
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
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_UAS = 0;
                    $entry->data_origin = 'eSIAM';

                    $entry->save();
                } elseif ($ceknl != null) {
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_UAS = $nilai;
                    $entry->data_origin = 'eSIAM';
                    $entry->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UAS' => 0, 'data_origin' => 'eSIAM']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM', 'data_origin' => 'eSIAM']);
                } elseif ($ceknl != null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UAS' => $nilai, 'data_origin' => 'eSIAM']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM', 'data_origin' => 'eSIAM']);
                }
            }
        }

        $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
        $jml_kelas = count($kelas_gabungan);

        for ($j = 0; $j < $jml_kelas; $j++) {
            $gabungan = $kelas_gabungan[$j];

            $id_kurperiode = Kurikulum_periode::where('id_kurperiode', $gabungan->id_kurperiode)->first();
            Ujian_transaction::where('id_periodetahun', $id_kurperiode->id_periodetahun)
                ->where('id_periodetipe', $id_kurperiode->id_periodetipe)
                ->where('jenis_ujian', 'UAS')
                ->where('id_prodi', $request->id_prodi)
                ->where('id_kelas', $request->id_kelas)
                ->where('id_makul', $request->id_makul)
                ->update(['aktual_pengoreksi' => Auth::user()->name, 'data_origin' => 'eSIAM']);
        }

        //ke halaman list mahasiswa
        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$request->id_kurperiode]);

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $request->id_kurperiode)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;
        $idkur = $request->id_kurperiode;
        return view('dosenluar/list_mhs', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function input_akhir($id)
    {
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student_record.id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
            ->orderBy('student.nim', 'ASC')
            ->get();

        $kurrr = $id;

        return view('dosenluar/input_akhir', ['kuri' => $kurrr, 'ck' => $cks, 'id' => $id]);
    }

    public function save_nilai_AKHIR(Request $request)
    {
        $jumlahid = $request->id_student;
        $jmlids = $request->id_studentrecord;
        $jmlnil = $request->nilai_AKHIR_angka;
        $jml = count($jmlnil);
        for ($i = 0; $i < $jml; $i++) {
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
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                } elseif ($ceknl != null) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR_angka = $nilai;
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                }

                if ($ceknl < 50) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'E';
                    $ceknilai->nilai_ANGKA = '0';
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                } elseif ($ceknl < 60) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'D';
                    $ceknilai->nilai_ANGKA = '1';
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                } elseif ($ceknl < 65) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'C';
                    $ceknilai->nilai_ANGKA = '2';
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                } elseif ($ceknl < 70) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'C+';
                    $ceknilai->nilai_ANGKA = '2.5';
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                } elseif ($ceknl < 75) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'B';
                    $ceknilai->nilai_ANGKA = '3';
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                } elseif ($ceknl < 80) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'B+';
                    $ceknilai->nilai_ANGKA = '3.5';
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                } elseif ($ceknl <= 100) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'A';
                    $ceknilai->nilai_ANGKA = '4';
                    $ceknilai->data_origin = 'eSIAM';
                    $ceknilai->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR_angka' => 0]);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl != null) {
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
                } elseif ($ceknl < 60) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'D']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '1']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl < 65) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'C']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '2']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl < 70) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'C+']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '2.5']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl < 75) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'B']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '3']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl < 80) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'B+']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '3.5']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['data_origin' => 'eSIAM']);
                } elseif ($ceknl <= 100) {
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

        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student_record.id_kurperiode', $request->id_kurperiode)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
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
        return view('dosenluar/list_mhs', ['ck' => $cks, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function change_dsnluar($id)
    {
        return view('dosenluar/change_pwd_dsn', ['dsn' => $id]);
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
        } else {
            Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
            return redirect('home');
        }
    }

    public function entri_bap($id)
    {
        $id_dosen = Auth::user()->id_user;
        $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
            ->first();

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('kuliah_transaction.id_dosen', $id_dosen)
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
            ->orderBy('bap.id_bap', 'ASC')
            ->get();

        return view('dosenluar/bap', ['bap' => $bap, 'data' => $data]);
    }

    public function input_bap($id)
    {
        $jam = Kurikulum_jam::all();

        return view('dosenluar/form_bap', ['id' => $id, 'jam' => $jam]);
    }

    public function save_bap(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute sudah terdaftar',
        ];
        $this->validate(
            $request,
            [
                'pertemuan' => 'required',
                'tanggal' => 'required',
                'jam_mulai' => 'required',
                'jam_selsai' => 'required',
                'jenis_kuliah' => 'required',
                'id_tipekuliah' => 'required',
                'metode_kuliah' => 'required',
                'materi_kuliah' => 'required',
                'file_kuliah_tatapmuka' => 'mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
                'file_materi_kuliah' => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG,docx,DOCX,PDF|max:4000',
                'file_materi_tugas' => 'mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
            ],
            $message,
        );

        $id_dosen = Auth::user()->id_user;
        $id_kurperiode = $request->id_kurperiode;

        $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$id_kurperiode]);

        $cek_bap = Bap::where('id_kurperiode', $request->id_kurperiode)
            ->where('id_dosen', Auth::user()->id_user)
            ->where('pertemuan', $request->pertemuan)
            ->where('status', 'ACTIVE')
            ->count();

        if ($cek_bap > 0) {
            Alert::error('Maaf pertemuan yang diinput sudah ada', 'maaf');
            return redirect()->back();
        } elseif ($cek_bap == 0) {
            $jml_idkurperiode = count($kelas_gabungan);

            for ($i = 0; $i < $jml_idkurperiode; $i++) {
                $kurperiode = $kelas_gabungan[$i];
                $id_kur = $kurperiode->id_kurperiode;

                $path_tatapmuka = 'File_BAP' . '/' . $id_dosen . '/' . $id_kur . '/' . 'Kuliah Tatap Muka';

                if (!File::exists($path_tatapmuka)) {
                    File::makeDirectory(public_path() . '/' . $path_tatapmuka, 0777, true);
                }

                $path_materikuliah = 'File_BAP' . '/' . $id_dosen . '/' . $id_kur . '/' . 'Materi Kuliah';

                if (!File::exists($path_materikuliah)) {
                    File::makeDirectory($path_materikuliah);
                }

                $path_tugaskuliah = 'File_BAP' . '/' . $id_dosen . '/' . $id_kur . '/' . 'Tugas Kuliah';

                if (!File::exists($path_tugaskuliah)) {
                    File::makeDirectory($path_tugaskuliah);
                }

                $bap = new Bap();
                $bap->id_kurperiode = $id_kur;
                $bap->id_dosen = $id_dosen;
                $bap->pertemuan = $request->pertemuan;
                $bap->tanggal = $request->tanggal;
                $bap->jam_mulai = $request->jam_mulai;
                $bap->jam_selsai = $request->jam_selsai;
                $bap->jenis_kuliah = $request->jenis_kuliah;
                $bap->id_tipekuliah = $request->id_tipekuliah;
                $bap->metode_kuliah = $request->metode_kuliah;
                $bap->materi_kuliah = $request->materi_kuliah;
                $bap->media_pembelajaran = $request->media_pembelajaran;

                if ($i == 0) {
                    if ($request->hasFile('file_kuliah_tatapmuka')) {
                        $file = $request->file('file_kuliah_tatapmuka');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $id_kur . '/' . 'Kuliah Tatap Muka';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_kuliah_tatapmuka = $nama_file;
                    }

                    if ($request->hasFile('file_materi_kuliah')) {
                        $file = $request->file('file_materi_kuliah');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $id_kur . '/' . 'Materi Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_kuliah = $nama_file;
                    }

                    if ($request->hasFile('file_materi_tugas')) {
                        $file = $request->file('file_materi_tugas');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $id_kur . '/' . 'Tugas Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_tugas = $nama_file;
                    }
                } elseif ($i > 0) {
                    if ($request->hasFile('file_kuliah_tatapmuka')) {
                        $tes1 = $kelas_gabungan[0];
                        $d1 = $tes1->id_kurperiode;
                        $file = $request->file('file_kuliah_tatapmuka');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $d1 . '/' . 'Kuliah Tatap Muka';

                        $tes2 = $kelas_gabungan[$i];
                        $d2 = $tes2->id_kurperiode;
                        $path = 'File_BAP' . '/' . $id_dosen . '/' . $d2 . '/' . 'Kuliah Tatap Muka';
                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_kuliah_tatapmuka = $nama_file1;
                    }

                    if ($request->hasFile('file_materi_kuliah')) {
                        $tes1 = $kelas_gabungan[0];
                        $d1 = $tes1->id_kurperiode;
                        $file = $request->file('file_materi_kuliah');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $d1 . '/' . 'Materi Kuliah';

                        $tes2 = $kelas_gabungan[$i];
                        $d2 = $tes2->id_kurperiode;

                        $path = 'File_BAP' . '/' . $id_dosen . '/' . $d2 . '/' . 'Materi Kuliah';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_materi_kuliah = $nama_file1;
                    }

                    if ($request->hasFile('file_materi_tugas')) {
                        $tes1 = $kelas_gabungan[0];
                        $d1 = $tes1->id_kurperiode;
                        $file = $request->file('file_materi_tugas');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . $id_dosen . '/' . $d1 . '/' . 'Tugas Kuliah';

                        $tes2 = $kelas_gabungan[$i];
                        $d2 = $tes2->id_kurperiode;

                        $path = 'File_BAP' . '/' . $id_dosen . '/' . $d2 . '/' . 'Tugas Kuliah';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_materi_tugas = $nama_file1;
                    }
                }

                $bap->save();

                $users = DB::table('bap')
                    ->limit(1)
                    ->orderByDesc('id_bap')
                    ->first();

                $kuliah = new Kuliah_transaction();
                $kuliah->id_kurperiode = $id_kur;
                $kuliah->id_dosen = $id_dosen;
                $kuliah->id_tipekuliah = $request->id_tipekuliah;
                $kuliah->tanggal = $request->tanggal;
                $kuliah->akt_jam_mulai = $request->jam_mulai;
                $kuliah->akt_jam_selesai = $request->jam_selsai;
                $kuliah->id_bap = $users->id_bap;
                $kuliah->save();
            }

            return redirect('entri_bap_dsn/' . $id_kur)->with('success', 'Data Berhasil diupload');
        }
    }

    public function entri_absen($id)
    {
        $idbap = Bap::where('id_bap', $id)->get();
        foreach ($idbap as $keybap) {
            # code...
        }
        $idp = $keybap->id_kurperiode;

        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idp]);

        return view('dosenluar/absensi', ['absen' => $kelas_gabungan, 'idk' => $idp, 'id' => $id]);
    }

    public function save_absensi(Request $request)
    {
        $id_record = $request->id_studentrecord;
        $jmlrecord = count($id_record);

        $id_kur = $request->id_kurperiode;

        $id_bp = $request->id_bap;

        $absen = $request->absensi;
        $jmlabsen = count($absen);

        $cek_bap = Bap::where('id_bap', $id_bp)
            ->select('id_bap', 'id_kurperiode', 'pertemuan')
            ->first();

        if ($absen != null) {
            $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$id_kur]);

            $jml_kelas_gabungan = count($cek_kelas_gabungan);

            //looping entri absen semua
            for ($i = 0; $i < $jml_kelas_gabungan; $i++) {
                $kelas = $cek_kelas_gabungan[$i];

                $id_kurperiode = $kelas->id_kurperiode;

                $absen_mahasiswa = DB::select('CALL absensi_mahasiswa_prodi_kelas(?)', [$id_kurperiode]);

                $jml_mhs = count($absen_mahasiswa);

                $cek_idbap_gabungan = Bap::where('id_kurperiode', $kelas->id_kurperiode)
                    ->where('pertemuan', $cek_bap->pertemuan)
                    ->where('status', 'ACTIVE')
                    ->select('id_bap')
                    ->first();

                for ($j = 0; $j < $jml_mhs; $j++) {
                    $kurperiode = $absen_mahasiswa[$j];

                    $abs = new Absensi_mahasiswa();
                    $abs->id_bap = $cek_idbap_gabungan->id_bap;
                    $abs->id_studentrecord = $kurperiode->id_studentrecord;
                    $abs->save();
                }
            }

            //looping untuk entri mahasiswa yang hadir
            for ($i = 0; $i < $jmlabsen; $i++) {
                $abs = $request->absensi[$i];

                $cek_idstudentrecord = Student_record::where('id_studentrecord', $abs)
                    ->select('id_studentrecord', 'id_kurperiode')
                    ->first();

                $cek_kelas = DB::select('CALL kelas_gabungan_prodi_kelas(?,?)', [$cek_idstudentrecord->id_kurperiode, $cek_bap->pertemuan]);
                $jml_kelas = count($cek_kelas);

                for ($l = 0; $l < $jml_kelas; $l++) {
                    $idkelas = $cek_kelas[$l];

                    $bap = Bap::join('absensi_mahasiswa', 'bap.id_bap', '=', 'absensi_mahasiswa.id_bap')
                        ->where('bap.id_kurperiode', $idkelas->id_kurperiode)
                        ->where('bap.pertemuan', $cek_bap->pertemuan)
                        ->where('absensi_mahasiswa.id_studentrecord', $abs)
                        ->where('bap.id_bap', $idkelas->id_bap)
                        ->where('bap.status', 'ACTIVE')
                        ->update(['absensi_mahasiswa.absensi' => 'ABSEN']);
                }
            }

            //looping untuk jumlah mahasiswa dari dan tidak
            for ($h = 0; $h < $jml_kelas_gabungan; $h++) {
                $kelas = $cek_kelas_gabungan[$h];

                $id_kurperiode = $kelas->id_kurperiode;

                $cek_idbap_gabungan = Bap::where('id_kurperiode', $kelas->id_kurperiode)
                    ->where('pertemuan', $cek_bap->pertemuan)
                    ->where('status', 'ACTIVE')
                    ->select('id_bap')
                    ->first();

                $jml_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
                    ->where('absensi', 'ABSEN')
                    ->count();
                $jml_tdk_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
                    ->where('absensi', 'HADIR')
                    ->count();

                $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['hadir' => $jml_hadir]);
                $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['tidak_hadir' => $jml_tdk_hadir]);
            }
        }

        return redirect('entri_bap_dsn/' . $id_kur);
    }

    public function edit_absen($id)
    {
        $kur = Bap::where('id_bap', $id)->first();

        $idk = $kur->id_kurperiode;
        $per = $kur->pertemuan;

        $p = DB::select('CALL editAbsenMahasiswa(?,?)', [$idk, $per]);
        // $p = DB::select('CALL editAbsenMhs(?,?)', array($idk, $per));

        return view('dosenluar/edit_absen', ['idk' => $idk, 'abs' => $p, 'id' => $id]);
    }

    public function save_edit_absensi(Request $request)
    {
        //id BAP
        $id_bp = $request->id_bap;

        // cek bap yang sama
        $bap_gabungan = DB::select('CALL bap_gabungan(?)', [$id_bp]);
        $jml_bap_gabungan = count($bap_gabungan);

        //jumlah yang masuk/absen
        $absen = $request->absensi;

        //jumlah yang sebelumnya tidak masuk
        $absr = $request->abs;

        $cek_bap = Bap::where('id_bap', $id_bp)
            ->select('id_bap', 'id_kurperiode', 'pertemuan')
            ->first();

        if ($absen != null) {
            //looping untuk edit semua absen jadi HADIR
            for ($i = 0; $i < $jml_bap_gabungan; $i++) {
                $id_bap_gabungan = $bap_gabungan[$i];
                $get_id_bap = $id_bap_gabungan->id_bap;

                Absensi_mahasiswa::where('id_bap', $get_id_bap)->update(['absensi' => 'HADIR']);
            }

            $jmlabsen = count($absen);
            for ($i = 0; $i < $jmlabsen; $i++) {
                $abs = $request->absensi[$i];

                $idabsen = DB::select('CALL absensi_gabungan_prodi_kelas(?)', [$abs]);
                $jml_idabsen = count($idabsen);

                for ($j = 0; $j < $jml_idabsen; $j++) {
                    $id_absensi = $idabsen[$j];

                    Absensi_mahasiswa::where('id_absensi', $id_absensi->id_absensi)->update(['absensi' => 'ABSEN']);
                }
            }
        } elseif ($absen == null) {
            for ($i = 0; $i < $jml_bap_gabungan; $i++) {
                $id_bap_gabungan = $bap_gabungan[$i];
                $get_id_bap = $id_bap_gabungan->id_bap;

                Absensi_mahasiswa::where('id_bap', $get_id_bap)->update(['absensi' => 'HADIR']);
            }
        }

        if ($absr != null) {
            $jml_mhs = count($absr);
            for ($i = 0; $i < $jml_mhs; $i++) {
                $studentrecord = $absr[$i];
                $cek_idstudentrecord = Student_record::where('id_studentrecord', $studentrecord)->first();
                $cek_idkurperiode = $cek_idstudentrecord->id_kurperiode;

                $cek_bap_id = DB::select('CALL kelas_gabungan_prodi_kelas(?,?)', [$cek_idkurperiode, $cek_bap->pertemuan]);
                $jml_bap_id = count($cek_bap_id);
                for ($l = 0; $l < $jml_bap_id; $l++) {
                    $bap_fix = $cek_bap_id[$l];

                    $abs = new Absensi_mahasiswa();
                    $abs->id_bap = $bap_fix->id_bap;
                    $abs->id_studentrecord = $studentrecord;
                    $abs->absensi = 'ABSEN';
                    $abs->save();
                }
            }
        }

        $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$cek_bap->id_kurperiode]);
        $jml_kelas_gabungan = count($cek_kelas_gabungan);

        for ($h = 0; $h < $jml_kelas_gabungan; $h++) {
            $kelas = $cek_kelas_gabungan[$h];

            $id_kurperiode = $kelas->id_kurperiode;

            $cek_idbap_gabungan = Bap::where('id_kurperiode', $kelas->id_kurperiode)
                ->where('pertemuan', $cek_bap->pertemuan)
                ->where('status', 'ACTIVE')
                ->select('id_bap')
                ->first();

            $jml_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
                ->where('absensi', 'ABSEN')
                ->count();
            $jml_tdk_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
                ->where('absensi', 'HADIR')
                ->count();

            $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['hadir' => $jml_hadir]);
            $bp = Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['tidak_hadir' => $jml_tdk_hadir]);
        }

        $id_kur = $cek_bap->id_kurperiode;

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
        $bulan = [
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
        ];
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
            'pertemuan' => 'required',
            'tanggal' => 'required',
            'jam_mulai' => 'required',
            'jam_selsai' => 'required',
            'jenis_kuliah' => 'required',
            'id_tipekuliah' => 'required',
            'metode_kuliah' => 'required',
            'materi_kuliah' => 'required',
            'file_kuliah_tatapmuka' => 'mimes:jpg,jpeg,png|max:2048',
            'file_materi_kuliah' => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG,docx,DOCX,PDF|max:4000',
            'file_materi_tugas' => 'mimes:jpg,jpeg,png|max:2048',
        ]);

        $data_bap = Bap::where('id_bap', $id)->first();
        $data = Kurikulum_periode::where('id_kurperiode', $request->id_kurperiode)->first();
        $sama = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
            ->where('periode_tahun.id_periodetahun', $data->id_periodetahun)
            ->where('periode_tipe.id_periodetipe', $data->id_periodetipe)
            ->where('kurikulum_periode.id_dosen', $data->id_dosen)
            ->where('kurikulum_periode.id_jam', $data->id_jam)
            ->where('kurikulum_periode.id_hari', $data->id_hari)
            ->where('bap.pertemuan', $data_bap->pertemuan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('bap.status', 'ACTIVE')
            ->select('kurikulum_periode.id_kurperiode', 'bap.id_bap')
            ->get();

        $jml_id = count($sama);

        for ($i = 0; $i < $jml_id; $i++) {
            $tes = $sama[$i];
            $d = $tes['id_kurperiode'];
            $e = $tes['id_bap'];

            $path_tatapmuka = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d . '/' . 'Kuliah Tatap Muka';

            if (!File::exists($path_tatapmuka)) {
                File::makeDirectory(public_path() . '/' . $path_tatapmuka, 0777, true);
            }

            $path_materikuliah = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d . '/' . 'Materi Kuliah';

            if (!File::exists($path_materikuliah)) {
                File::makeDirectory($path_materikuliah);
            }

            $path_tugaskuliah = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d . '/' . 'Tugas Kuliah';

            if (!File::exists($path_tugaskuliah)) {
                File::makeDirectory($path_tugaskuliah);
            }

            $bap = Bap::find($e);
            $bap->id_kurperiode = $d;
            $bap->pertemuan = $request->pertemuan;
            $bap->tanggal = $request->tanggal;
            $bap->jam_mulai = $request->jam_mulai;
            $bap->jam_selsai = $request->jam_selsai;
            $bap->jenis_kuliah = $request->jenis_kuliah;
            $bap->id_tipekuliah = $request->id_tipekuliah;
            $bap->metode_kuliah = $request->metode_kuliah;
            $bap->materi_kuliah = $request->materi_kuliah;
            $bap->media_pembelajaran = $request->media_pembelajaran;

            if ($i == 0) {
                if ($bap->file_kuliah_tatapmuka) {
                    if ($request->hasFile('file_kuliah_tatapmuka')) {
                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Kuliah Tatap Muka/' . $bap->file_kuliah_tatapmuka);
                        $file = $request->file('file_kuliah_tatapmuka');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Kuliah Tatap Muka';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_kuliah_tatapmuka = $nama_file;
                    }
                } else {
                    if ($request->hasFile('file_kuliah_tatapmuka')) {
                        $file = $request->file('file_kuliah_tatapmuka');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Kuliah Tatap Muka';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_kuliah_tatapmuka = $nama_file;
                    }
                }

                if ($bap->file_materi_kuliah) {
                    if ($request->hasFile('file_materi_kuliah')) {
                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Materi Kuliah/' . $bap->file_materi_kuliah);
                        $file = $request->file('file_materi_kuliah');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Materi Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_kuliah = $nama_file;
                    }
                } else {
                    if ($request->hasFile('file_materi_kuliah')) {
                        $file = $request->file('file_materi_kuliah');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Materi Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_kuliah = $nama_file;
                    }
                }

                if ($bap->file_materi_tugas) {
                    if ($request->hasFile('file_materi_tugas')) {
                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Tugas Kuliah/' . $bap->file_materi_tugas);
                        $file = $request->file('file_materi_tugas');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Tugas Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_tugas = $nama_file;
                    }
                } else {
                    if ($request->hasFile('file_materi_tugas')) {
                        $file = $request->file('file_materi_tugas');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Tugas Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_tugas = $nama_file;
                    }
                }
            } elseif ($i > 0) {
                if ($bap->file_kuliah_tatapmuka) {
                    if ($request->hasFile('file_kuliah_tatapmuka')) {
                        $tes1 = $sama[0];
                        $d1 = $tes1['id_kurperiode'];

                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Kuliah Tatap Muka/' . $bap->file_kuliah_tatapmuka);
                        $file = $request->file('file_kuliah_tatapmuka');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Kuliah Tatap Muka';

                        $tes2 = $sama[$i];
                        $d2 = $tes2['id_kurperiode'];
                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Kuliah Tatap Muka/' . $bap->file_kuliah_tatapmuka);
                        $path = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Kuliah Tatap Muka';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_kuliah_tatapmuka = $nama_file1;
                    }
                } else {
                    if ($request->hasFile('file_kuliah_tatapmuka')) {
                        $tes1 = $sama[0];
                        $d1 = $tes1['id_kurperiode'];
                        $file = $request->file('file_kuliah_tatapmuka');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Kuliah Tatap Muka';

                        $tes2 = $sama[$i];
                        $d2 = $tes2['id_kurperiode'];

                        $path = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Kuliah Tatap Muka';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_kuliah_tatapmuka = $nama_file1;
                    }
                }

                if ($bap->file_materi_kuliah) {
                    if ($request->hasFile('file_materi_kuliah')) {
                        $tes1 = $sama[0];
                        $d1 = $tes1['id_kurperiode'];

                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Materi Kuliah/' . $bap->file_materi_kuliah);
                        $file = $request->file('file_materi_kuliah');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Materi Kuliah';

                        $tes2 = $sama[$i];
                        $d2 = $tes2['id_kurperiode'];
                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Materi Kuliah/' . $bap->file_materi_kuliah);
                        $path = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Materi Kuliah';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_materi_kuliah = $nama_file1;
                    }
                } else {
                    if ($request->hasFile('file_materi_kuliah')) {
                        $tes1 = $sama[0];
                        $d1 = $tes1['id_kurperiode'];
                        $file = $request->file('file_materi_kuliah');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Materi Kuliah';

                        $tes2 = $sama[$i];
                        $d2 = $tes2['id_kurperiode'];

                        $path = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Materi Kuliah';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_materi_kuliah = $nama_file1;
                    }
                }

                if ($bap->file_materi_tugas) {
                    if ($request->hasFile('file_materi_tugas')) {
                        $tes1 = $sama[0];
                        $d1 = $tes1['id_kurperiode'];

                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Tugas Kuliah/' . $bap->file_materi_tugas);
                        $file = $request->file('file_materi_tugas');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Tugas Kuliah';

                        $tes2 = $sama[$i];
                        $d2 = $tes2['id_kurperiode'];
                        File::delete('File_BAP/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Tugas Kuliah/' . $bap->file_materi_tugas);
                        $path = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Tugas Kuliah';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_materi_tugas = $nama_file1;
                    }
                } else {
                    if ($request->hasFile('file_materi_tugas')) {
                        $tes1 = $sama[0];
                        $d1 = $tes1['id_kurperiode'];
                        $file = $request->file('file_materi_tugas');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d1 . '/' . 'Tugas Kuliah';

                        $tes2 = $sama[$i];
                        $d2 = $tes2['id_kurperiode'];

                        $path = 'File_BAP' . '/' . Auth::user()->id_user . '/' . $d2 . '/' . 'Tugas Kuliah';

                        $nama_file1 = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                        $bap->file_materi_tugas = $nama_file1;
                    }
                }
            }

            $bap->save();

            Kuliah_transaction::where('id_bap', $e)->update(['id_tipekuliah' => $request->id_tipekuliah]);

            Kuliah_transaction::where('id_bap', $e)->update(['tanggal' => $request->tanggal]);

            Kuliah_transaction::where('id_bap', $e)->update(['akt_jam_mulai' => $request->jam_mulai]);

            Kuliah_transaction::where('id_bap', $e)->update(['akt_jam_selesai' => $request->jam_selsai]);
        }

        Alert::success('', 'BAP berhasil diedit')->autoclose(3500);
        return redirect('entri_bap_dsn/' . $request->id_kurperiode);
    }

    public function delete_bap($id)
    {
        $data_bap = Bap::where('id_bap', $id)->first();
        $data = Kurikulum_periode::where('id_kurperiode', $data_bap->id_kurperiode)->first();
        $sama = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
            ->where('periode_tahun.id_periodetahun', $data->id_periodetahun)
            ->where('periode_tipe.id_periodetipe', $data->id_periodetipe)
            ->where('kurikulum_periode.id_dosen', $data->id_dosen)
            ->where('kurikulum_periode.id_jam', $data->id_jam)
            ->where('kurikulum_periode.id_hari', $data->id_hari)
            ->where('bap.pertemuan', $data_bap->pertemuan)
            ->select('kurikulum_periode.id_kurperiode', 'bap.id_bap')
            ->get();

        $jml_id = count($sama);

        for ($i = 0; $i < $jml_id; $i++) {
            $tes = $sama[$i];
            // $d = $tes['id_kurperiode'];
            $e = $tes['id_bap'];

            Bap::where('id_bap', $e)->update(['status' => 'NOT ACTIVE']);

            Kuliah_transaction::where('id_bap', $e)->update(['status' => 'NOT ACTIVE']);

            Absensi_mahasiswa::where('id_bap', $e)->update(['status' => 'NOT ACTIVE']);
        }

        $idk = Bap::where('id_bap', $id)
            ->select('id_kurperiode')
            ->get();

        foreach ($idk as $key) {
            # code...
        }

        Alert::success('', 'BAP berhasil dihapus')->autoclose(3500);
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

        $pdf = PDF::loadView('dosen/download/absensi_perkuliahan_pdf', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key])->setPaper('a4', 'landscape');
        return $pdf->download('Absensi Matakuliah' . ' ' . $makul . ' ' . $tahun . ' ' . $tipe . ' ' . $kelas . '.pdf');
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
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir', 'kuliah_transaction.tanggal_validasi')
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
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir', 'kuliah_transaction.tanggal_validasi')
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        $bulan = [
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
        ];
        $d = date('d');
        $m = $bulan[date('m')];
        $y = date('Y');

        return view('dosenluar/cetak_jurnal', ['cekkprd' => $cekkprd, 'd' => $d, 'm' => $m, 'y' => $y, 'bap' => $key, 'data' => $data]);
    }

    public function download_jurnal($id)
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
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir', 'kuliah_transaction.tanggal_validasi')
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        $pdf = PDF::loadView('dosen/download/jurnal_perkuliahan_pdf', ['cekkprd' => $cekkprd, 'bap' => $key, 'data' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('Jurnal Matakuliah' . ' ' . $makul . ' ' . $tahun . ' ' . $tipe . ' ' . $kelas . '.pdf');
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
        $bulan = [
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
        ];
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
            ->orderBy('bap.id_bap', 'ASC')
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

    public function pembimbing_pkl()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosenluar/prausta/pembimbing_pkl', compact('data'));
    }

    public function record_bim_pkl($id)
    {
        $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.acc_seminar_sidang', 'student.idstudent', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi')
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_master_kode.kode_prausta', ['FA-601', 'TI-601', 'TK-601'])
            ->get();

        return view('dosenluar/prausta/cek_bimbingan_pkl', compact('jdl', 'pkl'));
    }

    public function komentar_bimbingan_dsnlr(Request $request, $id)
    {
        $prd = Prausta_trans_bimbingan::find($id);
        $prd->komentar_bimbingan = $request->komentar_bimbingan;
        $prd->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function val_bim_pkl($id)
    {
        $val = Prausta_trans_bimbingan::find($id);
        $val->validasi = 'SUDAH';
        $val->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function status_judul(Request $request)
    {
        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update(['acc_judul_dospem' => $request->acc_judul]);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function acc_seminar_pkl($id)
    {
        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['acc_seminar_sidang' => 'TERIMA']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function tolak_seminar_pkl($id)
    {
        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['acc_seminar_sidang' => 'TOLAK']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function penguji_pkl()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            //->where('prausta_trans_hasil.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [1, 2, 3])
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.acc_seminar_sidang', 'prausta_trans_hasil.validasi')
            ->get();

        return view('dosenluar/prausta/penguji_pkl', compact('data'));
    }

    public function isi_form_nilai_pkl($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_dosbing = Prausta_master_penilaian::where('kategori', 1)
            ->where('jenis_form', 'Form Pembimbing')
            ->where('status', 'ACTIVE')
            ->get();

        $form_seminar = Prausta_master_penilaian::where('kategori', 1)
            ->where('jenis_form', 'Form Seminar')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosenluar/prausta/form_nilai_pkl', compact('data', 'id', 'form_dosbing', 'form_seminar'));
    }

    public function simpan_nilai_prakerin(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $nilai_pem_lap = $request->nilai_pembimbing_lapangan;
        $id_penilaian1 = $request->id_penilaian_prausta1;
        $id_penilaian2 = $request->id_penilaian_prausta2;
        $nilai1 = $request->nilai1;
        $nilai2 = $request->nilai2;

        $hitung_id_penilaian1 = count($id_penilaian1);
        $hitung_id_penilaian2 = count($id_penilaian2);

        for ($i = 0; $i < $hitung_id_penilaian1; $i++) {
            $id_nilai1 = $id_penilaian1[$i];
            $n1 = $nilai1[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai1;
            $usta->nilai = $n1;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai2;
            $usta->nilai = $n2;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai_1 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 1)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $ceknilai_2 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 1)
            ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
            ->first();

        // $nilai_2 = $request->total;
        // $nilai_3 = $request->totals;

        if ($nilai_pem_lap == null) {
            $huruf = ($ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 2;
        } elseif ($nilai_pem_lap != null) {
            $huruf = ($nilai_pem_lap + $ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 3;
        }

        $hasilavg = round($huruf, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $usta = new Prausta_trans_hasil();
        $usta->id_settingrelasi_prausta = $id_prausta;
        $usta->nilai_1 = $nilai_pem_lap;
        $usta->nilai_2 = $ceknilai_1->nilai1;
        $usta->nilai_3 = $ceknilai_2->nilai2;
        $usta->nilai_huruf = $nilai_huruf;
        $usta->added_by = Auth::user()->name;
        $usta->status = 'ACTIVE';
        $usta->data_origin = 'eSIAM';
        $usta->save();

        Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
        return redirect('penguji_pkl_dsnlr');
    }

    public function edit_nilai_pkl_by_dosen_luar($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pkl = Prausta_trans_hasil::where('prausta_trans_hasil.id_settingrelasi_prausta', $id)->first();
        $nilai_1 = $nilai_pkl->nilai_1;

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 1)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        $nilai_sem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 1)
            ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosenluar/prausta/edit_nilai_prakerin', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
    }

    public function put_nilai_prakerin_dosen_luar(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $nilai_pem_lap = $request->nilai_pembimbing_lapangan;
        $id_penilaian1 = $request->id_penilaian_prausta1;
        $id_penilaian2 = $request->id_penilaian_prausta2;
        $nilai1 = $request->nilai1;
        $nilai2 = $request->nilai2;

        $hitung_id_penilaian1 = count($id_penilaian1);
        $hitung_id_penilaian2 = count($id_penilaian2);

        for ($i = 0; $i < $hitung_id_penilaian1; $i++) {
            $id_nilai1 = $id_penilaian1[$i];
            $n1 = $nilai1[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)->update([
                'nilai' => $n1,
                'updated_by' => Auth::user()->name,
            ]);
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)->update([
                'nilai' => $n2,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai_1 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 1)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $ceknilai_2 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 1)
            ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
            ->first();

        if ($nilai_pem_lap == null) {
            $huruf = ($ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 2;
        } elseif ($nilai_pem_lap != null) {
            $huruf = ($nilai_pem_lap + $ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 3;
        }

        $hasilavg = round($huruf, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $usta = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_1' => $nilai_pem_lap,
            'nilai_2' => $ceknilai_1->nilai1,
            'nilai_3' => $ceknilai_2->nilai2,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
        return redirect('penguji_pkl_dsnlr');
    }

    public function pembimbing_sempro()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosenluar/prausta/pembimbing_sempro', compact('data'));
    }

    public function record_bim_sempro($id)
    {
        $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.acc_seminar_sidang', 'student.idstudent', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi')
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->get();

        return view('dosenluar/prausta/cek_bimbingan_sempro', compact('jdl', 'pkl'));
    }

    public function penguji_sempro()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')

            ->where(function ($query) use ($id) {
                $query
                    ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            //->where('prausta_trans_hasil.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [4, 5, 6])
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.id_student', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.acc_seminar_sidang', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'prausta_setting_relasi.validasi_pembimbing', 'prausta_setting_relasi.validasi_penguji_1', 'prausta_setting_relasi.validasi_penguji_2')
            ->get();

        return view('dosenluar/prausta/penguji_sempro', compact('data', 'id'));
    }

    public function isi_form_nilai_proposal_dospem($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_dosbing = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Pembimbing')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosenluar/prausta/form_nilai_sempro_dospem', compact('data', 'id', 'form_dosbing'));
    }

    public function simpan_nilai_sempro_dospem(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dospem = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = $nilai_dospem / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_1 = $nilai_dospem;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_1' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect('penguji_sempro_dsnlr');
    }

    public function isi_form_nilai_proposal_dosji1($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng1 = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Penguji I')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosenluar/prausta/form_nilai_sempro_dosji1', compact('data', 'id', 'form_peng1'));
    }

    public function simpan_nilai_sempro_dosji1(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dosji1 = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = $nilai_dosji1 / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_2 = $nilai_dosji1;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dosji1 + $cek_nilai->nilai_1 + $cek_nilai->nilai_3) / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_2' => $nilai_dosji1,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect('penguji_sempro_dsnlr');
    }

    public function isi_form_nilai_proposal_dosji2($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng2 = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Penguji II')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosenluar/prausta/form_nilai_sempro_dosji2', compact('data', 'id', 'form_peng2'));
    }

    public function simpan_nilai_sempro_dosji2(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dosji2 = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = $nilai_dosji2 / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_3 = $nilai_dosji2;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dosji2 + $cek_nilai->nilai_1 + $cek_nilai->nilai_2) / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_3' => $nilai_dosji2,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect('penguji_sempro_dsnlr');
    }

    public function validasi_dospem_dsnlr($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update([
            'validasi_pembimbing' => 'SUDAH',
            'tgl_val_pembimbing' => $date,
        ]);

        return redirect()->back();
    }

    public function validasi_dosji1_dsnlr($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update([
            'validasi_penguji_1' => 'SUDAH',
            'tgl_val_penguji_1' => $date,
        ]);

        return redirect()->back();
    }

    public function validasi_dosji2_dsnlr($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update([
            'validasi_penguji_2' => 'SUDAH',
            'tgl_val_penguji_2' => $date,
        ]);

        return redirect()->back();
    }

    public function edit_nilai_sempro_by_dospem_luar($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosenluar/prausta/edit_nilai_sempro_dospem', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_dospem_luar(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dospem = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_1' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('penguji_sempro_dsnlr');
    }

    public function edit_nilai_sempro_by_dospeng1_luar($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosenluar/prausta/edit_nilai_sempro_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_dospeng1_luar(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
            ->first();

        $nilai_dospem = $ceknilai->nilai2;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_2' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('penguji_sempro_dsnlr');
    }

    public function edit_nilai_sempro_by_dospeng2_luar($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosenluar/prausta/edit_nilai_sempro_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_dospeng2_luar(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
            ->first();

        $nilai_dospem = $ceknilai->nilai3;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_3' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('penguji_sempro_dsnlr');
    }

    public function pembimbing_ta()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosenluar/prausta/pembimbing_ta', compact('data'));
    }

    public function record_bim_ta($id)
    {
        $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.acc_seminar_sidang', 'student.idstudent', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'prausta_setting_relasi.file_plagiarisme')
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->get();

        return view('dosenluar/prausta/cek_bimbingan_ta', compact('jdl', 'pkl'));
    }

    public function penguji_ta()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')

            ->where(function ($query) use ($id) {
                $query
                    ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            //->where('prausta_trans_hasil.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [7, 8, 9])
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.acc_seminar_sidang', 'prausta_trans_hasil.validasi', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'prausta_setting_relasi.id_student', 'prausta_setting_relasi.file_plagiarisme')
            ->get();

        return view('dosenluar/prausta/penguji_ta', compact('data', 'id'));
    }

    public function isi_form_nilai_ta_dospem($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_dosbing = Prausta_master_penilaian::where('kategori', 3)
            ->where('jenis_form', 'Form Pembimbing')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosenluar/prausta/form_nilai_ta_dospem', compact('data', 'id', 'form_dosbing'));
    }

    public function simpan_nilai_ta_dospem(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dospem = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = ($nilai_dospem * 60) / 100;

            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_1 = $nilai_dospem;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dospem * 60) / 100 + ($cek_nilai->nilai_2 * 20) / 100 + ($cek_nilai->nilai_3 * 20) / 100;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_1' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai berhasil dientri')->autoclose(3500);
        return redirect('penguji_ta_dsnlr');
    }

    public function isi_form_nilai_ta_dosji1($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng1 = Prausta_master_penilaian::where('kategori', 3)
            ->where('jenis_form', 'Form Penguji I')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosenluar/prausta/form_nilai_ta_dosji1', compact('data', 'id', 'form_peng1'));
    }

    public function simpan_nilai_ta_dosji1(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dosji1 = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = ($nilai_dosji1 * 20) / 100;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_2 = $nilai_dosji1;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dosji1 * 20) / 100 + ($cek_nilai->nilai_1 * 60) / 100 + ($cek_nilai->nilai_3 * 20) / 100;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_2' => $nilai_dosji1,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai berhasil dientri')->autoclose(3500);
        return redirect('penguji_ta_dsnlr');
    }

    public function isi_form_nilai_ta_dosji2($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng2 = Prausta_master_penilaian::where('kategori', 3)
            ->where('jenis_form', 'Form Penguji II')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosenluar/prausta/form_nilai_ta_dosji2', compact('data', 'id', 'form_peng2'));
    }

    public function simpan_nilai_ta_dosji2(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dosji2 = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = ($nilai_dosji2 * 20) / 100;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_3 = $nilai_dosji2;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dosji2 * 20) / 100 + ($cek_nilai->nilai_1 * 60) / 100 + ($cek_nilai->nilai_2 * 20) / 100;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_3' => $nilai_dosji2,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai berhasil dientri')->autoclose(3500);
        return redirect('penguji_ta_dsnlr');
    }

    public function edit_nilai_ta_by_dospem_luar($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosenluar/prausta/edit_nilai_ta', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_ta_dospem_luar(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dospem = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem * 60) / 100 + ($cek_nilai->nilai_2 * 20) / 100 + ($cek_nilai->nilai_3 * 20) / 100;
        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_1' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
        return redirect('penguji_ta_dsnlr');
    }

    public function edit_nilai_ta_by_dospeng1_luar($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosenluar/prausta/edit_nilai_ta_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_ta_dospeng1_luar(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
            ->first();

        $nilai_dospeng1 = $ceknilai->nilai2;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospeng1 * 20) / 100 + ($cek_nilai->nilai_1 * 60) / 100 + ($cek_nilai->nilai_3 * 20) / 100;

        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_2' => $nilai_dospeng1,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
        return redirect('penguji_ta_dsnlr');
    }

    public function edit_nilai_ta_by_dospeng2_luar($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 3)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosenluar/prausta/edit_nilai_ta_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_ta_dospeng2_luar(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
            ->first();

        $nilai_dospeng2 = $ceknilai->nilai3;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospeng2 * 20) / 100 + ($cek_nilai->nilai_1 * 60) / 100 + ($cek_nilai->nilai_2 * 20) / 100;

        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_3' => $nilai_dospeng2,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
        return redirect('penguji_ta_dsnlr');
    }

    public function jadwal_seminar_prakerin_luar()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where(function ($query) use ($id) {
                $query
                    ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [1, 2, 3])
            ->select('student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang', 'prausta_setting_relasi.ruangan')
            ->get();

        return view('dosenluar/prausta/jadwal_seminar_prakerin', compact('data'));
    }

    public function jadwal_seminar_proposal_luar()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where(function ($query) use ($id) {
                $query
                    ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [4, 5, 6])
            ->select('student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.dosen_penguji_2', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang', 'prausta_setting_relasi.ruangan')
            ->get();

        return view('dosenluar/prausta/jadwal_seminar_proposal', compact('data'));
    }

    public function jadwal_sidang_ta_luar()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where(function ($query) use ($id) {
                $query
                    ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [7, 8, 9])
            ->select('student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.dosen_penguji_2', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang', 'prausta_setting_relasi.ruangan')
            ->get();

        return view('dosenluar/prausta/jadwal_sidang_ta', compact('data'));
    }

    public function simpan_soal_uts_dsn_luar(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,
            [
                'soal_uts' => 'mimes:pdf,docx,DOCX,PDF,doc,DOC|max:4000',
            ],
            $message,
        );

        $cek = Soal_ujian::where('id_kurperiode', $request->id_kurperiode)->first();

        if ($cek == null) {
            $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
            $jml_kelas = count($kelas_gabungan);

            for ($i = 0; $i < $jml_kelas; $i++) {
                $kurperiode = $kelas_gabungan[$i];

                $path_soal = 'Soal Ujian/' . 'UTS/' . $kurperiode->id_kurperiode;

                if (!File::exists($path_soal)) {
                    File::makeDirectory(public_path() . '/' . $path_soal, 0777, true);
                }

                // $path_soal_uts = 'Soal Ujian/' . 'UTS/' . $kurperiode->id_kurperiode;

                // if (!File::exists($path_soal_uts)) {
                //     File::makeDirectory($path_soal_uts);
                // }

                $info = new Soal_ujian();
                $info->id_kurperiode = $kurperiode->id_kurperiode;
                $info->created_by = Auth::user()->name;

                // if ($request->hasFile('soal_uts')) {
                //     $file = $request->file('soal_uts');

                //     $nama_file = time() . '_' . $file->getClientOriginalName();

                //     $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $kurperiode->id_kurperiode;
                //     $file->move($tujuan_upload, $nama_file);
                //     $info->soal_uts = $nama_file;
                // }

                if ($request->hasFile('soal_uts')) {
                    $tes1 = $kelas_gabungan[0];
                    $d1 = $tes1->id_kurperiode;
                    $file = $request->file('soal_uts');
                    $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $d1;
                    $nama_file = time() . '_' . $file->getClientOriginalName();

                    $tes2 = $kelas_gabungan[$i];
                    $d2 = $tes2->id_kurperiode;
                    $path = 'Soal Ujian' . '/' . 'UTS/' . $d2;
                    $nama_file1 = time() . '_' . $file->getClientOriginalName();
                   
                    File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                    $info->soal_uts = $nama_file1;
                }

                $info->save();
            }
        } elseif ($cek != null) {
            $id = $cek->id_soal;
            $info = Soal_ujian::find($id);

            if ($info->soal_uts) {
                if ($request->hasFile('soal_uts')) {
                    File::delete('Soal Ujian/' . 'UTS/' . $request->id_kurperiode . '/' . $info->soal_uts);

                    $file = $request->file('soal_uts');

                    $nama_file = time() . '_' . $file->getClientOriginalName();

                    // isi dengan nama folder tempat kemana file diupload
                    $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $request->id_kurperiode;
                    $file->move($tujuan_upload, $nama_file);
                    $info->soal_uts = $nama_file;
                }
            } else {
                if ($request->hasFile('soal_uts')) {
                    $file = $request->file('soal_uts');

                    $nama_file = time() . '_' . $file->getClientOriginalName();

                    // isi dengan nama folder tempat kemana file diupload
                    $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $request->id_kurperiode;
                    $file->move($tujuan_upload, $nama_file);
                    $info->soal_uts = $nama_file;
                }
            }

            $info->save();
        }

        $kurper = Kurikulum_periode::where('id_kurperiode', $request->id_kurperiode)->first();

        $periodetahun = Periode_tahun::where('id_periodetahun', $kurper->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $kurper->id_periodetipe)->first();
        $nama_periodetahun = $periodetahun->periode_tahun;
        $nama_periodetipe = $periodetipe->periode_tipe;
        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;

        $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tp = Periode_tipe::all();

        $id = Auth::user()->id_user;

        $makul = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->leftjoin('soal_ujian', 'kurikulum_periode.id_kurperiode', '=', 'soal_ujian.id_kurperiode')
            ->where('kurikulum_periode.id_dosen', $id)
            ->where('periode_tahun.id_periodetahun', $idperiodetahun)
            ->where('periode_tipe.id_periodetipe', $idperiodetipe)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_hari.hari', 'kurikulum_jam.jam', 'kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'soal_ujian.soal_uts', 'soal_ujian.soal_uas')
            ->get();

        Alert::success('', 'Soal berhasil ditambahkan')->autoclose(3500);
        return view('dosenluar/makul_diampu', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
    }

    public function simpan_soal_uas_dsn_luar(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,
            [
                'soal_uas' => 'mimes:pdf,docx,DOCX,PDF,doc,DOC|max:4000',
            ],
            $message,
        );

        $cek = Soal_ujian::where('id_kurperiode', $request->id_kurperiode)->first();

        if ($cek == null) {
            $info = new Soal_ujian();
            $info->id_kurperiode = $request->id_kurperiode;
            $info->created_by = Auth::user()->name;

            if ($request->hasFile('soal_uas')) {
                $file = $request->file('soal_uas');

                $nama_file = time() . '_' . $file->getClientOriginalName();

                $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $request->id_kurperiode;
                $file->move($tujuan_upload, $nama_file);
                $info->soal_uas = $nama_file;
            }

            $info->save();
        } elseif ($cek != null) {
            $id = $cek->id_soal;
            $info = Soal_ujian::find($id);

            if ($info->soal_uas) {
                if ($request->hasFile('soal_uas')) {
                    File::delete('Soal Ujian/' . 'UAS/' . $request->id_kurperiode . '/' . $info->soal_uas);

                    $file = $request->file('soal_uas');

                    $nama_file = time() . '_' . $file->getClientOriginalName();

                    // isi dengan nama folder tempat kemana file diupload
                    $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $request->id_kurperiode;
                    $file->move($tujuan_upload, $nama_file);
                    $info->soal_uas = $nama_file;
                }
            } else {
                if ($request->hasFile('soal_uas')) {
                    $file = $request->file('soal_uas');

                    $nama_file = time() . '_' . $file->getClientOriginalName();

                    // isi dengan nama folder tempat kemana file diupload
                    $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $request->id_kurperiode;
                    $file->move($tujuan_upload, $nama_file);
                    $info->soal_uas = $nama_file;
                }
            }

            $info->save();
        }

        $kurper = Kurikulum_periode::where('id_kurperiode', $request->id_kurperiode)->first();

        $periodetahun = Periode_tahun::where('id_periodetahun', $kurper->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $kurper->id_periodetipe)->first();
        $nama_periodetahun = $periodetahun->periode_tahun;
        $nama_periodetipe = $periodetipe->periode_tipe;
        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;

        $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tp = Periode_tipe::all();

        $id = Auth::user()->id_user;

        $makul = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->leftjoin('soal_ujian', 'kurikulum_periode.id_kurperiode', '=', 'soal_ujian.id_kurperiode')
            ->where('kurikulum_periode.id_dosen', $id)
            ->where('periode_tahun.id_periodetahun', $idperiodetahun)
            ->where('periode_tipe.id_periodetipe', $idperiodetipe)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_hari.hari', 'kurikulum_jam.jam', 'kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'soal_ujian.soal_uts', 'soal_ujian.soal_uas')
            ->get();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return view('dosenluar/makul_diampu', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
    }

    public function download_bap_pkl_dsn_luar($id)
    {
        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'prodi.id_prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_trans_hasil.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.tanggal_selesai', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'dosen.nama as nama_dsn', 'dosen.nik', 'dosen.akademik')
            ->first();
        if ($data == null) {
            Alert::warning('', 'Data PKL Belum ada')->autoclose(3500);
            return redirect('pembimbing_pkl_dsnlr');
        } else {
            $nama = $data->nama;
            $nim = $data->nim;
            $kelas = $data->kelas;
            $idprodi = $data->id_prodi;

            $kaprodi = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
                ->where('kaprodi.id_prodi', $idprodi)
                ->select('dosen.nama', 'dosen.nik', 'dosen.akademik')
                ->first();
            $nama_kaprodi = $kaprodi->nama;
            $akademik_kaprodi = $kaprodi->akademik;
            $nik_kaprodi = $kaprodi->nik;

            $cektgl = date(' d F Y', strtotime($data->tanggal_selesai));
            $cekhari = date('l', strtotime($data->tanggal_selesai));

            switch ($cekhari) {
                case 'Sunday':
                    $hari = 'Minggu';
                    break;
                case 'Monday':
                    $hari = 'Senin';
                    break;
                case 'Tuesday':
                    $hari = 'Selasa';
                    break;
                case 'Wednesday':
                    $hari = 'Rabu';
                    break;
                case 'Thursday':
                    $hari = 'Kamis';
                    break;
                case 'Friday':
                    $hari = 'Jum\'at';
                    break;
                case 'Saturday':
                    $hari = 'Sabtu';
                    break;
                default:
                    $hari = 'Tidak ada';
                    break;
            }

            $bulan = [
                1 => 'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember',
            ];

            $pecahkan = explode('-', $data->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];

            $pdf = PDF::loadView('prausta/prakerin/unduh_bap_prakerin', compact('data', 'hari', 'tglhasil', 'nama_kaprodi', 'nik_kaprodi', 'akademik_kaprodi'))->setPaper('a4');
            return $pdf->download('BAP Prakerin' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function download_bap_sempro_dsn_luar($id)
    {
        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_trans_hasil.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.dosen_penguji_2', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.id_dosen_pembimbing', 'prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'dosen.nama as nama_dsn', 'dosen.akademik')
            ->first();

        if ($data == null) {
            Alert::warning('', 'Data SEMPRO Belum ada')->autoclose(3500);
            return redirect('pembimbing_sempro_dsnlr');
        } else {
            $nama = $data->nama;
            $nim = $data->nim;
            $kelas = $data->kelas;

            $cektgl = date(' d F Y', strtotime($data->tanggal_selesai));
            $cekhari = date('l', strtotime($data->tanggal_selesai));

            switch ($cekhari) {
                case 'Sunday':
                    $hari = 'Minggu';
                    break;
                case 'Monday':
                    $hari = 'Senin';
                    break;
                case 'Tuesday':
                    $hari = 'Selasa';
                    break;
                case 'Wednesday':
                    $hari = 'Rabu';
                    break;
                case 'Thursday':
                    $hari = 'Kamis';
                    break;
                case 'Friday':
                    $hari = 'Jum\'at';
                    break;
                case 'Saturday':
                    $hari = 'Sabtu';
                    break;
                default:
                    $hari = 'Tidak ada';
                    break;
            }

            $bulan = [
                1 => 'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember',
            ];

            $pecahkan = explode('-', $data->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];

            $dospem = Dosen::where('iddosen', $data->id_dosen_pembimbing)->first();

            $dospeng1 = Dosen::where('iddosen', $data->id_dosen_penguji_1)->first();

            $dospeng2 = Dosen::where('iddosen', $data->id_dosen_penguji_2)->first();

            $pdf = PDF::loadView('prausta/sempro/unduh_bap_sempro', compact('data', 'hari', 'tglhasil', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
            return $pdf->download('BAP Sempro' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function download_bap_ta_dsn_luar($id)
    {
        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_trans_hasil.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing', 'prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.tanggal_selesai', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'dosen.nama as nama_dsn', 'dosen.akademik')
            ->first();

        if ($data == null) {
            Alert::warning('', 'Data TA Belum ada')->autoclose(3500);
            return redirect('pembimbing_ta_dsnlr');
        } else {
            $nama = $data->nama;
            $nim = $data->nim;
            $kelas = $data->kelas;

            $cektgl = date(' d F Y', strtotime($data->tanggal_selesai));
            $cekhari = date('l', strtotime($data->tanggal_selesai));

            switch ($cekhari) {
                case 'Sunday':
                    $hari = 'Minggu';
                    break;
                case 'Monday':
                    $hari = 'Senin';
                    break;
                case 'Tuesday':
                    $hari = 'Selasa';
                    break;
                case 'Wednesday':
                    $hari = 'Rabu';
                    break;
                case 'Thursday':
                    $hari = 'Kamis';
                    break;
                case 'Friday':
                    $hari = 'Jum\'at';
                    break;
                case 'Saturday':
                    $hari = 'Sabtu';
                    break;
                default:
                    $hari = 'Tidak ada';
                    break;
            }

            $bulan = [
                1 => 'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember',
            ];

            $pecahkan = explode('-', $data->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];

            $dospem = Dosen::where('iddosen', $data->id_dosen_pembimbing)->first();

            $dospeng1 = Dosen::where('iddosen', $data->id_dosen_penguji_1)->first();

            $dospeng2 = Dosen::where('iddosen', $data->id_dosen_penguji_2)->first();

            $pdf = PDF::loadView('prausta/ta/unduh_bap_ta', compact('data', 'hari', 'tglhasil', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
            return $pdf->download('BAP TA' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function generate_nilai_akhir_dsn_luar(Request $request)
    {
        $idkur = $request->id_kurperiode;

        $set_nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();
        $kat = $set_nilai->kat;
        $uts = $set_nilai->uts;
        $uas = $set_nilai->uas;

        // $data1 = Student_record::where('id_kurperiode', $idkur)->get();
        $data = DB::select('CALL absen_mahasiswa(?)', [$idkur]);
        $jml_mhs = count($data);

        for ($i = 0; $i < $jml_mhs; $i++) {
            $nilai = $data[$i];

            $id_record = $nilai->id_studentrecord;
            $id_student = $nilai->id_student;
            $n_kat = $nilai->nilai_KAT;
            $n_uts = $nilai->nilai_UTS;
            $n_uas = $nilai->nilai_UAS;
            $id_kurtrans = $nilai->id_kurtrans;

            $cek_id = Student_record::where('id_student', $id_student)
                ->where('id_kurtrans', $id_kurtrans)
                ->get();

            $banyak_id = count($cek_id);

            $hsl_kat = ($n_kat * $kat) / 100;
            $hsl_uts = ($n_uts * $uts) / 100;
            $hsl_uas = ($n_uas * $uas) / 100;

            $n_total = $hsl_kat + $hsl_uts + $hsl_uas;

            if ($banyak_id == 1) {
                $id = $id_record;
                $ceknilai = Student_record::find($id);
                $ceknilai->nilai_AKHIR_angka = $n_total;
                $ceknilai->save();

                if ($n_total < 50) {
                    $id = $id_record;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'E';
                    $ceknilai->nilai_ANGKA = '0';
                    $ceknilai->save();
                } elseif ($n_total < 60) {
                    $id = $id_record;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'D';
                    $ceknilai->nilai_ANGKA = '1';
                    $ceknilai->save();
                } elseif ($n_total < 65) {
                    $id = $id_record;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'C';
                    $ceknilai->nilai_ANGKA = '2';
                    $ceknilai->save();
                } elseif ($n_total < 70) {
                    $id = $id_record;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'C+';
                    $ceknilai->nilai_ANGKA = '2.5';
                    $ceknilai->save();
                } elseif ($n_total < 75) {
                    $id = $id_record;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'B';
                    $ceknilai->nilai_ANGKA = '3';
                    $ceknilai->save();
                } elseif ($n_total < 80) {
                    $id = $id_record;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'B+';
                    $ceknilai->nilai_ANGKA = '3.5';
                    $ceknilai->save();
                } elseif ($n_total <= 100) {
                    $id = $id_record;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'A';
                    $ceknilai->nilai_ANGKA = '4';
                    $ceknilai->save();
                }
            } elseif ($banyak_id > 1) {
                Student_record::where('id_student', $id_student)
                    ->where('id_kurtrans', $id_kurtrans)
                    ->update(['nilai_AKHIR_angka' => $n_total]);

                if ($n_total < 50) {
                    Student_record::where('id_student', $id_student)
                        ->where('id_kurtrans', $id_kurtrans)
                        ->update([
                            'nilai_AKHIR' => 'E',
                            'nilai_ANGKA' => '0',
                        ]);
                } elseif ($n_total < 60) {
                    Student_record::where('id_student', $id_student)
                        ->where('id_kurtrans', $id_kurtrans)
                        ->update([
                            'nilai_AKHIR' => 'D',
                            'nilai_ANGKA' => '1',
                        ]);
                } elseif ($n_total < 65) {
                    Student_record::where('id_student', $id_student)
                        ->where('id_kurtrans', $id_kurtrans)
                        ->update([
                            'nilai_AKHIR' => 'C',
                            'nilai_ANGKA' => '2',
                        ]);
                } elseif ($n_total < 70) {
                    Student_record::where('id_student', $id_student)
                        ->where('id_kurtrans', $id_kurtrans)
                        ->update([
                            'nilai_AKHIR' => 'C+',
                            'nilai_ANGKA' => '2.5',
                        ]);
                } elseif ($n_total < 75) {
                    Student_record::where('id_student', $id_student)
                        ->where('id_kurtrans', $id_kurtrans)
                        ->update([
                            'nilai_AKHIR' => 'B',
                            'nilai_ANGKA' => '3',
                        ]);
                } elseif ($n_total < 80) {
                    Student_record::where('id_student', $id_student)
                        ->where('id_kurtrans', $id_kurtrans)
                        ->update([
                            'nilai_AKHIR' => 'B+',
                            'nilai_ANGKA' => '3.5',
                        ]);
                } elseif ($n_total <= 100) {
                    Student_record::where('id_student', $id_student)
                        ->where('id_kurtrans', $id_kurtrans)
                        ->update([
                            'nilai_AKHIR' => 'A',
                            'nilai_ANGKA' => '4',
                        ]);
                }
            }
        }
        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();

        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idkur]);

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $idkur)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;
        $idkur = $idkur;

        return view('dosenluar/list_mhs', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function post_settingnilai_dsn_luar(Request $request)
    {
        $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
        $jml_id_kur = count($cek_kelas_gabungan);

        for ($i = 0; $i < $jml_id_kur; $i++) {
            $idkurperiode = $cek_kelas_gabungan[$i];

            $kpr = new Setting_nilai();
            $kpr->id_kurperiode = $idkurperiode->id_kurperiode;
            $kpr->kat = $request->kat;
            $kpr->uts = $request->uts;
            $kpr->uas = $request->uas;
            $kpr->created_by = Auth::user()->name;
            $kpr->save();
        }

        //cek setting nilai
        $idkur = $request->id_kurperiode;
        $nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();

        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idkur]);

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $idkur)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;
        $idkur = $idkur;

        Alert::success('Berhasil');
        return view('dosenluar/list_mhs', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function put_settingnilai_dsn_luar(Request $request, $id)
    {
        $id_setting = Setting_nilai::where('id_settingnilai', $id)->first();
        $cek_kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$id_setting->id_kurperiode]);
        $jml_id_kur = count($cek_kelas_gabungan);

        for ($i = 0; $i < $jml_id_kur; $i++) {
            $idkurperiode = $cek_kelas_gabungan[$i];

            Setting_nilai::where('id_kurperiode', $idkurperiode->id_kurperiode)->update([
                'kat' => $request->kat,
                'uts' => $request->uts,
                'uas' => $request->uas,
                'updated_by' => Auth::user()->name,
            ]);
        }

        //cek setting nilai
        $idkur = $request->id_kurperiode;
        $nilai = Setting_nilai::where('id_kurperiode', $idkur)->first();

        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$idkur]);

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $idkur)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;
        $idkur = $idkur;

        Alert::success('Berhasil');
        return view('dosenluar/list_mhs', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function sop_dsn_luar()
    {
        $data = Standar::where('status', 'ACTIVE')->get();

        return view('dosenluar/sop', compact('data'));
    }
}
