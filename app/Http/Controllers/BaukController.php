<?php

namespace App\Http\Controllers;

use Alert;
use App\Penangguhan_kategori;
use App\Penangguhan_trans;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Waktu;
use App\Prausta_setting_relasi;
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
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->where('penangguhan_master_trans.id_penangguhan_kategori', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'penangguhan_master_trans.id_periodetahun', 'penangguhan_master_trans.id_periodetipe', 'penangguhan_master_trans.id_student', 'penangguhan_master_trans.id_penangguhan_kategori', 'penangguhan_master_kategori.kategori', 'penangguhan_master_trans.total_tunggakan', 'penangguhan_master_trans.rencana_bayar', 'penangguhan_master_trans.alasan', 'penangguhan_master_trans.validasi_kaprodi', 'penangguhan_master_trans.validasi_dsn_pa', 'penangguhan_master_trans.validasi_bauk', 'penangguhan_master_trans.validasi_baak', 'penangguhan_master_trans.id_penangguhan_trans')
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

    public function waktu_penangguhan()
    {
        $tahun = Periode_tahun::where('status', 'ACTIVE')->first();

        $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $now = date('Y-m-d');

        $data = Waktu::where('tipe_waktu', 3)->first();

        return view('bauk/penangguhan/waktu_penangguhan', compact('data', 'tahun', 'tipe', 'now'));
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

    public function edit_time_penangguhan(Request $request)
    {
        $id = $request->id_waktu;

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
}
