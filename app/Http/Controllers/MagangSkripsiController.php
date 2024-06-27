<?php

namespace App\Http\Controllers;

use PDF;
use File;
use Alert;
use App\Models\Biaya;
use App\Models\Kuitansi;
use App\Models\Student;
use App\Models\Beasiswa;
use App\Models\Student_record;
use App\Models\Prausta_setting_relasi;
use App\Models\Prausta_master_kategori;
use App\Models\Prausta_trans_bimbingan;
use App\Models\Prausta_master_penilaian;
use App\Models\Prausta_trans_hasil;
use App\Models\Prausta_trans_penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MagangSkripsiController extends Controller
{
    public function magang_mhs()
    {
        $id = Auth::user()->id_user;

        $data_mhs = Student::where('idstudent', $id)->first();
        $angkatan = $data_mhs->idangkatan;

        $cek_krs = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.idmakul', ['478'])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek_krs);

        #cek pembimbing magang
        $cekdata_pembimbing = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing'
            )
            ->first();

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Magang Industri', 'MAAF !!');
            return redirect('home');
        } elseif ($hasil_krs > 0) {

            if ($cekdata_pembimbing == null) {
                Alert::error('Maaf Dosen Pembimbing Magang anda belum di Setting', 'Silahkan hubungi Prodi masing-masing !!');
                return redirect('home');
            } elseif ($cekdata_pembimbing != null) {
                #data magang
                $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                    ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select(
                        'prausta_setting_relasi.file_laporan_revisi',
                        'prausta_setting_relasi.file_draft_laporan',
                        'prausta_setting_relasi.jam_selesai_sidang',
                        'prausta_setting_relasi.jam_mulai_sidang',
                        'prausta_setting_relasi.ruangan',
                        'prausta_setting_relasi.tanggal_selesai',
                        'prausta_setting_relasi.dosen_penguji_1',
                        'prausta_setting_relasi.tanggal_mulai',
                        'prausta_master_kategori.kategori',
                        'prausta_setting_relasi.acc_seminar_sidang',
                        'prausta_setting_relasi.id_settingrelasi_prausta',
                        'student.nama',
                        'student.nim',
                        'prausta_master_kode.kode_prausta',
                        'prausta_master_kode.nama_prausta',
                        'prodi.prodi',
                        'prausta_setting_relasi.dosen_pembimbing',
                        'prausta_setting_relasi.judul_prausta',
                        'prausta_setting_relasi.tempat_prausta',
                        'prausta_setting_relasi.tempat_prausta2',
                        'prausta_setting_relasi.file_draft_laporan',
                        'prausta_setting_relasi.validasi_baak',
                        'prausta_setting_relasi.total_uang_saku',
                        'student.kodeprodi'
                    )
                    ->first();

                #data untuk keuangan
                $maha = Student::where('idstudent', $id)
                    ->select(
                        'student.idstudent',
                        'student.nama',
                        'student.idangkatan',
                        'student.idstatus',
                        'student.kodeprodi'
                    )
                    ->first();

                $idangkatan = $maha->idangkatan;
                $idstatus = $maha->idstatus;
                $kodeprodi = $maha->kodeprodi;

                $cek_study = Student::leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                    ->where('student.idstudent', $id)
                    ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
                    ->first();

                $biaya = Biaya::where('idangkatan', $idangkatan)
                    ->where('idstatus', $idstatus)
                    ->where('kodeprodi', $kodeprodi)
                    ->select('spp6', 'prakerin')
                    ->first();

                #cek beasiswa mahasiswa
                $cb = Beasiswa::where('idstudent', $id)->first();

                $biaya_spp = $biaya->prakerin;

                if (($cb) != null) {

                    $spp = $biaya->prakerin - ($biaya->prakerin * $cb->prakerin) / 100;
                } elseif (($cb) == null) {

                    $spp = $biaya->prakerin;
                }

                $sisaspp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                    ->where('kuitansi.idstudent', $id)
                    ->where('bayar.iditem', 35)
                    ->sum('bayar.bayar');

                $hasil_spp = $sisaspp - $spp;

                if ($hasil_spp == 0 or $hasil_spp > 0) {
                    $validasi = 'Sudah Lunas';
                } elseif ($hasil_spp < 0) {
                    $validasi = 'Belum Lunas';
                }

                $databimb = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->limit(1)
                    ->orderByDesc('prausta_trans_bimbingan.id_transbimb_prausta')
                    ->first();

                $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select(
                        'prausta_trans_bimbingan.id_transbimb_prausta',
                        'prausta_trans_bimbingan.file_bimbingan',
                        'prausta_trans_bimbingan.validasi',
                        'prausta_trans_bimbingan.tanggal_bimbingan',
                        'prausta_trans_bimbingan.remark_bimbingan',
                        'prausta_trans_bimbingan.komentar_bimbingan',
                        'prausta_trans_bimbingan.id_transbimb_prausta'
                    )
                    ->get();

                $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->count();

                //cek nilai dan file seminar magang
                $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select('prausta_setting_relasi.file_draft_laporan', 'prausta_trans_hasil.nilai_huruf', 'prausta_setting_relasi.file_laporan_revisi')
                    ->first();

                return view('mhs/magang_skripsi/magang_mhs', compact('data', 'validasi', 'databimb', 'bim', 'angkatan', 'jml_bim', 'hasil_spp', 'cekdata_nilai'));
            }
        }
    }

    public function magang2_mhs()
    {
        $id = Auth::user()->id_user;

        $data_mhs = Student::where('idstudent', $id)->first();
        $angkatan = $data_mhs->idangkatan;

        $cek_krs = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.idmakul', [483])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek_krs);

        #cek pembimbing magang
        $cekdata_pembimbing = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing'
            )
            ->first();

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Magang Industri 2', 'MAAF !!');
            return redirect('home');
        } elseif ($hasil_krs > 0) {

            if ($cekdata_pembimbing == null) {
                Alert::error('Maaf Dosen Pembimbing Magang anda belum di Setting', 'Silahkan hubungi Prodi masing-masing !!');
                return redirect('home');
            } elseif ($cekdata_pembimbing != null) {
                #data magang
                $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                    ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select(
                        'prausta_setting_relasi.file_laporan_revisi',
                        'prausta_setting_relasi.file_draft_laporan',
                        'prausta_setting_relasi.jam_selesai_sidang',
                        'prausta_setting_relasi.jam_mulai_sidang',
                        'prausta_setting_relasi.ruangan',
                        'prausta_setting_relasi.tanggal_selesai',
                        'prausta_setting_relasi.dosen_penguji_1',
                        'prausta_setting_relasi.tanggal_mulai',
                        'prausta_master_kategori.kategori',
                        'prausta_setting_relasi.acc_seminar_sidang',
                        'prausta_setting_relasi.id_settingrelasi_prausta',
                        'student.nama',
                        'student.nim',
                        'prausta_master_kode.kode_prausta',
                        'prausta_master_kode.nama_prausta',
                        'prodi.prodi',
                        'prausta_setting_relasi.dosen_pembimbing',
                        'prausta_setting_relasi.judul_prausta',
                        'prausta_setting_relasi.tempat_prausta',
                        'prausta_setting_relasi.tempat_prausta2',
                        'prausta_setting_relasi.file_draft_laporan',
                        'prausta_setting_relasi.validasi_baak',
                        'prausta_setting_relasi.total_uang_saku',
                        'student.kodeprodi'
                    )
                    ->first();

                #data untuk keuangan
                $maha = Student::where('idstudent', $id)
                    ->select(
                        'student.idstudent',
                        'student.nama',
                        'student.idangkatan',
                        'student.idstatus',
                        'student.kodeprodi'
                    )
                    ->first();

                $idangkatan = $maha->idangkatan;
                $idstatus = $maha->idstatus;
                $kodeprodi = $maha->kodeprodi;

                $cek_study = Student::leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                    ->where('student.idstudent', $id)
                    ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
                    ->first();

                $biaya = Biaya::where('idangkatan', $idangkatan)
                    ->where('idstatus', $idstatus)
                    ->where('kodeprodi', $kodeprodi)
                    ->select('spp6', 'prakerin', 'seminar')
                    ->first();

                #cek beasiswa mahasiswa
                $cb = Beasiswa::where('idstudent', $id)->first();

                $biaya_spp = $biaya->seminar;

                if (($cb) != null) {

                    $spp = $biaya->seminar - ($biaya->seminar * $cb->seminar) / 100;
                } elseif (($cb) == null) {

                    $spp = $biaya->seminar;
                }

                $sisaspp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                    ->where('kuitansi.idstudent', $id)
                    ->where('bayar.iditem', 37)
                    ->sum('bayar.bayar');

                $hasil_spp = $sisaspp - $spp;

                if ($hasil_spp == 0 or $hasil_spp > 0) {
                    $validasi = 'Sudah Lunas';
                } elseif ($hasil_spp < 0) {
                    $validasi = 'Belum Lunas';
                }

                $databimb = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->limit(1)
                    ->orderByDesc('prausta_trans_bimbingan.id_transbimb_prausta')
                    ->first();

                $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select(
                        'prausta_trans_bimbingan.id_transbimb_prausta',
                        'prausta_trans_bimbingan.file_bimbingan',
                        'prausta_trans_bimbingan.validasi',
                        'prausta_trans_bimbingan.tanggal_bimbingan',
                        'prausta_trans_bimbingan.remark_bimbingan',
                        'prausta_trans_bimbingan.komentar_bimbingan',
                        'prausta_trans_bimbingan.id_transbimb_prausta'
                    )
                    ->get();

                $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->count();

                //cek nilai dan file seminar magang
                $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select('prausta_setting_relasi.file_draft_laporan', 'prausta_trans_hasil.nilai_huruf', 'prausta_setting_relasi.file_laporan_revisi')
                    ->first();

                return view('mhs/magang_skripsi/magang2_mhs', compact('data', 'validasi', 'databimb', 'bim', 'angkatan', 'jml_bim', 'hasil_spp', 'cekdata_nilai'));
            }
        }
    }

    public function input_data_magang($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_master_kategori.kategori',
                'prodi.id_prodi',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        $kategori = Prausta_master_kategori::join('prodi', 'prausta_master_kategori.id_prodi', '=', 'prodi.id_prodi')
            ->where('prodi.id_prodi', $data->id_prodi)
            ->get();

        return view('mhs/magang_skripsi/input_data_magang', compact('data', 'kategori'));
    }

    public function input_data_magang2($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_master_kategori.kategori',
                'prodi.id_prodi',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        $kategori = Prausta_master_kategori::join('prodi', 'prausta_master_kategori.id_prodi', '=', 'prodi.id_prodi')
            ->where('prodi.id_prodi', $data->id_prodi)
            ->get();

        return view('mhs/magang_skripsi/input_data_magang2', compact('data', 'kategori'));
    }

    public function simpan_data_magang(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'id_kategori_prausta' => 'required',
            'tanggal_mulai' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $judul = $request->judul_prausta;
        $judul_ok = str_replace("'", "", $judul);

        $tempat = $request->tempat_prausta;
        $tempat_ok = str_replace("'", "", $tempat);

        Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $judul_ok,
            'tempat_prausta' => $tempat_ok,
            'id_kategori_prausta' => $request->id_kategori_prausta,
            'tanggal_mulai' => $request->tanggal_mulai
        ]);

        Alert::success('', 'Data Magang Berhasil Diinput')->autoclose(3500);
        return redirect('magang_mhs');
    }

    public function simpan_data_magang2(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'id_kategori_prausta' => 'required',
            'tanggal_mulai' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $judul = $request->judul_prausta;
        $judul_ok = str_replace("'", "", $judul);

        $tempat = $request->tempat_prausta;
        $tempat_ok = str_replace("'", "", $tempat);

        Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $judul_ok,
            'tempat_prausta' => $tempat_ok,
            'id_kategori_prausta' => $request->id_kategori_prausta,
            'tanggal_mulai' => $request->tanggal_mulai
        ]);

        Alert::success('', 'Data Magang Berhasil Diinput')->autoclose(3500);
        return redirect('magang2_mhs');
    }

    public function put_data_magang(Request $request, $id)
    {
        $prd = Prausta_setting_relasi::find($id);
        $prd->judul_prausta = $request->judul_prausta;
        $prd->tempat_prausta = $request->tempat_prausta;
        $prd->tempat_prausta2 = $request->tempat_prausta2;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        Alert::success('', 'Data Magang Berhasil Diedit')->autoclose(3500);
        return redirect()->back();
    }

    public function simpan_bimbingan_magang(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,
            [
                'file_bimbingan' => 'file:pdf,PDF,doc,docx,DOC,DOCX|max:4096',
            ],
            $message
        );

        $nama = Auth::user()->name;
        $nama_ok = str_replace("'", "", $nama);

        $bimbingan = $request->remark_bimbingan;
        $bim_ok = str_replace("'", "", $bimbingan);

        $usta = new Prausta_trans_bimbingan();
        $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
        $usta->tanggal_bimbingan = $request->tanggal_bimbingan;
        $usta->remark_bimbingan = $bim_ok;
        $usta->added_by = $nama_ok;
        $usta->status = 'ACTIVE';
        $usta->data_origin = 'eSIAM';

        if ($request->hasFile('file_bimbingan')) {
            $file = $request->file('file_bimbingan');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'File Bimbingan Magang/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $usta->file_bimbingan = $nama_file;
        }

        $usta->save();

        Alert::success('', 'Data Bimbingan Magang Berhasil Diinput')->autoclose(3500);
        return redirect()->back();
    }

    public function ajukan_seminar_magang(Request $request)
    {
        $this->validate($request, [
            'file_draft_laporan' => 'mimes:pdf|max:5000',
        ]);

        $date_now = date('Y-m-d');

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);
        $bap->acc_seminar_sidang = 'PENGAJUAN';
        $bap->tgl_pengajuan = $date_now;

        if ($bap->file_draft_laporan) {
            if ($request->hasFile('file_draft_laporan')) {
                File::delete('File Draft Laporan 2/' . Auth::user()->id_user . '/' . $bap->file_draft_laporan);
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_draft_laporan')) {
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Draft Laporan Magang Berhasil upload')->autoclose(3500);
        return redirect()->back();
    }

    function simpan_honor_magang(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;
        $bap = Prausta_setting_relasi::find($id);
        $bap->total_uang_saku = $request->total_uang_saku;
        $bap->save();

        Alert::success('', 'Uang Saku Selama Magang Berhasil disimpan')->autoclose(3500);
        return redirect()->back();
    }

    public function ajukan_seminar_magang2(Request $request)
    {
        $this->validate($request, [
            'file_draft_laporan' => 'mimes:pdf|max:5000',
        ]);

        $date_now = date('Y-m-d');

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);
        $bap->acc_seminar_sidang = 'PENGAJUAN';
        $bap->tgl_pengajuan = $date_now;
        $bap->total_uang_saku = $request->total_uang_saku;

        if ($bap->file_draft_laporan) {
            if ($request->hasFile('file_draft_laporan')) {
                File::delete('File Draft Laporan 2/' . Auth::user()->id_user . '/' . $bap->file_draft_laporan);
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan 2/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_draft_laporan')) {
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan 2/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Draft Laporan Magang Berhasil upload')->autoclose(3500);
        return redirect()->back();
    }

    public function simpan_draft_magang(Request $request)
    {
        $this->validate($request, [
            'file_laporan_revisi' => 'mimes:pdf|max:6000',
        ]);

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);

        if ($bap->file_laporan_revisi) {
            if ($request->hasFile('file_laporan_revisi')) {
                File::delete('File Laporan Revisi 2/' . Auth::user()->id_user . '/' . $bap->file_laporan_revisi);
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        } else {
            if ($request->hasFile('file_laporan_revisi')) {
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function simpan_draft_magang2(Request $request)
    {
        $this->validate($request, [
            'file_laporan_revisi' => 'mimes:pdf|max:6000',
        ]);

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);

        if ($bap->file_laporan_revisi) {
            if ($request->hasFile('file_laporan_revisi')) {
                File::delete('File Laporan Revisi 2/' . Auth::user()->id_user . '/' . $bap->file_laporan_revisi);
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi 2/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        } else {
            if ($request->hasFile('file_laporan_revisi')) {
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi 2/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    function sempro_mhs()
    {
        $id = Auth::user()->id_user;

        //cek id sempro
        $cekdata_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        if ($cekdata_prausta != null) {
            $idprausta = $cekdata_prausta->id_settingrelasi_prausta;
        }

        $cek_krs = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.idmakul', [490])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_studentrecord', 'matakuliah.kode', 'matakuliah.makul')
            ->get();

        $hasil_krs = count($cek_krs);

        #cek nilai dan file magang 2
        $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $id)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.file_draft_laporan', 'prausta_trans_hasil.nilai_huruf', 'prausta_setting_relasi.file_laporan_revisi')
            ->first();

        #cek pembimbing sempro
        $cekdata_bim_sempro = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing'
            )
            ->get();

        if ($hasil_krs == 0) {

            Alert::error('Maaf anda belum melakukan pengisian KRS Skripsi', 'MAAF !!');
            return redirect('home');
        }

        if (count($cekdata_bim_sempro) == 0) {

            Alert::error('Maaf Dosen Pembimbing Sempro anda belum di setting', 'Silahkan hubungi Prodi masing-masing !!');
            return redirect('home');
        }

        if ($cekdata_nilai == null) {

            Alert::error('Maaf anda belum melakukan Upload Laporan Magang', 'MAAF !!');
            return redirect('home');
        }

        if ($cekdata_nilai->nilai_huruf == null) {

            Alert::error('Maaf nilai Magang anda belum dientri oleh dosen', 'MAAF !!');
            return redirect('home');
        }

        #data seminar proposal
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.ruangan',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_master_kategori.kategori',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_judul_dospem',
                'prausta_setting_relasi.acc_judul_kaprodi',
                'prausta_setting_relasi.validasi_pembimbing',
                'prausta_setting_relasi.validasi_penguji_1',
                'prausta_setting_relasi.validasi_penguji_2',
                'prausta_setting_relasi.validasi_baak'
            )
            ->first();

        $maha = Student::where('idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
            ->first();

        $idangkatan = $maha->idangkatan;
        $idstatus = $maha->idstatus;
        $kodeprodi = $maha->kodeprodi;

        $cek_study = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->where('student.idstudent', $id)
            ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
            ->first();

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('seminar')
            ->first();

        //cek beasiswa mahasiswa
        $cb = Beasiswa::where('idstudent', $id)->first();

        if (($cb) != null) {

            $seminar = $biaya->seminar - ($biaya->seminar * $cb->seminar) / 100;
        } elseif (($cb) == null) {

            $seminar = $biaya->seminar;
        }

        $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->where('bayar.iditem', 37)
            ->sum('bayar.bayar');

        $hasil_seminar = $sisaseminar - $seminar;

        if ($hasil_seminar == 0 or $hasil_seminar > 0) {
            $validasi = 'Sudah Lunas';
        } else {
            $validasi = 'Belum Lunas';
        }

        $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.komentar_bimbingan',
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.id_settingrelasi_prausta'
            )
            ->get();

        $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->count();

        $databimb = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->limit(1)
            ->orderByDesc('prausta_trans_bimbingan.id_transbimb_prausta')
            ->first();

        return view('mhs/magang_skripsi/seminar_proposal', compact('data', 'validasi', 'bim', 'jml_bim', 'databimb', 'hasil_seminar', 'cekdata_nilai'));
    }

    public function pengajuan_sempro($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_master_kategori.kategori',
                'prodi.id_prodi',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        $kategori = Prausta_master_kategori::where('id_prodi', $data->id_prodi)->get();

        return view('mhs/magang_skripsi/ajuan_proposal', compact('data', 'kategori'));
    }

    public function simpan_ajuan_sempro(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'id_kategori_prausta' => 'required',
            'tanggal_mulai' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $judul = $request->judul_prausta;
        $judul_ok = str_replace("'", "", $judul);

        $tempat = $request->tempat_prausta;
        $tempat_ok = str_replace("'", "", $tempat);

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $judul_ok,
            'tempat_prausta' => $tempat_ok,
            'id_kategori_prausta' => $request->id_kategori_prausta,
            'tanggal_mulai' => $request->tanggal_mulai,
        ]);

        Alert::success('', 'Data Seminar Proposal Berhasil Diinput')->autoclose(3500);
        return redirect('sempro_mhs');
    }

    public function put_sempro(Request $request, $id)
    {
        $prd = Prausta_setting_relasi::find($id);
        $prd->judul_prausta = $request->judul_prausta;
        $prd->tempat_prausta = $request->tempat_prausta;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        Alert::success('', 'Data Berhasil Diedit')->autoclose(3500);
        return redirect()->back();
    }

    public function simpan_bim_sempro(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,[
                'file_bimbingan' => 'file:pdf,PDF|max:4096',
            ],
            $message,
        );

        $bimbingan = $request->remark_bimbingan;
        $bim_ok = str_replace("'", "", $bimbingan);

        $usta = new Prausta_trans_bimbingan();
        $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
        $usta->tanggal_bimbingan = $request->tanggal_bimbingan;
        $usta->remark_bimbingan = $bim_ok;
        $usta->added_by = Auth::user()->name;
        $usta->status = 'ACTIVE';
        $usta->data_origin = 'eSIAM';

        if ($request->hasFile('file_bimbingan')) {
            $file = $request->file('file_bimbingan');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'File Bimbingan SEMPRO/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $usta->file_bimbingan = $nama_file;
        }

        $usta->save();

        Alert::success('', 'Data Bimbingan Seminar Proposal Berhasil Diinput')->autoclose(3500);
        return redirect()->back();
    }

    public function pembimbing_magang()
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosen/magang_skripsi/pembimbing_magang', compact('data'));
    }

    public function edit_bim_sempro(Request $request, $id)
    {
        $sch = Prausta_trans_bimbingan::find($id);
        $sch->tanggal_bimbingan = $request->tanggal_bimbingan;
        $sch->remark_bimbingan = $request->remark_bimbingan;
        $sch->updated_by = Auth::user()->name;
        if ($sch->file_bimbingan) {
            if ($request->hasFile('file_bimbingan')) {

                File::delete('File Bimbingan SEMPRO/' . Auth::user()->id_user . '/' . $sch->file_bimbingan);
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan SEMPRO/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_bimbingan')) {
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan SEMPRO/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        }
        $sch->save();

        Alert::success('', 'Data Bimbingan Berhasil Diedit')->autoclose(3500);
        return redirect()->back();
    }

    function pembimbing_magang2()
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->select(DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'), 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.validasi_baak')
            ->groupBy('student.nama', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.validasi_baak')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('dosen/magang_skripsi/pembimbing_magang2', compact('data'));
    }

    public function record_bim_magang($id)
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
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30, 33, 34, 35])
            ->get();

        return view('dosen/magang_skripsi/cek_bimbingan_magang', compact('jdl', 'pkl'));
    }

    public function record_bim_magang2($id)
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
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30, 33, 34, 35])
            ->get();

        return view('dosen/magang_skripsi/cek_bimbingan_magang2', compact('jdl', 'pkl'));
    }

    public function komentar_bimbingan_magang(Request $request, $id)
    {
        $prd = Prausta_trans_bimbingan::find($id);
        $prd->komentar_bimbingan = $request->komentar_bimbingan;
        $prd->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function val_bim_magang($id)
    {
        $val = Prausta_trans_bimbingan::find($id);
        $val->validasi = 'SUDAH';
        $val->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function edit_bimbingan_magang(Request $request, $id)
    {
        $sch = Prausta_trans_bimbingan::find($id);
        $sch->tanggal_bimbingan = $request->tanggal_bimbingan;
        $sch->remark_bimbingan = $request->remark_bimbingan;
        $sch->updated_by = Auth::user()->name;

        if ($sch->file_bimbingan) {
            if ($request->hasFile('file_bimbingan')) {

                File::delete('File Bimbingan Magang/' . Auth::user()->id_user . '/' . $sch->file_bimbingan);
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan Magang/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_bimbingan')) {
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan Magang/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        }

        $sch->save();

        Alert::success('', 'Data Bimbingan Magang Berhasil Diedit')->autoclose(3500);
        return redirect()->back();
    }

    public function download_bimbingan_magang_mhs($id)
    {
        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'dosen.akademik'
            )
            ->first();

        $nama = $mhs->nama;
        $nim = $mhs->nim;
        $kelas = $mhs->kelas;

        $data = Prausta_trans_bimbingan::where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)->get();

        $pdf = PDF::loadView('magang_skripsi/magang/unduh_bim_magang', compact('mhs', 'data'))->setPaper('a4');
        return $pdf->download('Kartu Bimbingan Magang' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function penguji_magang_dlm()
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
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
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_trans_hasil.validasi'
            )
            ->get();

        return view('dosen/magang_skripsi/penguji_magang', compact('data'));
    }

    function penguji_magang2_dlm()
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
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
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
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_trans_hasil.validasi'
            )
            ->get();

        return view('dosen/magang_skripsi/penguji_magang2', compact('data'));
    }

    public function isi_form_nilai_magang($id)
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

        return view('dosen/magang_skripsi/form_nilai_magang', compact('data', 'id', 'form_dosbing', 'form_seminar'));
    }

    public function isi_form_nilai_magang2($id)
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

        return view('dosen/magang_skripsi/form_nilai_magang2', compact('data', 'id', 'form_dosbing', 'form_seminar'));
    }

    public function simpan_nilai_magang(Request $request)
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

        Alert::success('', 'Nilai Magang berhasil disimpan')->autoclose(3500);
        return redirect('penguji_magang_dlm');
    }

    public function simpan_nilai_magang2(Request $request)
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

        Alert::success('', 'Nilai Magang berhasil disimpan')->autoclose(3500);
        return redirect('penguji_magang2_dlm');
    }

    public function edit_nilai_magang_by_dosen_dlm($id)
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

        return view('dosen/magang_skripsi/edit_nilai_magang', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
    }

    public function edit_nilai_magang2_by_dosen_dlm($id)
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

        return view('dosen/magang_skripsi/edit_nilai_magang2', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
    }

    public function put_nilai_magang_by_dsn_dlm(Request $request)
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

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)->update([
                'nilai' => $n1,
                'updated_by' => Auth::user()->name,
            ]);
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)->update([
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

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_1' => $nilai_pem_lap,
            'nilai_2' => $ceknilai_1->nilai1,
            'nilai_3' => $ceknilai_2->nilai2,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name
        ]);

        Alert::success('', 'Nilai Magang berhasil disimpan')->autoclose(3500);
        return redirect('penguji_magang_dlm');
    }

    public function put_nilai_magang2_by_dsn_dlm(Request $request)
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

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)->update([
                'nilai' => $n1,
                'updated_by' => Auth::user()->name,
            ]);
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)->update([
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

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_1' => $nilai_pem_lap,
            'nilai_2' => $ceknilai_1->nilai1,
            'nilai_3' => $ceknilai_2->nilai2,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name
        ]);

        Alert::success('', 'Nilai Magang berhasil disimpan')->autoclose(3500);
        return redirect('penguji_magang2_dlm');
    }

    public function skripsi_mhs()
    {
        $id = Auth::user()->id_user;

        //cek ID Skripsi
        $cekdata_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        // CEK KRS SKRIPSI
        $cek_krs = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.idmakul', [490])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_studentrecord', 'matakuliah.kode', 'matakuliah.makul')
            ->get();

        if (empty($cek_krs)) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Skripsi', 'MAAF !!');
            return redirect('home');
        }

        // CEK SETTING DOSEN PEMBIMBING
        $cekdata_bim_sempro = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing'
            )
            ->get();

        if (empty($cekdata_bim_sempro)) {

            Alert::error('Maaf Dosen Pembimbing Skripsi anda belum di setting', 'Silahkan hubungi Prodi masing-masing !!');
            return redirect('home');
        }

        // CEK NILAI SEMPRO 
        $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $id)
            // ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.id_student', 'prausta_setting_relasi.file_draft_laporan', 'prausta_trans_hasil.nilai_huruf', 'prausta_setting_relasi.file_laporan_revisi', 'prausta_setting_relasi.id_masterkode_prausta')
            ->first();

        if (empty($cekdata_nilai)) {

            Alert::error('Maaf anda belum melakukan Upload Laporan Sempro', 'MAAF !!');
            return redirect('home');
        }

        if (empty($cekdata_nilai->nilai_huruf)) {

            Alert::error('Maaf nilai Sempro anda belum dientri oleh dosen', 'MAAF !!');
            return redirect('home');
        }

        // DATA SKRIPSI
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.ruangan',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_master_kategori.kategori',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_judul_dospem',
                'prausta_setting_relasi.acc_judul_kaprodi',
                'prausta_setting_relasi.validasi_pembimbing',
                'prausta_setting_relasi.validasi_penguji_1',
                'prausta_setting_relasi.validasi_penguji_2',
                'prausta_setting_relasi.validasi_baak'
            )
            ->first();

        $maha = Student::where('idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
            ->first();

        $idangkatan = $maha->idangkatan;
        $idstatus = $maha->idstatus;
        $kodeprodi = $maha->kodeprodi;

        $cek_study = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->where('student.idstudent', $id)
            ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
            ->first();

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('sidang')
            ->first();

        //CEK BEASISWA MAHASISWA
        $cb = Beasiswa::where('idstudent', $id)->first();
        if (($cb) != null) {

            $skripsi = $biaya->sidang - ($biaya->sidang * $cb->sidang) / 100;
        } elseif (($cb) == null) {

            $skripsi = $biaya->sidang;
        }

        $sisaSkripsi = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->where('bayar.iditem', 38)
            ->sum('bayar.bayar');

        $hasil_skripsi = $sisaSkripsi - $skripsi;

        if ($hasil_skripsi == 0 or $hasil_skripsi > 0) {
            $validasi = 'Sudah Lunas';
        } else {
            $validasi = 'Belum Lunas';
        }

        $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.komentar_bimbingan',
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.id_settingrelasi_prausta'
            )
            ->get();

        $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->count();

        $databimb = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->limit(1)
            ->orderByDesc('prausta_trans_bimbingan.id_transbimb_prausta')
            ->first();

        return view('mhs/magang_skripsi/skripsi_mhs', compact('data', 'validasi', 'bim', 'jml_bim', 'databimb', 'hasil_skripsi', 'cekdata_nilai'));
    }

    public function pengajuan_skripsi($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_master_kategori.kategori',
                'prodi.id_prodi',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        $kategori = Prausta_master_kategori::where('id_prodi', $data->id_prodi)->get();

        return view('mhs/magang_skripsi/ajuan_skripsi', compact('data', 'kategori'));
    }

    public function simpan_ajuan_skripsi(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'id_kategori_prausta' => 'required',
            'tanggal_mulai' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $judul = $request->judul_prausta;
        $judul_ok = str_replace("'", "", $judul);

        $tempat = $request->tempat_prausta;
        $tempat_ok = str_replace("'", "", $tempat);

        Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $judul_ok,
            'tempat_prausta' => $tempat_ok,
            'id_kategori_prausta' => $request->id_kategori_prausta,
            'tanggal_mulai' => $request->tanggal_mulai,
        ]);

        Alert::success('', 'Data Skripsi Berhasil Diinput')->autoclose(3500);
        return redirect('skripsi_mhs');
    }

    public function simpan_bim_skripsi(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,
            [
                'file_bimbingan' => 'file:pdf,PDF|max:4096',
            ],
            $message,
        );

        $bimbingan = $request->remark_bimbingan;
        $bim_ok = str_replace("'", "", $bimbingan);

        $usta = new Prausta_trans_bimbingan();
        $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
        $usta->tanggal_bimbingan = $request->tanggal_bimbingan;
        $usta->remark_bimbingan = $bim_ok;
        $usta->added_by = Auth::user()->name;
        $usta->status = 'ACTIVE';
        $usta->data_origin = 'eSIAM';

        if ($request->hasFile('file_bimbingan')) {
            $file = $request->file('file_bimbingan');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'File Bimbingan SKRIPSI/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $usta->file_bimbingan = $nama_file;
        }

        $usta->save();

        Alert::success('', 'Data Bimbingan Skripsi Berhasil Diinput')->autoclose(3500);
        return redirect()->back();
    }
}
