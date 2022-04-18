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
use App\Periode_tahun;
use App\Periode_tipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AdminPraustaController extends Controller
{
    public function data_prakerin()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.status',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/prakerin/data_prakerin', compact('data'));
    }

    public function atur_prakerin($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
        $data = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)
            ->update([
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai_sidang' => $request->jam_mulai_sidang,
                'jam_selesai_sidang' => $request->jam_selesai_sidang,
                'dosen_penguji_1' => $request->dosen_penguji_1,
                'ruangan' => $request->ruangan,
                'id_dosen_penguji_1' => $request->id_dosen_penguji_1,
            ]);

        Alert::success('', 'Berhasil setting jadwal prakerin')->autoclose(3500);
        return redirect('data_prakerin');
    }

    public function data_sempro()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.status',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/sempro/data_sempro', compact('data'));
    }

    public function atur_sempro($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

    public function data_ta()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select(
                'prausta_setting_relasi.status',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tanggal_mulai',
                'prausta_setting_relasi.tanggal_selesai',
                'prausta_setting_relasi.jam_mulai_sidang',
                'prausta_setting_relasi.jam_selesai_sidang'
            )
            ->orderBy('prausta_setting_relasi.id_settingrelasi_prausta', 'DESC')
            ->get();

        return view('prausta/ta/data_ta', compact('data'));
    }

    public function atur_ta($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

        Alert::success('', 'Berhasil setting jadwal sidang tugas akhir')->autoclose(3500);
        return redirect('data_ta');
    }

    public function bim_prakerin()
    {
        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
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


        return view('prausta/prakerin/bimbingan_prakerin', compact('data'));
    }

    public function cek_bim_prakerin($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'student.idstudent'
            )
            ->get();

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

        return view('prausta/prakerin/cek_bimbingan_prakerin', compact('data', 'mhs'));
    }

    public function bim_sempro()
    {
        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
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

        return view('prausta/sempro/bimbingan_sempro', compact('data'));
    }

    public function cek_bim_sempro($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'student.idstudent'
            )
            ->get();

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

    public function bim_ta()
    {
        $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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

        return view('prausta/ta/bimbingan_ta', compact('data'));
    }

    public function cek_bim_ta($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_trans_bimbingan.tanggal_bimbingan',
                'prausta_trans_bimbingan.file_bimbingan',
                'prausta_trans_bimbingan.remark_bimbingan',
                'prausta_trans_bimbingan.validasi',
                'student.idstudent'
            )
            ->get();

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

        return view('prausta/ta/cek_bimbingan_ta', compact('data', 'mhs'));
    }

    public function nilai_prakerin()
    {
        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
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
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/nilai_prakerin', compact('data'));
    }

    public function edit_nilai_prakerin($id)
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

            return view('prausta/prakerin/edit_nilai_prakerin', compact('nilai_pem', 'datadiri', 'nilai_sem', 'id', 'nilai_1'));
        }
    }

    public function put_nilai_prakerin(Request $request)
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

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai1)
                ->update([
                    'nilai' => $n1,
                    'updated_by' => Auth::user()->name
                ]);
        }

        for ($i = 0; $i < $hitung_id_penilaian2; $i++) {
            $id_nilai2 = $id_penilaian2[$i];
            $n2 = $nilai2[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai2)
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

        $usta = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_1' => $nilai_pem_lap,
                'nilai_2' => $ceknilai_1->nilai1,
                'nilai_3' => $ceknilai_2->nilai2,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Prakerin berhasil disimpan')->autoclose(3500);
        return redirect('nilai_prakerin');
    }

    public function nilai_sempro()
    {
        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
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
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/nilai_sempro', compact('data'));
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
                'nilai_3' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('nilai_sempro');
    }

    public function nilai_ta()
    {
        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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
                'prausta_trans_hasil.id_settingrelasi_prausta'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/nilai_ta', compact('data'));
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

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)
            ->update([
                'nilai_2' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
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
                'nilai_3' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
                'updated_by' => Auth::user()->name
            ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('nilai_sempro');
    }

    public function nonatifkan_prausta($id)
    {
        $data = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)
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
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
        return $pdf->download('Kartu Bimbingan Prakerin' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function download_bimbingan_sempro(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

    public function bap_sempro()
    {
        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
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

        return view('prausta/sempro/bap_sempro', compact('data'));
    }

    public function download_bap_sempro(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

        $pdf = PDF::loadView('prausta/sempro/unduh_bap_sempro', compact('data', 'hari', 'tglhasil'))->setPaper('a4');
        return $pdf->download('BAP Sempro' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function bap_ta()
    {
        $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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

        return view('prausta/ta/bap_ta', compact('data'));
    }

    public function download_bap_ta(Request $request)
    {
        $id = $request->id_settingrelasi_prausta;

        $data = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
        return $pdf->download('BAP TA' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
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
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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
            return redirect('nilai_sempro');
        } elseif ($jml != 0) {
            $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
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

        $prodi = Prodi::all();

        return view('prausta/export_prausta', compact('periode', 'prodi'));
    }

    public function excel_prakerin(Request $request)
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

        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->whereIn('matakuliah.kode', ['FA-601', 'TI-601', 'TK-601'])
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
            ->where('student_record.status', 'TAKEN')
            ->where('id_periodetahun', $id1)
            ->where('id_periodetipe', $id2)
            ->where('student.kodeprodi', $kodeprodi)
            ->select('matakuliah.makul', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->get();

        $nama_file = 'Data Prakerin' . ' ' . $pro . ' ' . $ganti . ' ' . $tp . '.xlsx';
        return Excel::download(new DataPrakerinExport($id1, $id2, $kodeprodi), $nama_file);
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
}
