<?php

namespace App\Http\Controllers;

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
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use App\Prausta_trans_bimbingan;
use App\Prausta_master_kategori;
use App\Prausta_trans_hasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->select('prausta_setting_relasi.acc_seminar_sidang', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tanggal_mulai', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang')
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

        $dosen = Dosen::where('idstatus', 1)
            ->where('active', 1)
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
        $data = Prausta_trans_bimbingan::leftjoin('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
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
                'prausta_trans_bimbingan.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_trans_bimbingan.id_settingrelasi_prausta',
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
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
            )
            ->first();

        return view('prausta/prakerin/cek_bimbingan_prakerin', compact('data', 'mhs'));
    }

    public function bim_sempro()
    {
        $data = Prausta_trans_bimbingan::leftjoin('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
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
                'prausta_trans_bimbingan.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_trans_bimbingan.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan'
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
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan'
            )
            ->first();

        return view('prausta/sempro/cek_bimbingan_sempro', compact('data', 'mhs'));
    }

    public function bim_ta()
    {
        $data = Prausta_trans_bimbingan::leftjoin('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
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
                'prausta_trans_bimbingan.id_settingrelasi_prausta'
            )
            ->groupBy(
                'student.nama',
                'prausta_trans_bimbingan.id_settingrelasi_prausta',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan'
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
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan'
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
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/nilai_prakerin', compact('data'));
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
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/sempro/nilai_sempro', compact('data'));
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
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/ta/nilai_ta', compact('data'));
    }
}
