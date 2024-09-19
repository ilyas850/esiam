<?php

namespace App\Http\Controllers;

use PDF;
use Alert;
use App\Models\Penangguhan_kategori;
use App\Models\Penangguhan_trans;
use App\Models\Periode_tahun;
use App\Models\Periode_tipe;
use App\Models\Waktu;
use App\Models\Prausta_setting_relasi;
use App\Models\Beasiswa_trans;
use App\Models\Beasiswa;
use App\Models\Student;
use App\Models\Min_biaya;
use App\Models\Kelas;
use App\Exports\DataPengajuanBeasiswa;
use App\Models\Pengajuan_trans;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BaukController extends Controller
{
    public function kategori_penangguhan_bauk()
    {
        $tahun = Periode_tahun::join('penangguhan_master_trans', 'periode_tahun.id_periodetahun', '=', 'penangguhan_master_trans.id_periodetahun')
            ->groupBy('periode_tahun.id_periodetahun', 'periode_tahun.periode_tahun')
            ->select('periode_tahun.id_periodetahun', 'periode_tahun.periode_tahun')
            ->orderBy('periode_tahun.periode_tahun', 'DESC')
            ->get();

        $tipe = Periode_tipe::all();

        $thn_aktif = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp_aktif = Periode_tipe::where('status', 'ACTIVE')->first();

        $data = Penangguhan_kategori::leftjoin('penangguhan_master_trans', 'penangguhan_master_kategori.id_penangguhan_kategori', '=', 'penangguhan_master_trans.id_penangguhan_kategori')
            ->leftjoin('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->leftjoin('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->select('penangguhan_master_kategori.id_penangguhan_kategori', 'penangguhan_master_kategori.kategori', DB::raw('COUNT(penangguhan_master_trans.id_penangguhan_kategori) as jml_penangguhan'))
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('periode_tahun.id_periodetahun', $thn_aktif->id_periodetahun)
            ->where('periode_tipe.id_periodetipe', $tp_aktif->id_periodetipe)
            ->groupBy('penangguhan_master_kategori.id_penangguhan_kategori', 'penangguhan_master_kategori.kategori')
            ->get();

        return view('bauk/penangguhan/kategori_penangguhan', compact('data', 'thn_aktif', 'tp_aktif', 'tahun', 'tipe'));
    }

    public function pilih_ta_penangguhan(Request $request)
    {
        $tahun = Periode_tahun::join('penangguhan_master_trans', 'periode_tahun.id_periodetahun', '=', 'penangguhan_master_trans.id_periodetahun')
            ->groupBy('periode_tahun.id_periodetahun', 'periode_tahun.periode_tahun')
            ->select('periode_tahun.id_periodetahun', 'periode_tahun.periode_tahun')
            ->orderBy('periode_tahun.periode_tahun', 'DESC')
            ->get();

        $tipe = Periode_tipe::all();

        $thn_aktif = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $data = Penangguhan_kategori::leftjoin('penangguhan_master_trans', 'penangguhan_master_kategori.id_penangguhan_kategori', '=', 'penangguhan_master_trans.id_penangguhan_kategori')
            ->leftjoin('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->leftjoin('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->select('penangguhan_master_kategori.id_penangguhan_kategori', 'penangguhan_master_kategori.kategori', DB::raw('COUNT(penangguhan_master_trans.id_penangguhan_kategori) as jml_penangguhan'))
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('periode_tahun.id_periodetahun', $thn_aktif->id_periodetahun)
            ->where('periode_tipe.id_periodetipe', $tp_aktif->id_periodetipe)
            ->groupBy('penangguhan_master_kategori.id_penangguhan_kategori', 'penangguhan_master_kategori.kategori')
            ->get();

        return view('bauk/penangguhan/kategori_penangguhan', compact('data', 'thn_aktif', 'tp_aktif', 'tahun', 'tipe'));
    }

    public function data_penangguhan_bauk(Request $request)
    {
        $thn_aktif = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $request->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $request->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $request->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $request->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function data_penangguhan_bauk1($id)
    {
        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data'));
    }

    public function put_tunggakan(Request $request, $id)
    {
        $ang = Penangguhan_trans::find($id);
        $ang->total_tunggakan = $request->total_tunggakan;
        $ang->save();

        $thn_aktif = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $request->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $request->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $request->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $request->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function val_penangguhan_bauk(Request $request)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $request->id_penangguhan_trans)->update(['validasi_bauk' => 'SUDAH']);

        $thn_aktif = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $request->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $request->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $request->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $request->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function validasi_penangguhan_bauk($id)
    {
        $data = Penangguhan_trans::where('id_penangguhan_trans', $id)->first();

        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_bauk' => 'SUDAH']);

        $thn_aktif = Periode_tahun::where('id_periodetahun', $data->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $data->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $data->id_penangguhan_kategori)->first();


        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $data->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $data->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $data->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();


        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function batal_val_penangguhan_bauk(Request $request)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $request->id_penangguhan_trans)->update(['validasi_bauk' => 'BELUM']);

        $thn_aktif = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $request->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $request->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $request->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $request->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function batal_validasi_penangguhan_bauk($id)
    {
        $data = Penangguhan_trans::where('id_penangguhan_trans', $id)->first();

        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_bauk' => 'BELUM']);

        $thn_aktif = Periode_tahun::where('id_periodetahun', $data->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $data->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $data->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $data->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $data->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $data->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();


        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function tolak_penangguhan_bauk($id)
    {
        $data = Penangguhan_trans::where('id_penangguhan_trans', $id)->first();

        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['validasi_bauk' => 'TOLAK']);

        $thn_aktif = Periode_tahun::where('id_periodetahun', $data->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $data->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $data->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $data->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $data->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $data->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();


        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function close_penangguhan($id)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['status_penangguhan' => 'CLOSE']);

        $GET_data =  Penangguhan_trans::where('id_penangguhan_trans', $id)->first();

        $thn_aktif = Periode_tahun::where('id_periodetahun', $GET_data->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $GET_data->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $GET_data->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $GET_data->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $GET_data->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $GET_data->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function open_penangguhan($id)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['status_penangguhan' => 'OPEN']);

        $GET_data =  Penangguhan_trans::where('id_penangguhan_trans', $id)->first();

        $thn_aktif = Periode_tahun::where('id_periodetahun', $GET_data->id_periodetahun)->first();
        $tp_aktif = Periode_tipe::where('id_periodetipe', $GET_data->id_periodetipe)->first();
        $kategori = Penangguhan_kategori::where('id_penangguhan_kategori', $GET_data->id_penangguhan_kategori)->first();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'penangguhan_master_trans.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $GET_data->id_penangguhan_kategori)
            ->where('penangguhan_master_trans.id_periodetahun', $GET_data->id_periodetahun)
            ->where('penangguhan_master_trans.id_periodetipe', $GET_data->id_periodetipe)
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
                'penangguhan_master_trans.id_penangguhan_trans',
                'penangguhan_master_trans.status_penangguhan'
            )
            ->get();

        return view('bauk/penangguhan/data_penangguhan', compact('data', 'kategori', 'thn_aktif', 'tp_aktif'));
    }

    public function waktu_penangguhan()
    {
        $tahun = Periode_tahun::where('status', 'ACTIVE')->first();

        $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $now = date('Y-m-d');

        $data = Waktu::where('tipe_waktu', 3)->first();

        return view('bauk/penangguhan/waktu_penangguhan1', compact('data', 'tahun', 'tipe', 'now'));
    }

    public function set_waktu_penangguhan()
    {
        # code...
    }

    public function simpan_waktu_penangguhan(Request $request)
    {
        $cektgl = strtotime($request->waktu_akhir);
        $cektglawal = strtotime('now');

        if ($cektgl < $cektglawal) {
            Alert::error('Maaf waktu yang anda atur salah', 'maaf');
            return redirect()->back();
        } else {
            $id = $request->id_waktu;
            $time_nya = Waktu::find($id);
            $time_nya->waktu_awal = $request->waktu_awal;
            $time_nya->waktu_akhir = $request->waktu_akhir;
            $time_nya->status = $request->status;
            $time_nya->save();

            Alert::success('Pembukaan Penangguhan', 'Berhasil')->autoclose(3500);
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {

        $id = $request->id_waktu;
        $time_nya = Waktu::find($id);
        $time_nya->status = '0';
        $time_nya->save();

        Alert::success('Penutupan Penangguhan', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function stop_waktu_penangguhan($id)
    {
        $time_nya = Waktu::find($id);
        $time_nya->status = '0';
        $time_nya->save();

        Alert::success('Penutupan Penangguhan', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function edit_time_penangguhan(Request $request)
    {
        $id = $request->id_waktu;
        $waktu = Waktu::where('id_waktu', $id)->first();
        $cektgl = strtotime($waktu->waktu_akhir);
        $cektglawal = strtotime('now');



        $time_nya = Waktu::find($id);

        $time_nya->status = '0';
        $time_nya->save();

        Alert::success('Penutupan Penangguhan', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function waktu_beasiswa()
    {
        $tahun = Periode_tahun::where('status', 'ACTIVE')->first();

        $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $now = date('Y-m-d');

        $data = Waktu::where('tipe_waktu', 4)->first();

        return view('bauk/beasiswa/waktu_beasiswa', compact('data', 'tahun', 'tipe', 'now'));
    }

    public function simpan_waktu_pengajuan_beasiswa(Request $request)
    {
        $cektgl = strtotime($request->waktu_akhir);
        $cektglawal = strtotime('now');

        if ($cektgl < $cektglawal) {
            Alert::error('Maaf waktu yang anda atur salah', 'maaf');
            return redirect()->back();
        } else {
            $id = $request->id_waktu;
            $time_nya = Waktu::find($id);
            $time_nya->waktu_awal = $request->waktu_awal;
            $time_nya->waktu_akhir = $request->waktu_akhir;
            $time_nya->status = $request->status;
            $time_nya->save();

            Alert::success('Pembukaan Pengajuan Beasiswa', 'Berhasil')->autoclose(3500);
            return redirect()->back();
        }
    }

    public function edit_time_pengajuan_beasiswa(Request $request)
    {
        $id = $request->id_waktu;

        $time_nya = Waktu::find($id);

        $time_nya->status = '0';
        $time_nya->save();

        Alert::success('Penutupan Pengajuan Beasiswa', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function pengajuan_beasiswa_by_mhs()
    {
        $thn_aktif = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp_aktif = Periode_tipe::where('status', 'ACTIVE')->first();

        $data = Beasiswa_trans::join('student', 'beasiswa_trans.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('periode_tahun', 'beasiswa_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'beasiswa_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('semester', 'beasiswa_trans.id_semester', '=', 'semester.idsemester')
            ->join('beasiswa', 'beasiswa_trans.id_student', '=', 'beasiswa.idstudent')
            ->where('beasiswa_trans.status', 'ACTIVE')
            ->where('beasiswa_trans.id_periodetahun', $thn_aktif->id_periodetahun)
            ->where('beasiswa_trans.id_periodetipe', $tp_aktif->id_periodetipe)
            ->select(
                'student.idstudent',
                'student.nim',
                'student.nama',
                'prodi.prodi',
                'kelas.kelas',
                'student.tgllahir',
                'student.tmptlahir',
                'student.hp',
                'student.email',
                'beasiswa_trans.id_trans_beasiswa',
                'semester.semester',
                'beasiswa_trans.validasi_bauk',
                'beasiswa_trans.validasi_wadir3',
                'beasiswa_trans.status',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'beasiswa_trans.ipk',
                'beasiswa_trans.id_periodetahun',
                'beasiswa_trans.id_periodetipe',
                'beasiswa_trans.id_semester',
                'beasiswa_trans.beasiswa',
                'beasiswa.spp1',
                'beasiswa.spp2',
                'beasiswa.spp3',
                'beasiswa.spp4',
                'beasiswa.spp5',
                'beasiswa.spp6',
                'beasiswa.spp7',
                'beasiswa.spp8'
            )
            ->get();

        return view('bauk/beasiswa/pengajuan_beasiswa', compact('data', 'thn_aktif', 'tp_aktif'));
    }

    public function put_beasiswa(Request $request, $id)
    {
        $idstudent = $request->id_student;
        $idsemester = $request->id_semester;
        $beasiswa = $request->beasiswa;

        $ang = Beasiswa_trans::find($id);
        $ang->beasiswa = $request->beasiswa;
        $ang->save();

        if ($idsemester == 2) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp2' => $beasiswa]);
        } elseif ($idsemester == 3) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp3' => $beasiswa]);
        } elseif ($idsemester == 4) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp4' => $beasiswa]);
        } elseif ($idsemester == 5) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp5' => $beasiswa]);
        } elseif ($idsemester == 6) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp6' => $beasiswa]);
        } elseif ($idsemester == 7) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp7' => $beasiswa]);
        } elseif ($idsemester == 8) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp8' => $beasiswa]);
        } elseif ($idsemester == 9) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp9' => $beasiswa]);
        } elseif ($idsemester == 10) {
            Beasiswa::where('idstudent', $idstudent)->update(['spp10' => $beasiswa]);
        }

        return redirect('pengajuan_beasiswa_by_mhs');
    }

    public function download_khs_by_bauk(Request $request)
    {
        $thn = $request->id_periodetahun;
        $tp = $request->id_periodetipe;
        $id = $request->id_student;

        $mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'student.idstudent')
            ->first();

        $periode_tahun = Periode_tahun::where('id_periodetahun',  $thn)->first();
        $periode_tipe = Periode_tipe::where('id_periodetipe', $tp)->first();

        if ($tp == 1) {
            $id_thn = $periode_tahun->id_periodetahun - 1;
            $id_tp = 2.3;
            $periode_tahun1 = Periode_tahun::where('id_periodetahun',  $id_thn)->select('periode_tahun')->first();
            $periode_tipe1 = 'GENAP';
        } elseif ($tp == 2) {
            $id_thn = $periode_tahun->id_periodetahun;
            $id_tp = 1;
            $periode_tahun1 = Periode_tahun::where('id_periodetahun',  $id_thn)->select('periode_tahun')->first();
            $periode_tipe1 = 'GANJIL';
        }

        $data = DB::select('CALL ipk_pengajuan_beasiswa(?,?,?)', [$id, $id_thn, $id_tp]);

        $sks = 0;
        $ipkk = 0;
        foreach ($data as $ips) {
            $sks += $ips->akt_sks_teori + $ips->akt_sks_praktek;
            $ipkk += ($ips->akt_sks_teori + $ips->akt_sks_praktek) * ($ips->nilai_indeks);
        }

        $hasil_ipk = $ipkk / $sks;

        $pdf = PDF::loadView('bauk/download/khs_nilai_pdf', compact('periode_tahun1', 'periode_tipe1', 'hasil_ipk', 'sks', 'mhs', 'data', 'id', 'ipkk'));
        return $pdf->download('KHS ' . $mhs->nama . ' ' . $periode_tahun1->periode_tahun . '-' . $periode_tipe1 . '.pdf');
    }

    public function val_pengajuan_beasiswa_bauk($id)
    {
        Beasiswa_trans::where('id_trans_beasiswa', $id)->update(['validasi_bauk' => 'SUDAH']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function batal_val_pengajuan_beasiswa_bauk($id)
    {
        Beasiswa_trans::where('id_trans_beasiswa', $id)->update(['validasi_bauk' => 'BELUM']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function export_excel_pengajuan_beasiswa(Request $request)
    {
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;

        $tahun = Periode_tahun::where('id_periodetahun', $id_tahun)->first();
        $tipe = Periode_tipe::where('id_periodetipe', $id_tipe)->first();
        $ganti_tahun = str_replace('/', '_', $tahun->periode_tahun);

        $nama_file = 'Data Pengajuan Beasiswa' . ' ' . $ganti_tahun . '-' . $tipe->periode_tipe . '.xlsx';

        return Excel::download(new DataPengajuanBeasiswa($id_tahun, $id_tipe), $nama_file);
    }

    public function uang_saku_pkl()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.active', 1)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->select('student.idstudent', 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.total_uang_saku')
            ->orderBy('prodi.prodi', 'ASC')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('bauk/mahasiswa/uang_saku', compact('data'));
    }

    public function min_biaya()
    {
        $data = Min_biaya::where('status', 'ACTIVE')->get();

        return view('bauk/biaya/minimal', compact('data'));
    }

    public function post_min_biaya(Request $request)
    {
        $cek_kategori = Min_biaya::where('kategori', $request->kategori)->get();

        if (count($cek_kategori) == 0) {
            $kpr = new Min_biaya();
            $kpr->kategori = $request->kategori;
            $kpr->persentase = $request->persentase;
            $kpr->created_by = Auth::user()->name;
            $kpr->save();
            Alert::success('Berhasil')->autoclose(3500);
            return redirect()->back();
        } else {
            Alert::warning('Kategori Sudah ada')->autoclose(3500);
            return redirect()->back();
        }
    }

    public function data_cuti_bauk()
    {
        $data = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'pengajuan_trans.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->where('pengajuan_trans.id_kategori_pengajuan', 1)
            ->select(
                'pengajuan_trans.id_trans_pengajuan',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'student.nim',
                'student.nama',
                'kelas.kelas',
                'prodi.prodi',
                'pengajuan_trans.sks_ditempuh',
                'pengajuan_trans.alasan',
                'pengajuan_trans.cuti_sebelumnya',
                'pengajuan_trans.no_hp',
                'pengajuan_trans.alamat',
                'pengajuan_trans.val_bauk',
                'pengajuan_trans.val_dsn_pa',
                'pengajuan_trans.val_baak',
                'pengajuan_trans.val_kaprodi',
                'pengajuan_trans.tgl_pengajuan',
                'pengajuan_trans.created_at'
            )
            ->orderBy('pengajuan_trans.id_trans_pengajuan', 'DESC')
            ->get();

        return view('bauk/pengajuan/data_cuti', compact('data'));
    }

    public function validasi_pengajuan_cuti_bauk($id)
    {
        Pengajuan_trans::where('id_trans_pengajuan', $id)->update(['val_bauk' => 'SUDAH']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function batal_validasi_pengajuan_cuti_bauk($id)
    {
        Pengajuan_trans::where('id_trans_pengajuan', $id)->update(['val_bauk' => 'BELUM']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function data_mengundurkan_diri_bauk()
    {
        $data = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'pengajuan_trans.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->where('pengajuan_trans.id_kategori_pengajuan', 2)
            ->select(
                'pengajuan_trans.id_trans_pengajuan',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'student.nim',
                'student.nama',
                'kelas.kelas',
                'prodi.prodi',
                'pengajuan_trans.semester_keluar',
                'pengajuan_trans.alasan',
                'pengajuan_trans.no_hp',
                'pengajuan_trans.val_bauk',
                'pengajuan_trans.val_dsn_pa',
                'pengajuan_trans.val_baak',
                'pengajuan_trans.val_kaprodi',
                'pengajuan_trans.tgl_pengajuan'
            )
            ->orderBy('pengajuan_trans.id_trans_pengajuan', 'DESC')
            ->get();

        return view('bauk/pengajuan/data_mengundurkan_diri', compact('data'));
    }

    public function data_pindah_kelas_bauk()
    {
        $data = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'pengajuan_trans.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->where('pengajuan_trans.id_kategori_pengajuan', 3)
            ->select(
                'pengajuan_trans.id_trans_pengajuan',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'student.nim',
                'student.nama',
                'kelas.kelas',
                'prodi.prodi',
                'pengajuan_trans.kelas_sebelum',
                'pengajuan_trans.kelas_tujuan',
                'pengajuan_trans.alasan',
                'pengajuan_trans.no_hp',
                'pengajuan_trans.val_bauk',
                'pengajuan_trans.val_dsn_pa',
                'pengajuan_trans.val_baak',
                'pengajuan_trans.val_kaprodi'
            )
            ->orderBy('pengajuan_trans.id_trans_pengajuan', 'DESC')
            ->get();

        $kelas = Kelas::orderBy('kelas', 'ASC')->get();

        return view('bauk/pengajuan/data_pindah_kelas', compact('data', 'kelas'));
    }
}
