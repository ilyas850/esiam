<?php

namespace App\Http\Controllers;

use PDF;
use File;
use Alert;
use Session;
use App\Bap;
use App\Itembayar;
use App\Absensi_mahasiswa;
use App\Edom_transaction;
use App\Kaprodi;
use App\User;
use App\Dosen;
use App\Kelas;
use App\Prodi;
use App\Ruangan;
use App\Kuitansi;
use App\Biaya;
use App\Beasiswa;
use App\Student;
use App\Semester;
use App\Angkatan;
use App\Matakuliah;
use App\Kuliah_tipe;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_master;
use App\Student_record;
use App\Dosen_pembimbing;
use App\Kuliah_transaction;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Ujian_transaction;
use App\Prausta_master_kode;
use App\Prausta_setting_relasi;
use App\Prausta_trans_bimbingan;
use App\Prausta_trans_hasil;
use App\Prausta_master_penilaian;
use App\Prausta_trans_penilaian;
use App\Setting_nilai;
use App\Standar;
use App\Pedoman_akademik;
use App\Penangguhan_kategori;
use App\Penangguhan_trans;
use App\Perwalian_trans_bimbingan;
use App\Exports\DataNilaiExport;
use App\Http\Requests;
use App\Pedoman_khusus;
use App\Soal_ujian;
use App\Absen_ujian;
use App\Permohonan_ujian;
use App\Pertemuan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class DosenController extends Controller
{
    public function mhs_bim()
    {
        $id = Auth::user()->id_user;

        $p = DB::select('CALL mhs_bim(?)', [$id]);

        $k = DB::table('student_record')
            ->leftjoin('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftjoin('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->leftjoin('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->leftjoin('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->leftjoin('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->select(
                'student.idstudent',
                'student.nama',
                'student.nim',
                'student_record.tanggal_krs',
                'angkatan.angkatan',
                'kelas.kelas',
                'prodi.prodi',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe'
            )
            ->whereIn('student_record.id_studentrecord', function ($query) {
                $query
                    ->from('student_record')
                    ->select(DB::raw('MAX(student_record.id_studentrecord)'))
                    ->groupBy('student_record.id_student');
            })
            ->where('student.active', 1)
            ->where('dosen_pembimbing.id_dosen', $id)
            ->where('student_record.status', 'TAKEN')
            ->get();

        return view('dosen/mhs_bim', ['mhs' => $k]);
    }

    public function record_nilai($id)
    {
        $mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
            ->first();

        $idangkatan = $mhs->idangkatan;
        $idstatus = $mhs->idstatus;
        $kodeprodi = $mhs->kodeprodi;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        //cek semester
        $sub_thn = substr($thn->periode_tahun, 6, 2);
        $tipe = $tp->id_periodetipe;
        $smt = $sub_thn . $tipe;

        if ($smt % 2 != 0) {
            if ($tipe == 1) {
                //ganjil
                $a = ($smt + 10 - 1) / 10;
                $b = $a - $idangkatan;
                $c = $b * 2 - 1;
            } elseif ($tipe == 3) {
                //pendek
                $a = ($smt + 10 - 3) / 10;
                $b = $a - $idangkatan;
                $c = $b * 2 . '0' . '1';
            }
        } else {
            //genap
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $idangkatan;
            $c = $b * 2;
        }

        // dd($c);
        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14')
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        //list biaya kuliah mahasiswa
        if ($cb != null) {
            $daftar = $biaya->daftar - ($biaya->daftar * $cb->daftar) / 100;
            $awal = $biaya->awal - ($biaya->awal * $cb->awal) / 100;
            $dsp = $biaya->dsp - ($biaya->dsp * $cb->dsp) / 100;
            $spp1 = $biaya->spp1 - ($biaya->spp1 * $cb->spp1) / 100;
            $spp2 = $biaya->spp2 - ($biaya->spp2 * $cb->spp2) / 100;
            $spp3 = $biaya->spp3 - ($biaya->spp3 * $cb->spp3) / 100;
            $spp4 = $biaya->spp4 - ($biaya->spp4 * $cb->spp4) / 100;
            $spp5 = $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100;
            $spp6 = $biaya->spp6 - ($biaya->spp6 * $cb->spp6) / 100;
            $spp7 = $biaya->spp7 - ($biaya->spp7 * $cb->spp7) / 100;
            $spp8 = $biaya->spp8 - ($biaya->spp8 * $cb->spp8) / 100;
            $spp9 = $biaya->spp9 - ($biaya->spp9 * $cb->spp9) / 100;
            $spp10 = $biaya->spp10 - ($biaya->spp10 * $cb->spp10) / 100;
            $spp11 = $biaya->spp11 - ($biaya->spp11 * $cb->spp11) / 100;
            $spp12 = $biaya->spp12 - ($biaya->spp12 * $cb->spp12) / 100;
            $spp13 = $biaya->spp13 - ($biaya->spp13 * $cb->spp13) / 100;
            $spp14 = $biaya->spp14 - ($biaya->spp14 * $cb->spp14) / 100;
        } elseif ($cb == null) {
            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
        }

        //total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->sum('bayar.bayar');

        if ($c == 1) {
            $cekbyr = $daftar + $awal - $total_semua_dibayar;
        } elseif ($c == 2) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 - $total_semua_dibayar;
        } elseif ($c == '201') {
            $cekbyr = $daftar + $awal + ($dsp * 91) / 100 + $spp1 + ($spp2 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 3) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 - $total_semua_dibayar;
        } elseif ($c == 4) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 - $total_semua_dibayar;
        } elseif ($c == '401') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 5) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
        } elseif ($c == 6) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 - $total_semua_dibayar;
        } elseif ($c == '601') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 7) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
        } elseif ($c == 8) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 - $total_semua_dibayar;
        } elseif ($c == '801') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 9) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
        } elseif ($c == 10) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 - $total_semua_dibayar;
        } elseif ($c == '1001') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 11) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
        } elseif ($c == 12) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 - $total_semua_dibayar;
        } elseif ($c == 13) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 - $total_semua_dibayar;
        } elseif ($c == 14) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 - $total_semua_dibayar;
        }

        if ($cekbyr < 0 or $cekbyr == 0) {
            $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->where('student_record.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tipe)
                ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                ->where('student_record.status', 'TAKEN')
                ->select('kurikulum_periode.id_makul')
                ->groupBy('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen')
                ->get();
            $hit = count($records);

            $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->where('edom_transaction.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tipe)
                ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
                ->get();
            $sekhit = count($cekedom);

            if ($hit == $sekhit) {
                $makul = Matakuliah::all();
                $cek = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                    ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->where('student_record.id_student', $id)
                    ->where('student_record.status', 'TAKEN')
                    ->select('kurikulum_periode.id_makul', 'student.nama', 'student.nim', 'student.idstatus', 'student.kodeprodi', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode')
                    ->groupBy('kurikulum_periode.id_makul', 'student.nama', 'student.nim', 'student.idstatus', 'student.kodeprodi', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', 'matakuliah.makul', 'matakuliah.kode')
                    ->get();
                foreach ($cek as $key) {
                    // code...
                }

                return view('dosen/record_nilai', ['cek' => $cek, 'key' => $mhs]);
            } else {
                Alert::error('maaf mahasiswa tersebut belum melakukan pengisian edom', 'MAAF !!');
                return redirect('mhs_bim');
            }
        } else {
            Alert::warning('Maaf anda tidak dapat melihat nilai mahasiswa ini karena keuangannya belum memenuhi syarat');
            return redirect('mhs_bim');
        }
    }

    public function val_krs()
    {
        $ids = Auth::user()->id_user;
        $tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $data_mhs = DB::select('CALL validasi_krs(' . $ids . ')');

        return view('dosen/validasi_krs', ['mhs' => $data_mhs, 'tahun' => $tahun, 'tipe' => $tipe]);
    }

    public function cek_krs($id)
    {
        //data mahasiswa politeknik
        $data_mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->select('student.nama', 'student.nim', 'prodi.id_prodi', 'prodi.prodi', 'kelas.kelas', 'student.idangkatan', 'student.kodeprodi', 'student.idstatus')
            ->where('student.idstudent', $id)
            ->first();

        //kode prodi
        $prod = Prodi::where('kodeprodi', $data_mhs->kodeprodi)->first();

        //tambah krs
        $krs = Kurikulum_transaction::join('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
            ->join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_master.status', 'ACTIVE')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')

            ->where('kurikulum_periode.id_prodi', $data_mhs->id_prodi)
            ->where('kurikulum_transaction.id_prodi', $data_mhs->id_prodi)
            ->where('kurikulum_transaction.id_angkatan', $data_mhs->idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('kurikulum_transaction.status', 'ACTIVE')
            ->select('kurikulum_periode.id_kurperiode', 'kurikulum_transaction.idkurtrans', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'dosen.nama', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'kelas.kelas')
            ->orderBy('semester.semester', 'ASC')
            ->orderBy('kurikulum_periode.id_kurperiode', 'ASC')
            ->get();

        //data krs diambil
        $val = Student_record::leftjoin('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->leftjoin('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('student_record.id_student', $id)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'student_record.remark', 'student_record.id_student', 'student_record.id_studentrecord')
            ->get();

        //cek validasi krs
        $valkrs = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('student_record.id_student', $id)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select(DB::raw('DISTINCT(student_record.remark)'), 'student.idstudent')
            ->get();

        if ($val->count() == 0) {
            Alert::error('', 'Maaf mahasiswa ini belum melakukan KRS')->autoclose(3500);
            return redirect()->back();
        } elseif ($val->count() > 0) {
            foreach ($valkrs as $valuekrs) {
                // code...
            }

            $b = $valuekrs->remark;
            return view('dosen/cek_krs', ['b' => $b, 'mhss' => $id, 'add' => $krs, 'val' => $val, 'key' => $data_mhs]);
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

    public function savekrs_new(Request $request)
    {
        $this->validate($request, [
            'id_student' => 'required',
            'id_kurperiode' => 'required',
        ]);

        $jml = count($request->id_kurperiode);
        for ($i = 0; $i < $jml; $i++) {
            $kurp = $request->id_kurperiode[$i];
            $idr = explode(',', $kurp, 2);
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
        } elseif (count($cekkrs) == 0) {
            $krs = new Student_record();
            $krs->tanggal_krs = date('Y-m-d');
            $krs->id_student = $request->id_student;
            $krs->id_kurperiode = $tra;
            $krs->id_kurtrans = $trs;
            $krs->save();

            Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
            return redirect()->back();
        }
    }

    public function krs_validasi(Request $request)
    {
        $id = $request->id_student;

        $krs = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->select(DB::raw('DISTINCT(kurikulum_transaction.idkurtrans)'), 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
            ->groupBy('kurikulum_transaction.idkurtrans', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
            ->get();

        $t = count($krs);

        $jumlah = 0;
        for ($i = 0; $i < $t; $i++) {
            $satu = $krs[$i];
            $skst[] = $satu['akt_sks_teori'];
            $sksp[] = $satu['akt_sks_praktek'];
        }

        $jumlahskst = array_sum($skst);
        $jumlahsksp = array_sum($sksp);

        $totalsks = $jumlahskst + $jumlahsksp;

        $id_dsn = Auth::user()->id_user;

        $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
            ->where('dosen_pembimbing.id_dosen', $id_dsn)
            ->where('student.idstudent', $id)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('student.active', [1, 5])
            ->select('student_record.id_student', 'student.nama', 'student.nim', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR')
            ->groupBy('student_record.id_student', 'student.nama', 'student.nim', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR')
            ->get();

        $hitungnilai_de = count($data);

        if ($totalsks > 24) {
            Alert::warning('maaf sks yang diambil mahasiswa ini melebihi 24 sks', 'MAAF !!');
            return redirect('val_krs');
        } elseif ($totalsks <= 24) {
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

            if ($hitungnilai_de > 0) {
                Alert::warning('Mahasiswa ini ada ' . $hitungnilai_de . ' matakuliah mengulang', 'Berhasil')->autoclose(3500);
                return redirect()->back();
            } elseif ($hitungnilai_de == 0) {
                Alert::success('', 'KRS Berhasil divalidasi')->autoclose(3500);
                return redirect()->back();
            }
        }
    }

    public function batal_krs_validasi(Request $request)
    {
        $id = $request->id_student;

        Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('student_record.id_student', $id)
            ->update(['student_record.remark' => $request->remark]);

        Alert::success('', 'KRS berhasil dibatalkan')->autoclose(3500);
        return redirect()->back();
    }

    public function change($id)
    {
        return view('dosen/change_pwd', ['dsn' => $id]);
    }

    public function store_pwd_dsn(Request $request, $id)
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

    public function makul_diampu_dsn()
    {
        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();
        $nama_periodetahun = $periodetahun->periode_tahun;
        $nama_periodetipe = $periodetipe->periode_tipe;
        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;

        $thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tp = Periode_tipe::all();

        $id = Auth::user()->id_user;

        $makul = DB::select('CALL matakuliah_diampu_dosen(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('dosen/matakuliah/makul_diampu_dsn', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
    }

    public function filter_makul_diampu_dsn_dlm(Request $request)
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

        return view('dosen/matakuliah/makul_diampu_dsn', compact('makul', 'nama_periodetahun', 'nama_periodetipe', 'thn', 'tp'));
    }

    public function export_xlsnilai($id)
    {
        // $id = $request->id_kurperiode;

        $keymk = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', 'kelas.idkelas')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select('matakuliah.makul', 'prodi.prodi', 'kelas.kelas')
            ->first();

        $mkul = $keymk->makul;
        $prdi = $keymk->prodi;
        $klas = $keymk->kelas;

        $nama_file = 'Nilai Matakuliah' . ' ' . $mkul . ' ' . $prdi . ' ' . $klas . '.xlsx';
        return Excel::download(new DataNilaiExport($id), $nama_file);
    }

    public function unduh_pdf_nilai($id)
    {
        // $id = $request->id_kurperiode;

        $mk = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', 'kelas.idkelas')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select('periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'dosen.nama', 'dosen.akademik', 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'prodi.prodi', 'kelas.kelas')
            ->get();

        $kelas_gabungan = DB::select('CALL absensi_mahasiswa_prodi_kelas(?)', [$id]);

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
        $pdf = PDF::loadView('dosen/unduh_nilai_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'data' => $key, 'tb' => $kelas_gabungan]);
        return $pdf->download('Nilai Matakuliah' . ' ' . $makul . ' ' . $tahun . ' ' . $tipe . ' ' . $kelas . '.pdf');
    }

    public function cekmhs_dsn($id)
    {
        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $id)->first();

        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        return view('dosen/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $id, 'nilai' => $nilai]);
    }

    public function input_kat_dsn($id)
    {
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        $kurrr = $id;

        return view('dosen/input_kat_dsn', ['kuri' => $kurrr, 'ck' => $kelas_gabungan, 'id' => $id]);
    }

    public function save_nilai_KAT_dsn(Request $request)
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
                        ->update(['nilai_KAT' => 0, 'data_origin' => 'eSIAM']);
                } elseif ($ceknl != null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_KAT' => $nilai, 'data_origin' => 'eSIAM']);
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

        return view('dosen/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function input_uts_dsn($id)
    {
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        $keymkl = Kurikulum_periode::where('id_kurperiode', $id)->first();

        $kmkl = $keymkl->id_makul;
        $kprd = $keymkl->id_prodi;
        $kkls = $keymkl->id_kelas;
        $kurrr = $id;

        return view('dosen/input_uts_dsn', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $kelas_gabungan, 'id' => $id]);
    }

    public function save_nilai_UTS_dsn(Request $request)
    {
        $jumlahid = $request->id_student;
        $jmlids = $request->id_studentrecord;
        $jmlnil = $request->nilai_UTS;

        $jml = count($jmlnil);

        for ($i = 0; $i < $jml; $i++) {
            $idstu = $jumlahid[$i];
            $pisah = explode(',', $idstu, 2);
            $stu = $pisah[0];
            $kur = $pisah[1];

            $cekid = Student_record::where('id_student', $stu)
                ->where('id_kurtrans', $kur)
                ->select('id_studentrecord')
                ->get();

            $banyak = count($cekid);

            $nilai = $request->nilai_UTS[$i];
            $id_kur = $jmlids[$i];
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
                        ->update(['nilai_UTS' => 0, 'data_origin' => 'eSIAM']);
                } elseif ($ceknl != null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UTS' => $nilai, 'data_origin' => 'eSIAM']);
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
                // ->where('id_prodi', $request->id_prodi)
                ->where('id_kelas', $request->id_kelas)
                ->where('id_makul', $request->id_makul)
                ->where('id_jam', $id_kurperiode->id_jam)
                ->where('id_ruangan', $id_kurperiode->id_ruangan)
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

        // $kur = $ckstr->id_kurtrans;
        $idkur = $request->id_kurperiode;

        return view('dosen/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur,  'nilai' => $nilai]);
    }

    public function input_uas_dsn($id)
    {
        //cek mahasiswa untuk input nilai
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$id]);

        $keymkl = Kurikulum_periode::where('id_kurperiode', $id)->first();

        $kmkl = $keymkl->id_makul;
        $kprd = $keymkl->id_prodi;
        $kkls = $keymkl->id_kelas;
        $kurrr = $id;

        return view('dosen/input_uas_dsn', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $kelas_gabungan, 'id' => $id]);
    }

    public function save_nilai_UAS_dsn(Request $request)
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
                } elseif ($ceknl != null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UAS' => $nilai, 'data_origin' => 'eSIAM']);
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
                // ->where('id_prodi', $request->id_prodi)
                ->where('id_kelas', $request->id_kelas)
                ->where('id_makul', $request->id_makul)
                ->where('id_jam', $id_kurperiode->id_jam)
                ->where('id_ruangan', $id_kurperiode->id_ruangan)
                ->update(['aktual_pengoreksi' => Auth::user()->name, 'data_origin' => 'eSIAM']);
        }

        //ke halaman list mahasiswa
        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absen_mahasiswa(?)', [$request->id_kurperiode]);

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('student_record.id_kurperiode', $request->id_kurperiode)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        // $kur = $ckstr->id_kurtrans;
        $idkur = $request->id_kurperiode;

        return view('dosen/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur,  'nilai' => $nilai]);
    }

    public function input_akhir_dsn($id)
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

        return view('dosen/input_akhir_dsn', ['kuri' => $kurrr, 'ck' => $cks, 'id' => $id]);
    }

    public function save_nilai_AKHIR_dsn(Request $request)
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
                    $ceknilai->save();
                } elseif ($ceknl != null) {
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
                } elseif ($ceknl < 60) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'D';
                    $ceknilai->nilai_ANGKA = '1';
                    $ceknilai->save();
                } elseif ($ceknl < 65) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'C';
                    $ceknilai->nilai_ANGKA = '2';
                    $ceknilai->save();
                } elseif ($ceknl < 70) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'C+';
                    $ceknilai->nilai_ANGKA = '2.5';
                    $ceknilai->save();
                } elseif ($ceknl < 75) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'B';
                    $ceknilai->nilai_ANGKA = '3';
                    $ceknilai->save();
                } elseif ($ceknl < 80) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'B+';
                    $ceknilai->nilai_ANGKA = '3.5';
                    $ceknilai->save();
                } elseif ($ceknl <= 100) {
                    $id = $id_kur;
                    $ceknilai = Student_record::find($id);
                    $ceknilai->nilai_AKHIR = 'A';
                    $ceknilai->nilai_ANGKA = '4';
                    $ceknilai->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR_angka' => 0]);
                } elseif ($ceknl != null) {
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
                } elseif ($ceknl < 60) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'D']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '1']);
                } elseif ($ceknl < 65) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'C']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '2']);
                } elseif ($ceknl < 70) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'C+']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '2.5']);
                } elseif ($ceknl < 75) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'B']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '3']);
                } elseif ($ceknl < 80) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_AKHIR' => 'B+']);

                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_ANGKA' => '3.5']);
                } elseif ($ceknl <= 100) {
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
        //cek setting nilai
        $nilai = Setting_nilai::where('id_kurperiode', $request->id_kurperiode)->first();
        //cek mahasiswa
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
            ->orderBy('student.nim', 'ASC')
            ->get();

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $request->id_kurperiode)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->first();

        $kur = $ckstr->id_kurtrans;
        $idkur = $request->id_kurperiode;

        return view('dosen/list_mhs_dsn', ['ck' => $cks, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function generate_nilai_akhir_dsn_dlm(Request $request)
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

        return view('dosen/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function entri_bap($id)
    {
        $id_dosen = Auth::user()->id_user;
        $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'prodi.konsentrasi', 'kelas.kelas', 'semester.semester')
            ->first();

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            // ->where('kuliah_transaction.id_dosen', $id_dosen)
            ->select(
                'kuliah_transaction.kurang_jam',
                'kuliah_transaction.tanggal_validasi',
                'bap.id_bap',
                'bap.pertemuan',
                'bap.tanggal',
                'bap.jam_mulai',
                'bap.jam_selsai',
                'bap.materi_kuliah',
                'bap.metode_kuliah',
                'kuliah_tipe.tipe_kuliah',
                'bap.jenis_kuliah',
                'bap.hadir',
                'bap.tidak_hadir'
            )
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        return view('dosen/bap', compact('data', 'bap'));
    }

    public function input_bap($id)
    {
        $jam = Kurikulum_jam::all();

        $sisa_pertemuan = Kuliah_transaction::join('bap', 'kuliah_transaction.id_kurperiode', '=', 'bap.id_kurperiode')
            ->where('kuliah_transaction.id_kurperiode', $id)

            ->get();

        $nilai_pertemuan = DB::table('pertemuan')
            ->select('pertemuan.id_pertemuan')

            ->leftJoin('bap', function ($join) use ($id) {
                $join->on('pertemuan.pertemuan', '=', 'bap.pertemuan')
                    ->where('bap.id_kurperiode', '=', $id)
                    ->where('bap.status', 'ACTIVE');
            })

            ->whereNull('bap.pertemuan')
            ->get();

        return view('dosen/form_bap', compact('id', 'jam', 'nilai_pertemuan'));
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
                'file_kuliah_tatapmuka' => 'image|mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
                'file_materi_kuliah' => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG,docx,DOCX,PDF|max:4000',
                'file_materi_tugas' => 'image|mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
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

            return redirect('entri_bap/' . $id_kur)->with('success', 'Data Berhasil diupload');
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

        return view('dosen/absensi', ['absen' => $kelas_gabungan, 'idk' => $idp, 'id' => $id]);
    }

    public function save_absensi(Request $request)
    {
        $id_record = $request->id_studentrecord;

        $id_kur = $request->id_kurperiode;

        $id_bp = $request->id_bap;

        $absen = $request->absensi;

        $cek_bap = Bap::where('id_bap', $id_bp)
            ->where('status', 'ACTIVE')
            ->select('id_bap', 'id_kurperiode', 'pertemuan', 'id_dosen')
            ->first();

        $cek_kelas_gabungan1 = DB::select('CALL kelas_gabungan_new(?)', [$id_kur]);
        $jml_kls_gabungan = count($cek_kelas_gabungan1);

        for ($z = 0; $z < $jml_kls_gabungan; $z++) {
            $kls_gabungan = $cek_kelas_gabungan1[$z];

            $id_kurpe = $kls_gabungan->id_kurperiode;

            $absensi_mahasiswa = DB::select('CALL absensi_mahasiswa_prodi_kelas(?)', [$id_kurpe]);
            $jml_absensi = count($absensi_mahasiswa);

            $get_id_bap = Bap::where('id_kurperiode', $id_kurpe)
                ->where('id_dosen', $cek_bap->id_dosen)
                ->where('pertemuan', $cek_bap->pertemuan)
                ->where('status', 'ACTIVE')
                ->select('id_bap')
                ->first();

            for ($y = 0; $y < $jml_absensi; $y++) {
                $get_idrecord = $absensi_mahasiswa[$y];
                $get_absensi = $absen[$y];

                $abs = new Absensi_mahasiswa();
                $abs->id_bap = $get_id_bap->id_bap;
                $abs->id_studentrecord = $get_idrecord->id_studentrecord;
                $abs->absensi = $get_absensi;
                $abs->save();
            }

            $jml_hadir = Absensi_mahasiswa::where('id_bap', $get_id_bap->id_bap)
                ->where('absensi', 'ABSEN')
                ->count();
            $jml_tdk_hadir = Absensi_mahasiswa::where('id_bap', $get_id_bap->id_bap)
                ->whereIn('absensi', ['HADIR', 'SAKIT', 'IZIN', 'ALFA'])
                ->count();

            Bap::where('id_bap', $get_id_bap->id_bap)->update(['hadir' => $jml_hadir]);
            Bap::where('id_bap', $get_id_bap->id_bap)->update(['tidak_hadir' => $jml_tdk_hadir]);
        }

        return redirect('entri_bap/' . $id_kur);
    }

    public function edit_absen($id)
    {
        $kur = Bap::where('id_bap', $id)->first();

        $idk = $kur->id_kurperiode;
        $per = $kur->pertemuan;

        $p = DB::select('CALL editAbsenMahasiswa(?,?)', [$idk, $per]);
        // $p1 = DB::select('CALL editAbsenMhs(?,?)', [$idk, $per]);

        return view('dosen/edit_absen', ['idk' => $idk, 'abs' => $p, 'id' => $id]);
    }

    public function save_edit_absensi(Request $request)
    {
        #id BAP
        $id_bp = $request->id_bap;

        #cek bap yang sama
        $bap_gabungan = DB::select('CALL bap_gabungan(?)', [$id_bp]);
        $jml_bap_gabungan = count($bap_gabungan);

        #jumlah yang masuk/absen
        $absen = $request->absensi;
        $jmlabsen = count($absen);

        #jumlah yang sebelumnya tidak masuk
        $absr = $request->abs;

        $cek_bap = Bap::where('id_bap', $id_bp)
            ->select('id_bap', 'id_kurperiode', 'pertemuan', 'id_dosen')
            ->first();

        for ($m = 0; $m < $jml_bap_gabungan; $m++) {
            $get_id_bap = $bap_gabungan[$m];
            $id_bap_found = $get_id_bap->id_bap;

            for ($n = 0; $n < $jmlabsen; $n++) {
                $get_id_student = $absen[$n];
                $idst = explode(',', $get_id_student, 2);
                $tra = $idst[0];
                $trs = $idst[1];
                $cek_hadir = Absensi_mahasiswa::where('id_bap', $id_bap_found)
                    ->where('id_studentrecord', $tra)
                    ->get();

                if (count($cek_hadir) == 0) {

                    $abs = new Absensi_mahasiswa();
                    $abs->id_bap = $id_bap_found;
                    $abs->id_studentrecord = $tra;
                    $abs->absensi = $trs;
                    $abs->save();
                } elseif (count($cek_hadir) > 0) {

                    Absensi_mahasiswa::where('id_bap', $id_bap_found)
                        ->where('id_studentrecord', $tra)
                        ->update(['absensi' => $trs]);
                }
            }

            $jml_hadir = Absensi_mahasiswa::where('id_bap', $id_bap_found)
                ->where('absensi', 'ABSEN')
                ->count();
            $jml_tdk_hadir = Absensi_mahasiswa::where('id_bap', $id_bap_found)
                ->whereIn('absensi', ['HADIR', 'SAKIT', 'IZIN', 'ALFA'])
                ->count();

            Bap::where('id_bap', $id_bap_found)->update(['hadir' => $jml_hadir]);
            Bap::where('id_bap', $id_bap_found)->update(['tidak_hadir' => $jml_tdk_hadir]);
        }

        // if ($absen != null) {
        //     #looping untuk edit semua absen jadi HADIR
        //     for ($i = 0; $i < $jml_bap_gabungan; $i++) {
        //         $id_bap_gabungan = $bap_gabungan[$i];
        //         $get_id_bap = $id_bap_gabungan->id_bap;

        //         Absensi_mahasiswa::where('id_bap', $get_id_bap)->update(['absensi' => 'HADIR']);
        //     }


        //     for ($i = 0; $i < $jmlabsen; $i++) {
        //         $abs = $request->absensi[$i];

        //         $idabsen = DB::select('CALL absensi_gabungan_prodi_kelas(?)', [$abs]);
        //         $jml_idabsen = count($idabsen);

        //         for ($j = 0; $j < $jml_idabsen; $j++) {
        //             $id_absensi = $idabsen[$j];

        //             Absensi_mahasiswa::where('id_absensi', $id_absensi->id_absensi)->update(['absensi' => 'ABSEN']);
        //         }
        //     }
        // } elseif ($absen == null) {
        //     for ($i = 0; $i < $jml_bap_gabungan; $i++) {
        //         $id_bap_gabungan = $bap_gabungan[$i];
        //         $get_id_bap = $id_bap_gabungan->id_bap;

        //         Absensi_mahasiswa::where('id_bap', $get_id_bap)->update(['absensi' => 'HADIR']);
        //     }
        // }

        // if ($absr != null) {
        //     $jml_mhs = count($absr);
        //     for ($i = 0; $i < $jml_mhs; $i++) {
        //         $studentrecord = $absr[$i];
        //         $cek_idstudentrecord = Student_record::where('id_studentrecord', $studentrecord)->first();
        //         $cek_idkurperiode = $cek_idstudentrecord->id_kurperiode;

        //         $cek_bap_id = DB::select('CALL kelas_gabungan_prodi_kelas(?,?)', [$cek_idkurperiode, $cek_bap->pertemuan]);
        //         $jml_bap_id = count($cek_bap_id);
        //         for ($l = 0; $l < $jml_bap_id; $l++) {
        //             $bap_fix = $cek_bap_id[$l];

        //             $abs = new Absensi_mahasiswa();
        //             $abs->id_bap = $bap_fix->id_bap;
        //             $abs->id_studentrecord = $studentrecord;
        //             $abs->absensi = 'ABSEN';
        //             $abs->save();
        //         }
        //     }
        // }

        // $cek_kelas_gabungan = DB::select('CALL kelas_gabungan_new(?)', [$cek_bap->id_kurperiode]);
        // $jml_kelas_gabungan = count($cek_kelas_gabungan);

        // for ($h = 0; $h < $jml_kelas_gabungan; $h++) {
        //     $kelas = $cek_kelas_gabungan[$h];

        //     $id_kurperiode = $kelas->id_kurperiode;

        //     $cek_idbap_gabungan = Bap::where('id_kurperiode', $id_kurperiode)
        //         ->where('pertemuan', $cek_bap->pertemuan)
        //         ->where('id_dosen', $cek_bap->id_dosen)
        //         ->where('status', 'ACTIVE')
        //         ->select('id_bap')
        //         ->first();

        //     $jml_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
        //         ->where('absensi', 'ABSEN')
        //         ->count();
        //     $jml_tdk_hadir = Absensi_mahasiswa::where('id_bap', $cek_idbap_gabungan->id_bap)
        //         ->whereIn('absensi', ['HADIR', 'SAKIT', 'IZIN', 'ALFA'])
        //         ->count();

        //     Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['hadir' => $jml_hadir]);
        //     Bap::where('id_bap', $cek_idbap_gabungan->id_bap)->update(['tidak_hadir' => $jml_tdk_hadir]);
        // }

        $id_kur = $cek_bap->id_kurperiode;

        Alert::success('', 'Absen berhasil diedit')->autoclose(3500);
        return redirect('entri_bap/' . $id_kur);
    }

    public function view_bap($id)
    {
        $bp = Bap::where('id_bap', $id)->get();
        foreach ($bp as $dtbp) {
            # code...
        }

        $date = $dtbp->tanggal;
       

        
        // dd($bp->tanggal);
        // $tanggalIndonesia = Carbon::createFromFormat('Y-m-d', $dtbp->tanggal)->format('d-m-Y');

        // dd($tanggalIndonesia);

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

        return view('dosen/view_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
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

        return view('dosen/cetak_bap', ['d' => $d, 'm' => $m, 'y' => $y, 'prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
    }

    public function edit_bap($id)
    {
        $bap = Bap::where('id_bap', $id)->get();
        foreach ($bap as $key_bap) {
            # code...
        }
        return view('dosen/edit_bap', ['id' => $id, 'bap' => $key_bap]);
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
            'file_kuliah_tatapmuka' => 'mimes:jpg,jpeg,png|max:2000',
            'file_materi_kuliah' => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG,docx,DOCX,PDF|max:4000',
            'file_materi_tugas' => 'mimes:jpg,jpeg,png|max:2000',
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
        return redirect('entri_bap/' . $request->id_kurperiode);
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
            ->first();

        Alert::success('', 'BAP berhasil dihapus')->autoclose(3500);
        return redirect('entri_bap/' . $idk->id_kurperiode);
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
            ->select('kurikulum_periode.id_dosen_2', 'matakuliah.akt_sks_praktek', 'matakuliah.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
            ->get();

        foreach ($bap as $key) {
            # code...
        }

        $dosen2 = Dosen::where('iddosen', $key->id_dosen_2)->get();
        foreach ($dosen2 as $keydsn) {
            // code...
        }
        if (count($dosen2) > 0) {
            $nama_dsn2 = $keydsn->nama . ', ' . $keydsn->akademik;
        } else {
            $nama_dsn2 = '';
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

        return view('dosen/absensi_perkuliahan', ['nama_dosen_2' => $nama_dsn2, 'abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
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

        return view('dosen/cetak_absensi', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
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
            ->select('kurikulum_periode.id_dosen_2', 'matakuliah.akt_sks_praktek', 'matakuliah.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
            ->get();
        foreach ($bap as $key) {
            # code...
        }

        $dosen2 = Dosen::where('iddosen', $key->id_dosen_2)->get();
        foreach ($dosen2 as $keydsn) {
            // code...
        }
        if (count($dosen2) > 0) {
            $nama_dsn2 = $keydsn->nama . ', ' . $keydsn->akademik;
        } else {
            $nama_dsn2 = '';
        }

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir', 'kuliah_transaction.tanggal_validasi')
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        return view('dosen/jurnal_perkuliahan', ['nama_dosen_2' => $nama_dsn2, 'bap' => $key, 'data' => $data]);
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
            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'prodi.kodeprodi', 'kelas.kelas', 'semester.semester')
            ->get();

        foreach ($bap as $key) {
            # code...
        }

        $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
            ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
            ->where('prodi.kodeprodi', $key->kodeprodi)
            ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
            ->first();

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir', 'kuliah_transaction.tanggal_validasi')
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        return view('dosen/cetak_jurnal', ['cekkprd' => $cekkprd, 'bap' => $key, 'data' => $data]);
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

    public function history_makul_dsn()
    {
        $iddsn = Auth::user()->id_user;

        $mkul = DB::select('CALL history_makul_diampu_new(?)', [$iddsn]);

        return view('dosen/history_makul_dsn', ['makul' => $mkul]);
    }

    public function cekmhs_dsn_his($id)
    {
        //cek mahasiswa
        $kelas_gabungan = DB::select('CALL absensi_mahasiswa_prodi_kelas(?)', [$id]);

        return view('dosen/list_mhs_dsn_his', ['ck' => $kelas_gabungan, 'ids' => $id]);
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

        return view('dosen/view_bap_his', ['bap' => $key, 'data' => $data]);
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

        return view('dosen/view_history_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
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

        return view('dosen/absensi_perkuliahan_his', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
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

        return view('dosen/jurnal_perkuliahan_his', ['bap' => $key, 'data' => $data]);
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosen/prausta/pembimbing_pkl', compact('data'));
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
            ->select(
                'prausta_setting_relasi.acc_seminar_sidang',
                'student.idstudent',
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.dosen_pembimbing'
            )
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->get();

        return view('dosen/prausta/cek_bimbingan_pkl', compact('jdl', 'pkl'));
    }

    public function komentar_bimbingan(Request $request, $id)
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.acc_seminar_sidang', 'prausta_trans_hasil.validasi')
            ->get();

        return view('dosen/prausta/penguji_pkl', compact('data'));
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

        return view('dosen/prausta/form_nilai_pkl', compact('data', 'id', 'form_dosbing', 'form_seminar'));
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
        return redirect('penguji_pkl');
    }

    public function edit_nilai_pkl_by_dosen_dlm($id)
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

        return view('dosen/prausta/edit_nilai_prakerin', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
    }

    public function put_nilai_prakerin_dosen_dlm(Request $request)
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
        return redirect('penguji_pkl');
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosen/prausta/pembimbing_sempro', compact('data'));
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
            ->select(
                'prausta_setting_relasi.acc_seminar_sidang',
                'student.idstudent',
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi'
            )
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->get();

        return view('dosen/prausta/cek_bimbingan_sempro', compact('jdl', 'pkl'));
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.id_student', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.acc_seminar_sidang', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'prausta_setting_relasi.validasi_pembimbing', 'prausta_setting_relasi.validasi_penguji_1', 'prausta_setting_relasi.validasi_penguji_2', 'prausta_trans_hasil.validasi')
            ->get();

        return view('dosen/prausta/penguji_sempro', compact('data', 'id'));
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

        return view('dosen/prausta/form_nilai_sempro_dospem', compact('data', 'id', 'form_dosbing'));
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
        return redirect('penguji_sempro');
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

        return view('dosen/prausta/form_nilai_sempro_dosji1', compact('data', 'id', 'form_peng1'));
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
        return redirect('penguji_sempro');
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

        return view('dosen/prausta/form_nilai_sempro_dosji2', compact('data', 'id', 'form_peng2'));
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
        return redirect('penguji_sempro');
    }

    public function validasi_dospem($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update([
            'validasi_pembimbing' => 'SUDAH',
            'tgl_val_pembimbing' => $date,
        ]);

        return redirect()->back();
    }

    public function validasi_dosji1($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update([
            'validasi_penguji_1' => 'SUDAH',
            'tgl_val_penguji_1' => $date,
        ]);

        return redirect()->back();
    }

    public function validasi_dosji2($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update([
            'validasi_penguji_2' => 'SUDAH',
            'tgl_val_penguji_2' => $date,
        ]);

        return redirect()->back();
    }

    public function edit_nilai_sempro_by_dospem_dlm($id)
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

        return view('dosen/prausta/edit_nilai_sempro_dospem', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_dospem_dlm(Request $request)
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
        return redirect('penguji_sempro');
    }

    public function edit_nilai_sempro_by_dospeng1_dlm($id)
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

        return view('dosen/prausta/edit_nilai_sempro_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_dospeng1_dlm(Request $request)
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
        return redirect('penguji_sempro');
    }

    public function edit_nilai_sempro_by_dospeng2_dlm($id)
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

        return view('dosen/prausta/edit_nilai_sempro_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_dospeng2_dlm(Request $request)
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
        return redirect('penguji_sempro');
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosen/prausta/pembimbing_ta', compact('data'));
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
            ->select(
                'prausta_setting_relasi.acc_seminar_sidang',
                'student.idstudent',
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_plagiarisme',
                'prausta_setting_relasi.dosen_pembimbing'
            )
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->get();

        return view('dosen/prausta/cek_bimbingan_ta', compact('jdl', 'pkl'));
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing', 'prausta_trans_hasil.nilai_1', 'prausta_trans_hasil.nilai_2', 'prausta_trans_hasil.nilai_3', 'prausta_trans_hasil.nilai_huruf', 'student.nim', 'student.nama', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.acc_seminar_sidang', 'prausta_trans_hasil.validasi', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'prausta_setting_relasi.id_student', 'prausta_setting_relasi.file_plagiarisme')
            ->get();

        return view('dosen/prausta/penguji_ta', compact('data', 'id'));
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

        return view('dosen/prausta/form_nilai_ta_dospem', compact('data', 'id', 'form_dosbing'));
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
        return redirect('penguji_ta');
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

        return view('dosen/prausta/form_nilai_ta_dosji1', compact('data', 'id', 'form_peng1'));
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
        return redirect('penguji_ta');
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

        return view('dosen/prausta/form_nilai_ta_dosji2', compact('data', 'id', 'form_peng2'));
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
        return redirect('penguji_ta');
    }

    public function edit_nilai_ta_by_dospem_dlm($id)
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

        return view('dosen/prausta/edit_nilai_ta_dospem', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_ta_dospem_dlm(Request $request)
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
        return redirect('penguji_ta');
    }

    public function edit_nilai_ta_by_dospeng1_dlm($id)
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

        return view('dosen/prausta/edit_nilai_ta_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_ta_dospeng1_dlm(Request $request)
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
        return redirect('penguji_ta');
    }

    public function edit_nilai_ta_by_dospeng2_dlm($id)
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

        return view('dosen/prausta/edit_nilai_ta_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_ta_dospeng2_dlm(Request $request)
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
        return redirect('penguji_ta');
    }

    public function jadwal_prausta_dsn_dlm()
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
            ->where('student.active', 1)
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [1, 2, 3, 4, 5, 6, 7, 8, 9])
            ->select(
                'student.nama',
                'student.nim',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.ruangan'
            )
            ->orderBy('tanggal_selesai', 'DESC')
            ->get();

        return view('dosen/prausta/jadwal_prausta', compact('data'));
    }

    public function jadwal_seminar_prakerin_dlm()
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->select('student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang', 'prausta_setting_relasi.ruangan')
            ->get();

        return view('dosen/prausta/jadwal_seminar_prakerin', compact('data'));
    }

    public function jadwal_seminar_proposal_dlm()
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->select('student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.dosen_penguji_2', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang', 'prausta_setting_relasi.ruangan')
            ->get();

        return view('dosen/prausta/jadwal_seminar_proposal', compact('data'));
    }

    public function jadwal_sidang_ta_dlm()
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->select('student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.dosen_penguji_2', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang', 'prausta_setting_relasi.ruangan')
            ->get();

        return view('dosen/prausta/jadwal_sidang_ta', compact('data'));
    }

    public function upload_soal_dsn_dlm()
    {
        $id = Auth::user()->id_user;

        $data = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->leftjoin('soal_ujian', 'kurikulum_periode.id_kurperiode', '=', 'soal_ujian.id_kurperiode')
            ->where('kurikulum_periode.id_dosen', $id)
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'soal_ujian.soal_uts', 'soal_ujian.soal_uas')
            ->get();

        return view('dosen/soal/soal_ujian', compact('data'));
    }

    public function simpan_soal_uts_dsn_dlm(Request $request)
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

        $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
        $jml_kelas = count($kelas_gabungan);

        for ($i = 0; $i < $jml_kelas; $i++) {
            $gabungan = $kelas_gabungan[$i];
            $cek = Soal_ujian::where('id_kurperiode', $gabungan->id_kurperiode)->first();

            if ($cek == null) {
                $path_soal = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;

                if (!File::exists($path_soal)) {
                    File::makeDirectory($path_soal);
                }

                $info = new Soal_ujian();
                $info->id_kurperiode = $gabungan->id_kurperiode;
                $info->created_by = Auth::user()->name;
                $info->tipe_ujian_uts = $request->tipe_ujian_uts;
                $info->cetak_soal_uts = $request->cetak_soal_uts;

                if ($i == 0) {
                    if ($request->hasFile('soal_uts')) {
                        $file = $request->file('soal_uts');
                        $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;
                        $nama_file = time() . '_' . $file->getClientOriginalName();
                        $file->move($tujuan_upload, $nama_file);
                        $info->soal_uts = $nama_file;
                    }
                } elseif ($i > 0) {
                    if ($request->hasFile('soal_uts')) {
                        $id_kur = $kelas_gabungan[0];
                        $kurperiode = $id_kur->id_kurperiode;
                        $file = $request->file('soal_uts');
                        $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $kurperiode;
                        $nama_file = time() . '_' . $file->getClientOriginalName();

                        $id_kur1 = $kelas_gabungan[$i];
                        $kurperiode1 = $id_kur1->id_kurperiode;
                        $new_path = 'Soal Ujian/' . 'UTS/' . $kurperiode1;
                        $new_nama_file = time() . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $new_path . '/' . $new_nama_file);

                        $info->soal_uts = $new_nama_file;
                    }
                }

                $info->save();
            } elseif ($cek != null) {
                $path_soal = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;

                if (!File::exists($path_soal)) {
                    File::makeDirectory($path_soal);
                }

                $id = $cek->id_soal;
                $info = Soal_ujian::find($id);
                $info->tipe_ujian_uts = $request->tipe_ujian_uts;
                $info->cetak_soal_uts = $request->cetak_soal_uts;

                if ($i == 0) {
                    if ($info->soal_uts) {
                        if ($request->hasFile('soal_uts')) {
                            File::delete('Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode . '/' . $info->soal_uts);
                            $file = $request->file('soal_uts');
                            $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;
                            $nama_file = time() . '_' . $file->getClientOriginalName();
                            $file->move($tujuan_upload, $nama_file);
                            $info->soal_uts = $nama_file;
                        }
                    } else {
                        if ($request->hasFile('soal_uts')) {
                            $file = $request->file('soal_uts');
                            $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $gabungan->id_kurperiode;
                            $nama_file = time() . '_' . $file->getClientOriginalName();
                            $file->move($tujuan_upload, $nama_file);
                            $info->soal_uts = $nama_file;
                        }
                    }
                } elseif ($i > 0) {
                    if ($info->soal_uts) {
                        if ($request->hasFile('soal_uts')) {
                            $id_kur1 = $kelas_gabungan[0];
                            $d1 = $id_kur1->id_kurperiode;
                            File::delete('Soal Ujian/' . 'UTS/' . $d1 . '/' . $info->soal_uts);
                            $file = $request->file('soal_uts');
                            $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $d1;
                            $nama_file = time() . '_' . $file->getClientOriginalName();

                            $tes2 = $kelas_gabungan[$i];
                            $d2 = $tes2->id_kurperiode;
                            File::delete('Soal Ujian/' . 'UTS/' . $d2 . '/' . $info->soal_uts);
                            $path = 'Soal Ujian/' . 'UTS/' . $d2;
                            $nama_file1 = time() . '_' . $file->getClientOriginalName();

                            File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                            $info->soal_uts = $nama_file1;
                        }
                    } else {
                        if ($request->hasFile('soal_uts')) {
                            $tes1 = $kelas_gabungan[0];
                            $d1 = $tes1->id_kurperiode;
                            $file = $request->file('soal_uts');
                            $nama_file = time() . '_' . $file->getClientOriginalName();
                            $tujuan_upload = 'Soal Ujian/' . 'UTS/' . $d1;

                            $tes2 = $kelas_gabungan[$i];
                            $d2 = $tes2->id_kurperiode;
                            $path = 'Soal Ujian/' . 'UTS/' . $d2;
                            $nama_file1 = time() . '_' . $file->getClientOriginalName();

                            File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                            $info->soal_uts = $nama_file1;
                        }
                    }
                }

                $info->save();
            }
        }

        Alert::success('', 'Soal berhasil ditambahkan')->autoclose(3500);
        return redirect('makul_diampu_dsn');
    }

    public function simpan_soal_uas_dsn_dlm(Request $request)
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

        $kelas_gabungan = DB::select('CALL kelas_gabungan(?)', [$request->id_kurperiode]);
        $jml_kelas = count($kelas_gabungan);

        for ($i = 0; $i < $jml_kelas; $i++) {
            $gabungan = $kelas_gabungan[$i];
            $cek = Soal_ujian::where('id_kurperiode', $gabungan->id_kurperiode)->first();

            if ($cek == null) {
                $path_soal = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;

                if (!File::exists($path_soal)) {
                    File::makeDirectory($path_soal);
                }

                $info = new Soal_ujian();
                $info->id_kurperiode = $gabungan->id_kurperiode;
                $info->created_by = Auth::user()->name;
                $info->tipe_ujian_uas = $request->tipe_ujian_uas;
                $info->cetak_soal_uas = $request->cetak_soal_uas;

                if ($i == 0) {
                    if ($request->hasFile('soal_uas')) {
                        $file = $request->file('soal_uas');
                        $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;
                        $nama_file = time() . '_' . $file->getClientOriginalName();
                        $file->move($tujuan_upload, $nama_file);
                        $info->soal_uas = $nama_file;
                    }
                } elseif ($i > 0) {
                    if ($request->hasFile('soal_uas')) {
                        $id_kur = $kelas_gabungan[0];
                        $kurperiode = $id_kur->id_kurperiode;
                        $file = $request->file('soal_uas');
                        $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $kurperiode;
                        $nama_file = time() . '_' . $file->getClientOriginalName();

                        $id_kur1 = $kelas_gabungan[$i];
                        $kurperiode1 = $id_kur1->id_kurperiode;
                        $new_path = 'Soal Ujian/' . 'UAS/' . $kurperiode1;
                        $new_nama_file = time() . '_' . $file->getClientOriginalName();

                        File::copy($tujuan_upload . '/' . $nama_file, $new_path . '/' . $new_nama_file);

                        $info->soal_uas = $new_nama_file;
                    }
                }

                $info->save();
            } elseif ($cek != null) {
                $path_soal = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;

                if (!File::exists($path_soal)) {
                    File::makeDirectory($path_soal);
                }

                $id = $cek->id_soal;
                $info = Soal_ujian::find($id);
                $info->tipe_ujian_uas = $request->tipe_ujian_uas;
                $info->cetak_soal_uas = $request->cetak_soal_uas;

                if ($i == 0) {
                    if ($info->soal_uas) {
                        if ($request->hasFile('soal_uas')) {
                            File::delete('Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode . '/' . $info->soal_uas);
                            $file = $request->file('soal_uas');
                            $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;
                            $nama_file = time() . '_' . $file->getClientOriginalName();
                            $file->move($tujuan_upload, $nama_file);
                            $info->soal_uas = $nama_file;
                        }
                    } else {
                        if ($request->hasFile('soal_uas')) {
                            $file = $request->file('soal_uas');
                            $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $gabungan->id_kurperiode;
                            $nama_file = time() . '_' . $file->getClientOriginalName();
                            $file->move($tujuan_upload, $nama_file);
                            $info->soal_uas = $nama_file;
                        }
                    }
                } elseif ($i > 0) {
                    if ($info->soal_uas) {
                        if ($request->hasFile('soal_uas')) {
                            $id_kur1 = $kelas_gabungan[0];
                            $d1 = $id_kur1->id_kurperiode;
                            File::delete('Soal Ujian/' . 'UAS/' . $d1 . '/' . $info->soal_uas);
                            $file = $request->file('soal_uas');
                            $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $d1;
                            $nama_file = time() . '_' . $file->getClientOriginalName();

                            $tes2 = $kelas_gabungan[$i];
                            $d2 = $tes2->id_kurperiode;
                            File::delete('Soal Ujian/' . 'UAS/' . $d2 . '/' . $info->soal_uas);
                            $path = 'Soal Ujian/' . 'UAS/' . $d2;
                            $nama_file1 = time() . '_' . $file->getClientOriginalName();

                            File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                            $info->soal_uas = $nama_file1;
                        }
                    } else {
                        if ($request->hasFile('soal_uas')) {
                            $tes1 = $kelas_gabungan[0];
                            $d1 = $tes1->id_kurperiode;
                            $file = $request->file('soal_uas');
                            $nama_file = time() . '_' . $file->getClientOriginalName();
                            $tujuan_upload = 'Soal Ujian/' . 'UAS/' . $d1;

                            $tes2 = $kelas_gabungan[$i];
                            $d2 = $tes2->id_kurperiode;
                            $path = 'Soal Ujian/' . 'UAS/' . $d2;
                            $nama_file1 = time() . '_' . $file->getClientOriginalName();

                            File::copy($tujuan_upload . '/' . $nama_file, $path . '/' . $nama_file1);

                            $info->soal_uas = $nama_file1;
                        }
                    }
                }
                $info->save();
            }
        }

        Alert::success('', 'Soal berhasil ditambahkan')->autoclose(3500);
        return redirect('makul_diampu_dsn');
    }

    public function record_pembayaran_mhs($id)
    {
        $maha = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->select('student.idstudent', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.prodi', 'kelas.kelas', 'student.nama', 'student.nim')
            ->where('idstudent', $id)
            ->first();

        $cek_study = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->where('student.idstudent', $id)
            ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        $biaya = Biaya::where('idangkatan', $maha->idangkatan)
            ->where('idstatus', $maha->idstatus)
            ->where('kodeprodi', $maha->kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14', 'prakerin', 'seminar', 'sidang', 'wisuda')
            ->first();

        if ($cek_study->study_year == '3') {
            $itembayar = Itembayar::where('study_year', '3')
                ->orderBy('iditem', 'ASC')
                ->get();

            $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 1)
                ->sum('bayar.bayar');

            $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 2)
                ->sum('bayar.bayar');

            $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 3)
                ->sum('bayar.bayar');

            $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 4)
                ->sum('bayar.bayar');

            $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 5)
                ->sum('bayar.bayar');

            $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 6)
                ->sum('bayar.bayar');

            $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 7)
                ->sum('bayar.bayar');

            $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 8)
                ->sum('bayar.bayar');

            $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 9)
                ->sum('bayar.bayar');

            $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 10)
                ->sum('bayar.bayar');

            $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 11)
                ->sum('bayar.bayar');

            $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 12)
                ->sum('bayar.bayar');

            $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 13)
                ->sum('bayar.bayar');

            $sisaprakerin = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 36)
                ->sum('bayar.bayar');

            $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 14)
                ->sum('bayar.bayar');

            $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 15)
                ->sum('bayar.bayar');

            $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 16)
                ->sum('bayar.bayar');

            return view('dosen/mhs/data_biaya', compact('maha', 'itembayar', 'cb', 'biaya', 'sisadaftar', 'sisaawal', 'sisadsp', 'sisaspp1', 'sisaspp2', 'sisaspp3', 'sisaspp4', 'sisaspp5', 'sisaspp6', 'sisaspp7', 'sisaspp8', 'sisaspp9', 'sisaspp10', 'sisaprakerin', 'sisaseminar', 'sisasidang', 'sisawisuda'));
        } elseif ($cek_study->study_year == '4') {
            $itembayar = Itembayar::where('study_year', '4')
                ->orderBy('iditem', 'ASC')
                ->get();

            $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 1)
                        ->orWhere('bayar.iditem', 18);
                })
                ->sum('bayar.bayar');

            $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 2)
                        ->orWhere('bayar.iditem', 19);
                })
                ->sum('bayar.bayar');

            $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 3)
                        ->orWhere('bayar.iditem', 20);
                })
                ->sum('bayar.bayar');

            $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 4)
                        ->orWhere('bayar.iditem', 21);
                })
                ->sum('bayar.bayar');

            $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 5)
                        ->orWhere('bayar.iditem', 22);
                })
                ->sum('bayar.bayar');

            $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 6)
                        ->orWhere('bayar.iditem', 23);
                })
                ->sum('bayar.bayar');

            $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 7)
                        ->orWhere('bayar.iditem', 24);
                })
                ->sum('bayar.bayar');

            $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 8)
                        ->orWhere('bayar.iditem', 25);
                })
                ->sum('bayar.bayar');

            $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 9)
                        ->orWhere('bayar.iditem', 26);
                })
                ->sum('bayar.bayar');

            $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 10)
                        ->orWhere('bayar.iditem', 27);
                })
                ->sum('bayar.bayar');

            $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 11)
                        ->orWhere('bayar.iditem', 28);
                })
                ->sum('bayar.bayar');

            $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 12)
                        ->orWhere('bayar.iditem', 29);
                })
                ->sum('bayar.bayar');

            $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 13)
                        ->orWhere('bayar.iditem', 30);
                })
                ->sum('bayar.bayar');

            $sisaspp11 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 31)
                ->sum('bayar.bayar');

            $sisaspp12 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 32)
                ->sum('bayar.bayar');

            $sisaspp13 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 33)
                ->sum('bayar.bayar');

            $sisaspp14 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 34)
                ->sum('bayar.bayar');

            $sisaprakerin = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 36)
                        ->orWhere('bayar.iditem', 35);
                })
                ->sum('bayar.bayar');

            $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 14)
                        ->orWhere('bayar.iditem', 37);
                })
                ->sum('bayar.bayar');

            $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 38)
                ->sum('bayar.bayar');

            $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 39)
                ->sum('bayar.bayar');

            return view('dosen/mhs/data_biaya', compact('maha', 'itembayar', 'cb', 'biaya', 'sisadaftar', 'sisaawal', 'sisadsp', 'sisaspp1', 'sisaspp2', 'sisaspp3', 'sisaspp4', 'sisaspp5', 'sisaspp6', 'sisaspp7', 'sisaspp8', 'sisaspp9', 'sisaspp10', 'sisaspp11', 'sisaspp12', 'sisaspp13', 'sisaspp14', 'sisaprakerin', 'sisaseminar', 'sisasidang', 'sisawisuda'));
        }
    }

    public function download_bap_pkl_dsn_dlm($id)
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
            return redirect('pembimbing_pkl');
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

    public function download_bap_sempro_dsn_dlm($id)
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
            return redirect('pembimbing_sempro');
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

    public function download_bap_ta_dsn_dlm($id)
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
            return redirect('pembimbing_ta');
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

    public function cek_makul_mengulang($id)
    {
        $id_dsn = Auth::user()->id_user;

        $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
            ->where('dosen_pembimbing.id_dosen', $id_dsn)
            ->where('student.idstudent', $id)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('student.active', [1, 5])
            ->select('student_record.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.id_kurtrans', 'matakuliah.makul', 'student_record.nilai_AKHIR')
            ->groupBy('student_record.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.id_kurtrans', 'matakuliah.makul', 'student_record.nilai_AKHIR')
            ->get();

        if (count($data) > 0) {
            return view('dosen/mhs/makul_mengulang', compact('data'));
        } else {
            Alert::warning('Mahasiswa ini tidak ada matakuliah mengulang');
            return redirect('mhs_bim');
        }
    }

    public function post_settingnilai_dsn_dlm(Request $request)
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

        // $kur = $ckstr->id_kurtrans;
        $idkur = $idkur;

        Alert::success('Berhasil');
        return view('dosen/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'nilai' => $nilai]);
    }

    public function put_settingnilai_dsn_dlm(Request $request, $id)
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
        return view('dosen/list_mhs_dsn', ['ck' => $kelas_gabungan, 'ids' => $idkur, 'kur' => $kur, 'nilai' => $nilai]);
    }

    public function sop_dsn_dlm()
    {
        $data = Standar::where('status', 'ACTIVE')->get();

        return view('dosen/sop', compact('data'));
    }

    public function pedoman_akademik_dsn_dlm()
    {
        $pedoman = Pedoman_akademik::join('periode_tahun', 'pedoman_akademik.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->where('pedoman_akademik.status', 'ACTIVE')
            ->get();

        return view('dosen/pedoman_akademik', ['pedoman' => $pedoman]);
    }

    public function download_pedoman_dsn_dlm($id)
    {
        $ped = Pedoman_akademik::where('id_pedomanakademik', $id)->get();
        foreach ($ped as $keyped) {
            // code...
        }
        //PDF file is stored under project/public/download/info.pdf
        $file = 'pedoman/' . $keyped->file;
        return Response::download($file);
    }

    public function pedoman_khusus_dsn_dlm()
    {
        $data = Pedoman_khusus::join('periode_tahun', 'pedoman_khusus.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->where('pedoman_khusus.status', 'ACTIVE')
            ->get();

        return view('dosen/pedoman_khusus', compact('data'));
    }

    public function download_pedoman_khusus_dsn_dlm($id)
    {
        $ped = Pedoman_khusus::where('id_pedomankhusus', $id)->first();

        //PDF file is stored under project/public/download/info.pdf
        $file = 'Pedoman Khusus/' . $ped->file;
        return Response::download($file);
    }

    public function penangguhan_mhs_dsn()
    {
        $id = Auth::user()->id_user;

        $data = Dosen_pembimbing::join('penangguhan_master_trans', 'dosen_pembimbing.id_student', '=', 'penangguhan_master_trans.id_student')
            ->join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('dosen_pembimbing.id_dosen', $id)
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'penangguhan_master_trans.id_periodetahun',
                'penangguhan_master_trans.id_periodetipe',
                'penangguhan_master_trans.id_student',
                'penangguhan_master_trans.id_penangguhan_kategori',
                'penangguhan_master_kategori.kategori',
                'penangguhan_master_trans.total_tunggakan',
                'penangguhan_master_trans.rencana_bayar',
                'penangguhan_master_trans.alasan',
                'penangguhan_master_trans.validasi_kaprodi',
                'penangguhan_master_trans.validasi_dsn_pa',
                'penangguhan_master_trans.validasi_bauk',
                'penangguhan_master_trans.validasi_baak',
                'penangguhan_master_trans.id_penangguhan_trans'
            )
            ->orderBy('student.nim')
            ->get();

        return view('dosen/penangguhan/data_penangguhan', compact('data'));
    }

    public function val_penangguhan_dsn_pa($id)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_dsn_pa' => 'SUDAH']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function batal_val_penangguhan_dsn_pa($id)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_dsn_pa' => 'BELUM']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function cek_bim_perwalian($id)
    {
        $data = Perwalian_trans_bimbingan::join('dosen_pembimbing', 'perwalian_trans_bimbingan.id_dosbim_pa', '=', 'dosen_pembimbing.id')
            ->join('periode_tahun', 'perwalian_trans_bimbingan.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'perwalian_trans_bimbingan.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('dosen_pembimbing.id_student', $id)
            ->where('perwalian_trans_bimbingan.status', 'ACTIVE')
            ->select(
                'perwalian_trans_bimbingan.*',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe'
            )
            ->get();

        return view('dosen/mhs/perwalian', compact('data'));
    }

    public function val_bim_perwalian($id)
    {
        Perwalian_trans_bimbingan::where('id_transbim_perwalian', $id)->update(['validasi' => 'SUDAH']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function data_pengajuan_keringanan_absen_dlm()
    {
        $id = Auth::user()->id_user;

        $data = DB::select('CALL keringanan_absensi(?)', [$id]);

        return view('dosen/mhs/keringanan_absensi', compact('data'));
    }

    public function acc_keringanan_dlm($id)
    {
        Permohonan_ujian::where('id_studentrecord', $id)->update([
            'permohonan' => 'DISETUJUI',
            'updated_by' => Auth::user()->name
        ]);

        Absen_ujian::where('id_studentrecord', $id)->update([
            'permohonan' => 'DISETUJUI',
            'updated_by' => Auth::user()->name
        ]);

        return redirect('data_pengajuan_keringanan_absen_dlm');
    }

    public function reject_keringanan_dlm($id)
    {
        Permohonan_ujian::where('id_studentrecord', $id)->update([
            'permohonan' => 'TIDAK DISETUJUI',
            'updated_by' => Auth::user()->name
        ]);

        Absen_ujian::where('id_studentrecord', $id)->update([
            'permohonan' => 'TIDAK DISETUJUI',
            'updated_by' => Auth::user()->name
        ]);

        return redirect('data_pengajuan_keringanan_absen_dlm');
    }
}
