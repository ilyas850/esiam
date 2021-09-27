<?php

namespace App\Http\Controllers;

use App\Bap;
use App\Dosen;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Kuliah_transaction;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
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
                              ->select('kurikulum_periode.id_kurperiode','matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                              ->get();

      return view('wadir.data_bap', compact('data'));
    }

    public function cek_jurnal_bap_wadir($id)
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
                              ->select('kurikulum_periode.id_dosen_2','matakuliah.akt_sks_praktek','matakuliah.akt_sks_teori','kurikulum_periode.id_kelas','periode_tipe.periode_tipe','periode_tahun.periode_tahun','dosen.akademik','dosen.nama','ruangan.nama_ruangan','kurikulum_jam.jam','kurikulum_hari.hari',DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'),'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
                              ->get();
      foreach ($bap as $key) {
        # code...
      }

      $dosen2 = Dosen::where('iddosen', $key->id_dosen_2)->get();
      foreach ($dosen2 as $keydsn) {
        // code...
      }
      if (count($dosen2) > 0) {
        $nama_dsn2 = $keydsn->nama.', '.$keydsn->akademik;
      }else {
        $nama_dsn2 = '';
      }

      $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
                  ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
                  ->where('bap.id_kurperiode', $id)
                  ->where('bap.status', 'ACTIVE')
                  ->select('kuliah_transaction.payroll_check','bap.id_bap','bap.pertemuan','bap.tanggal','bap.jam_mulai','bap.jam_selsai', 'bap.materi_kuliah','bap.metode_kuliah','kuliah_tipe.tipe_kuliah','bap.jenis_kuliah','bap.hadir','bap.tidak_hadir')
                  ->orderBy('bap.tanggal', 'ASC')
                  ->get();

      return view ('wadir/jurnal_bap', ['nama_dosen_2'=>$nama_dsn2,'bap'=>$key, 'data'=>$data]);
    }
}
