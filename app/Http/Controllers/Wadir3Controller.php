<?php

namespace App\Http\Controllers;

use App\User;
use App\Kritiksaran_kategori;
use App\Kritiksaran_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Wadir3Controller extends Controller
{
    public function data_kritiksaran()
    {
        $data = Kritiksaran_kategori::leftjoin('kritiksaran_trans', 'kritiksaran_kategori.id_kategori_kritiksaran', '=', 'kritiksaran_trans.id_kategori_kritiksaran')
            ->where('kritiksaran_kategori.status', 'ACTIVE')
            ->select('kritiksaran_kategori.id_kategori_kritiksaran', 'kritiksaran_kategori.kategori_kritiksaran', DB::raw('COUNT(kritiksaran_trans.id_kategori_kritiksaran) as jml'))
            ->groupBy('kritiksaran_kategori.id_kategori_kritiksaran', 'kritiksaran_kategori.kategori_kritiksaran')
            ->get();

        return view('wadir3/kritiksaran/master_kritiksaran', compact('data'));
    }

    public function cek_kritiksaran($id)
    {
        $data = Kritiksaran_transaction::join('kritiksaran_kategori', 'kritiksaran_trans.id_kategori_kritiksaran', '=', 'kritiksaran_kategori.id_kategori_kritiksaran')
            ->join('periode_tahun', 'kritiksaran_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kritiksaran_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'kritiksaran_trans.id_student', '=', 'student.idstudent')
            ->where('kritiksaran_trans.id_kategori_kritiksaran', $id)
            ->where('kritiksaran_trans.status', 'ACTIVE')
            ->where('kritiksaran_kategori.status', 'ACTIVE')
            ->select(
                'kritiksaran_trans.id_trans_kritiksaran',
                'kritiksaran_trans.id_periodetahun',
                'kritiksaran_trans.id_periodetahun',
                'kritiksaran_trans.id_kategori_kritiksaran',
                'kritiksaran_kategori.kategori_kritiksaran',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'student.nim',
                'student.nama',
                'kritiksaran_trans.kritik',
                'kritiksaran_trans.saran',
                'kritiksaran_trans.status',
                'periode_tahun.status as thn_status',
                'periode_tipe.status as tp_status'
            )
            ->get();

            $kat = Kritiksaran_kategori::where('id_kategori_kritiksaran', $id)->first();

        return view('wadir3/kritiksaran/cek_kritiksaran', compact('data', 'kat'));
    }
}
