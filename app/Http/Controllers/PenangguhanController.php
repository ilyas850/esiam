<?php

namespace App\Http\Controllers;

use File;
use RealRashid\SweetAlert\Facades\Alert;
use App\Prodi;
use App\Student;
use App\Sertifikat;
use App\Yudisium;
use App\Waktu_krs;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Student_record;
use App\Kurikulum_master;
use App\Kurikulum_transaction;
use App\Penangguhan_trans;
use App\Edom_transaction;
use App\Kuisioner_transaction;
use App\Prausta_setting_relasi;
use App\Ujian_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenangguhanController extends Controller
{
    public function penangguhan_krs($id)
    {
        $waktu_krs = Waktu_krs::where('status', 1)->first();

        if ($waktu_krs == null) {

            alert()->error('Maaf KRS Belum dibuka', 'Silahkan menghubungi BAAK');
            return redirect()->back();
        } elseif ($waktu_krs->status == 1) {
            $ids = Auth::user()->id_user;
            $thn = Periode_tahun::where('status', 'ACTIVE')->first();
            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            $dt_penangguhan = Penangguhan_trans::where('id_penangguhan_trans', $id)
                ->where('id_periodetahun', $thn->id_periodetahun)
                ->where('id_periodetipe', $tp->id_periodetipe)
                ->first();
            if ($dt_penangguhan == null) {
                alert()->error('Maaf Periode KRS Sudah Berakhir', 'Silahkan menghubungi BAAK');
                return redirect()->back();
            } else {

                $data_mhs = Student::leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                    ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                    ->where('student.idstudent', $ids)
                    ->select(
                        'student.idstudent',
                        'student.nama',
                        'student.nim',
                        'kelas.kelas',
                        'prodi.prodi',
                        'prodi.konsentrasi',
                        'student.idangkatan',
                        'student.idstatus',
                        'student.kodeprodi',
                        'student.intake'
                    )
                    ->first();

                $idperiodetahun = $thn->id_periodetahun;
                $idperiodetipe = $tp->id_periodetipe;
                $periodetahun = $thn->periode_tahun;
                $periodetipe = $tp->periode_tipe;

                //data KRS yang diambil
                $data_krs = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                    ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
                    ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
                    ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
                    ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                    ->where('student_record.id_student', $ids)
                    ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                    ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                    ->where('student_record.status', 'TAKEN')
                    ->where('kurikulum_periode.status', 'ACTIVE')
                    ->select('student_record.remark', 'student_record.id_studentrecord', 'student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
                    ->orderBy('kurikulum_periode.id_hari', 'ASC')
                    ->orderBy('kurikulum_periode.id_jam', 'ASC')
                    ->get();

                //cek sks dari KRS
                $sks_krs = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->where('student_record.id_student', $ids)
                    ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                    ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                    ->where('student_record.status', 'TAKEN')
                    ->select('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                    ->groupBy('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                    ->get();

                //jumlah SKS
                $sks = 0;
                foreach ($sks_krs as $keysks) {
                    $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
                }

                return view('mhs/penangguhan/penangguhan_krs', compact('data_krs', 'sks_krs', 'data_mhs', 'idperiodetahun', 'idperiodetipe', 'periodetahun', 'periodetipe', 'sks'));
            }
        }
    }

    public function input_krs_penangguhan(Request $request)
    {
        $id = Auth::user()->id_user;
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;

        $data_mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.idstudent',
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'student.kodekonsentrasi',
                'student.intake'
            )
            ->first();

        $idangkatan = $data_mhs->idangkatan;
        $idstatus = $data_mhs->idstatus;
        $kodeprodi = $data_mhs->kodeprodi;
        $kodekonsentrasi = $data_mhs->kodekonsentrasi;
        $intake = $data_mhs->intake;

        $thn = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
        $tp = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        //cek semester
        $sub_thn = substr($thn->periode_tahun, 6, 2);
        $tipe = $tp->id_periodetipe;
        $smt = $sub_thn . $tipe;

        if ($smt % 2 != 0) {
            if ($tipe == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10; // ( 211 + 10 - 1 ) / 10 = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                if ($intake == 2) {
                    $c = ($b * 2) - 1 - 1;
                } elseif ($intake == 1) {
                    $c = ($b * 2) - 1;
                } // 2 * 2 - 1 = 3
            } elseif ($tipe == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10; // ( 213 + 10 - 3 ) / 10  = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                // $c = ($b * 2);
                if ($intake == 2) {
                    $c = $b * 2 - 1;
                } elseif ($intake == 1) {
                    $c = $b * 2;
                }
            }
        } else {
            //genap
            $a = (($smt + 10) - 2) / 10; // (212 + 10 - 2) / 10 = 22
            $b = $a - $idangkatan; // 22 - 20 = 2
            // 2 * 2 = 4
            if ($intake == 2) {
                $c = $b * 2 - 1;
            } elseif ($intake == 1) {
                $c = $b * 2;
            }
        }

        if ($kodeprodi == 24) {
            $value = Prodi::where('kodeprodi', $kodeprodi)->first();
        } else {
            $value = Prodi::where('kodeprodi', $kodeprodi)
                ->where('kodekonsentrasi', $kodekonsentrasi)
                ->first();
        }

        $krlm = Kurikulum_master::where('remark', $intake)->first();

        if ($kodeprodi == 23 or $kodeprodi == 25 or $kodeprodi == 22) {
            if ($kodekonsentrasi == null) {
                alert()->warning('Anda tidak dapat melakukan KRS karena Anda belum memiliki konsentrasi', 'Hubungi Prodi masing-masing')->autoclose(5000);
                return redirect()->back();
            } else {
                if ($tipe == 3) {
                    $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                        ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
                        ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                        ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                        ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                        ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                        ->where('kurikulum_periode.id_periodetipe', $tipe)
                        ->where('kurikulum_periode.id_kelas', $idstatus)
                        ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                        ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                        ->where('kurikulum_periode.status', 'ACTIVE')
                        ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
                        ->where('matakuliah_bom.status', 'ACTIVE');

                    $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                        ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                        ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                        ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                        ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                        ->where('kurikulum_periode.id_periodetipe', $tipe)
                        ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                        ->where('kurikulum_periode.id_kelas', $idstatus)
                        ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                        ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                        ->where('kurikulum_periode.status', 'ACTIVE')
                        ->where('kurikulum_transaction.status', 'ACTIVE')
                        ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
                        ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
                        ->union($add_krs)
                        ->get();

                    return view('mhs/penangguhan/form_penangguhan_krs', compact('final_krs'));
                } else {
                    $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                        ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
                        ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
                        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                        ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                        ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                        ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                        ->where('kurikulum_periode.id_periodetipe', $tipe)
                        ->where('kurikulum_periode.id_kelas', $idstatus)
                        ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                        ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                        ->where('kurikulum_transaction.id_semester', $c)
                        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                        ->where('kurikulum_periode.status', 'ACTIVE')
                        ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
                        ->where('matakuliah_bom.status', 'ACTIVE');

                    $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                        ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                        ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
                        ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                        ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                        ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                        ->where('kurikulum_periode.id_periodetipe', $tipe)
                        ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                        ->where('kurikulum_periode.id_kelas', $idstatus)
                        ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                        ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                        ->where('kurikulum_transaction.id_semester', $c)
                        ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                        ->where('kurikulum_periode.status', 'ACTIVE')
                        ->where('kurikulum_transaction.status', 'ACTIVE')
                        ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
                        ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
                        ->union($add_krs)
                        ->get();

                    return view('mhs/penangguhan/form_penangguhan_krs', compact('final_krs'));
                }
            }
        } elseif ($kodeprodi == 24) {
            if ($tipe == 3) {
                $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                    ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
                    ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                    ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                    ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                    ->where('kurikulum_periode.id_periodetipe', $tipe)
                    ->where('kurikulum_periode.id_kelas', $idstatus)
                    ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                    ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                    ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                    ->where('kurikulum_periode.status', 'ACTIVE')
                    ->where('matakuliah_bom.status', 'ACTIVE');

                $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                    ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                    ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                    ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                    ->where('kurikulum_periode.id_periodetipe', $tipe)
                    ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                    ->where('kurikulum_periode.id_kelas', $idstatus)
                    ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                    ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                    ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                    ->where('kurikulum_periode.status', 'ACTIVE')
                    ->where('kurikulum_transaction.status', 'ACTIVE')
                    ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
                    ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
                    ->union($add_krs)
                    ->get();

                return view('mhs/penangguhan/form_penangguhan_krs', compact('final_krs'));
            } else {
                $add_krs = Kurikulum_transaction::join('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                    ->join('kurikulum_periode', 'matakuliah_bom.slave_idmakul', '=', 'kurikulum_periode.id_makul')
                    ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                    ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                    ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                    ->where('kurikulum_periode.id_periodetipe', $tipe)
                    ->where('kurikulum_periode.id_kelas', $idstatus)
                    ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                    ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                    ->where('kurikulum_transaction.id_semester', $c)
                    ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                    ->where('kurikulum_periode.status', 'ACTIVE')
                    ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
                    ->where('matakuliah_bom.status', 'ACTIVE');

                $final_krs = Kurikulum_transaction::leftjoin('matakuliah_bom', 'kurikulum_transaction.id_makul', '=', 'matakuliah_bom.master_idmakul')
                    ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
                    ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
                    ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
                    ->where('kurikulum_periode.id_periodetipe', $tipe)
                    ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                    ->where('kurikulum_periode.id_kelas', $idstatus)
                    ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
                    ->where('kurikulum_periode.id_prodi', $value->id_prodi)
                    ->where('kurikulum_transaction.id_semester', $c)
                    ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                    ->where('kurikulum_periode.status', 'ACTIVE')
                    ->where('kurikulum_transaction.status', 'ACTIVE')
                    ->where('kurikulum_transaction.pelaksanaan_paket', 'OPEN')
                    ->whereNotIn('kurikulum_periode.id_makul', [386, 384, 385, 387])
                    ->union($add_krs)
                    ->get();

                return view('mhs/penangguhan/form_penangguhan_krs', compact('final_krs'));
            }
        }
    }

    public function save_penangguhan_krs(Request $request)
    {
        $id = Auth::user()->id_user;

        $jml = count($request->id_kurperiode);

        for ($i = 0; $i < $jml; $i++) {
            $kurp = $request->id_kurperiode[$i];
            $idr = explode(',', $kurp, 2);
            $tra = $idr[0];
            $trs = $idr[1];
            $cekkrs = Student_record::where('id_student', $id)
                ->where('id_kurperiode', $tra)
                ->where('id_kurtrans', $trs)
                ->where('status', 'TAKEN')
                ->get();

            if (count($cekkrs) == 0) {
                $krs = new Student_record;
                $krs->tanggal_krs   = date("Y-m-d");
                $krs->id_student    = $id;
                $krs->data_origin   = 'eSIAM';
                $krs->id_kurperiode = $tra;
                $krs->id_kurtrans   = $trs;
                $krs->save();
            }
        }
        Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
        return redirect('penangguhan_krs');
    }

    public function penangguhan_absen_ujian($id)
    {
        $ids = Auth::user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $ids)
            ->select(
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi'
            )
            ->first();

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $id_tahun = $periode_tahun->id_periodetahun;
        $id_tipe = $periode_tipe->id_periodetipe;

        $cek_ujian = Ujian_transaction::where('ujian_transaction.id_periodetahun', $id_tahun)
            ->where('ujian_transaction.id_periodetipe', $id_tipe)
            ->where('ujian_transaction.status', 'ACTIVE')
            ->select(
                DB::raw('MAX(ujian_transaction.id_ujiantrans)'),
                'ujian_transaction.id_periodetahun',
                'ujian_transaction.id_periodetipe',
                'ujian_transaction.jenis_ujian'
            )
            ->groupBy(
                'ujian_transaction.id_periodetahun',
                'ujian_transaction.id_periodetipe',
                'ujian_transaction.jenis_ujian'
            )
            ->get();

        $hitung_ujian = count($cek_ujian);

        $dt_penangguhan = Penangguhan_trans::where('id_penangguhan_trans', $id)
            ->where('id_periodetahun', $periode_tahun->id_periodetahun)
            ->where('id_periodetipe', $periode_tipe->id_periodetipe)
            ->first();

        if ($dt_penangguhan == null) {
            alert()->error('Maaf Periode Download Kartu UTS Sudah Berakhir', 'Silahkan menghubungi BAAK');
            return redirect()->back();
        } else {

            $data_ujian = DB::select('CALL absensi_ujian(?,?,?)', [$id_tahun, $id_tipe, $ids]);

            return view('mhs/ujian/absensi_ujian', compact('periode_tahun', 'periode_tipe', 'datamhs', 'data_ujian'));
        }
    }

    public function penangguhan_kartu_uts($id)
    {
        $ids = Auth::user()->id_user;
        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $ids)
            ->select('student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.id_prodi')
            ->first();

        $idprodi = $datamhs->id_prodi;

        $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
            ->first();

        $dt_penangguhan = Penangguhan_trans::where('id_penangguhan_trans', $id)
            ->where('id_periodetahun', $thn->id_periodetahun)
            ->where('id_periodetipe', $tp->id_periodetipe)
            ->first();

        if ($dt_penangguhan == null) {
            alert()->error('Maaf Periode Download Kartu UTS Sudah Berakhir', 'Silahkan menghubungi BAAK');
            return redirect()->back();
        } else {

            $data_uts = DB::select('CALL jadwal_uts(?,?,?,?,?)', [$ids, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

            return view('mhs/penangguhan/penangguhan_uts', compact('periodetahun', 'periodetipe', 'datamhs', 'data_uts'));
        }
    }

    public function penangguhan_kartu_uas($id)
    {
        $ids = Auth::user()->id_user;
        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $ids)
            ->select('student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.id_prodi')
            ->first();

        $idprodi = $datamhs->id_prodi;

        $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->where('student_record.id_student', $ids)
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->get();

        $hit = count($records);

        //cek jumlah pengisian edom
        $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->where('edom_transaction.id_student', $ids)
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
            ->get();

        $sekhit = count($cekedom);

        if ($hit == $sekhit) {
            //cek kuisioner pembimbing akademik
            $cek_kuis_pa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 1)
                ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                ->get();

            if (count($cek_kuis_pa) > 0) {
                //cek kuisioner BAAK
                $cek_kuis_baak = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                    ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                    ->where('kuisioner_transaction.id_student', $ids)
                    ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
                    ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                    ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                    ->get();

                if (count($cek_kuis_baak) > 0) {
                    //cek kuisioner BAUK
                    $cek_kuis_bauk = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                        ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                        ->where('kuisioner_transaction.id_student', $ids)
                        ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
                        ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                        ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                        ->get();

                    if (count($cek_kuis_bauk) > 0) {
                        //cek kuisioner PERPUS
                        $cek_kuis_perpus = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                            ->where('kuisioner_transaction.id_student', $ids)
                            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
                            ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                            ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                            ->get();

                        $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                            ->where('student_record.id_student', $ids)
                            ->where('student_record.status', 'TAKEN')
                            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                            ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
                            ->first();

                        if (count($cek_kuis_perpus) > 0) {
                            $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->where('student_record.id_student', $ids)
                                ->where('student_record.status', 'TAKEN')
                                ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                                ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                                ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
                                ->first();

                            $data_uts = DB::select('CALL jadwal_uas(?,?,?,?,?)', [$ids, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

                            return view('mhs/penangguhan/penangguhan_uas', compact('periodetahun', 'periodetipe', 'datamhs', 'data_uts'));
                        } elseif (count($cek_kuis_perpus) == 0) {
                            Alert::error('Maaf anda belum melakukan pengisian kuisioner PERPUSTAKAAN', 'MAAF !!');
                            return redirect()->back();
                        }
                    } elseif (count($cek_kuis_bauk) == 0) {
                        Alert::error('Maaf anda belum melakukan pengisian kuisioner BAUK', 'MAAF !!');
                        return redirect()->back();
                    }
                } elseif (count($cek_kuis_baak) == 0) {
                    Alert::error('Maaf anda belum melakukan pengisian kuisioner BAAK', 'MAAF !!');
                    return redirect()->back();
                }
            } elseif (count($cek_kuis_pa) == 0) {
                Alert::error('Maaf anda belum melakukan pengisian kuisioner Pembimbing Akademik', 'MAAF !!');
                return redirect()->back();
            }
        } else {
            Alert::error('Maaf anda belum melakukan pengisian edom', 'MAAF !!');
            return redirect()->back();
        }
    }

    public function penangguhan_yudisium($id)
    {
        $ids = Auth::user()->id_user;

        $data_mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $ids)
            ->select(
                'student.idstudent',
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi'
            )
            ->first();

        $idangkatan = $data_mhs->idangkatan;
        $idstatus = $data_mhs->idstatus;
        $kodeprodi = $data_mhs->kodeprodi;
        $idprodi = $data_mhs->id_prodi;

        $cekdata_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->where('prausta_setting_relasi.id_student', $ids)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.validasi_baak'
            )
            ->first();

        if ($cekdata_prausta->validasi_baak == 'SUDAH') {
            //cek nilai kosong atau tidak lulus
            $cek_kur = Kurikulum_transaction::join('student_record', 'kurikulum_transaction.idkurtrans', '=', 'student_record.id_kurtrans')
                ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                ->where('kurikulum_transaction.id_prodi', $idprodi)
                ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                ->where('kurikulum_transaction.status', 'ACTIVE')
                ->where('student_record.id_student', $ids)
                ->where('student_record.status', 'TAKEN')
                ->where(function ($query) {
                    $query
                        ->where('student_record.nilai_AKHIR', '0')
                        ->orWhere('student_record.nilai_AKHIR', 'D')
                        ->orWhere('student_record.nilai_AKHIR', 'E');
                })
                ->select('kurikulum_transaction.id_makul', 'matakuliah.makul', 'student_record.nilai_AKHIR')
                ->get();

            $hitjml_kur = count($cek_kur);

            if ($hitjml_kur == 0) {
                $serti = Sertifikat::where('id_student', $ids)->count();

                if ($serti >= 10) {
                    //cek kuisioner dosen pembimbing pkl
                    $cek_kuis_dospem_pkl = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                        ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                        ->where('kuisioner_transaction.id_student', $ids)
                        ->where('kuisioner_master_kategori.id_kategori_kuisioner', 2)
                        ->count();

                    if (($cek_kuis_dospem_pkl) > 0) {

                        //cek kuisioner dosen pembimbing ta
                        $cek_kuis_dospem_ta = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                            ->where('kuisioner_transaction.id_student', $ids)
                            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 3)
                            ->count();

                        if (($cek_kuis_dospem_ta) > 0) {

                            //cek kuisioner dosen penguji 1 ta
                            $cek_kuis_dospeng_ta_1 = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                ->where('kuisioner_transaction.id_student', $ids)
                                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 4)
                                ->count();

                            if (($cek_kuis_dospeng_ta_1) > 0) {

                                //cek kuisioner dosen penguji 2 ta
                                $cek_kuis_dospeng_ta_2 = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                    ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                    ->where('kuisioner_transaction.id_student', $ids)
                                    ->where('kuisioner_master_kategori.id_kategori_kuisioner', 5)
                                    ->count();

                                if (($cek_kuis_dospeng_ta_2) > 0) {
                                    $data = Yudisium::where('id_student', $ids)->first();

                                    return view('mhs/penangguhan/penangguhan_yudisium', compact('id', 'data', 'ids'));
                                } else {
                                    alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen Penguji 2 TA')->autoclose(5000);
                                    return redirect('home');
                                }
                            } else {
                                alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen Penguji 1 TA')->autoclose(5000);
                                return redirect('home');
                            }
                        } else {
                            alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen pembimbing TA')->autoclose(5000);
                            return redirect('home');
                        }
                    } else {
                        alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen pembimbing PKL')->autoclose(5000);
                        return redirect('home');
                    }
                } else {
                    alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena sertifikat anda kurang dari 10')->autoclose(5000);
                    return redirect('home');
                }
            } else {
                alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena ada nilai yang masih kosong / belum lulus')->autoclose(5000);
                return redirect('home');
            }
        } else {
            alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena BAAK belum validasi Tugas Akhir anda')->autoclose(5000);
            return redirect('home');
        }
    }

    public function save_penangguhan_yudisium(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi'
        ];
        $this->validate(
            $request,
            [
                'nama_lengkap' => 'required',
                'tmpt_lahir' => 'required',
                'tgl_lahir' => 'required',
                'nik' => 'required',
                'file_ijazah'    => 'mimes:jpg,jpeg,JPG,JPEG|max:4000',
                'file_ktp'      => 'mimes:jpg,jpeg,JPG,JPEG|max:4000',
                'file_foto'     => 'mimes:jpg,jpeg,JPG,JPEG|max:4000',
            ],
            $message,
        );

        $bap = new Yudisium();
        $bap->id_student = $request->id_student;
        $bap->nama_lengkap = $request->nama_lengkap;
        $bap->tmpt_lahir = $request->tmpt_lahir;
        $bap->tgl_lahir = $request->tgl_lahir;
        $bap->nik = $request->nik;

        if ($request->hasFile('file_ijazah')) {
            $file = $request->file('file_ijazah');
            $nama_file = 'File Ijazah' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
            $tujuan_upload = 'File Yudisium/' . $request->id_student;
            $file->move($tujuan_upload, $nama_file);
            $bap->file_ijazah = $nama_file;
        }

        if ($request->hasFile('file_ktp')) {
            $file = $request->file('file_ktp');
            $nama_file = 'File KTP' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
            $tujuan_upload = 'File Yudisium/' . $request->id_student;
            $file->move($tujuan_upload, $nama_file);
            $bap->file_ktp = $nama_file;
        }

        if ($request->hasFile('file_foto')) {
            $file = $request->file('file_foto');
            $nama_file = 'File Foto' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
            $tujuan_upload = 'File Yudisium/' . $request->id_student;
            $file->move($tujuan_upload, $nama_file);
            $bap->file_foto = $nama_file;
        }

        $bap->save();

        Alert::success('', 'Data Yudisium berhasil ditambahkan')->autoclose(3500);
        return redirect()->back();
    }

    public function put_penangguhan_yudisium(Request $request, $id)
    {
        $bap = Yudisium::find($id);
        $bap->id_student = Auth::user()->id_user;
        $bap->nama_lengkap = $request->nama_lengkap;
        $bap->tmpt_lahir = $request->tmpt_lahir;
        $bap->tgl_lahir = $request->tgl_lahir;
        $bap->nik = $request->nik;
        $bap->created_by = Auth::user()->name;

        if ($bap->file_ijazah) {
            if ($request->hasFile('file_ijazah')) {
                File::delete('File Yudisium/' . Auth::user()->id_user . '/' . $bap->file_ijazah);
                $file = $request->file('file_ijazah');
                $nama_file = 'File Ijazah' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ijazah = $nama_file;
            }
        } else {
            if ($request->hasFile('file_ijazah')) {
                $file = $request->file('file_ijazah');
                $nama_file = 'File Ijazah' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ijazah = $nama_file;
            }
        }

        if ($bap->file_ktp) {
            if ($request->hasFile('file_ktp')) {
                File::delete('File Yudisium/' . Auth::user()->id_user . '/' . $bap->file_ktp);
                $file = $request->file('file_ktp');
                $nama_file = 'File KTP' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ktp = $nama_file;
            }
        } else {
            if ($request->hasFile('file_ktp')) {
                $file = $request->file('file_ktp');
                $nama_file = 'File KTP' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ktp = $nama_file;
            }
        }

        if ($bap->file_foto) {
            if ($request->hasFile('file_foto')) {
                File::delete('File Yudisium/' . Auth::user()->id_user . '/' . $bap->file_foto);
                $file = $request->file('file_foto');
                $nama_file = 'File Foto' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_foto = $nama_file;
            }
        } else {
            if ($request->hasFile('file_foto')) {
                $file = $request->file('file_foto');
                $nama_file = 'File Foto' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_foto = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Data Yudisium berhasil diedit')->autoclose(3500);
        return redirect()->back();
    }
}
