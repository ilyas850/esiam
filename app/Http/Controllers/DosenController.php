<?php

namespace App\Http\Controllers;

use PDF;
use File;
use Alert;
use Session;
use App\Bap;
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
use App\Exports\DataNilaiExport;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function mhs_bim()
    {
        $id = Auth::user()->id_user;

        $k =  DB::table('student_record')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('dosen_pembimbing', 'student.idstudent', '=', 'dosen_pembimbing.id_student')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->select('student.idstudent', 'student.nama', 'student.nim', 'student_record.tanggal_krs', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe')
            ->whereIn('student_record.id_studentrecord', (function ($query) {
                $query->from('student_record')
                    ->select(DB::raw('MAX(student_record.id_studentrecord)'))
                    ->groupBy('student_record.id_student');
            }))
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
            ->select(
                'student.idstudent',
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi'
            )
            ->first();

        $idangkatan = $mhs->idangkatan;
        $idstatus = $mhs->idstatus;
        $kodeprodi = $mhs->kodeprodi;

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
        $angk = $mhs->idangkatan;

        if ($smt % 2 != 0) {
            $a = ($smt + 10 - 1) / 10;
            $b = $a - $angk;
            $c = $b * 2 - 1;
        } else {
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $angk;
            $c = $b * 2;
        }

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14')
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        if (($cb) != null) {

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
            $spp11 = $biaya->spp11 - (($biaya->spp11 * ($cb->spp11)) / 100);
            $spp12 = $biaya->spp12 - (($biaya->spp12 * ($cb->spp12)) / 100);
            $spp13 = $biaya->spp13 - (($biaya->spp13 * ($cb->spp13)) / 100);
            $spp14 = $biaya->spp14 - (($biaya->spp14 * ($cb->spp14)) / 100);
        } elseif (($cb) == null) {
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

        //cek masa studi 
        $cek_study = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->where('student.idstudent', $id)
            ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
            ->first();

        if ($cek_study->study_year == 3) {
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
        } elseif ($cek_study->study_year == 4) {

            $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 18)
                ->sum('bayar.bayar');

            $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 19)
                ->sum('bayar.bayar');

            $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 20)
                ->sum('bayar.bayar');

            $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 21)
                ->sum('bayar.bayar');

            $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 22)
                ->sum('bayar.bayar');

            $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 23)
                ->sum('bayar.bayar');

            $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 24)
                ->sum('bayar.bayar');

            $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 25)
                ->sum('bayar.bayar');

            $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 26)
                ->sum('bayar.bayar');

            $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 27)
                ->sum('bayar.bayar');

            $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 28)
                ->sum('bayar.bayar');

            $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 29)
                ->sum('bayar.bayar');

            $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 30)
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
        }

        if ($cek_study->study_year == 3) {
            $tots1 = $sisadaftar + $sisaawal + $sisaspp1;
            $tots2 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1;
            $tots3 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2;
            $tots4 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3;
            $tots5 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4;
            $tots6 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5;
            $tots7 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6;
            $tots8 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7;
            $tots9 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8;
            $tots10 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9;
        } elseif ($cek_study->study_year == 4) {
            $tots1 = $sisadaftar + $sisaawal + $sisaspp1;
            $tots2 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1;
            $tots3 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2;
            $tots4 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3;
            $tots5 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4;
            $tots6 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5;
            $tots7 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6;
            $tots8 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7;
            $tots9 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8;
            $tots10 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9;
            $tots11 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10;
            $tots12 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaspp11;
            $tots13 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaspp11 + $sisaspp12;
            $tots14 = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaspp11 + $sisaspp12 + $sisaspp13;
        }

        if ($c == 1) {
            $cekbyr = $daftar + $awal + $spp1 - $tots1;
        } elseif ($c == 2) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 - $tots2;
        } elseif ($c == 3) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 - $tots3;
        } elseif ($c == 4) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $tots4;
        } elseif ($c == 5) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 - $tots5;
        } elseif ($c == 6) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $tots6;
        } elseif ($c == 7) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 - $tots7;
        } elseif ($c == 8) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $tots8;
        } elseif ($c == 9) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 - $tots9;
        } elseif ($c == 10) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $tots10;
        } elseif ($c == 11) {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10) - $tots11;
        } elseif ($c == 12) {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11) - $tots12;
        } elseif ($c == 13) {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12) - $tots13;
        } elseif ($c == 14) {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13) - $tots14;
        }

        if ($cekbyr == 0) {
            $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->where('student_record.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tp)
                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
                ->where('student_record.status', 'TAKEN')
                ->select('kurikulum_periode.id_makul')
                ->groupBy('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen')
                ->get();
            $hit = count($records);

            $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->where('edom_transaction.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tp)
                ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
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
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'student_record.remark', 'student.idstatus', 'student.nim', 'student.idangkatan', 'student.kodeprodi', 'student.nama')
            ->orderBy('student.nim', 'ASC')
            ->orderBy('student.idangkatan', 'ASC')
            ->get();

        return view('dosen/validasi_krs', ['val' => $val, 'angk' => $angk]);
    }

    public function cek_krs($id)
    {
        //data mahasiswa
        $data_mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'student.idangkatan', 'student.kodeprodi', 'student.idstatus')
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
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_master.status', 'ACTIVE')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('kurikulum_periode.id_kelas', $data_mhs->idstatus)
            ->where('kurikulum_periode.id_prodi', $prod->id_prodi)
            ->where('kurikulum_transaction.id_prodi', $prod->id_prodi)
            ->where('kurikulum_transaction.id_angkatan', $data_mhs->idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('kurikulum_transaction.status', 'ACTIVE')
            ->select('kurikulum_periode.id_kurperiode', 'kurikulum_transaction.idkurtrans', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'dosen.nama')
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
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('student_record.id_student', $id)
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
            ->select(DB::raw('DISTINCT(student_record.remark)'), 'student.idstudent')
            ->get();

        foreach ($valkrs as $valuekrs) {
            // code...
        }

        $b = $valuekrs->remark;

        return view('dosen/cek_krs', ['b' => $b, 'mhss' => $id, 'add' => $krs, 'val' => $val, 'key' => $data_mhs]);
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

        if ($totalsks > 24) {
            Alert::warning('maaf sks yang diambil mahasiswa ini melebihi 24 sks', 'MAAF !!');
            return redirect('val_krs');
        } elseif ($totalsks < 24) {
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

        $id = Auth::user()->id_user;

        $mkul = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->where('kurikulum_periode.id_dosen', $id)
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_hari.hari', 'kurikulum_jam.jam', 'kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
            ->get();

        return view('dosen/makul_diampu_dsn', ['makul' => $mkul]);
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
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->where('kurikulum_periode.id_dosen', $iddsn)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
            ->orderBy('kurikulum_periode.id_periodetahun', 'DESC')
            ->get();

        return view('dosen/history_makul_dsn', ['prd_tipe' => $prd_tipe, 'prd_tahun' => $prd_tahun, 'makul' => $mkul, 'mk' => $mk, 'prd' => $prd, 'kls' => $kls, 'smt' => $smt]);
    }

    public function cekmhs_dsn($id)
    {
        $mhs = Student::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $angk = Angkatan::all();
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosen/list_mhs_dsn', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $id]);
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
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
            ->get();

        $ckstr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans')
            ->get();
        foreach ($ckstr as $str) {
            # code...
        }
        $kur = $str->id_kurtrans;

        return view('dosen/list_mhs_dsn_his', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $id, 'kur' => $kur]);
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
            ->orderBy('student.nim', 'ASC')
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
        $pdf = PDF::loadView('dosen/unduh_nilai_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'data' => $key, 'tb' => $cks]);
        return $pdf->download('Nilai Matakuliah' . ' ' . $makul . ' ' . $tahun . ' ' . $tipe . ' ' . $kelas . '.pdf');
    }

    public function input_kat_dsn($id)
    {
        $mhs = Student::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $angk = Angkatan::all();
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_KAT')
            ->get();
        $kurrr = $id;
        return view('dosen/input_kat_dsn', ['kuri' => $kurrr, 'ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'id' => $id]);
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
                    $entry->save();
                } elseif ($ceknl != null) {
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_KAT = $nilai;
                    $entry->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_KAT' => 0]);
                } elseif ($ceknl != null) {
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
        return view('dosen/list_mhs_dsn', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
    }

    public function input_uts_dsn($id)
    {
        $mhs = Student::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $angk = Angkatan::all();
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_UTS')
            ->get();

        $mkl = Kurikulum_periode::where('id_kurperiode', $id)->get();

        foreach ($mkl as $keymkl) {
            # code...
        }
        $kmkl = $keymkl->id_makul;
        $kprd = $keymkl->id_prodi;
        $kkls = $keymkl->id_kelas;
        $kurrr = $id;

        return view('dosen/input_uts_dsn', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'id' => $id]);
    }

    public function save_nilai_UTS_dsn(Request $request)
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
                    $entry->save();
                } elseif ($ceknl != null) {
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_UTS = $nilai;
                    $entry->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UTS' => 0]);
                } elseif ($ceknl != null) {
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
        return view('dosen/list_mhs_dsn', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
    }

    public function input_uas_dsn($id)
    {
        $mhs = Student::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $angk = Angkatan::all();
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_UAS')
            ->get();

        $mkl = Kurikulum_periode::where('id_kurperiode', $id)->get();

        foreach ($mkl as $keymkl) {
            # code...
        }
        $kmkl = $keymkl->id_makul;
        $kprd = $keymkl->id_prodi;
        $kkls = $keymkl->id_kelas;
        $kurrr = $id;

        return view('dosen/input_uas_dsn', ['kuri' => $kurrr, 'kkls' => $kkls, 'kprd' => $kprd, 'mkl' => $kmkl, 'ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'id' => $id]);
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
                    $entry->save();
                } elseif ($ceknl != null) {
                    $id = $id_kur;
                    $entry = Student_record::find($id);
                    $entry->nilai_UAS = $nilai;
                    $entry->save();
                }
            } elseif ($banyak > 1) {
                if ($ceknl == null) {
                    Student_record::where('id_student', $stu)
                        ->where('id_kurtrans', $kur)
                        ->update(['nilai_UAS' => 0]);
                } elseif ($ceknl != null) {
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
        return view('dosen/list_mhs_dsn', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
    }

    public function input_akhir_dsn($id)
    {
        $mhs = Student::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $angk = Angkatan::all();
        //cek mahasiswa
        $cks = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'student.idangkatan', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
            ->get();
        $kurrr = $id;

        return view('dosen/input_akhir_dsn', ['kuri' => $kurrr, 'ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'id' => $id]);
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
        return view('dosen/list_mhs_dsn', ['ck' => $cks, 'mhs' => $mhs, 'prd' => $prd, 'kls' => $kls, 'angk' => $angk, 'ids' => $idkur, 'kur' => $kur]);
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
            ->select('kuliah_transaction.kurang_jam', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
            ->get();

        return view('dosen/bap', ['bap' => $key, 'data' => $data]);
    }

    public function input_bap($id)
    {
        $data = Kurikulum_periode::where('id_kurperiode', $id)->get();

        return view('dosen/form_bap', ['id' => $id]);
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
                'file_materi_kuliah' => 'mimes:jpg,jpeg,JPG,JPEG,pdf,png,PNG|max:2048',
                'file_materi_tugas' => 'image|mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
            ],
            $message,
        );
        $data = Kurikulum_periode::where('id_kurperiode', $request->id_kurperiode)->first();

        $sama = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('kurikulum_periode.id_dosen', $data->id_dosen)
            ->where('kurikulum_periode.id_jam', $data->id_jam)
            ->where('kurikulum_periode.id_hari', $data->id_hari)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_periode.id_kurperiode')
            ->get();

        $cek_bap = Bap::where('id_kurperiode', $request->id_kurperiode)
            ->where('id_dosen', Auth::user()->id_user)
            ->where('pertemuan', $request->pertemuan)
            ->where('status', 'ACTIVE')
            ->get();
        $jml_bap = count($cek_bap);
        if ($jml_bap > 0) {
            Alert::error('Maaf pertemuan yang diinput sudah ada', 'maaf');
            return redirect()->back();
        } elseif ($jml_bap == 0) {
            $jml_id = count($sama);

            for ($i = 0; $i < $jml_id; $i++) {
                $tes = $sama[$i];
                $d = $tes['id_kurperiode'];

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

                $bap = new Bap();
                $bap->id_kurperiode = $d;
                $bap->id_dosen = Auth::user()->id_user;
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
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Kuliah Tatap Muka';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_kuliah_tatapmuka = $nama_file;
                    }

                    if ($request->hasFile('file_materi_kuliah')) {
                        $file = $request->file('file_materi_kuliah');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Materi Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_kuliah = $nama_file;
                    }

                    if ($request->hasFile('file_materi_tugas')) {
                        $file = $request->file('file_materi_tugas');
                        $nama_file = 'Pertemuan Ke-' . $request->pertemuan . '_' . $file->getClientOriginalName();
                        $tujuan_upload = 'File_BAP/' . Auth::user()->id_user . '/' . $d . '/' . 'Tugas Kuliah';
                        $file->move($tujuan_upload, $nama_file);
                        $bap->file_materi_tugas = $nama_file;
                    }
                } elseif ($i > 0) {
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

                $bap->save();

                $users = DB::table('bap')
                    ->limit(1)
                    ->orderByDesc('id_bap')
                    ->first();

                $kuliah = new Kuliah_transaction();
                $kuliah->id_kurperiode = $d;
                $kuliah->id_dosen = Auth::user()->id_user;
                $kuliah->id_tipekuliah = $request->id_tipekuliah;
                $kuliah->tanggal = $request->tanggal;
                $kuliah->akt_jam_mulai = $request->jam_mulai;
                $kuliah->akt_jam_selesai = $request->jam_selsai;
                $kuliah->id_bap = $users->id_bap;
                $kuliah->save();
            }
            return redirect('entri_bap/' . $d)->with('success', 'Data Berhasil diupload');
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

        return view('dosen/absensi', ['absen' => $cks, 'idk' => $idp, 'id' => $id]);
    }

    public function save_absensi(Request $request)
    {
        $id_record = $request->id_studentrecord;
        $jmlrecord = count($id_record);
        $id_kur = $request->id_kurperiode;
        $id_bp = $request->id_bap;
        $absen = $request->absensi;

        if ($absen != null) {
            $jmlabsen = count($request->absensi);
            for ($i = 0; $i < $jmlrecord; $i++) {
                $kurp = $request->id_studentrecord[$i];
                $idr = explode(',', $kurp);
                $tra = $idr[0];

                $cek = Absensi_mahasiswa::where('id_studentrecord', $tra)
                    ->where('id_bap', $id_bp)
                    ->get();

                $hit = count($cek);

                if ($hit == 0) {
                    $abs = new Absensi_mahasiswa();
                    $abs->id_bap = $id_bp;
                    $abs->id_studentrecord = $tra;
                    $abs->save();
                }
            }

            for ($i = 0; $i < $jmlabsen; $i++) {
                $abs = $request->absensi[$i];
                $idab = explode(',', $abs, 2);
                $trsen = $idab[0];
                $trsi = $idab[1];

                Absensi_mahasiswa::where('id_studentrecord', $trsen)
                    ->where('id_bap', $id_bp)
                    ->update(['absensi' => 'ABSEN']);
            }

            $bp = Bap::where('id_bap', $id_bp)->update(['hadir' => $jmlabsen]);
            $bp = Bap::where('id_bap', $id_bp)->update(['tidak_hadir' => $jmlrecord - $jmlabsen]);
        }

        return redirect('entri_bap/' . $id_kur);
    }

    public function edit_absen($id)
    {
        $kur = Bap::where('id_bap', $id)->first();

        $idk = $kur->id_kurperiode;
        $per = $kur->pertemuan;

        $abs = Absensi_mahasiswa::leftjoin('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('absensi_mahasiswa.id_bap', $id)
            ->where('student_record.status', 'TAKEN')
            ->select(
                'student_record.id_kurperiode',
                'absensi_mahasiswa.id_absensi',
                'angkatan.angkatan',
                'kelas.kelas',
                'prodi.prodi',
                'student_record.id_studentrecord',
                'student.nama',
                'student.nim',
                'absensi_mahasiswa.absensi'
            )
            ->get();

        foreach ($abs as $ab) {
        }

        $absen = Student_record::leftjoin('absensi_mahasiswa', 'student_record.id_studentrecord', '=', 'absensi_mahasiswa.id_studentrecord')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->where('student_record.id_kurperiode', $idk)
            ->where('bap.pertemuan', $per)
            ->where('student_record.status', 'TAKEN')
            ->where(function ($query)  use ($id, $idk) {
                $query->where('absensi_mahasiswa.id_bap', $id)
                    ->orWhere('absensi_mahasiswa.id_bap', NULL);
            })
            ->select(
                'student_record.id_kurperiode',
                'student_record.id_studentrecord',
                'absensi_mahasiswa.id_bap',
                'angkatan.angkatan',
                'kelas.kelas',
                'prodi.prodi',
                'student.nama',
                'student.nim',
                'absensi_mahasiswa.absensi',
                'absensi_mahasiswa.id_absensi',
                'bap.pertemuan'
            )
            ->get();

        dd($absen);
        $dt = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student_record.id_kurperiode', $idk)

            ->where('student_record.status', 'TAKEN')
            ->select('angkatan.angkatan', 'kelas.kelas', 'prodi.prodi', 'student_record.id_kurtrans', 'student_record.id_student', 'student_record.id_studentrecord', 'student.nama', 'student.nim')
            ->get();


        return view('dosen/edit_absen', ['idk' => $idk, 'abs' => $abs, 'id' => $id, 'dt' => $dt, 'absen' => $absen, 'ab' => $ab]);
    }

    public function save_edit_absensi(Request $request)
    {
        $id_record = $request->id_studentrecord;
        $jmlrecord = count($id_record);

        $id_bp = $request->id_bap;
        $absen = $request->absensi;
        $absr = $request->abs;

        if ($absen != null) {
            $jmlabsen = count($request->absensi);
            for ($i = 0; $i < $jmlrecord; $i++) {
                $kurp = $request->id_studentrecord[$i];
                $idr = explode(',', $kurp);
                $tra = $idr[0];

                $cek = Absensi_mahasiswa::where('id_studentrecord', $tra)
                    ->where('id_bap', $id_bp)
                    ->update(['absensi' => 'HADIR']);
            }

            for ($i = 0; $i < $jmlabsen; $i++) {
                $abs = $request->absensi[$i];
                $idab = explode(',', $abs, 2);
                $trsen = $idab[0];
                $trsi = $idab[1];

                Absensi_mahasiswa::where('id_absensi', $trsen)->update(['absensi' => $trsi]);
            }
        } elseif ($absen == null) {

            for ($i = 0; $i < $jmlrecord; $i++) {
                $kurp = $request->id_studentrecord[$i];
                $idr = explode(',', $kurp);
                $tra = $idr[0];

                $cek = Absensi_mahasiswa::where('id_studentrecord', $tra)
                    ->where('id_bap', $id_bp)
                    ->update(['absensi' => 'HADIR']);
            }
        }

        if ($absr != null) {
            $jmlabs = count($request->abs);
            for ($i = 0; $i < $jmlabs; $i++) {
                $absn = $request->abs[$i];
                $idab = explode(',', $absn, 2);
                $trsen = $idab[0];
                $trsi = $idab[1];

                $bsa = new Absensi_mahasiswa();
                $bsa->id_bap = $id_bp;
                $bsa->id_studentrecord = $trsen;
                $bsa->absensi = $trsi;
                $bsa->save();
            }
        }

        $cekhdr = Absensi_mahasiswa::where('id_bap', $id_bp)
            ->where('absensi', 'ABSEN')
            ->get();

        $cekthdr = Absensi_mahasiswa::where('id_bap', $id_bp)
            ->where('absensi', 'HADIR')
            ->get();

        $hithdr = count($cekhdr);

        $hitthdr = count($cekthdr);

        $bp = Bap::where('id_bap', $id_bp)->update(['hadir' => $hithdr]);

        $bp = Bap::where('id_bap', $id_bp)->update(['tidak_hadir' => $hitthdr]);

        $kur = Bap::where('id_bap', $id_bp)
            ->select('id_kurperiode')
            ->get();

        foreach ($kur as $kui) {
            # code...
        }
        $id_kur = $kui->id_kurperiode;


        Alert::success('', 'Absen berhasil diedit')->autoclose(3500);

        return redirect('entri_bap/' . $id_kur);
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
            'file_materi_kuliah' => 'mimes:jpg,jpeg,pdf,png|max:2000',
            'file_materi_tugas' => 'mimes:jpg,jpeg,png|max:2000',
        ]);

        $data_bap = Bap::where('id_bap', $id)->first();
        $data = Kurikulum_periode::where('id_kurperiode', $request->id_kurperiode)->first();
        $sama = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
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
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
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
        return redirect('entri_bap/' . $key->id_kurperiode);
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
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
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
            ->select('kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        $pdf = PDF::loadView('dosen/download/jurnal_perkuliahan_pdf', ['cekkprd' => $cekkprd, 'bap' => $key, 'data' => $data])->setPaper('a4', 'landscape');
        return $pdf->download('Jurnal Matakuliah' . ' ' . $makul . ' ' . $tahun . ' ' . $tipe . ' ' . $kelas . '.pdf');
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

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')

            ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.kode_prausta', ['FA-601', 'TI-601', 'TK-601'])
            ->select(
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta'
            )
            ->get();

        return view('dosen/prausta/pembimbing_pkl', compact('data'));
    }

    public function record_bim_pkl($id)
    {
        $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
                'prausta_setting_relasi.tempat_prausta'
            )
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_master_kode.kode_prausta', ['FA-601', 'TI-601', 'TK-601'])
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
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            //->where('prausta_trans_hasil.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [1, 2, 3])
            ->select(
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_seminar_sidang'
            )
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

        // $id_prausta = $request->id_settingrelasi_prausta;
        // $nilai_1 = $request->nilai_pembimbing_lapangan;
        // $nilai_2 = $request->total;
        // $nilai_3 = $request->totals;

        if ($nilai_pem_lap == null) {

            $huruf = ($ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 2;
        } elseif ($nilai_pem_lap != null) {

            $huruf = (($nilai_pem_lap + $ceknilai_1->nilai1 + $ceknilai_2->nilai2) / 3);
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
        $usta->save();

        Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
        return redirect('penguji_pkl');
    }

    public function pembimbing_sempro()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')

            ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->select(
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_judul_dospem',
                'prausta_setting_relasi.acc_judul_kaprodi'
            )
            ->get();

        return view('dosen/prausta/pembimbing_sempro', compact('data'));
    }

    public function record_bim_sempro($id)
    {
        $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
                'prausta_setting_relasi.tempat_prausta'
            )
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->get();

        return view('dosen/prausta/cek_bimbingan_sempro', compact('jdl', 'pkl'));
    }

    public function penguji_sempro()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')

            ->where(function ($query)  use ($id) {
                $query->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            //->where('prausta_trans_hasil.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [4, 5, 6])
            ->select(
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.id_student',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_pembimbing',
                'prausta_setting_relasi.validasi_penguji_1',
                'prausta_setting_relasi.validasi_penguji_2'
            )
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

        if (($cek_nilai) == null) {
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
            $usta->save();
        } elseif (($cek_nilai) != null) {
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

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
                ->update([
                    'nilai_1' => $nilai_dospem,
                    'nilai_huruf' => $nilai_huruf
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

        if (($cek_nilai) == null) {
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
            $usta->save();
        } elseif (($cek_nilai) != null) {
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

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
                ->update([
                    'nilai_2' => $nilai_dosji1,
                    'nilai_huruf' => $nilai_huruf
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

        if (($cek_nilai) == null) {
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
            $usta->save();
        } elseif (($cek_nilai) != null) {
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

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
                ->update([
                    'nilai_3' => $nilai_dosji2,
                    'nilai_huruf' => $nilai_huruf
                ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect('penguji_sempro');
    }

    public function validasi_dospem($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
            ->update([
                'validasi_pembimbing' => 'SUDAH',
                'tgl_val_pembimbing' => $date
            ]);

        return redirect()->back();
    }

    public function validasi_dosji1($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
            ->update([
                'validasi_penguji_1' => 'SUDAH',
                'tgl_val_penguji_1' => $date
            ]);

        return redirect()->back();
    }

    public function validasi_dosji2($id)
    {
        $date = date('Y-m-d');

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
            ->update([
                'validasi_penguji_2' => 'SUDAH',
                'tgl_val_penguji_2' => $date
            ]);

        return redirect()->back();
    }

    public function pembimbing_ta()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('prausta_setting_relasi.id_dosen_pembimbing', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->select(
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_judul_dospem',
                'prausta_setting_relasi.acc_judul_kaprodi'
            )
            ->get();

        return view('dosen/prausta/pembimbing_ta', compact('data'));
    }

    public function record_bim_ta($id)
    {
        $jdl = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
                'prausta_setting_relasi.tempat_prausta'
            )
            ->first();

        $pkl = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $jdl->idstudent)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->get();

        return view('dosen/prausta/cek_bimbingan_ta', compact('jdl', 'pkl'));
    }

    public function penguji_ta()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')

            ->where(function ($query)  use ($id) {
                $query->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            //->where('prausta_trans_hasil.status', 'ACTIVE')
            ->whereIn('prausta_master_kode.id_masterkode_prausta', [7, 8, 9])
            ->select(
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_seminar_sidang'
            )
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

        if (($cek_nilai) == null) {
            $hasil = $nilai_dospem * 60 / 100;

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
            $usta->save();
        } elseif (($cek_nilai) != null) {
            $hasil = (($nilai_dospem * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100) + ($cek_nilai->nilai_3 * 20 / 100));
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

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
                ->update([
                    'nilai_1' => $nilai_dospem,
                    'nilai_huruf' => $nilai_huruf
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

        if (($cek_nilai) == null) {
            $hasil = $nilai_dosji1 * 20 / 100;
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
            $usta->save();
        } elseif (($cek_nilai) != null) {
            $hasil = (($nilai_dosji1 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_3 * 20 / 100));
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

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
                ->update([
                    'nilai_2' => $nilai_dosji1,
                    'nilai_huruf' => $nilai_huruf
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

        if (($cek_nilai) == null) {
            $hasil = $nilai_dosji2 * 20 / 100;
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
            $usta->save();
        } elseif (($cek_nilai) != null) {
            $hasil = (($nilai_dosji2 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100));
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

            $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
                ->update([
                    'nilai_3' => $nilai_dosji2,
                    'nilai_huruf' => $nilai_huruf
                ]);
        }

        Alert::success('', 'Nilai berhasil dientri')->autoclose(3500);
        return redirect('penguji_ta');
    }
}
