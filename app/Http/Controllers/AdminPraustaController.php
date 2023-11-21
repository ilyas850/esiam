<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\User;
use App\Angkatan;
use App\Prodi;
use App\Student;
use App\Biaya;
use App\Matakuliah;
use App\Beasiswa;
use App\Kuitansi;
use App\Bayar;
use App\Dosen;
use App\Ruangan;
use App\Student_record;
use App\Kurikulum_jam;
use App\Kurikulum_periode;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use App\Prausta_trans_bimbingan;
use App\Prausta_master_kategori;
use App\Prausta_trans_hasil;
use App\Prausta_master_penilaian;
use App\Prausta_trans_penilaian;
use App\Exports\DataPrakerinExport;
use App\Exports\DataTaExport;
use App\Exports\DataMagangExport;
use App\Exports\DataMagang2Export;
use App\Exports\DataSkripsiExport;
use App\Kaprodi;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Prausta_master_waktu;
use App\Kuliah_nilaihuruf;
use DateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdminPraustaController extends Controller
{
    public function filter_nilai_prausta(Request $request)
    {
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $kd_prodi = $request->kodeprodi;
        $tp_prausta = $request->tipe_prausta;

        $tahun = Periode_tahun::where('id_periodetahun', $id_tahun)->first();
        $tipe = Periode_tipe::where('id_periodetipe', $id_tipe)->first();
        $prodi = Prodi::where('kodeprodi', $kd_prodi)->first();


        if ($tp_prausta == 'PKL') {

            $data = DB::select('CALL filter_nilai_pkl(?,?,?)', [$kd_prodi, $id_tahun, $id_tipe]);

            return view('prausta/filter/filter_nilai_pkl', compact('data', 'tp_prausta', 'prodi', 'tahun', 'tipe'));
        } else {
            $data = DB::select('CALL filter_nilai_sempro_ta(?,?,?)', [$id_tahun, $id_tipe, $kd_prodi]);

            return view('prausta/filter/filter_nilai_sempro_ta', compact('data', 'tp_prausta', 'prodi', 'tahun', 'tipe'));
        }
    }

    public function save_nilai_pkl_to_trans(Request $request)
    {
        $jml = count($request->nilai_AKHIR);

        for ($i = 0; $i < $jml; $i++) {
            $nilai = $request->nilai_AKHIR[$i];
            $nilaiusta = explode(',', $nilai, 2);
            $ids = $nilaiusta[0];
            $nsta = $nilaiusta[1];

            $angka = Kuliah_nilaihuruf::where('nilai_huruf', $nsta)
                ->where('status', 'ACTIVE')
                ->select('nilai_indeks')
                ->first();

            Student_record::where('id_studentrecord', $ids)->update([
                'nilai_AKHIR' => $nsta,
                'nilai_ANGKA' => $angka->nilai_indeks
            ]);
        }
        Alert::success('', 'Niali berhasil diinput')->autoclose(3500);
        return redirect('nilai_prausta');
    }

    public function save_nilai_sempro_ta_to_trans(Request $request)
    {
        $jml = count($request->nilai_AKHIR);

        for ($i = 0; $i < $jml; $i++) {
            $nilai = $request->nilai_AKHIR[$i];
            $nilaiusta = explode(',', $nilai, 2);
            $ids = $nilaiusta[0];
            $nsta = $nilaiusta[1];

            $angka = Kuliah_nilaihuruf::where('nilai_huruf', $nsta)
                ->where('status', 'ACTIVE')
                ->select('nilai_indeks')
                ->first();

            Student_record::where('id_studentrecord', $ids)->update([
                'nilai_AKHIR' => $nsta,
                'nilai_ANGKA' => $angka->nilai_indeks
            ]);
        }
        Alert::success('', 'Niali berhasil diinput')->autoclose(3500);
        return redirect('nilai_prausta');
    }

    public function data_pkl_magang()
    {

        return view('prausta/prakerin/pkl_magang');
    }

    public function data_pkl_mahasiswa()
    {
        $akhir = time(); #Waktu sekarang

        $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prausta_master_waktu', function ($join) {
                $join->on('prausta_master_waktu.id_periodetahun', '=', 'kurikulum_periode.id_periodetahun')
                    ->on('prausta_master_waktu.id_periodetipe', '=', 'kurikulum_periode.id_periodetipe')
                    ->on('prausta_master_waktu.id_prodi', '=', 'kurikulum_periode.id_prodi');
            })
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [135, 177, 180, 205, 235, 281])
            ->where('prausta_master_waktu.tipe_prausta', 'PKL')
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.idstudent',
                'student.nama',
                'student.nim',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.status',
                'prausta_master_kode.batas_waktu',
                'prausta_setting_relasi.tgl_pengajuan'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/prakerin/data_pkl_mahasiswa', compact('akhir', 'data'));
    }

    public function data_magang_mahasiswa()
    {
        $akhir = time(); #Waktu sekarang

        $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prausta_master_waktu', function ($join) {
                $join->on('prausta_master_waktu.id_periodetahun', '=', 'kurikulum_periode.id_periodetahun')
                    ->on('prausta_master_waktu.id_periodetipe', '=', 'kurikulum_periode.id_periodetipe')
                    ->on('prausta_master_waktu.id_prodi', '=', 'kurikulum_periode.id_prodi');
            })
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [478])
            ->where('prausta_master_waktu.tipe_prausta', 'Magang')
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.idstudent',
                'student.nama',
                'student.nim',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.status',
                'prausta_master_kode.batas_waktu',
                'prausta_setting_relasi.tgl_pengajuan'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/prakerin/data_magang_mahasiswa', compact('akhir', 'data'));
    }

    public function data_magang2_mahasiswa()
    {
        $akhir = time(); #Waktu sekarang

        $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prausta_master_waktu', function ($join) {
                $join->on('prausta_master_waktu.id_periodetahun', '=', 'kurikulum_periode.id_periodetahun')
                    ->on('prausta_master_waktu.id_periodetipe', '=', 'kurikulum_periode.id_periodetipe')
                    ->on('prausta_master_waktu.id_prodi', '=', 'kurikulum_periode.id_prodi');
            })
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [483])
            ->where('prausta_master_waktu.tipe_prausta', 'Magang 2')
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.idstudent',
                'student.nama',
                'student.nim',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.status',
                'prausta_master_kode.batas_waktu',
                'prausta_setting_relasi.tgl_pengajuan'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/prakerin/data_magang2_mahasiswa', compact('akhir', 'data'));
    }

    public function atur_prakerin($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'dosen.iddosen',
                'prausta_setting_relasi.dosen_pembimbing',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.ruangan'
            )
            ->first();

        $dosen = Dosen::where('idstatus', 1)
            ->where('active', 1)
            ->get();

        $jam = Kurikulum_jam::all();

        $ruangan = Ruangan::all();

        return view('prausta/prakerin/atur_prakerin', compact('id', 'data', 'dosen', 'jam', 'ruangan'));
    }

    public function simpan_atur_prakerin(Request $request)
    {
        Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)
            ->update([
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai_sidang' => $request->jam_mulai_sidang,
                'jam_selesai_sidang' => $request->jam_selesai_sidang,
                'dosen_penguji_1' => $request->dosen_penguji_1,
                'ruangan' => $request->ruangan,
                'id_dosen_penguji_1' => $request->id_dosen_penguji_1
            ]);

        Alert::success('', 'Berhasil setting jadwal')->autoclose(3500);
        return redirect()->back();
    }

    public function data_sempro()
    {
        $akhir = time(); #Waktu sekarang

        $data1 = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_master_waktu', function ($join) {
                $join->on('prausta_master_waktu.id_periodetahun', '=', 'kurikulum_periode.id_periodetahun')->on('prausta_master_waktu.id_periodetipe', '=', 'kurikulum_periode.id_periodetipe')
                    ->on('prausta_master_waktu.id_prodi', '=', 'kurikulum_periode.id_prodi');
            })
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 25, 28, 31])
            ->whereIn('kurikulum_periode.id_makul', [136, 178, 179, 206, 286, 316, 430, 490])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('student.active', 1)
            ->where('prausta_master_waktu.tipe_prausta', 'SEMPRO')
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.idstudent',
                'student.nama',
                'student.nim',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.status',
                'prausta_setting_relasi.tgl_pengajuan'
            )
            ->orderBy('student.idangkatan', 'DESC')
            ->orderBy('student.nim', 'ASC')
            ->get();

        $data = DB::select('CALL data_sempro');

        return view('prausta/sempro/data_sempro', compact('akhir', 'data'));
    }

    public function atur_sempro($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'dosen.iddosen',
                'prausta_setting_relasi.dosen_pembimbing',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.ruangan'
            )
            ->first();

        $dosen = Dosen::where('active', 1)
            ->get();

        $jam = Kurikulum_jam::all();

        $ruangan = Ruangan::all();

        return view('prausta/sempro/atur_sempro', compact('id', 'data', 'dosen', 'jam', 'ruangan'));
    }

    public function simpan_atur_sempro(Request $request)
    {
        $dosen1 = $request->id_dosen_penguji_1;
        $pisah_dosen1 = explode(',', $dosen1);
        $dsn1_1 = $pisah_dosen1[0];
        $dsn1_2 = $pisah_dosen1[1];

        $dosen2 = $request->id_dosen_penguji_2;
        $pisah_dosen2 = explode(',', $dosen2);
        $dsn2_1 = $pisah_dosen2[0];
        $dsn2_2 = $pisah_dosen2[1];

        $data = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)
            ->update([
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai_sidang' => $request->jam_mulai_sidang,
                'jam_selesai_sidang' => $request->jam_selesai_sidang,
                'dosen_penguji_1' => $dsn1_2,
                'dosen_penguji_2' => $dsn2_2,
                'ruangan' => $request->ruangan,
                'id_dosen_penguji_1' => $dsn1_1,
                'id_dosen_penguji_2' => $dsn2_1
            ]);

        Alert::success('', 'Berhasil setting jadwal seminar proposal')->autoclose(3500);
        return redirect('data_sempro');
    }

    public function data_ta_skripsi()
    {
        return view('prausta/ta/ta_skripsi');
    }

    public function data_ta_mahasiswa()
    {
        $akhir = time(); // Waktu sekarang

        $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_master_waktu', function ($join) {
                $join->on('prausta_master_waktu.id_periodetahun', '=', 'kurikulum_periode.id_periodetahun')->on('prausta_master_waktu.id_periodetipe', '=', 'kurikulum_periode.id_periodetipe')
                    ->on('prausta_master_waktu.id_prodi', '=', 'kurikulum_periode.id_prodi');
            })
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [136, 178, 179, 206, 286, 316, 430])
            ->whereIn('prausta_master_waktu.tipe_prausta', ['TA'])
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.idstudent',
                'student.nama',
                'student.nim',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.status',
                'prausta_setting_relasi.tgl_pengajuan'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/data_ta_mahasiswa', compact('akhir', 'data'));
    }

    public function data_skripsi_mahasiswa()
    {
        $akhir = time(); // Waktu sekarang

        $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_master_waktu', function ($join) {
                $join->on('prausta_master_waktu.id_periodetahun', '=', 'kurikulum_periode.id_periodetahun')->on('prausta_master_waktu.id_periodetipe', '=', 'kurikulum_periode.id_periodetipe')
                    ->on('prausta_master_waktu.id_prodi', '=', 'kurikulum_periode.id_prodi');
            })
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [490])
            ->whereIn('prausta_master_waktu.tipe_prausta', ['Skripsi'])
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.idstudent',
                'student.nama',
                'student.nim',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.status',
                'prausta_setting_relasi.tgl_pengajuan'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/data_skripsi_mahasiswa', compact('akhir', 'data'));
    }

    public function filter_ta_use_prodi(Request $request)
    {
        $prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $prd_tp = Periode_tipe::all();
        $prodi = Prodi::all();

        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $akhir = time(); // Waktu sekarang

        $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_master_waktu', function ($join) {
                $join->on('prausta_master_waktu.id_periodetahun', '=', 'kurikulum_periode.id_periodetahun')->on('prausta_master_waktu.id_periodetipe', '=', 'kurikulum_periode.id_periodetipe')
                    ->on('prausta_master_waktu.id_prodi', '=', 'kurikulum_periode.id_prodi');
            })
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
            ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [136, 178, 179, 206, 286, 316])
            ->where('prausta_master_waktu.tipe_prausta', 'TA')
            ->where('prodi.id_prodi', $request->id_prodi)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.idstudent',
                'student.nama',
                'student.nim',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.status'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/data_ta', compact('akhir', 'data', 'prodi', 'namaperiodetahun', 'namaperiodetipe', 'prd_thn', 'prd_tp'));
    }

    public function atur_ta($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'dosen.iddosen',
                'prausta_setting_relasi.dosen_pembimbing',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.ruangan'
            )
            ->first();

        $dosen = Dosen::where('idstatus', 1)
            ->where('active', 1)
            ->get();

        $jam = Kurikulum_jam::all();

        $ruangan = Ruangan::all();

        return view('prausta/ta/atur_ta', compact('id', 'data', 'dosen', 'jam', 'ruangan'));
    }

    public function simpan_atur_ta(Request $request)
    {
        $dosen1 = $request->id_dosen_penguji_1;
        $pisah_dosen1 = explode(',', $dosen1);
        $dsn1_1 = $pisah_dosen1[0];
        $dsn1_2 = $pisah_dosen1[1];

        $dosen2 = $request->id_dosen_penguji_2;
        $pisah_dosen2 = explode(',', $dosen2);
        $dsn2_1 = $pisah_dosen2[0];
        $dsn2_2 = $pisah_dosen2[1];

        Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)
            ->update([
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai_sidang' => $request->jam_mulai_sidang,
                'jam_selesai_sidang' => $request->jam_selesai_sidang,
                'dosen_penguji_1' => $dsn1_2,
                'dosen_penguji_2' => $dsn2_2,
                'ruangan' => $request->ruangan,
                'id_dosen_penguji_1' => $dsn1_1,
                'id_dosen_penguji_2' => $dsn2_1
            ]);

        Alert::success('', 'Berhasil setting jadwal sidang tugas akhir/skripsi')->autoclose(3500);
        return redirect()->back();
    }

    public function bim_pkl_magang()
    {
        return view('prausta/prakerin/bim_pkl_magang');
    }

    public function data_bim_pkl_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/data_bim_pkl_mahasiswa', compact('data', 'prodi'));
    }

    public function data_bim_magang_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/data_bim_magang_mahasiswa', compact('data', 'prodi'));
    }

    public function data_bim_magang2_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/data_bim_magang2_mahasiswa', compact('data', 'prodi'));
    }

    public function filter_bim_pkl_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/data_bim_pkl_mahasiswa', compact('data', 'prodi'));
    }

    public function filter_bim_magang_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/data_bim_magang_mahasiswa', compact('data', 'prodi'));
    }

    public function filter_bim_prakerin_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bimbingan_prakerin', compact('data', 'prodi'));
    }

    public function cek_bim_pkl($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'prausta_trans_bimbingan.komentar_bimbingan',
                'student.idstudent'
            )
            ->get();

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
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'student.idstudent',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'dosen.akademik'
            )
            ->first();

        return view('prausta/prakerin/cek_bimbingan_pkl', compact('data', 'mhs'));
    }

    public function cek_bim_magang($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'prausta_trans_bimbingan.komentar_bimbingan',
                'student.idstudent'
            )
            ->get();

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
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'student.idstudent',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'dosen.akademik'
            )
            ->first();

        return view('prausta/prakerin/cek_bimbingan_magang', compact('data', 'mhs'));
    }

    public function bim_sempro()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 25, 28, 31])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/bimbingan_sempro', compact('data', 'prodi'));
    }

    public function filter_bim_sempro_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 25, 28, 31])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/bimbingan_sempro', compact('data', 'prodi'));
    }

    public function cek_bim_sempro($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'prausta_trans_bimbingan.komentar_bimbingan',
                'student.idstudent'
            )
            ->get();

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
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'student.idstudent',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'dosen.akademik'
            )
            ->first();

        return view('prausta/sempro/cek_bimbingan_sempro', compact('data', 'mhs'));
    }

    public function bim_ta_skripsi()
    {
        return view('prausta/ta/bim_ta_skripsi');
    }

    public function data_bim_ta_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->whereIn('student.active', [1, 2])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bimbingan_ta_mahasiswa', compact('data', 'prodi'));
    }

    public function data_bim_skripsi_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->whereIn('student.active', [1, 2])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bimbingan_skripsi_mahasiswa', compact('data', 'prodi'));
    }

    public function bim_ta()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bimbingan_ta', compact('data', 'prodi'));
    }

    public function filter_bim_ta_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bimbingan_ta_mahasiswa', compact('data', 'prodi'));
    }

    public function filter_bim_skripsi_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bimbingan_skripsi_mahasiswa', compact('data', 'prodi'));
    }

    public function cek_bim_ta_mahasiswa($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'prausta_trans_bimbingan.komentar_bimbingan',
                'student.idstudent'
            )
            ->get();

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
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'student.idstudent',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'dosen.akademik'
            )
            ->first();

        return view('prausta/ta/cek_bimbingan_ta_mahasiswa', compact('data', 'mhs'));
    }

    public function cek_bim_skripsi_mahasiswa($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.id_transbimb_prausta',
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'prausta_trans_bimbingan.komentar_bimbingan',
                'student.idstudent'
            )
            ->get();

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
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'student.idstudent',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'dosen.akademik'
            )
            ->first();

        return view('prausta/ta/cek_bimbingan_skripsi_mahasiswa', compact('data', 'mhs'));
    }

    public function nilai_pkl_magang()
    {
        return view('prausta/prakerin/nilai_pkl_magang');
    }

    public function data_nilai_pkl_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/nilai_pkl', compact('data', 'prodi'));
    }

    public function filter_nilai_pkl_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/prakerin/nilai_pkl', compact('data', 'prodi'));
    }

    public function edit_nilai_pkl($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_prakerin');
        } elseif ($jml != 0) {
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

            return view('prausta/prakerin/edit_nilai_pkl', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
        }
    }

    public function put_nilai_pkl(Request $request)
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

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)
                ->update([
                    'nilai' => $n1,
                    'updated_by' => Auth::user()->name
                ]);
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)
                ->update([
                    'nilai' => $n2,
                    'updated_by' => Auth::user()->name
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

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_1' => $nilai_pem_lap,
                'nilai_2' => $ceknilai_1->nilai1,
                'nilai_3' => $ceknilai_2->nilai2,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai PKL berhasil disimpan')->autoclose(3500);
        return redirect('data_nilai_pkl_mahasiswa');
    }

    public function data_nilai_magang_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/nilai_magang', compact('data', 'prodi'));
    }

    public function data_nilai_magang2_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/nilai_magang2', compact('data', 'prodi'));
    }

    public function filter_nilai_magang_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/prakerin/nilai_magang', compact('data', 'prodi'));
    }

    public function edit_nilai_magang($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_prakerin');
        } elseif ($jml != 0) {
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

            return view('prausta/prakerin/edit_nilai_magang', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
        }
    }

    public function edit_nilai_magang2($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_prakerin');
        } elseif ($jml != 0) {
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

            return view('prausta/prakerin/edit_nilai_magang2', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
        }
    }

    public function put_nilai_magang(Request $request)
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

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)
                ->update([
                    'nilai' => $n1,
                    'updated_by' => Auth::user()->name
                ]);
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)
                ->update([
                    'nilai' => $n2,
                    'updated_by' => Auth::user()->name
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

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_1' => $nilai_pem_lap,
                'nilai_2' => $ceknilai_1->nilai1,
                'nilai_3' => $ceknilai_2->nilai2,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
        return redirect('data_nilai_magang_mahasiswa');
    }

    public function put_nilai_magang2(Request $request)
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

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)
                ->update([
                    'nilai' => $n1,
                    'updated_by' => Auth::user()->name
                ]);
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)
                ->update([
                    'nilai' => $n2,
                    'updated_by' => Auth::user()->name
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

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_1' => $nilai_pem_lap,
                'nilai_2' => $ceknilai_1->nilai1,
                'nilai_3' => $ceknilai_2->nilai2,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
        return redirect('data_nilai_magang2_mahasiswa');
    }

    public function nilai_sempro()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/nilai_sempro', compact('data', 'prodi'));
    }

    public function filter_nilai_sempro_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/nilai_sempro', compact('data', 'prodi'));
    }

    public function edit_nilai_sempro_bim($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/sempro/edit_nilai_sempro', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_sempro_dospem(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_1' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('nilai_sempro');
    }

    public function edit_nilai_sempro_p1($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/sempro/edit_nilai_sempro_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_sempro_dospeng1(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        $hasil = ($nilai_dospem + $cek_nilai->nilai_1 + $cek_nilai->nilai_3) / 3;
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
                'nilai_2' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('nilai_sempro');
    }

    public function edit_nilai_sempro_p2($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/sempro/edit_nilai_sempro_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_sempro_dospeng2(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_1) / 3;
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
                'nilai_3' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('nilai_sempro');
    }

    public function nilai_ta_skripsi()
    {
        return view('prausta/ta/nilai_ta_skripsi');
    }

    public function data_nilai_ta_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/nilai_ta', compact('data', 'prodi'));
    }

    public function nilai_ta()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/nilai_ta', compact('data', 'prodi'));
    }

    public function filter_nilai_ta_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/nilai_ta', compact('data', 'prodi'));
    }

    public function edit_nilai_ta_bim($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_ta');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/ta/edit_nilai_ta', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_ta_dospem(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
        return redirect('nilai_ta');
    }

    public function edit_nilai_ta_p1($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_ta');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/ta/edit_nilai_ta_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_ta_dospeng1(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        $hasil = (($nilai_dospeng1 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_3 * 20 / 100));

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
                'nilai_2' => $nilai_dospeng1,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
        return redirect('nilai_ta');
    }

    public function edit_nilai_ta_p2($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_ta');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/ta/edit_nilai_ta_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_ta_dospeng2(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        $hasil = (($nilai_dospeng2 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100));

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
                'nilai_3' => $nilai_dospeng2,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);


        Alert::success('', 'Nilai TA berhasil diedit')->autoclose(3500);
        return redirect('nilai_ta');
    }

    public function data_nilai_skripsi_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/nilai_skripsi', compact('data', 'prodi'));
    }

    public function filter_nilai_skripsi_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_trans_hasil.validasi',
                'prausta_setting_relasi.tanggal_selesai'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/ta/nilai_skripsi', compact('data', 'prodi'));
    }

    public function edit_nilai_skripsi_bim($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_ta');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/ta/edit_nilai_skripsi', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_skripsi_dospem(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_1' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Skripsi berhasil diedit')->autoclose(3500);
        return redirect('data_nilai_skripsi_mahasiswa');
    }

    public function edit_nilai_skripsi_p1($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_ta');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/ta/edit_nilai_skripsi_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_skripsi_dospeng1(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        $hasil = (($nilai_dospeng1 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_3 * 20 / 100));

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

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_2' => $nilai_dospeng1,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Skripsi berhasil diedit')->autoclose(3500);
        return redirect('data_nilai_skripsi_mahasiswa');
    }

    public function edit_nilai_skripsi_p2($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_ta');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
                ->first();

            $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(
                    'prausta_master_penilaian.komponen',
                    'prausta_master_penilaian.bobot',
                    'prausta_master_penilaian.acuan',
                    'prausta_trans_penilaian.nilai',
                    'prausta_trans_penilaian.id_trans_penilaian'
                )
                ->get();

            return view('prausta/ta/edit_nilai_skripsi_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
        }
    }

    public function put_nilai_skripsi_dospeng2(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)
                ->update([
                    'nilai' => $n,
                    'updated_by' => Auth::user()->name
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

        $hasil = (($nilai_dospeng2 * 20 / 100) + ($cek_nilai->nilai_1 * 60 / 100) + ($cek_nilai->nilai_2 * 20 / 100));

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
                'nilai_3' => $nilai_dospeng2,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);


        Alert::success('', 'Nilai Skripsi berhasil diedit')->autoclose(3500);
        return redirect('data_nilai_skripsi_mahasiswa');
    }

    public function bap_pkl_magang()
    {
        return view('prausta/prakerin/bap_pkl_magang');
    }

    public function data_bap_pkl_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bap_pkl', compact('data', 'prodi'));
    }

    public function filter_bap_pkl_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bap_pkl', compact('data', 'prodi'));
    }

    public function data_bap_magang_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bap_magang', compact('data', 'prodi'));
    }

    public function data_bap_magang2_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bap_magang2', compact('data', 'prodi'));
    }

    public function filter_bap_magang_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bap_magang', compact('data', 'prodi'));
    }

    public function nonatifkan_prausta_prakerin($id)
    {
        Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
            ->update([
                'status' => 'NOT ACTIVE'
            ]);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function nonatifkan_prausta_sempro($id)
    {
        Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
            ->update([
                'status' => 'NOT ACTIVE'
            ]);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function nonatifkan_prausta_ta($id)
    {
        Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
            ->update([
                'status' => 'NOT ACTIVE'
            ]);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function download_bimbingan_prakerin(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

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

        $pdf = PDF::loadView('prausta/prakerin/unduh_bim_prakerin', compact('mhs', 'data'))->setPaper('a4');
        return $pdf->download('Kartu Bimbingan PKL' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function download_bimbingan_sempro(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2'
            )
            ->first();

        $nama = $mhs->nama;
        $nim = $mhs->nim;
        $kelas = $mhs->kelas;

        $dospem = Dosen::where('iddosen', $mhs->id_dosen_pembimbing)->first();

        $dospeng1 = Dosen::where('iddosen', $mhs->id_dosen_penguji_1)->first();

        $dospeng2 = Dosen::where('iddosen', $mhs->id_dosen_penguji_2)->first();

        $data = Prausta_trans_bimbingan::where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)->get();

        $pdf = PDF::loadView('prausta/sempro/unduh_bim_sempro', compact('mhs', 'data', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
        return $pdf->download('Kartu Bimbingan Sempro' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function download_bimbingan_ta(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2'
            )
            ->first();

        $nama = $mhs->nama;
        $nim = $mhs->nim;
        $kelas = $mhs->kelas;

        $dospem = Dosen::where('iddosen', $mhs->id_dosen_pembimbing)->first();

        $dospeng1 = Dosen::where('iddosen', $mhs->id_dosen_penguji_1)->first();

        $dospeng2 = Dosen::where('iddosen', $mhs->id_dosen_penguji_2)->first();

        $data = Prausta_trans_bimbingan::where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)->get();

        $pdf = PDF::loadView('prausta/ta/unduh_bim_ta', compact('mhs', 'data', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
        return $pdf->download('Kartu Bimbingan TA' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function bap_prakerin()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bap_prakerin', compact('data', 'prodi'));
    }

    public function filter_bap_prakerin_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/bap_prakerin', compact('data', 'prodi'));
    }

    public function download_bap_prakerin(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prodi.id_prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'dosen.nama as nama_dsn',
                'dosen.nik',
                'dosen.akademik'
            )
            ->first();

        $nama = $data->nama;
        $nim = $data->nim;
        $kelas = $data->kelas;
        $idprodi = $data->id_prodi;

        $kaprodi = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
            ->join('prodi', 'prodi.kodeprodi', '=', 'kaprodi.kodeprodi')
            ->where('prodi.id_prodi', $idprodi)
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

        $bulan = array(
            1 =>   'Januari',
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
            'Desember'
        );

        $pecahkan = explode('-', $data->tanggal_selesai);

        $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

        $pdf = PDF::loadView('prausta/prakerin/unduh_bap_prakerin', compact('data', 'hari', 'tglhasil', 'nama_kaprodi', 'nik_kaprodi', 'akademik_kaprodi'))->setPaper('a4');
        return $pdf->download('BAP PKL-Magang' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function download_bap_prakerin_all(Request $request)
    {
        $data_id = $request->id_settingrelasi_prausta;

        $hitung_id = count($data_id);

        for ($i = 0; $i < $hitung_id; $i++) {
            $id_prausta = $data_id[$i];

            $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
                ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id_prausta)
                ->select(
                    'student.nama',
                    'student.nim',
                    'prodi.prodi',
                    'prodi.id_prodi',
                    'kelas.kelas',
                    'angkatan.angkatan',
                    'prausta_trans_hasil.id_settingrelasi_prausta',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tempat_prausta',
                    'prausta_setting_relasi.dosen_pembimbing',
                    'prausta_setting_relasi.tanggal_selesai',
                    'prausta_trans_hasil.nilai_1',
                    'prausta_trans_hasil.nilai_2',
                    'prausta_trans_hasil.nilai_3',
                    'prausta_trans_hasil.nilai_huruf',
                    'dosen.nama as nama_dsn',
                    'dosen.nik',
                    'dosen.akademik'
                )
                ->first();

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

            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $data->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
        }

        $pdf = PDF::loadView('prausta/prakerin/unduh_bap_prakerin', compact('data', 'hari', 'tglhasil', 'nama_kaprodi', 'nik_kaprodi', 'akademik_kaprodi'))->setPaper('a4');

        return $pdf->download('BAP PKL-Magang' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function bap_sempro()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 24, 27, 30])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/bap_sempro', compact('data', 'prodi'));
    }

    public function filter_bap_sempro_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/bap_sempro', compact('data', 'prodi'));
    }

    public function download_bap_sempro(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'dosen.nama as nama_dsn',
                'dosen.akademik'
            )
            ->first();

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

        $bulan = array(
            1 =>   'Januari',
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
            'Desember'
        );

        $pecahkan = explode('-', $data->tanggal_selesai);

        $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

        $dospem = Dosen::where('iddosen', $data->id_dosen_pembimbing)->first();

        $dospeng1 = Dosen::where('iddosen', $data->id_dosen_penguji_1)->first();

        $dospeng2 = Dosen::where('iddosen', $data->id_dosen_penguji_2)->first();

        $pdf = PDF::loadView('prausta/sempro/unduh_bap_sempro', compact('data', 'hari', 'tglhasil', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
        return $pdf->download('BAP Sempro' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function bap_ta_skripsi()
    {
        return view('prausta/ta/bap_ta_skripsi');
    }

    public function data_bap_ta_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bap_ta', compact('data', 'prodi'));
    }

    public function filter_bap_ta_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bap_ta', compact('data', 'prodi'));
    }

    public function data_bap_skripsi_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bap_skripsi', compact('data', 'prodi'));
    }

    public function filter_bap_skripsi_use_prodi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')->get();

        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/bap_ta', compact('data', 'prodi'));
    }

    public function download_bap_ta(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_trans_hasil.id_settingrelasi_prausta',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'dosen.nama as nama_dsn',
                'dosen.akademik'
            )
            ->first();

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

        $bulan = array(
            1 =>   'Januari',
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
            'Desember'
        );

        $pecahkan = explode('-', $data->tanggal_selesai);

        $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

        $dospem = Dosen::where('iddosen', $data->id_dosen_pembimbing)->first();

        $dospeng1 = Dosen::where('iddosen', $data->id_dosen_penguji_1)->first();

        $dospeng2 = Dosen::where('iddosen', $data->id_dosen_penguji_2)->first();

        $pdf = PDF::loadView('prausta/ta/unduh_bap_ta', compact('data', 'hari', 'tglhasil', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
        return $pdf->download('BAP TA/Skripsi' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function unduh_nilai_prakerin_b($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_prakerin');
        } elseif ($jml != 0) {

            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
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
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 1)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 1)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;

            $pdf = PDF::loadView('prausta/prakerin/unduh_form_pembimbing', compact('datadiri', 'datanilai', 'hasil', 'tglhasil'))->setPaper('a4');
            return $pdf->download('Form Penilaian Pembimbing' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function unduh_nilai_prakerin_c($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_prakerin');
        } elseif ($jml != 0) {

            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
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
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 1)
                ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 1)
                ->where('prausta_master_penilaian.jenis_form', 'Form Seminar')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;


            $pdf = PDF::loadView('prausta/prakerin/unduh_form_seminar', compact('datadiri', 'datanilai', 'hasil', 'tglhasil'))->setPaper('a4');
            return $pdf->download('Form Penilaian Seminar' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function unduh_nilai_sempro_a($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
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
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cekhari = date('l', strtotime($datadiri->tanggal_selesai));

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

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.acuan', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;


            $pdf = PDF::loadView('prausta/sempro/unduh_form_pembimbing', compact('datadiri', 'datanilai', 'hasil', 'tglhasil', 'hari'))->setPaper('a4');
            return $pdf->download('Form Penilaian Seminar Proposal (Pembimbing)' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function unduh_nilai_sempro_b($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_1', '=', 'dosen.iddosen')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select(
                    'student.nama',
                    'student.nim',
                    'prodi.prodi',
                    'kelas.kelas',
                    'angkatan.angkatan',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cekhari = date('l', strtotime($datadiri->tanggal_selesai));

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

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.acuan', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;


            $pdf = PDF::loadView('prausta/sempro/unduh_form_penguji_1', compact('datadiri', 'datanilai', 'hasil', 'tglhasil', 'hari'))->setPaper('a4');
            return $pdf->download('Form Penilaian Seminar Proposal (Penguji I)' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function unduh_nilai_sempro_c($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_2', '=', 'dosen.iddosen')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select(
                    'student.nama',
                    'student.nim',
                    'prodi.prodi',
                    'kelas.kelas',
                    'angkatan.angkatan',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cekhari = date('l', strtotime($datadiri->tanggal_selesai));

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

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.acuan', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 2)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;


            $pdf = PDF::loadView('prausta/sempro/unduh_form_penguji_2', compact('datadiri', 'datanilai', 'hasil', 'tglhasil', 'hari'))->setPaper('a4');
            return $pdf->download('Form Penilaian Seminar Proposal (Penguji II)' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function unduh_nilai_ta_a($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('data_nilai_ta_mahasiswa');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
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
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cekhari = date('l', strtotime($datadiri->tanggal_selesai));

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

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.acuan', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;


            $pdf = PDF::loadView('prausta/ta/unduh_form_pembimbing', compact('datadiri', 'datanilai', 'hasil', 'tglhasil', 'hari'))->setPaper('a4');
            return $pdf->download('Form Penilaian Sidang Tugas Akhir (Pembimbing)' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function unduh_nilai_ta_b($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('data_nilai_ta_mahasiswa');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_1', '=', 'dosen.iddosen')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select(
                    'student.nama',
                    'student.nim',
                    'prodi.prodi',
                    'kelas.kelas',
                    'angkatan.angkatan',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cekhari = date('l', strtotime($datadiri->tanggal_selesai));

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

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.acuan', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;


            $pdf = PDF::loadView('prausta/ta/unduh_form_penguji_1', compact('datadiri', 'datanilai', 'hasil', 'tglhasil', 'hari'))->setPaper('a4');
            return $pdf->download('Form Penilaian Sidang Tugas Akhir (Penguji I)' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function unduh_nilai_ta_c($id)
    {
        $ceknilai = Prausta_trans_penilaian::where('id_settingrelasi_prausta', $id)->get();
        $jml = count($ceknilai);

        if ($jml == 0) {

            Alert::error('', 'Maaf nilai ini belum ada di history sistem')->autoclose(3500);
            return redirect('data_nilai_ta_mahasiswa');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
                ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_2', '=', 'dosen.iddosen')
                ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
                ->select(
                    'student.nama',
                    'student.nim',
                    'prodi.prodi',
                    'kelas.kelas',
                    'angkatan.angkatan',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tempat_prausta',
                    'dosen.akademik',
                    'dosen.nama as nama_dsn',
                    'prausta_setting_relasi.tanggal_selesai',
                )
                ->first();

            $nama = $datadiri->nama;
            $nim = $datadiri->nim;
            $kelas = $datadiri->kelas;

            $cekhari = date('l', strtotime($datadiri->tanggal_selesai));

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

            $cektgl = date(' d F Y', strtotime($datadiri->tanggal_selesai));
            $bulan = array(
                1 =>   'Januari',
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
                'Desember'
            );

            $pecahkan = explode('-', $datadiri->tanggal_selesai);

            $tglhasil = $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];

            $datanilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.acuan', 'prausta_master_penilaian.bobot', 'prausta_trans_penilaian.nilai')
                ->get();

            $totalnilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
                ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
                ->where('prausta_master_penilaian.kategori', 3)
                ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
                ->where('prausta_master_penilaian.status', 'ACTIVE')
                ->select(DB::raw('sum(prausta_trans_penilaian.nilai * (prausta_master_penilaian.bobot / 100)) as nilai'))
                ->first();

            $hasil = $totalnilai->nilai;


            $pdf = PDF::loadView('prausta/ta/unduh_form_penguji_2', compact('datadiri', 'datanilai', 'hasil', 'tglhasil', 'hari'))->setPaper('a4');
            return $pdf->download('Form Penilaian Sidang Tugas Akhir (Penguji II)' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
        }
    }

    public function export_data()
    {
        $periode = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->select(DB::raw('DISTINCT(kurikulum_periode.id_periodetahun)'), 'kurikulum_periode.id_periodetipe', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun')
            ->whereIn('kurikulum_periode.id_periodetipe', ['2', '1'])
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->orderBy('periode_tahun.periode_tahun', 'asc')
            ->orderBy('periode_tipe.periode_tipe', 'asc')
            ->get();

        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        return view('prausta/export_prausta', compact('periode', 'prodi'));
    }

    public function excel_pkl(Request $request)
    {
        $periode = $request->idperiode;
        $kodeprodi = $request->kodeprodi;
        $prd = explode(',', $periode, 2);
        $id1 = $prd[0];
        $id2 = $prd[1];

        $prodi = Prodi::where('kodeprodi', $kodeprodi)
            ->select('prodi', 'kodeprodi')
            ->first();

        $pro = $prodi->prodi;

        $tahun = Periode_tahun::where('id_periodetahun', $id1)
            ->select('periode_tahun')
            ->first();

        $thn = $tahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);

        $tipe = Periode_tipe::where('id_periodetipe', $id2)
            ->select('periode_tipe')
            ->first();

        $tp = $tipe->periode_tipe;

        $nama_file = 'Data PKL' . ' ' . $pro . ' ' . $ganti . ' ' . $tp . '.xlsx';
        return Excel::download(new DataPrakerinExport($id1, $id2, $kodeprodi), $nama_file);
    }

    public function excel_magang(Request $request)
    {
        $periode = $request->idperiode;
        $kodeprodi = $request->kodeprodi;
        $prd = explode(',', $periode, 2);
        $id1 = $prd[0];
        $id2 = $prd[1];

        $prodi = Prodi::where('kodeprodi', $kodeprodi)
            ->select('prodi', 'kodeprodi')
            ->first();

        $pro = $prodi->prodi;

        $tahun = Periode_tahun::where('id_periodetahun', $id1)
            ->select('periode_tahun')
            ->first();

        $thn = $tahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);

        $tipe = Periode_tipe::where('id_periodetipe', $id2)
            ->select('periode_tipe')
            ->first();

        $tp = $tipe->periode_tipe;

        $nama_file = 'Data Magang 1' . ' ' . $pro . ' ' . $ganti . ' ' . $tp . '.xlsx';
        return Excel::download(new DataMagangExport($id1, $id2, $kodeprodi), $nama_file);
    }

    public function excel_magang2(Request $request)
    {
        $periode = $request->idperiode;
        $kodeprodi = $request->kodeprodi;
        $prd = explode(',', $periode, 2);
        $id1 = $prd[0];
        $id2 = $prd[1];

        $prodi = Prodi::where('kodeprodi', $kodeprodi)
            ->select('prodi', 'kodeprodi')
            ->first();

        $pro = $prodi->prodi;

        $tahun = Periode_tahun::where('id_periodetahun', $id1)
            ->select('periode_tahun')
            ->first();

        $thn = $tahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);

        $tipe = Periode_tipe::where('id_periodetipe', $id2)
            ->select('periode_tipe')
            ->first();

        $tp = $tipe->periode_tipe;

        $nama_file = 'Data Magang 2' . ' ' . $pro . ' ' . $ganti . ' ' . $tp . '.xlsx';
        return Excel::download(new DataMagang2Export($id1, $id2, $kodeprodi), $nama_file);
    }

    public function excel_ta(Request $request)
    {
        $periode = $request->idperiode;
        $kodeprodi = $request->kodeprodi;
        $prd = explode(',', $periode, 2);
        $id1 = $prd[0];
        $id2 = $prd[1];

        $prodi = Prodi::where('kodeprodi', $kodeprodi)
            ->select('prodi', 'kodeprodi')
            ->first();

        $pro = $prodi->prodi;

        $tahun = Periode_tahun::where('id_periodetahun', $id1)
            ->select('periode_tahun')
            ->first();

        $thn = $tahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);

        $tipe = Periode_tipe::where('id_periodetipe', $id2)
            ->select('periode_tipe')
            ->first();

        $tp = $tipe->periode_tipe;

        $nama_file = 'Data Tugas Akhir' . ' ' . $pro . ' ' . $ganti . ' ' . $tp . '.xlsx';
        return Excel::download(new DataTaExport($id1, $id2, $kodeprodi), $nama_file);
    }

    public function excel_skripsi(Request $request)
    {
        $periode = $request->idperiode;
        $kodeprodi = $request->kodeprodi;
        $prd = explode(',', $periode, 2);
        $id1 = $prd[0];
        $id2 = $prd[1];

        $prodi = Prodi::where('kodeprodi', $kodeprodi)
            ->select('prodi', 'kodeprodi')
            ->first();

        $pro = $prodi->prodi;

        $tahun = Periode_tahun::where('id_periodetahun', $id1)
            ->select('periode_tahun')
            ->first();

        $thn = $tahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);

        $tipe = Periode_tipe::where('id_periodetipe', $id2)
            ->select('periode_tipe')
            ->first();

        $tp = $tipe->periode_tipe;

        $nama_file = 'Data Tugas Akhir' . ' ' . $pro . ' ' . $ganti . ' ' . $tp . '.xlsx';
        return Excel::download(new DataSkripsiExport($id1, $id2, $kodeprodi), $nama_file);
    }

    public function validate_nilai_pkl($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '1']);

        Alert::success('', 'Validasi Nilai berhasil')->autoclose(3500);
        return redirect('data_nilai_pkl_mahasiswa');
    }

    public function unvalidate_nilai_pkl($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '0']);

        Alert::success('', 'Batal Validasi Nilai berhasil')->autoclose(3500);
        return redirect('data_nilai_pkl_mahasiswa');
    }

    public function validate_nilai_magang($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '1']);

        Alert::success('', 'Validasi Nilai berhasil')->autoclose(3500);
        return redirect('data_nilai_magang_mahasiswa');
    }

    public function unvalidate_nilai_magang($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '0']);

        Alert::success('', 'Batal Validasi Nilai berhasil')->autoclose(3500);
        return redirect('data_nilai_magang_mahasiswa');
    }

    public function validate_nilai_magang2($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '1']);

        Alert::success('', 'Validasi Nilai berhasil')->autoclose(3500);
        return redirect('data_nilai_magang2_mahasiswa');
    }

    public function unvalidate_nilai_magang2($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '0']);

        Alert::success('', 'Batal Validasi Nilai berhasil')->autoclose(3500);
        return redirect('data_nilai_magang2_mahasiswa');
    }

    public function validate_nilai_sempro($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '1']);

        Alert::success('', 'Validasi Nilai berhasil')->autoclose(3500);
        return redirect('nilai_sempro');
    }

    public function unvalidate_nilai_sempro($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '0']);

        Alert::success('', 'Batal Validasi Nilai berhasil')->autoclose(3500);
        return redirect('nilai_sempro');
    }

    public function validate_nilai_ta($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '1']);

        Alert::success('', 'Validasi Nilai berhasil')->autoclose(3500);
        return redirect('nilai_ta');
    }

    public function unvalidate_nilai_ta($id)
    {
        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id)->update(['validasi' => '0']);

        Alert::success('', 'Batal Validasi Nilai berhasil')->autoclose(3500);
        return redirect('nilai_ta');
    }

    public function validasi_pkl_magang()
    {
        return view('prausta/prakerin/validasi_pkl_magang');
    }

    public function data_val_pkl_mahasiswa()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/prakerin/validasi_pkl', compact('data'));
    }

    public function data_val_magang_mahasiswa()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/prakerin/validasi_magang', compact('data'));
    }

    public function data_val_magang2_mahasiswa()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/prakerin/validasi_magang2', compact('data'));
    }

    public function validasi_sempro()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 24, 27, 30])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/sempro/validasi_sempro', compact('data'));
    }

    public function validasi_ta_skripsi()
    {
        return view('prausta/ta/validasi_ta_skripsi');
    }

    public function data_val_ta_mahasiswa()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf',
                'prausta_setting_relasi.file_plagiarisme'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_setting_relasi.file_plagiarisme'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/ta/validasi_ta', compact('data'));
    }

    public function data_val_skripsi_mahasiswa()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf',
                'prausta_setting_relasi.file_plagiarisme'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_setting_relasi.file_plagiarisme'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/ta/validasi_skripsi', compact('data'));
    }

    public function validasi_akhir_prausta($id)
    {
        Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['validasi_baak' => 'SUDAH']);

        Prausta_trans_bimbingan::where('id_settingrelasi_prausta', $id)->update(['validasi_baak' => 'SUDAH']);

        Alert::success('', 'Berhasil Validasi Akhir')->autoclose(3500);
        return redirect()->back();
    }

    public function batal_validasi_akhir_prausta($id)
    {
        Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['validasi_baak' => 'BELUM']);

        Prausta_trans_bimbingan::where('id_settingrelasi_prausta', $id)->update(['validasi_baak' => 'BELUM']);

        Alert::success('', 'Berhasil Batal Validasi Akhir')->autoclose(3500);
        return redirect()->back();
    }

    public function waktu_pkl()
    {
        $periodetahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $periodetipe = Periode_tipe::all();

        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = Prausta_master_waktu::join('periode_tahun', 'prausta_master_waktu.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'prausta_master_waktu.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('prodi', 'prausta_master_waktu.id_prodi', '=', 'prodi.id_prodi')
            ->whereIn('prausta_master_waktu.tipe_prausta', ['PKL', 'Magang', 'Magang 2'])
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->select(
                'prausta_master_waktu.id_masterwaktu_prausta',
                'periode_tahun.id_periodetahun',
                'periode_tipe.id_periodetipe',
                'prodi.id_prodi',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'prodi.prodi',
                'prodi.konsentrasi',
                'prodi.kodeprodi',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_master_waktu.tipe_prausta',
                'prausta_master_waktu.status'
            )
            ->orderBy('periode_tahun.periode_tahun')
            ->orderBy('prodi.prodi', 'ASC')
            ->orderBy('prodi.kodeprodi', 'ASC')
            ->get();

        return view('prausta/prakerin/waktu_pkl', compact('periodetahun', 'periodetipe', 'prodi', 'data'));
    }

    public function post_waktu_prausta(Request $request)
    {
        $cekProdi = Prodi::where('kodeprodi', $request->kodeprodi)->get();

        for ($i = 0; $i < count($cekProdi); $i++) {
            $prodi = $cekProdi[$i];

            $kpr = new Prausta_master_waktu();
            $kpr->id_periodetahun = $request->id_periodetahun;
            $kpr->id_periodetipe = $request->id_periodetipe;
            $kpr->id_prodi = $prodi->id_prodi;
            $kpr->set_waktu_awal = $request->set_waktu_awal;
            $kpr->set_waktu_akhir = $request->set_waktu_akhir;
            $kpr->tipe_prausta = $request->tipe_prausta;
            $kpr->created_by = Auth::user()->name;
            $kpr->save();
        }

        Alert::success('Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function hapus_waktu_prausta($id)
    {
        Prausta_master_waktu::where('id_masterwaktu_prausta', $id)->update([
            'status' => 'NOT ACTIVE'
        ]);

        Alert::success('Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function put_waktu_prausta(Request $request, $id)
    {
        $kpr = Prausta_master_waktu::find($id);
        $kpr->id_periodetahun = $request->id_periodetahun;
        $kpr->id_periodetipe = $request->id_periodetipe;
        $kpr->id_prodi = $request->id_prodi;
        $kpr->set_waktu_awal = $request->set_waktu_awal;
        $kpr->set_waktu_akhir = $request->set_waktu_akhir;
        $kpr->tipe_prausta = $request->tipe_prausta;
        $kpr->updated_by = Auth::user()->name;
        $kpr->save();

        Alert::success('Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function waktu_sempro()
    {
        $periodetahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();

        $periodetipe = Periode_tipe::all();

        $prodi = Prodi::all();

        $data = Prausta_master_waktu::join('periode_tahun', 'prausta_master_waktu.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'prausta_master_waktu.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('prodi', 'prausta_master_waktu.id_prodi', '=', 'prodi.id_prodi')
            ->where('prausta_master_waktu.tipe_prausta', 'SEMPRO')
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->select(
                'prausta_master_waktu.id_masterwaktu_prausta',
                'periode_tahun.id_periodetahun',
                'periode_tipe.id_periodetipe',
                'prodi.id_prodi',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'prodi.prodi',
                'prodi.konsentrasi',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_master_waktu.tipe_prausta',
                'prausta_master_waktu.status'
            )
            ->get();

        return view('prausta/sempro/waktu_sempro', compact('periodetahun', 'periodetipe', 'prodi', 'data'));
    }

    public function waktu_ta()
    {
        $periodetahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();

        $periodetipe = Periode_tipe::all();

        $prodi = Prodi::all();

        $data = Prausta_master_waktu::join('periode_tahun', 'prausta_master_waktu.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'prausta_master_waktu.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('prodi', 'prausta_master_waktu.id_prodi', '=', 'prodi.id_prodi')
            ->whereIn('prausta_master_waktu.tipe_prausta', ['TA', 'Skripsi'])
            ->where('prausta_master_waktu.status', 'ACTIVE')
            ->select(
                'prausta_master_waktu.id_masterwaktu_prausta',
                'periode_tahun.id_periodetahun',
                'periode_tipe.id_periodetipe',
                'prodi.id_prodi',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'prodi.prodi',
                'prodi.konsentrasi',
                'prausta_master_waktu.set_waktu_awal',
                'prausta_master_waktu.set_waktu_akhir',
                'prausta_master_waktu.tipe_prausta',
                'prausta_master_waktu.status'
            )
            ->get();

        return view('prausta/ta/waktu_ta', compact('periodetahun', 'periodetipe', 'prodi', 'data'));
    }

    public function honor_pkl_magang()
    {
        return view('prausta/prakerin/honor_pkl_magang');
    }

    public function data_honor_pkl_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_pkl');

        return view('prausta/prakerin/honor_pkl', compact('data', 'prodi'));
    }

    public function filter_honor_pkl(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_pkl_filter(?)', [$request->kodeprodi]);

        return view('prausta/prakerin/honor_pkl', compact('data', 'prodi'));
    }

    public function data_honor_magang_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_magang');

        return view('prausta/prakerin/honor_magang', compact('data', 'prodi'));
    }

    public function filter_honor_magang(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_magang_filter(?)', [$request->kodeprodi]);

        return view('prausta/prakerin/honor_magang', compact('data', 'prodi'));
    }

    public function data_honor_magang2_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_magang2');

        return view('prausta/prakerin/honor_magang2', compact('data', 'prodi'));
    }

    public function filter_honor_magang2(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_magang2_filter(?)', [$request->kodeprodi]);

        return view('prausta/prakerin/honor_magang2', compact('data', 'prodi'));
    }

    public function honor_sempro()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 25, 28, 31])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.validasi_baak', 'SUDAH')
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.payroll_check_dosen_pembimbing',
                'prausta_setting_relasi.payroll_check_dosen_penguji_1',
                'prausta_setting_relasi.payroll_check_dosen_penguji_2'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/sempro/honor_sempro', compact('data', 'prodi'));
    }

    public function filter_honor_sempro(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 25, 28, 31])
            ->where('student.active', 1)
            ->where('prausta_setting_relasi.validasi_baak', 'SUDAH')
            ->where('student.kodeprodi', $request->kodeprodi)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.payroll_check_dosen_pembimbing',
                'prausta_setting_relasi.payroll_check_dosen_penguji_1',
                'prausta_setting_relasi.payroll_check_dosen_penguji_2'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('prausta/sempro/honor_sempro', compact('data', 'prodi'));
    }

    public function honor_ta_skripsi()
    {
        return view('prausta/ta/honor_ta_skripsi');
    }

    public function data_honor_ta_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_ta');

        return view('prausta/ta/honor_ta', compact('data', 'prodi'));
    }

    public function filter_honor_ta(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_ta_filter(?)', [$request->kodeprodi]);

        return view('prausta/ta/honor_ta', compact('data', 'prodi'));
    }

    public function data_honor_skripsi_mahasiswa()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_skripsi');

        return view('prausta/ta/honor_skripsi', compact('data', 'prodi'));
    }

    public function filter_honor_skripsi(Request $request)
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')->select('kodeprodi', 'prodi')->get();

        $data = DB::select('CALL honor_skripsi_filter(?)', [$request->kodeprodi]);

        return view('prausta/ta/honor_skripsi', compact('data', 'prodi'));
    }
}
