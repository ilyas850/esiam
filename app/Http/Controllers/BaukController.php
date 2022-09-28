<?php

namespace App\Http\Controllers;

use Alert;
use App\Penangguhan_kategori;
use App\Penangguhan_trans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaukController extends Controller
{
    public function kategori_penangguhan_bauk()
    {
        $data = Penangguhan_kategori::leftjoin('penangguhan_master_trans', 'penangguhan_master_kategori.id_penangguhan_kategori', '=', 'penangguhan_master_trans.id_penangguhan_kategori')
            ->select('penangguhan_master_kategori.id_penangguhan_kategori', 'penangguhan_master_kategori.kategori', DB::raw('COUNT(penangguhan_master_trans.id_penangguhan_kategori) as jml_penangguhan'))
            ->groupBy('penangguhan_master_kategori.id_penangguhan_kategori', 'penangguhan_master_kategori.kategori')
            ->get();

        return view('bauk/penangguhan/kategori_penangguhan', compact('data'));
    }

    public function data_penangguhan_bauk($id)
    {
        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
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
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data'));
    }

    public function put_tunggakan(Request $request, $id)
    {
        $ang = Penangguhan_trans::find($id);
        $ang->total_tunggakan = $request->total_tunggakan;
        $ang->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function val_penangguhan_bauk($id)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_bauk' => 'SUDAH']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function batal_val_penangguhan_bauk($id)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_bauk' => 'BELUM']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }
}
