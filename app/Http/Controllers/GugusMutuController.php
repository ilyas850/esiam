<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Kurikulum_periode;
use App\Bap;
use App\Rps;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GugusMutuController extends Controller
{
    function data_bap_gugusmutu()
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tipe = Periode_tipe::all();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $idtipe = $tp->id_periodetipe;
        $namaperiodetipe = $tp->periode_tipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $idtahun = $thn->id_periodetahun;
        $namaperiodetahun = $thn->periode_tahun;

        $data = DB::select('CALL rekap_perkuliahan_new(?,?)', [$idtahun, $idtipe]);

        return view('gugusmutu/perkuliahan/bap_perkuliahan', compact('data', 'tahun', 'tipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    function cek_bap_gugusmutu($id)
    {
        $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
            ->first();

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->leftjoin('rps', (function ($join) {
                $join->on('bap.id_kurperiode', '=', 'rps.id_kurperiode')
                    ->on('bap.pertemuan', '=', 'rps.pertemuan');
            }))
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select(
                'kuliah_transaction.kurang_jam',
                'kuliah_transaction.tanggal_validasi',
                'kuliah_transaction.payroll_check',
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
                'bap.tidak_hadir',
                'bap.created_at',
                'rps.materi_pembelajaran',
                'bap.kesesuaian_rps',
                'rps.komentar',
                'rps.id_rps'
            )
            ->get();

        return view('gugusmutu/perkuliahan/cek_bap', compact('bap', 'data'));
    }

    function validasi_sesuai($id)
    {
        Bap::where('id_bap', $id)->update(['kesesuaian_rps' => 'SESUAI']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    function validasi_tidak_sesuai($id)
    {
        Bap::where('id_bap', $id)->update(['kesesuaian_rps' => 'TIDAK SESUAI']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    function komentar_rps_makul(Request $request, $id) 
    {
        $prd = Rps::find($id);
        $prd->komentar = $request->komentar;
        $prd->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }
}
