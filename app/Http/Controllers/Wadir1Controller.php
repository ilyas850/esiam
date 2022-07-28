<?php

namespace App\Http\Controllers;

use App\Bap;
use App\Dosen;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Kuliah_transaction;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Prausta_setting_relasi;
use App\Prausta_trans_bimbingan;
use App\Prausta_trans_hasil;
use App\Student;
use App\Student_record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Wadir1Controller extends Controller
{
  public function data_bap()
  {
    $tahun = Periode_tahun::where('status', 'ACTIVE')->first();
    $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

    $idtipe = $tipe->id_periodetipe;
    $idtahun = $tahun->id_periodetahun;

    $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
      ->where('kurikulum_periode.id_periodetipe', $idtipe)
      ->where('kurikulum_periode.id_periodetahun', $idtahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
      ->get();

    return view('wadir.data_bap', compact('data'));
  }

  public function cek_jurnal_bap_wadir($id)
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

    return view('wadir/jurnal_bap', ['nama_dosen_2' => $nama_dsn2, 'bap' => $key, 'data' => $data]);
  }

  public function bimbingan_prakerin_wadir()
  {
    $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
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

    return view('wadir/prausta/bimbingan_prakerin', compact('data'));
  }

  public function cek_bim_prakerin_wadir($id)
  {
    $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_trans_bimbingan.tanggal_bimbingan',
        'prausta_trans_bimbingan.file_bimbingan',
        'prausta_trans_bimbingan.remark_bimbingan',
        'prausta_trans_bimbingan.komentar_bimbingan',
        'prausta_trans_bimbingan.validasi',
        'prausta_trans_bimbingan.id_transbimb_prausta',
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

    return view('wadir/prausta/cek_bimbingan_prakerin', compact('data', 'mhs'));
  }

  public function bimbingan_sempro_wadir()
  {
    $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
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

    return view('wadir/prausta/bimbingan_sempro', compact('data'));
  }

  public function cek_bim_sempro_wadir($id)
  {
    $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_trans_bimbingan.tanggal_bimbingan',
        'prausta_trans_bimbingan.file_bimbingan',
        'prausta_trans_bimbingan.remark_bimbingan',
        'prausta_trans_bimbingan.komentar_bimbingan',
        'prausta_trans_bimbingan.validasi',
        'prausta_trans_bimbingan.id_transbimb_prausta',
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

    return view('wadir/prausta/cek_bimbingan_sempro', compact('data', 'mhs'));
  }

  public function bimbingan_ta_wadir()
  {
    $data = Prausta_setting_relasi::leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
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

    return view('wadir/prausta/bimbingan_ta', compact('data'));
  }

  public function cek_bim_ta_wadir($id)
  {
    $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
      ->select(
        'prausta_trans_bimbingan.tanggal_bimbingan',
        'prausta_trans_bimbingan.file_bimbingan',
        'prausta_trans_bimbingan.remark_bimbingan',
        'prausta_trans_bimbingan.komentar_bimbingan',
        'prausta_trans_bimbingan.validasi',
        'prausta_trans_bimbingan.id_transbimb_prausta',
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

    return view('wadir/prausta/cek_bimbingan_ta', compact('data', 'mhs'));
  }

  public function nilai_prakerin_wadir()
  {
    $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
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

    return view('wadir/prausta/nilai_prakerin', compact('data'));
  }

  public function nilai_sempro_wadir()
  {
    $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
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

    return view('wadir/prausta/nilai_sempro', compact('data'));
  }

  public function nilai_ta_wadir()
  {
    $data = Prausta_trans_hasil::join('prausta_setting_relasi', 'prausta_trans_hasil.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', (function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
          ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      }))
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

    return view('wadir/prausta/nilai_ta', compact('data'));
  }

  public function rekap_nilai_mhs_wadir()
  {
    $periode_tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();

    $periode_tipe = Periode_tipe::all();

    $tp = Periode_tipe::where('status', 'ACTIVE')->first();
    $tipe = $tp->id_periodetipe;
    $nama_tipe = $tp->periode_tipe;

    $thn = Periode_tahun::where('status', 'ACTIVE')->first();
    $tahun = $thn->id_periodetahun;
    $nama_tahun = $thn->periode_tahun;

    $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', DB::raw('COUNT(student_record.id_student) as jml_mhs'), 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->groupBy('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->get();

    return view('wadir/nilai/rekap_nilai_mhs', compact('periode_tahun', 'periode_tipe', 'data', 'nama_tipe', 'nama_tahun'));
  }

  public function filter_rekap_nilai_mhs_wadir(Request $request)
  {
    $periode_tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();

    $periode_tipe = Periode_tipe::all();

    $tp = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
    $tipe = $tp->id_periodetipe;
    $nama_tipe = $tp->periode_tipe;

    $thn = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
    $tahun = $thn->id_periodetahun;
    $nama_tahun = $thn->periode_tahun;

    $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
      ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
      ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->where('kurikulum_periode.id_periodetipe', $tipe)
      ->where('kurikulum_periode.id_periodetahun', $tahun)
      ->where('kurikulum_periode.status', 'ACTIVE')
      ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', DB::raw('COUNT(student_record.id_student) as jml_mhs'), 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->groupBy('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
      ->get();

    return view('wadir/nilai/rekap_nilai_mhs', compact('periode_tahun', 'periode_tipe', 'data', 'nama_tipe', 'nama_tahun'));
  }

  public function cek_rekap_nilai_mhs_wadir($id)
  {
    $nama = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
      ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
      ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
      ->where('kurikulum_periode.id_kurperiode', $id)
      ->select('matakuliah.kode', 'matakuliah.makul', 'kelas.kelas', 'prodi.prodi')
      ->first();

    $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
      ->leftJoin('prodi', function ($join) {
        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
      })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student_record.id_kurperiode', $id)
      ->where('student_record.status', 'TAKEN')
      ->select('student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
      ->get();

    return view('wadir/nilai/cek_rekap_nilai_mhs', compact('data', 'nama'));
  }

  public function rekap_pembayaran_mhs()
  {
    $data1 = Student::leftJoin('prodi', function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->select('student.idstudent', 'student.nama', 'student.nim', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi')
      ->whereIn('student.active', [1, 5])
      ->orderBy('student.nim', 'ASC')
      ->get();

    $data = DB::select('CALL data_pembayaran_mhs()');

    return view('wadir/pembayaran/data_pembayaran', compact('data'));
  }

  public function detail_pembayaran_mhs($id)
  {
    $mhs = Student::join('prodi', function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    })
      ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
      ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
      ->where('student.idstudent', $id)
      ->select('student.idstudent', 'student.nama', 'student.nim', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi')
      ->first();

    $data = DB::select('CALL detail_pembayaran_mhs(?)', [$id]);

    $detail_beasiswa = DB::select('CALL detail_beasiswa_mhs(?)', [$id]);

    foreach ($detail_beasiswa as $key_beasiswa) {
      # code...
    }

    $total_byr_mhs = DB::select('CALL detail_totalbayar_mhs(?)', [$id]);

    foreach ($total_byr_mhs as $key_total) {
      # code...
    }
    
    return view('wadir/pembayaran/detail_pembayaran', compact('data', 'mhs', 'key_beasiswa', 'key_total'));
  }
}
