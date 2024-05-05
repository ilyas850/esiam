<?php

namespace App\Http\Controllers;

use PDF;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Periode_tahun;
use App\Models\Periode_tipe;
use App\Models\Kurikulum_periode;
use App\Models\Bap;
use App\Models\Dosen;
use App\Models\Kuisioner_kategori;
use App\Models\Prodi;
use App\Models\Rps;
use Illuminate\Http\JsonResponse;
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

    function data_rekapitulasi_edom_gugusmutu()
    {
        $periodetahun = Periode_tahun::orderBy('id_periodetahun', 'DESC')->get();
        $periodetipe = Periode_tipe::orderBy('id_periodetipe', 'DESC')->get();
        $prodi = Prodi::select('kodeprodi', 'prodi')
            ->groupBy('kodeprodi', 'prodi')
            ->orderBy('kodeprodi', 'DESC')
            ->get();

        return view('gugusmutu/edom/master_edom', compact('periodetahun', 'periodetipe', 'prodi'));
    }

    function report_edom_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $tipe = $request->tipe_laporan;

        $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
        $prodii = Prodi::where('kodeprodi', $idprodi)->first();

        $thn = $periodetahun->periode_tahun;
        $tp = $periodetipe->periode_tipe;
        $prd = $prodii->prodi;

        if ($tipe == 'by_makul') {

            if ($idperiodetahun == 6 && $idperiodetipe == 1) {
                $data = DB::select('CALL edom_by_makul_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
            } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
                $data = DB::select('CALL edom_by_makul_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
            } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
                $data = DB::select('CALL edom_by_makul_fix(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
            } elseif ($idperiodetahun < 6) {
                $data = DB::select('CALL edom_by_makul_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
            } elseif ($idperiodetahun > 6) {
                $data = DB::select('CALL edom_by_makul_fix(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));
            }

            return view('gugusmutu/edom/report_edom_by_makul', compact('data', 'thn', 'tp', 'prd', 'idperiodetahun', 'idperiodetipe', 'idprodi'));
        } elseif ($tipe == 'by_dosen') {

            $data = DB::select('CALL edom_by_dosen_new(?,?)', array($idperiodetahun, $idperiodetipe));

            return view('gugusmutu/edom/report_edom_by_dosen', compact('data', 'thn', 'tp', 'idperiodetahun', 'idperiodetipe'));
        }
    }

    function download_report_edom_by_makul_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;

        $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
        $prodi = Prodi::where('kodeprodi', $idprodi)->first();

        $thn = $periodetahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);
        $tp = $periodetipe->periode_tipe;
        $prd = $prodi->prodi;

        $data = DB::select('CALL edom_by_makul_fix(?,?,?)', array($idperiodetahun, $idperiodetipe, $idprodi));

        $pdf = PDF::loadView('gugusmutu/edom/pdf_report_edom_makul', compact('data', 'thn', 'tp', 'prd'))->setPaper('a4', 'landscape');
        return $pdf->download('Report EDOM Matakuliah' . ' ' . $ganti . ' ' . $tp . ' ' . $prd . '.pdf');
    }

    public function download_report_edom_by_dosen_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;

        $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();

        $thn = $periodetahun->periode_tahun;
        $tp = $periodetipe->periode_tipe;

        $data = DB::select('CALL edom_by_dosen_new(?,?)', array($idperiodetahun, $idperiodetipe));

        $pdf = PDF::loadView('sadmin/edom/pdf_report_edom_dosen', compact('data', 'thn', 'tp'))->setPaper('a4', 'landscape');
        return $pdf->download('Report EDOM Dosen' . ' ' . $thn . ' ' . $tp . '.pdf');
    }

    function detail_edom_makul_gugusmutu(Request $request)
    {
        $idkurperiode = $request->id_kurperiode;
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;

        if ($idperiodetahun == 6 && $idperiodetipe == 1) {
            $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
            $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
            $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
        } elseif ($idperiodetahun < 6) {
            $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
        } elseif ($idperiodetahun > 6) {
            $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
        }

        $data_mk = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->where('kurikulum_periode.id_kurperiode', $idkurperiode)
            ->select('dosen.nama', 'periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'matakuliah.makul')
            ->first();

        return view('gugusmutu/edom/detail_edom_makul', compact('data', 'data_mk'));
    }

    function detail_edom_dosen_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;
        $nama = $request->nama;

        if ($idperiodetahun == 6 && $idperiodetipe == 1) {
            $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
            $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
            $data = DB::select('CALL detail_edom_dosen_new(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun < 6) {
            $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun > 6) {
            $data = DB::select('CALL detail_edom_dosen_new(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        }

        return view('gugusmutu/edom/detail_edom_dosen', compact('data', 'nama', 'periodetahun', 'periodetipe'));
    }

    function download_detail_edom_makul_gugusmutu(Request $request)
    {
        $idkurperiode = $request->id_kurperiode;
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;

        if ($idperiodetahun == 6 && $idperiodetipe == 1) {
            $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
            $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
            $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
        } elseif ($idperiodetahun < 6) {
            $data = DB::select('CALL detail_edom_makul_old(?)', array($idkurperiode));
        } elseif ($idperiodetahun > 6) {
            $data = DB::select('CALL detail_edom_makul_new(?)', array($idkurperiode));
        }

        $data_mk = Kurikulum_periode::join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->where('kurikulum_periode.id_kurperiode', $idkurperiode)
            ->select('dosen.nama', 'periode_tahun.periode_tahun', 'periode_tipe.periode_tipe', 'matakuliah.makul', 'kelas.kelas', 'prodi.prodi')
            ->first();

        $thn = $data_mk->periode_tahun;
        $tp = $data_mk->periode_tipe;
        $nama_mk = $data_mk->makul;
        $nama_dsn = $data_mk->nama;
        $nama_kls = $data_mk->kelas;
        $nama_prd = $data_mk->prodi;

        $pdf = PDF::loadView('sadmin/edom/pdf_detail_report_edom_makul', compact('data', 'thn', 'tp', 'nama_prd', 'nama_dsn', 'nama_mk', 'nama_kls'))->setPaper('a4', 'landscape');
        return $pdf->download('Report EDOM Matakuliah' . ' ' . $nama_mk . ' ' . $nama_kls . '.pdf');
    }

    public function download_detail_edom_dosen_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;
        $nama = $request->nama;

        if ($idperiodetahun == 6 && $idperiodetipe == 1) {
            $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 2) {
            $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun == 6 && $idperiodetipe == 3) {
            $data = DB::select('CALL detail_edom_dosen_new(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun < 6) {
            $data = DB::select('CALL detail_edom_dosen_old(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        } elseif ($idperiodetahun > 6) {
            $data = DB::select('CALL detail_edom_dosen_new(?,?,?)', array($idperiodetahun, $idperiodetipe, $iddosen));
        }

        $pdf = PDF::loadView('sadmin/edom/pdf_detail_report_edom_dosen', compact('data', 'periodetahun', 'periodetipe', 'nama'))->setPaper('a4', 'landscape');
        return $pdf->download('Report EDOM Dosen' . ' ' . $nama . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    function data_absensi_edom_gugusmutu()
    {
        $periodetahun = Periode_tahun::orderBy('id_periodetahun', 'DESC')->get();
        $periodetipe = Periode_tipe::orderBy('id_periodetipe', 'DESC')->get();

        return view('gugusmutu/edom/filter_absen_edom', compact('periodetahun', 'periodetipe'));
    }

    function report_absen_edom_gugusmutu(Request $request)
    {
        $tahun = $request->id_periodetahun;
        $tipe = $request->id_periodetipe;
        $absen = $request->tipe_absen;

        $idtahun = Periode_tahun::where('id_periodetahun', $tahun)->first();
        $idtipe = Periode_tipe::where('id_periodetipe', $tipe)->first();

        $data = DB::select('CALL absen_edom_dosen(?,?,?)', [$tahun, $tipe, $absen]);

        if ($absen == 'dosen') {

            return view('gugusmutu/edom/hasil_absen_edom', compact('data', 'idtahun', 'idtipe'));
        } elseif ($absen == 'matakuliah') {

            return view('gugusmutu/edom/hasil_absen_edom_makul', compact('data', 'idtahun', 'idtipe'));
        }
    }

    function data_report_kuisioner_gugusmutu()
    {
        $data = Kuisioner_kategori::all();

        $thn = Periode_tahun::all();

        $tp = Periode_tipe::all();

        return view('gugusmutu/kuisioner/report_kuisioner', compact('data', 'thn', 'tp'));
    }

    function report_kuisioner_kategori_gugusmutu($id)
    {
        if ($id == 1) {
            return $this->report_dospem_aka_gugusmutu($id);
        } elseif ($id == 2) {
            return $this->report_dospem_pkl_gugusmutu($id);
        } elseif ($id == 3) {
            return $this->report_dospem_ta_gugusmutu($id);
        } elseif ($id == 4) {
            return $this->report_dospeng1_ta_gugusmutu($id);
        } elseif ($id == 5) {
            return $this->report_dospeng2_ta_gugusmutu($id);
        } elseif ($id == 6) {
            return $this->report_kuis_baak_gugusmutu($id);
        } elseif ($id == 7) {
            return $this->report_kuis_bauk_gugusmutu($id);
        } elseif ($id == 8) {
            return $this->report_kuis_perpus_gugusmutu($id);
        } elseif ($id == 9) {
            return $this->report_kuis_beasiswa_gugusmutu($id);
        }
    }

    public function report_dospem_aka_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospem_aka', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_pa_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospem_aka', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_pa_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pa(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('gugusmutu/kuisioner/detail_kuisioner_dsn_pa', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_pa_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospem_aka', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing Akademik' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_pa_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pa(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_pa', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing Akademik' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospem_pkl_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pkl(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospem_pkl', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_pkl_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pkl(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospem_pkl', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_pkl_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pkl(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('gugusmutu/kuisioner/detail_kuisioner_dsn_pkl', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_pkl_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pkl(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospem_pkl', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing PKL' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_pkl_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pkl(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_pkl', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing PKL' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospem_ta_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospem_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_ta_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospem_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_ta_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('gugusmutu/kuisioner/detail_kuisioner_dsn_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_ta_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospem_ta', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing TA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_ta_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing TA' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospeng1_ta_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji1_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospeng1_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_peng1_ta_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji1_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospeng1_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_peng1_ta_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji1_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('gugusmutu/kuisioner/detail_kuisioner_dsn_peng1_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_peng1_ta_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji1_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospeng1_ta', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 1 TA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_peng1_ta_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji1_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_peng1_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 1 TA' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospeng2_ta_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji2_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospeng2_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_peng2_ta_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji2_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_dospeng2_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_peng2_ta_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji2_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('gugusmutu/kuisioner/detail_kuisioner_dsn_peng2_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_peng2_ta_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji2_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospeng2_ta', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 2 TA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_peng2_ta_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji2_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_peng2_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 2 TA' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_baak_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_baak', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_baak_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_baak', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_baak_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('gugusmutu/kuisioner/detail_kuisioner_baak', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_baak_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_baak', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAAK' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_baak_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_baak', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAAK' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_bauk_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_bauk', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_bauk_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_bauk', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_bauk_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('gugusmutu/kuisioner/detail_kuisioner_bauk', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_bauk_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_bauk', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAUK' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_bauk_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_bauk', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAUK' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_perpus_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_perpus', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_perpus_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_perpus', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_perpus_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('sadmin/kuisioner/detail_kuisioner_perpus', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_perpus_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_perpus', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner PERPUS' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_perpus_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_perpus', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner PERPUS' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_beasiswa_gugusmutu($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_beasiswa', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_beasiswa_gugusmutu(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('gugusmutu/kuisioner/report_kuisioner_beasiswa', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_beasiswa_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('gugusmutu/kuisioner/detail_kuisioner_beasiswa', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_beasiswa_gugusmutu(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_beasiswa', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BEASISWA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_beasiswa_gugusmutu(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->kodeprodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('kodeprodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_beasiswa', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BEASISWA' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }
}
