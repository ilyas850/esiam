<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use Alert;
use File;
use Session;
use App\User;
use App\Models\Invoice;
use App\Models\Tingkat;
use App\Models\Peserta;
use App\Models\Psikolog;
use App\Models\Sekolah;
use App\Models\Trans_peserta_test;
use App\Models\Trans_psikolog_bayar;
use App\Models\Laporan;
use App\Models\Rekomendasi;
use App\Models\Psikogram;
use App\Models\Gaya_belajar;
use App\Models\Level_kecerdasan;
use App\Models\Aspek_psikologis;
use App\Models\No_master_soal;
use App\Models\Jawaban;
use App\Models\Jawaban_peserta;
use App\Models\Soal;
use App\Models\Tipe_test;
use App\Models\Peserta_ambil_test;
use App\Models\Pesertatest_record;
use App\Models\Peserta_recordtest;
use App\Imports\PesertaImport;
use App\Imports\PesertaImporttes;
use App\Exports\HasilJawabanExport;
use App\Exports\ListJawabanExport;
use App\Exports\HasilExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
  public function login()
  {
    return view('auth.login_xms');
  }
  public function index()
  {
    $user = Auth::user();

    $pst = Peserta::all();
    $jml = count($pst);

    $psi = Psikolog::all();
    $jmlpsi = count($psi);

    $skl = Sekolah::all();
    $jmlskl = count($skl);

    $tkt = Tingkat::all();
    $jmltkt = count($tkt);

    return view('admin/home', compact('user', 'jml', 'jmlpsi', 'jmlskl', 'jmltkt'));
  }

  public function data_sekolah()
  {
    $sch = Sekolah::all();

    return view('admin/sekolah/data_sekolah', ['sch' => $sch]);
  }

  public function post_sch(Request $request)
  {
    $this->validate($request, [
      'nama_sekolah'      => 'required',
      'alamat_sekolah'    => 'required',
      'pic_sekolah'       => 'required',
      'notelp_sekolah'    => 'required',
    ]);

    $thn                    = new Sekolah;
    $thn->nama_sekolah      = $request->nama_sekolah;
    $thn->alamat_sekolah    = $request->alamat_sekolah;
    $thn->pic_sekolah       = $request->pic_sekolah;
    $thn->notelp_sekolah    = $request->notelp_sekolah;
    $thn->created_by        = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Sekolah Berhasil Disimpan!');

    return redirect('data_sekolah');
  }

  public function editsch($id)
  {
    $sch = Sekolah::find($id);
  }

  public function updatesch(Request $request, $id)
  {
    $sch                    = Sekolah::find($id);
    $sch->nama_sekolah      = $request->nama_sekolah;
    $sch->alamat_sekolah    = $request->alamat_sekolah;
    $sch->pic_sekolah       = $request->pic_sekolah;
    $sch->notelp_sekolah    = $request->notelp_sekolah;
    $thn->updated_by        = Auth::user()->name;
    $sch->save();

    Session::flash('sukses', 'Data Sekolah Berhasil Diedit!');
    return redirect('data_sekolah');
  }

  // public function destroysch($id)
  // {
  //   Sekolah::destroy($id);
  //
  //   Session::flash('sukses', 'Data Sekolah Berhasil Dihapus!');
  //   return redirect('data_sekolah');
  // }

  public function destroysch(Request $request)
  {
    $akun = Sekolah::where('id_sekolah', $request->id_sekolah)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Sekolah Berhasil Dinonaktifkan!');
    return redirect('data_sekolah');
  }

  public function aktifsch(Request $request)
  {
    $akun = Sekolah::where('id_sekolah', $request->id_sekolah)
      ->update(['status' => 'ACTIVE']);

    Session::flash('sukses', 'Data Sekolah Berhasil Diaktifkan!');
    return redirect('data_sekolah');
  }

  public function data_tingkat()
  {
    $tk = Tingkat::all();

    return view('admin/tingkat/data_tingkat', ['data' => $tk]);
  }

  public function post_tk(Request $request)
  {
    $this->validate($request, [
      'nama_tingkat' => 'required',
    ]);

    $thn                   = new Tingkat;
    $thn->nama_tingkat     = $request->nama_tingkat;
    $thn->created_by       = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Tingkat Berhasil Disimpan!');

    return redirect('data_tingkat');
  }

  public function updatetk(Request $request, $id)
  {
    $sch                    = Tingkat::find($id);
    $sch->nama_tingkat      = $request->nama_tingkat;
    $sch->updated_by        = Auth::user()->name;
    $sch->save();

    return redirect('data_tingkat');
  }

  public function destroytk($id)
  {
    Tingkat::destroy($id);

    return redirect('data_tingkat');
  }

  public function data_peserta()
  {
    $pest = Peserta::leftjoin('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->leftjoin('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->select('peserta.nama_peserta', 'peserta.tgl_test', 'peserta.tmpt_lahir', 'peserta.tgl_lahir', 'sekolah.nama_sekolah', 'peserta.kls_tujuan', 'peserta.nama_ortu', 'peserta.nohp_ortu', 'peserta.email_ortu', 'peserta.kota', 'tingkat.nama_tingkat', 'peserta.tahun_reg', 'peserta.fee')
      ->orderBy('peserta.id_peserta', 'DESC')
      ->get();

    $sch = Peserta::join('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->select(DB::raw('DISTINCT(peserta.id_sekolah)'), 'sekolah.nama_sekolah')
      ->get();

    $tkt = Peserta::join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->select(DB::raw('DISTINCT(peserta.id_tingkat)'), 'tingkat.nama_tingkat')
      ->get();

    $thn = Peserta::select(DB::raw('DISTINCT(tahun_reg)'))->get();

    $test = Peserta::select(DB::raw('DISTINCT(tgl_test)'))->get();

    $schl = Sekolah::all();
    $schll = Sekolah::all();
    $tk = Tingkat::all();
    $tktt = Tingkat::all();
    $thnnow = date('Y');
    $thnmin = $thnnow - 1;
    $thnmax = $thnnow + 1;

    return view('admin/peserta/data_peserta', ['tes' => $test, 'data' => $pest, 'sch' => $sch, 'tkt' => $tkt, 'tktt' => $tktt, 'thn' => $thn, 'schl' => $schl, 'schll' => $schll, 'tk' => $tk, 'thnnow' => $thnnow, 'thnmin' => $thnmin, 'thnmax' => $thnmax]);
  }

  public function import_excel(Request $request)
  {

    $sch = $request->id_sekolah;
    $thn = $request->tahun_reg;
    $tk = $request->id_tingkat;
    $fee = $request->fee;
    $tes = $request->tgl_test;

    // validasi
    $this->validate($request, [
      'file' => 'required|mimes:xls,xlsx'
    ]);

    // menangkap file excel
    $file = $request->file('file');

    // membuat nama file unik
    $nama_file = rand() . $file->getClientOriginalName();

    // upload ke folder file_siswa di dalam folder public
    $file->move('file_peserta', $nama_file);

    // import data
    //Excel::import(new PesertaImport($sch,$thn,$tk,$fee,$tes), public_path('/file_peserta/' . $nama_file));
    Excel::import(new PesertaImporttes($sch, $thn, $tk, $fee, $tes), public_path('/file_peserta/' . $nama_file));

    // notifikasi dengan session
    Session::flash('sukses', 'Data Peserta Berhasil Diimport!');

    // alihkan halaman kembali
    return redirect('data_peserta');
  }

  public function view_data(Request $request)
  {
    $sch = Peserta::join('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->select(DB::raw('DISTINCT(peserta.id_sekolah)'), 'sekolah.nama_sekolah')
      ->get();

    $tkt = Peserta::join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->select(DB::raw('DISTINCT(peserta.id_tingkat)'), 'tingkat.nama_tingkat')
      ->get();

    $thn = Peserta::select(DB::raw('DISTINCT(peserta.tahun_reg)'))->get();

    $test = Peserta::select(DB::raw('DISTINCT(tgl_test)'))->get();

    $psrt = Peserta::where('peserta.id_sekolah', $request->id_sekolah)
      ->where('peserta.id_tingkat', $request->id_tingkat)
      ->where('peserta.tahun_reg', $request->tahun_reg)
      ->leftjoin('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->leftjoin('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->select('peserta.nama_peserta', 'peserta.tgl_test', 'peserta.tmpt_lahir', 'peserta.tgl_lahir', 'sekolah.nama_sekolah', 'peserta.kls_tujuan', 'peserta.nama_ortu', 'peserta.nohp_ortu', 'peserta.email_ortu', 'peserta.kota', 'tingkat.nama_tingkat', 'peserta.tahun_reg', 'peserta.fee')
      ->orderBy('peserta.id_peserta', 'DESC')
      ->get();

    $schl = Sekolah::all();
    $schll = Sekolah::all();
    $tk = Tingkat::all();
    $tktt = Tingkat::all();
    $thnnow = date('Y');
    $thnmin = $thnnow - 1;
    $thnmax = $thnnow + 1;

    return view('admin/peserta/view_data_peserta', ['tes' => $test, 'data' => $psrt, 'sch' => $sch, 'tkt' => $tkt, 'thn' => $thn, 'tktt' => $tktt, 'schl' => $schl, 'schll' => $schll, 'tk' => $tk, 'thnnow' => $thnnow, 'thnmin' => $thnmin, 'thnmax' => $thnmax]);
  }

  public function peserta_sekolah()
  {
    $psrsk = Peserta::leftjoin('invoice', 'peserta.id_peserta', '=', 'invoice.id_peserta')
      ->join('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->select('peserta.tgl_test', 'peserta.id_sekolah', 'peserta.nama_peserta', 'tingkat.nama_tingkat', 'invoice.no_invoice', 'invoice.tgl_invoice', 'invoice.tgl_invoice', 'invoice.total_tagihan')
      ->orderBy('peserta.id_sekolah', 'DESC')
      ->get();

    $skl = Sekolah::all();

    $tes = Peserta::select(DB::raw('DISTINCT(tgl_test)'))->get();

    return view('admin/tagihan/peserta_sekolah', ['tes' => $tes, 'data' => $psrsk, 'skl' => $skl]);
  }

  public function pilih_tgl_test_pay(Request $request)
  {
    $tgl = Peserta::join('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->leftjoin('invoice', 'peserta.id_peserta', '=', 'invoice.id_peserta')
      ->select('peserta.id_peserta', 'sekolah.id_sekolah', 'peserta.tgl_test', 'peserta.nama_peserta', 'sekolah.nama_sekolah', 'tingkat.nama_tingkat', 'peserta.fee')
      ->where('peserta.tgl_test', $request->tgl_test)
      ->where('peserta.id_invoice', null)
      ->get();

    $ldate = date('Y-m-d');

    function getRomawi($bln)
    {
      switch ($bln) {
        case 1:
          return "I";
          break;
        case 2:
          return "II";
          break;
        case 3:
          return "III";
          break;
        case 4:
          return "IV";
          break;
        case 5:
          return "V";
          break;
        case 6:
          return "VI";
          break;
        case 7:
          return "VII";
          break;
        case 8:
          return "VIII";
          break;
        case 9:
          return "IX";
          break;
        case 10:
          return "X";
          break;
        case 11:
          return "XI";
          break;
        case 12:
          return "XII";
          break;
      }
    }

    $thn = date('Y');
    $bulan  = date('n');
    $romawi = getRomawi($bulan);

    $kd = "";

    $query = DB::table('invoice')
      ->select(DB::raw('MAX(LEFT(no_invoice,3)) as kd_max'));
    if ($query->count() > 0) {
      foreach ($query->get() as $key) {
        $tmp = ((int)$key->kd_max) + 1;
        $kd = sprintf("%03s", $tmp);
      }
    } else {
      $kd = "001";
    }

    $kode = $kd . '/' . $romawi . '/' . 'XE' . '/' . $thn;


    return view('admin/tagihan/invoice', ['tgl' => $tgl, 'dt' => $ldate, 'kode' => $kode]);
  }

  public function save_invoice(Request $request)
  {
    $this->validate($request, [
      'id_peserta'      => 'required'
    ]);

    $jml_inv = count($request->id_peserta);

    for ($i = 0; $i < $jml_inv; $i++) {
      $pay = $request->id_peserta[$i];
      $idr = explode(',', $pay, 2);
      $tra = $idr[0];
      $trs = $idr[1];

      $paym                   = new Invoice;
      $paym->id_peserta       = $tra;
      $paym->id_sekolah       = $trs;
      $paym->tgl_invoice      = $request->tgl_invoice;
      $paym->total_tagihan    = $request->total_tagihan;
      $paym->no_invoice       = $request->no_invoice;
      $paym->keterangan       = $request->keterangan;
      $paym->created_by       = Auth::user()->name;
      $paym->save();

      $ck = Invoice::where('id_peserta', $tra)->get();
      foreach ($ck as $keyid) {
        # code...
      }

      $py = Peserta::where('id_peserta', $tra)
        ->update(['id_invoice' => $keyid->id_invoice]);
    }

    return redirect('peserta_sekolah');
  }

  public function data_psikolog()
  {
    $psi = Psikolog::all();

    return view('admin/psikolog/data_psikolog', ['data' => $psi]);
  }

  public function post_psi(Request $request)
  {
    $this->validate($request, [
      'nama_psikolog'   => 'required',
      'nohp_psikolog'   => 'required',
      'email_psikolog'  => 'required',
      'alamat_psikolog' => 'required',
      'noktp_psikolog'  => 'required',
      'bank'            => 'required',
      'norek'           => 'required',
    ]);

    $thn                      = new Psikolog;
    $thn->nama_psikolog       = $request->nama_psikolog;
    $thn->nohp_psikolog       = $request->nohp_psikolog;
    $thn->email_psikolog      = $request->email_psikolog;
    $thn->alamat_psikolog     = $request->alamat_psikolog;
    $thn->noktp_psikolog      = $request->noktp_psikolog;
    $thn->npwp_psikolog       = $request->npwp_psikolog;
    $thn->bank                = $request->bank;
    $thn->norek               = $request->norek;
    $thn->created_by          = Auth::user()->name;
    $thn->save();


    Session::flash('sukses', 'Data Psikolog Berhasil Disimpan!');

    return redirect('data_psikolog');
  }

  public function peserta_test()
  {
    $test = Trans_peserta_test::leftjoin('peserta', 'trans_peserta_test.idpeserta', '=', 'peserta.id_peserta')
      ->leftjoin('psikolog', 'trans_peserta_test.id_psikolog', '=', 'psikolog.id_psikolog')
      ->leftjoin('trans_psikolog_bayar', 'trans_peserta_test.id_trans_pesertatest', '=', 'trans_psikolog_bayar.id_transpesertatest')
      ->leftjoin('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->leftjoin('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->select('psikolog.id_psikolog', 'peserta.id_peserta', 'tingkat.nama_tingkat', 'sekolah.nama_sekolah', 'trans_peserta_test.id_trans_pesertatest', 'peserta.tgl_test', 'peserta.nama_peserta',  'psikolog.nama_psikolog', 'trans_peserta_test.verifikasi', 'trans_peserta_test.laporan', 'trans_peserta_test.bayaran', 'trans_psikolog_bayar.id_transpesertatest')
      ->get();

    $tes = Peserta::select(DB::raw('DISTINCT(tgl_test)'))->get();
    $sch = Peserta::select(DB::raw('DISTINCT(id_sekolah)'))->get();
    $tkt = Peserta::select(DB::raw('DISTINCT(id_tingkat)'))->get();
    $thn = Peserta::select(DB::raw('DISTINCT(tahun_reg)'))->get();
    $schl = Sekolah::all();
    $tk = Tingkat::all();

    return view('admin/psikolog/peserta_test', ['tes' => $tes, 'data' => $test, 'sch' => $sch, 'tkt' => $tkt, 'thn' => $thn, 'schl' => $schl, 'tk' => $tk]);
  }

  public function view_peserta(Request $request)
  {

    $psrt = Peserta::where('peserta.id_sekolah', $request->id_sekolah)
      ->where('peserta.id_tingkat', $request->id_tingkat)
      ->where('peserta.tahun_reg', $request->tahun_reg)
      ->where('peserta.tgl_test', $request->tgl_test)
      ->leftjoin('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->leftjoin('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->leftjoin('trans_peserta_test', 'peserta.id_peserta', '=', 'trans_peserta_test.idpeserta')
      ->select('peserta.tgl_test', 'trans_peserta_test.idpeserta', 'peserta.id_peserta', 'peserta.nama_peserta', 'peserta.tmpt_lahir', 'peserta.tgl_lahir', 'sekolah.nama_sekolah', 'peserta.kls_tujuan', 'peserta.nama_ortu', 'peserta.nohp_ortu', 'peserta.email_ortu', 'peserta.kota', 'tingkat.nama_tingkat', 'peserta.tahun_reg', 'peserta.fee')
      ->orderBy('peserta.id_peserta', 'DESC')
      ->get();

    $psi = Psikolog::all();

    return view('admin/psikolog/view_peserta', ['data' => $psrt, 'psi' => $psi]);
  }

  public function save_pestes(Request $request)
  {

    $jml_pes = count($request->id_peserta);
    for ($i = 0; $i < $jml_pes; $i++) {
      $pes = $request->id_peserta[$i];

      $test                 = new Trans_peserta_test;
      $test->idpeserta      = $pes;
      $test->id_psikolog    = $request->id_psikolog;
      $test->created_by      = Auth::user()->name;
      //$test->tgl_test       = $request->tgl_test;
      $test->save();
    }
    return redirect('peserta_test');
  }

  public function verifikasi_all()
  {
    $ver = Trans_peserta_test::leftjoin('peserta', 'trans_peserta_test.idpeserta', '=', 'peserta.id_peserta')
      ->leftjoin('psikolog', 'trans_peserta_test.id_psikolog', '=', 'psikolog.id_psikolog')
      ->leftjoin('trans_psikolog_bayar', 'trans_peserta_test.id_trans_pesertatest', '=', 'trans_psikolog_bayar.id_transpesertatest')
      ->leftjoin('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->leftjoin('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('trans_peserta_test.verifikasi', 'no')
      ->select('tingkat.nama_tingkat', 'sekolah.nama_sekolah', 'trans_peserta_test.id_trans_pesertatest', 'peserta.tgl_test', 'peserta.nama_peserta', 'psikolog.nama_psikolog', 'trans_peserta_test.verifikasi', 'trans_peserta_test.laporan', 'trans_peserta_test.bayaran', 'trans_psikolog_bayar.id_transpesertatest')
      ->get();

    return view('admin/psikolog/verifikasi', ['data' => $ver]);
  }

  public function save_verifikasi(Request $request)
  {
    $jml_pes = count($request->verifikasi);

    for ($i = 0; $i < $jml_pes; $i++) {
      $pes = $request->verifikasi[$i];
      $idr = explode(',', $pes, 2);
      $tra = $idr[0];
      $trs = $idr[1];

      $kelas = Trans_peserta_test::where('id_trans_pesertatest', $idr)
        ->update(['verifikasi' => $trs]);
    }
    return redirect('peserta_test');
  }

  public function save_ver(Request $request)
  {
    $akun = Trans_peserta_test::where('id_trans_pesertatest', $request->id_trans_pesertatest)
      ->update(['verifikasi' => $request->verifikasi]);

    Session::flash('sukses', 'Data Verifikasi Berhasil Disimpan!');

    return redirect('peserta_test');
  }

  public function input_payment()
  {
    $cek = Trans_peserta_test::join('psikolog', 'trans_peserta_test.id_psikolog', '=', 'psikolog.id_psikolog')
      ->select(DB::raw('DISTINCT(trans_peserta_test.id_psikolog)'), 'psikolog.nama_psikolog')
      ->get();


    return view('admin/psikolog/pilih_psikolog', ['psi' => $cek]);
  }

  public function view_payment(Request $request)
  {
    $pay = Trans_peserta_test::leftjoin('peserta', 'trans_peserta_test.idpeserta', '=', 'peserta.id_peserta')
      ->leftjoin('psikolog', 'trans_peserta_test.id_psikolog', '=', 'psikolog.id_psikolog')
      ->leftjoin('trans_psikolog_bayar', 'trans_peserta_test.id_trans_pesertatest', '=', 'trans_psikolog_bayar.id_transpesertatest')
      ->leftjoin('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->leftjoin('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('trans_peserta_test.bayaran', 'no')
      ->where('trans_peserta_test.id_psikolog', $request->id_psikolog)
      ->select('psikolog.id_psikolog', 'peserta.fee', 'peserta.id_peserta', 'tingkat.nama_tingkat', 'sekolah.nama_sekolah', 'trans_peserta_test.id_trans_pesertatest', 'peserta.tgl_test', 'peserta.nama_peserta', 'psikolog.nama_psikolog', 'trans_peserta_test.verifikasi', 'trans_peserta_test.laporan', 'trans_peserta_test.bayaran', 'trans_psikolog_bayar.id_transpesertatest')
      ->get();

    return view('admin/psikolog/in_payment', ['data' => $pay]);
  }

  public function calculate_payment(Request $request)
  {
    $jml_pay = count($request->bayaran);
    for ($i = 0; $i < $jml_pay; $i++) {
      $pay = $request->bayaran[$i];
      $idr = explode(',', $pay, 2);
      $tra = $idr[0];
      $trs = $idr[1];

      $paym                          = new Trans_psikolog_bayar;
      $paym->id_transpesertatest     = $tra;
      $paym->tgl_bayar               = $request->tgl_bayar;
      $paym->nominal                 = $request->nominal;
      $paym->no_trf                  = $request->no_trf;
      $paym->created_by               = Auth::user()->name;
      $paym->save();

      $py = Trans_peserta_test::where('id_trans_pesertatest', $tra)
        ->update(['bayaran' => 'yes']);
    }

    return redirect('peserta_test');
  }

  public function edit_report(Request $request)
  {
    $lap = Laporan::join('peserta', 'laporan.id_peserta', '=', 'peserta.id_peserta')
      ->join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->join('aspek_psikologis', 'laporan.id_master', '=', 'aspek_psikologis.id_aspek')
      ->join('psikogram', 'aspek_psikologis.id_psikogram', '=', 'psikogram.id_psikogram')
      ->where('laporan.id_peserta', $request->id_peserta)
      ->where('laporan.id_psikolog', $request->id_psikolog)
      ->select('laporan.id_laporan', 'laporan.id_master', 'laporan.nilai', 'tingkat.nama_tingkat', 'laporan.tipe_nilai', 'aspek_psikologis.aspek_psikologis', 'psikogram.psikogram')
      ->get();

    $lapor = Laporan::join('peserta', 'laporan.id_peserta', '=', 'peserta.id_peserta')
      ->join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->join('aspek_psikologis', 'laporan.id_master', '=', 'aspek_psikologis.id_aspek')
      ->join('gaya_belajar', 'laporan.id_master', '=', 'gaya_belajar.id_gaya')
      ->where('laporan.tipe_nilai', 'gaya_belajar')
      ->where('laporan.id_peserta', $request->id_peserta)
      ->where('laporan.id_psikolog', $request->id_psikolog)
      ->select('laporan.id_laporan', 'laporan.id_master', 'laporan.nilai', 'tingkat.nama_tingkat', 'laporan.tipe_nilai', 'aspek_psikologis.aspek_psikologis', 'gaya_belajar.gaya_belajar')
      ->get();

    foreach ($lapor as $keyla) {
      # code...
    }

    $level = Laporan::join('peserta', 'laporan.id_peserta', '=', 'peserta.id_peserta')
      ->join('level_kecerdasan', 'laporan.id_master', '=', 'level_kecerdasan.id_level')
      ->where('laporan.tipe_nilai', 'level_kecerdasan')
      ->where('laporan.id_peserta', $request->id_peserta)
      ->where('laporan.id_psikolog', $request->id_psikolog)
      ->select('level_kecerdasan.level_ing', 'level_kecerdasan.level_ind', 'laporan.id_master', 'laporan.tipe_nilai', 'laporan.id_laporan')
      ->get();

    foreach ($level as $keylvl) {
      # code...
    }

    $reko = Laporan::join('peserta', 'laporan.id_peserta', '=', 'peserta.id_peserta')
      ->join('rekomendasi', 'laporan.id_master', '=', 'rekomendasi.id_rekomendasi')
      ->where('laporan.tipe_nilai', 'rekomendasi')
      ->where('laporan.id_peserta', $request->id_peserta)
      ->where('laporan.id_psikolog', $request->id_psikolog)
      ->select('laporan.id_laporan', 'rekomendasi.id_rekomendasi', 'rekomendasi.rekomendasi', 'laporan.id_master', 'laporan.tipe_nilai')
      ->get();

    foreach ($reko as $keyrek) {
      # code...
    }

    $conl = Laporan::where('id_peserta', $request->id_peserta)
      ->where('id_psikolog', $request->id_psikolog)
      ->where('tipe_nilai', 'narasi_kesimpulan')
      ->select('id_master', 'tipe_nilai', 'id_laporan', 'nilai')
      ->get();

    foreach ($conl as $keysar) {
      # code...
    }

    $conls = Laporan::where('id_peserta', $request->id_peserta)
      ->where('id_psikolog', $request->id_psikolog)
      ->where('tipe_nilai', 'narasi_saran')
      ->select('id_master', 'tipe_nilai', 'id_laporan', 'nilai')
      ->get();

    foreach ($conls as $keysaran) {
      # code...
    }

    $gy = Gaya_belajar::where('status', 'ACTIVE')->get();
    $lv = Level_kecerdasan::where('status', 'ACTIVE')->get();
    $rk = Rekomendasi::where('status', 'ACTIVE')->get();

    return view('admin/psikolog/edit_raport', ['saran' => $keysaran, 'sar' => $keysar, 'rekome' => $keyrek, 'levell' => $keylvl, 'rk' => $rk, 'lv' => $lv, 'gy' => $gy, 'lap' => $lap, 'rap' => $keyla]);
  }

  public function post_aspek_psikologis(Request $request)
  {
    $nilai = $request->nilai;

    $jml = count($nilai);

    for ($i = 0; $i < $jml; $i++) {
      $lapor = $request->id_laporan[$i];
      $nilaii = $request->nilai[$i];



      $akun = Laporan::where('id_laporan', $lapor)
        ->update(['nilai' => $nilaii]);
    }

    $gaya = Laporan::where('id_laporan', $request->id_laporan_gaya)
      ->update(['id_master' => $request->gaya_belajar]);

    $level = Laporan::where('id_laporan', $request->id_laporan_level)
      ->update(['id_master' => $request->level_kecerdasan]);

    $reko = Laporan::where('id_laporan', $request->id_laporan_reko)
      ->update(['id_master' => $request->rekomendasi]);

    $reko = Laporan::where('id_laporan', $request->id_laporan_kes)
      ->update(['nilai' => $request->narasi_kesimpulan]);

    $reko = Laporan::where('id_laporan', $request->id_laporan_sar)
      ->update(['nilai' => $request->narasi_saran]);



    Session::flash('sukses', 'Data Nilai Sudah diubah');
    return redirect('peserta_test');
  }

  public function save_pay(Request $request)
  {
    $pay                          = new Trans_psikolog_bayar;
    $pay->id_transpesertatest     = $request->id_transpesertatest;
    $pay->tgl_bayar               = $request->tgl_bayar;
    $pay->nominal                 = $request->nominal;
    $pay->no_trf                  = $request->no_trf;
    $pay->created_by            = Auth::user()->name;
    $pay->save();

    $py = Trans_peserta_test::where('id_trans_pesertatest', $request->id_transpesertatest)
      ->update(['bayaran' => 'yes']);

    return redirect('peserta_test');
  }

  public function datauser()
  {
    $user = Psikolog::leftJoin('users', 'username', '=', 'psikolog.user_id')
      ->select('psikolog.user_id', 'users.id', 'users.username',  'psikolog.nama_psikolog', 'users.role', 'psikolog.id_psikolog')
      ->orderBy('users.id', 'DESC')
      ->get();

    return view('admin/user/datauser', ['user' => $user]);
  }

  public function generate(Request $request)
  {
    $id = $request->id_psikolog;
    $psi = Psikolog::where('id_psikolog', $id)->get();
    foreach ($psi as $value) {
      // code...
    }
    $nm = substr($value->nama_psikolog, 0, 2);
    $nm_new = strtoupper($nm);
    $tanggal = date('y');

    $kd = "";
    $query = DB::table('psikolog')
      ->select(DB::raw('MAX(RIGHT(user_id,3)) as kd_max'));
    if ($query->count() > 0) {
      foreach ($query->get() as $key) {
        $tmp = ((int)$key->kd_max) + 1;
        $kd = sprintf("%03s", $tmp);
      }
    } else {
      $kd = "001";
    }

    $kode = 'XMS' . $nm_new . $tanggal . $kd;

    Psikolog::where('id_psikolog', $id)
      ->update(['user_id' => $kode]);

    $users = new User;
    $users->name = $value->nama_psikolog;
    $users->username = $kode;
    $users->role = 2;
    $users->password = bcrypt($kode);
    $users->save();

    return redirect('user');
  }

  public function resetuser(Request $request)
  {
    $id = $request->id;
    $user = User::find($id);
    $user->password = bcrypt($request->password);
    $user->save();

    Session::flash('sukses', 'Data Psikolog Berhasil Direset!');
    return redirect('user');
  }

  public function hapususer(Request $request, $id)
  {
    $user = User::where('username', $id)->forceDelete();
    $guru = Psikolog::where('user_id', $id)
      ->update(['user_id' => null]);

    Session::flash('hapus', 'Data Psikolog Berhasil Dihapus!');
    return redirect('user');
  }

  public function user_peserta()
  {
    $user = Peserta::leftJoin('users', 'username', '=', 'peserta.iduser')
      ->select('peserta.iduser', 'users.id', 'users.username',  'peserta.nama_peserta', 'users.role', 'peserta.id_peserta')
      ->orderBy('users.id', 'DESC')
      ->get();

    return view('admin/user/user_peserta', ['user' => $user]);
  }

  public function generate_peserta(Request $request)
  {
    $id = $request->id_peserta;
    $psi = Peserta::where('id_peserta', $id)->get();
    foreach ($psi as $value) {
      // code...
    }
    $nm = substr($value->nama_peserta, 0, 2);
    $nm_new = strtoupper($nm);
    $tanggal = date('y');

    $kd = "";
    $query = DB::table('peserta')
      ->select(DB::raw('MAX(RIGHT(iduser,3)) as kd_max'));
    if ($query->count() > 0) {
      foreach ($query->get() as $key) {
        $tmp = ((int)$key->kd_max) + 1;
        $kd = sprintf("%03s", $tmp);
      }
    } else {
      $kd = "001";
    }

    $kode = 'XMS' . $nm_new . $tanggal . $kd;

    Peserta::where('id_peserta', $id)
      ->update(['iduser' => $kode]);

    $users = new User;
    $users->name = $value->nama_peserta;
    $users->username = $kode;
    $users->role = 3;
    $users->password = bcrypt($kode);
    $users->save();

    return redirect('user_peserta');
  }

  public function resetuser_peserta(Request $request)
  {
    $id = $request->id;
    $user = User::find($id);
    $user->password = bcrypt($request->password);
    $user->save();

    Session::flash('sukses', 'Data Peserta Berhasil Direset!');
    return redirect('user_peserta');
  }

  public function hapususer_peserta(Request $request, $id)
  {
    $user = User::where('username', $id)->forceDelete();
    $guru = Peserta::where('iduser', $id)
      ->update(['iduser' => null]);

    Session::flash('hapus', 'Data Peserta Berhasil Dihapus!');
    return redirect('user_peserta');
  }

  public function level_kcd()
  {
    $lvl = Level_kecerdasan::where('status', 'ACTIVE')->get();

    return view('admin/laporan/level_kecerdasan', ['lvl' => $lvl]);
  }

  public function post_lvl(Request $request)
  {
    $this->validate($request, [
      'level_ind'   => 'required',
      'level_ing'   => 'required',
    ]);

    $thn                  = new Level_kecerdasan;
    $thn->level_ind       = $request->level_ind;
    $thn->level_ing       = $request->level_ing;
    $thn->created_by      = Auth::user()->name;
    $thn->save();


    Session::flash('sukses', 'Data Level Kecerdasan Berhasil Disimpan!');

    return redirect('level_kcd');
  }

  public function updatelvl(Request $request, $id)
  {
    $thn                     = Level_kecerdasan::find($id);
    $thn->level_ind          = $request->level_ind;
    $thn->level_ing          = $request->level_ing;
    $thn->updated_by            = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Level Kecerdasan Berhasil Diupdate!');

    return redirect('level_kcd');
  }

  public function destroylvl(Request $request)
  {

    $akun = Level_kecerdasan::where('id_level', $request->id_level)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Level Kecerdasan Berhasil Dihapus!');
    return redirect('level_kcd');
  }

  public function gaya_belajar()
  {
    $gy = Gaya_belajar::where('status', 'ACTIVE')->get();

    return view('admin/laporan/gaya_belajar', ['gy' => $gy]);
  }

  public function post_gy(Request $request)
  {
    $this->validate($request, [
      'gaya_belajar'   => 'required',
    ]);

    $thn                     = new Gaya_belajar;
    $thn->gaya_belajar       = $request->gaya_belajar;
    $thn->created_by            = Auth::user()->name;
    $thn->save();


    Session::flash('sukses', 'Data Gaya Belajar Berhasil Disimpan!');

    return redirect('gaya_belajar');
  }

  public function updategy(Request $request, $id)
  {
    $thn                     = Gaya_belajar::find($id);
    $thn->gaya_belajar       = $request->gaya_belajar;
    $thn->updated_by            = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Gaya Belajar Berhasil Diupdate!');

    return redirect('gaya_belajar');
  }

  public function destroygy(Request $request)
  {

    $akun = Gaya_belajar::where('id_gaya', $request->id_gaya)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Gaya Belajar Berhasil Dihapus!');
    return redirect('gaya_belajar');
  }

  public function rekomendasi()
  {
    $rkm = Rekomendasi::where('status', 'ACTIVE')->get();

    return view('admin/laporan/rekomendasi', ['rkm' => $rkm]);
  }

  public function post_rkm(Request $request)
  {
    $this->validate($request, [
      'rekomendasi'   => 'required',
    ]);

    $thn                     = new Rekomendasi;
    $thn->rekomendasi       = $request->rekomendasi;
    $thn->created_by            = Auth::user()->name;
    $thn->save();


    Session::flash('sukses', 'Data Rekomendasi Berhasil Disimpan!');

    return redirect('rekomendasi');
  }

  public function updaterkm(Request $request, $id)
  {
    $thn                     = Rekomendasi::find($id);
    $thn->rekomendasi       = $request->rekomendasi;
    $thn->updated_by            = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Rekomendasi Berhasil Diupdate!');

    return redirect('rekomendasi');
  }

  public function destroyrkm(Request $request)
  {

    $akun = Rekomendasi::where('id_rekomendasi', $request->id_rekomendasi)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Rekomendasi Berhasil Dihapus!');
    return redirect('rekomendasi');
  }

  public function psikogram()
  {
    $psgrm = Psikogram::where('status', 'ACTIVE')->get();

    return view('admin/laporan/psikogram', ['psgrm' => $psgrm]);
  }

  public function post_psgrm(Request $request)
  {
    $this->validate($request, [
      'psikogram'   => 'required',
    ]);

    $thn                  = new Psikogram;
    $thn->psikogram       = $request->psikogram;
    $thn->created_by            = Auth::user()->name;
    $thn->save();


    Session::flash('sukses', 'Data Psikogram Berhasil Disimpan!');

    return redirect('psikogram');
  }

  public function updatepsigrm(Request $request, $id)
  {
    $thn                  = Psikogram::find($id);
    $thn->psikogram       = $request->psikogram;
    $thn->updated_by            = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Psikogram Berhasil Diupdate!');

    return redirect('psikogram');
  }

  public function destroypsgrm(Request $request)
  {
    $akun = Psikogram::where('id_psikogram', $request->id_psikogram)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Psikogram Berhasil Dihapus!');
    return redirect('psikogram');
  }

  public function psikologis()
  {
    $psiko = Aspek_psikologis::join('tingkat', 'aspek_psikologis.id_tingkat', '=', 'tingkat.id_tingkat')
      ->join('psikogram', 'aspek_psikologis.id_psikogram', '=', 'psikogram.id_psikogram')
      ->where('aspek_psikologis.status', 'ACTIVE')
      ->select('aspek_psikologis.id_psikogram', 'aspek_psikologis.id_tingkat', 'aspek_psikologis.id_aspek', 'tingkat.nama_tingkat', 'psikogram.psikogram', 'aspek_psikologis.aspek_psikologis')
      ->get();

    $tkt = Tingkat::where('status', 'ACTIVE')->get();
    $group = Psikogram::where('status', 'ACTIVE')->get();

    $tktt = Tingkat::all();
    $grp = Psikogram::all();

    return view('admin/laporan/aspek_psikologis', ['tktt' => $tktt, 'grp' => $grp, 'psiko' => $psiko, 'tkt' => $tkt, 'group' => $group]);
  }

  public function post_psiko(Request $request)
  {
    $this->validate($request, [
      'id_tingkat'   => 'required',
      'id_psikogram'   => 'required',
      'aspek_psikologis'   => 'required',
    ]);

    $thn                    = new Aspek_psikologis;
    $thn->id_tingkat        = $request->id_tingkat;
    $thn->id_psikogram      = $request->id_psikogram;
    $thn->aspek_psikologis  = $request->aspek_psikologis;
    $thn->created_by            = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Aspek Psikologis Berhasil Disimpan!');

    return redirect('aspek_psikologis');
  }

  public function updatepsikologis(Request $request, $id)
  {
    $thn                    = Aspek_psikologis::find($id);
    $thn->id_tingkat        = $request->id_tingkat;
    $thn->id_psikogram      = $request->id_psikogram;
    $thn->aspek_psikologis  = $request->aspek_psikologis;
    $thn->updated_by            = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Aspek Psikologis Berhasil Diupdate!');

    return redirect('aspek_psikologis');
  }

  public function destroypsiko(Request $request)
  {
    $akun = Aspek_psikologis::where('id_aspek', $request->id_aspek)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Aspek Psikologis Berhasil Dihapus!');
    return redirect('aspek_psikologis');
  }

  public function change($id)
  {
    return view('admin/change_pwd', ['adm' => $id]);
  }

  public function store_new_pass(Request $request, $id)
  {
    $this->validate($request, [
      'oldpassword' => 'required',
      'password' => 'required|min:7|confirmed',
    ]);

    $sandi = bcrypt($request->password);
    $user = User::find($id);
    $pass = password_verify($request->oldpassword, $user->password);

    if ($pass) {
      $user->password = $sandi;
      $user->save();

      Session::flash('sukses', 'Password Berhasil Diubah!');
      return redirect('home');
    } else {
      Session::flash('salah', 'password lama yang anda ketikan salah !');
      return redirect('home');
    }
  }

  public function no_master_soal()
  {
    $nmr = No_master_soal::all();

    return view('admin/soal/no_master_soal', ['nmr' => $nmr]);
  }

  public function post_nmr(Request $request)
  {
    $this->validate($request, [
      'nomor_master_soal'   => 'required',
    ]);

    $thn                        = new No_master_soal;
    $thn->nomor_master_soal     = $request->nomor_master_soal;
    $thn->created_by            = Auth::user()->name;
    $thn->save();


    Session::flash('sukses', 'Data Nomor Master Soal Berhasil Disimpan!');
    return redirect('no_master_soal');
  }

  public function updatenmr(Request $request, $id)
  {
    $thn                     = No_master_soal::find($id);
    $thn->nomor_master_soal     = $request->nomor_master_soal;
    $thn->updated_by         = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Nomor Master Soal Berhasil Diupdate!');
    return redirect('no_master_soal');
  }

  public function destroynmr(Request $request)
  {
    $akun = No_master_soal::where('id_nomormaster', $request->id_nomormaster)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Nomor Master Soal Berhasil Dinonaktifkan!');
    return redirect('no_master_soal');
  }

  public function aktifnmr(Request $request)
  {
    $akun = No_master_soal::where('id_nomormaster', $request->id_nomormaster)
      ->update(['status' => 'ACTIVE']);

    Session::flash('sukses', 'Data Nomor Master Soal Berhasil Diaktifkan!');
    return redirect('no_master_soal');
  }

  public function tipe_test()
  {
    $tingkat = Tingkat::all();

    $tipe = Tipe_test::leftjoin('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tipe_test.status', 'ACTIVE')
      ->select('tipe_test.ket', 'tipe_test.id_tingkat', 'tipe_test.id_tipetest', 'tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tipe_test.setup_timer', 'tipe_test.menit')
      ->orderBy('tipe_test.id_tipetest', 'DESC')
      ->get();

    return view('admin/soal/tipe_test', ['tipe' => $tipe, 'tk' => $tingkat]);
  }

  public function post_tipetest(Request $request)
  {
    $this->validate($request, [
      'tipe_test'    => 'required',
      'id_tingkat'   => 'required',
    ]);

    $thn                 = new Tipe_test;
    $thn->tipe_test      = $request->tipe_test;
    $thn->ket                = $request->ket;
    $thn->id_tingkat     = $request->id_tingkat;
    $thn->setup_timer    = $request->setup_timer;
    $thn->menit          = $request->menit;
    $thn->created_by     = Auth::user()->name;
    $thn->save();


    Session::flash('sukses', 'Data Tipe Test Berhasil Disimpan!');
    return redirect('tipe_test');
  }

  public function updatetipetest(Request $request, $id)
  {
    $thn                     = Tipe_test::find($id);
    $thn->tipe_test          = $request->tipe_test;
    $thn->ket                = $request->ket;
    $thn->id_tingkat         = $request->id_tingkat;
    $thn->setup_timer        = $request->setup_timer;
    $thn->menit              = $request->menit;
    $thn->updated_by         = Auth::user()->name;
    $thn->save();

    Session::flash('sukses', 'Data Tipe Test Berhasil Diupdate!');
    return redirect('tipe_test');
  }

  public function destroytipetest(Request $request)
  {
    $akun = Tipe_test::where('id_tipetest', $request->id_tipetest)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Tipe Test Berhasil Dihapus!');
    return redirect('tipe_test');
  }

  public function soal()
  {
    $tingkat = Tingkat::where('status', 'ACTIVE')->get();
    $nmr = No_master_soal::where('status', 'ACTIVE')->get();
    $tipetest = Tipe_test::join('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tipe_test.status', 'ACTIVE')
      ->select('tipe_test.id_tipetest', 'tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tingkat.id_tingkat')
      ->get();

    $soal = Soal::leftjoin('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->leftjoin('tipe_test', 'tb_soal.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->leftjoin('tingkat', 'tb_soal.id_tingkat', '=', 'tingkat.id_tingkat')
      ->leftjoin('tb_jawaban', 'tb_jawaban.id_soal', '=', 'tb_soal.id_soal')
      ->where('tb_soal.status', 'ACTIVE')
      ->select(DB::raw('DISTINCT(tb_jawaban.id_soal) as ids'), 'tb_soal.gambar', 'tb_soal.id_soal', 'tb_soal.id_nomormaster', 'tb_soal.id_tipetest', 'tb_soal.id_tingkat', 'nomor_master_soal.nomor_master_soal', 'tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tb_soal.nomor', 'tb_soal.soal')
      ->orderBy('tb_soal.id_soal', 'DESC')
      ->get();

    $nmor = No_master_soal::where('status', 'ACTIVE')->get();
    $tptest = Tipe_test::join('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tipe_test.status', 'ACTIVE')
      ->select('tipe_test.id_tipetest', 'tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tingkat.id_tingkat')
      ->get();

    return view('admin/soal/soal', ['tipe' => $tipetest, 'nmr' => $nmr, 'tk' => $tingkat, 'soal' => $soal, 'nmor' => $nmor, 'tpts' => $tptest]);
  }

  public function post_soal(Request $request)
  {
    $tkt = Tipe_test::where('id_tipetest', $request->id_tipetest)
      ->get();
    foreach ($tkt as $key) {
      // code...
    }

    $message = [
      'max'       => ':attribute harus diisi maksimal :max karakter',
      'min'       => ':attribute harus diisi minimal :min karakter',
      'required'  => ':attribute wajib diisi',
    ];

    $this->validate($request, [
      'id_nomormaster'    => 'required',
      'id_tipetest'       => 'required',
      'nomor'             => 'required',
      'gambar'            => 'mimes:jpeg,jpg,png',
    ]);


    //$path = $request->file('gambar')->store('public/Soal_gambar');

    $thn                 = new Soal;
    $thn->id_nomormaster = $request->id_nomormaster;
    $thn->id_tipetest    = $request->id_tipetest;
    $thn->id_tingkat     = $key->id_tingkat;
    $thn->nomor          = $request->nomor;
    $thn->soal           = $request->soal;
    $thn->created_by     = Auth::user()->name;
    //$thn->gambar         = $path;

    if ($request->hasFile('gambar')) {
      $file             = $request->file('gambar');
      $nama_file        = time() . "_" . $file->getClientOriginalName();
      $tujuan_upload    = 'Soal_gambar';
      $file->move($tujuan_upload, $nama_file);
      $thn->gambar      = $nama_file;
    }

    $thn->save();

    Session::flash('sukses', 'Data Soal Berhasil Disimpan!');
    return redirect('soal');
  }

  public function updatesoal(Request $request, $id)
  {
    $tkt = Tipe_test::where('id_tipetest', $request->id_tipetest)
      ->get();
    foreach ($tkt as $key) {
      // code...
    }

    $thn                    = Soal::find($id);
    $thn->id_nomormaster    = $request->id_nomormaster;
    $thn->id_tipetest       = $request->id_tipetest;
    $thn->id_tingkat        = $key->id_tingkat;
    $thn->nomor             = $request->nomor;
    $thn->soal              = $request->soal;
    $thn->updated_by        = Auth::user()->name;

    // if($request->hasFile('gambar')){
    //
    //     $request->validate([
    //       'gambar' => 'mimes:jpeg,jpg,png|max:1024',
    //     ]);
    //
    //     Storage::delete($thn->gambar);
    //     $path = $request->file('gambar')->store('public/Soal_gambar');
    //
    //     $thn->gambar = $path;
    // }


    if ($thn->gambar) {

      if ($request->hasFile('gambar')) {

        File::delete('Soal_gambar/' . $thn->gambar);

        $file = $request->file('gambar');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        $tujuan_upload = 'Soal_gambar';
        $file->move($tujuan_upload, $nama_file);
        $thn->gambar        = $nama_file;
      }
    } else {
      if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        $tujuan_upload = 'Soal_gambar';
        $file->move($tujuan_upload, $nama_file);
        $thn->gambar        = $nama_file;
      }
    }
    $thn->save();

    Session::flash('sukses', 'Data Soal Berhasil Diupdate!');
    return redirect('soal');
  }

  public function destroysoal(Request $request)
  {
    $akun = Soal::where('id_soal', $request->id_soal)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('hapus', 'Data Soal Berhasil Dihapus!');
    return redirect('soal');
  }

  //input jawaban
  public function savejwb(Request $request)
  {
    $idsoal = $request->id_soal;
    $bs = $request->b_s;
    $opsi = $request->opsi;
    $gambar = $request->jawab_gambar;

    $jmlopsi = count($opsi);
    $jmlbenar = count($bs);

    for ($i = 0; $i < $jmlopsi; $i++) {
      $arrayopsi = $request->opsi[$i];

      $filegambar = $request->jawab_gambar[$i];
      $filename =  time() . rand(1, 100) . '-' . $filegambar->getClientOriginalName();
      $tujuan_upload    = 'Jawaban_gambar';
      $filegambar->move($tujuan_upload, $filename);

      $sl = new Jawaban();
      $sl->id_soal        = $idsoal;
      $sl->opsi           = $arrayopsi;
      $sl->jawab_gambar   = $filename;
      $sl->created_by     = Auth::user()->name;
      $sl->save();
    }

    for ($i = 0; $i < $jmlbenar; $i++) {
      $abs    = $request->b_s[$i];
      $idab   = explode(',', $abs, 2);
      $trsen  = $idab[0];
      $trsi   = $idab[1];

      Jawaban::where('id_soal', $idsoal)
        ->where('opsi', $trsen)
        ->update([
          'b_s' => 'B',
          'created_by' => Auth::user()->name
        ]);
    }

    Session::flash('sukses', 'Data Jawaban Berhasil Dimasukan!');
    return redirect('soal');
  }

  public function jawaban()
  {
    $bank = No_master_soal::where('status', 'ACTIVE')->get();

    $tes = Tipe_test::join('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tipe_test.status', 'ACTIVE')
      ->select('tipe_test.id_tipetest', 'tipe_test.tipe_test', 'tingkat.nama_tingkat')
      ->get();

    $jwbn = Jawaban::join('tb_soal', 'tb_jawaban.id_soal', '=', 'tb_soal.id_soal')
      ->join('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->join('tipe_test', 'tb_soal.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'tb_soal.id_tingkat', '=', 'tingkat.id_tingkat')

      ->where('tb_jawaban.status', 'ACTIVE')
      ->select('tb_jawaban.jawab_gambar', 'tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tb_soal.id_soal', 'tb_jawaban.id_jawaban', 'tb_soal.soal', 'nomor_master_soal.nomor_master_soal', 'tb_jawaban.opsi', 'tb_jawaban.jawaban', 'tb_jawaban.b_s')
      ->get();

    return view('admin/soal/jawaban', ['jawaban' => $bank, 'jwbn' => $jwbn, 'tes' => $tes]);
  }

  public function pilih_master(Request $request)
  {
    $idbank = $request->id_nomormaster;

    $idtes = $request->id_tipetest;

    $soal = Soal::join('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->join('tipe_test', 'tb_soal.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'tb_soal.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tb_soal.id_nomormaster', $idbank)
      ->where('tb_soal.id_tipetest', $idtes)
      ->where('tb_soal.status', 'ACTIVE')
      ->select('tb_soal.gambar', 'tb_soal.soal', 'tb_soal.id_soal', 'nomor_master_soal.nomor_master_soal', 'tingkat.nama_tingkat', 'tipe_test.tipe_test')
      ->get();

    if (count($soal) > 0) {

      foreach ($soal as $keys) {
        // code...
      }

      return view('admin/soal/get_jawaban', compact('soal', 'idbank', 'keys'));
    } else {

      Session::flash('hapus', 'Soal belum ada');
      return redirect()->back();
    }
  }

  public function save_jawaban(Request $request)
  {
    $opsi = $request->opsi;
    $jawaban = $request->jawaban;
    $benar = $request->b_s;

    $jmlopsi = count($opsi);
    $jmlbnr = count($benar);

    for ($i = 0; $i < $jmlopsi; $i++) {
      $arrayopsi = $request->opsi[$i];
      $pisah   = explode(',', $arrayopsi, 2);
      $row1   = $pisah[0];
      $row2   = $pisah[1];

      $arrayjwbn = $request->jawaban[$i];
      $idr = explode(',', $arrayjwbn);
      $tra = $idr[0];

      $sl = new Jawaban;
      $sl->id_soal        = $row1;
      $sl->opsi           = $row2;
      $sl->jawaban        = $tra;

      $sl->created_by     = Auth::user()->name;
      $sl->save();
    }

    for ($i = 0; $i < $jmlbnr; $i++) {
      $abs    = $request->b_s[$i];
      $idab   = explode(',', $abs, 3);
      $trsen  = $idab[0];
      $trsi   = $idab[1];
      $trsa   = $idab[2];

      Jawaban::where('id_soal', $trsen)
        ->where('opsi', $trsi)
        ->update([
          'b_s' => 'B',
          'created_by' => Auth::user()->name
        ]);
    }
    return redirect('jawaban');
  }

  public function jawaban_gambar()
  {
    $bank = No_master_soal::where('status', 'ACTIVE')->get();

    $tes = Tipe_test::join('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tipe_test.status', 'ACTIVE')
      ->select('tipe_test.id_tipetest', 'tipe_test.tipe_test', 'tingkat.nama_tingkat')
      ->get();

    $jwbn = Jawaban::join('tb_soal', 'tb_jawaban.id_soal', '=', 'tb_soal.id_soal')
      ->join('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->join('tipe_test', 'tb_soal.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'tb_soal.id_tingkat', '=', 'tingkat.id_tingkat')

      ->where('tb_jawaban.status', 'ACTIVE')
      ->select('tb_jawaban.jawab_gambar', 'tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tb_soal.id_soal', 'tb_jawaban.id_jawaban', 'tb_soal.soal', 'nomor_master_soal.nomor_master_soal', 'tb_jawaban.opsi', 'tb_jawaban.jawaban', 'tb_jawaban.b_s')
      ->get();

    return view('admin/soal/jawaban_gambar', ['jawaban' => $bank, 'jwbn' => $jwbn, 'tes' => $tes]);
  }

  public function pilih_master_gambar(Request $request)
  {
    $idbank = $request->id_nomormaster;

    $idtes = $request->id_tipetest;

    $soal = Soal::join('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->join('tipe_test', 'tb_soal.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'tb_soal.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tb_soal.id_nomormaster', $idbank)
      ->where('tb_soal.id_tipetest', $idtes)
      ->where('tb_soal.status', 'ACTIVE')
      ->select('tb_soal.gambar', 'tb_soal.soal', 'tb_soal.id_soal', 'nomor_master_soal.nomor_master_soal', 'tingkat.nama_tingkat', 'tipe_test.tipe_test')
      ->get();

    if (count($soal) > 0) {

      foreach ($soal as $keys) {
        // code...
      }

      return view('admin/soal/get_jawaban_gambar', compact('soal', 'idbank', 'keys'));
    } else {

      Session::flash('hapus', 'Soal belum ada');
      return redirect()->back();
    }
  }

  public function save_jawaban_gambar(Request $request)
  {
    $this->validate($request, [
      'jawab_gambar' => 'required',
      'jawab_gambar.*' => 'image'
    ]);

    $opsi = $request->opsi;

    $benar = $request->b_s;

    $gambar = $request->jawab_gambar;

    $jmlopsi = count($opsi);

    $jmlbnr = count($benar);

    for ($i = 0; $i < $jmlopsi; $i++) {
      $arrayopsi = $request->opsi[$i];

      $pisah   = explode(',', $arrayopsi, 2);

      $row1   = $pisah[0];
      $row2   = $pisah[1];

      $filegambar = $request->jawab_gambar[$i];

      $filename =  time() . rand(1, 100) . '-' . $filegambar->getClientOriginalName();
      $tujuan_upload    = 'Jawaban_gambar';
      $filegambar->move($tujuan_upload, $filename);


      $sl = new Jawaban();
      $sl->id_soal        = $row1;
      $sl->opsi           = $row2;
      $sl->jawab_gambar   = $filename;
      $sl->created_by     = Auth::user()->name;
      $sl->save();
    }

    for ($i = 0; $i < $jmlbnr; $i++) {
      $abs    = $request->b_s[$i];
      $idab   = explode(',', $abs, 3);
      $trsen  = $idab[0];
      $trsi   = $idab[1];
      $trsa   = $idab[2];

      Jawaban::where('id_soal', $trsen)
        ->where('opsi', $trsi)
        ->update([
          'b_s' => 'B',
          'created_by' => Auth::user()->name
        ]);
    }
    return redirect('jawab_gambar');
  }

  public function post_jawaban(Request $request)
  {

    $this->validate($request, [
      'id_soal'    => 'required',
      'opsi'       => 'required',
      'jawaban'    => 'required',
      'b_s'        => 'required',
    ]);

    $thn                 = new Jawaban;
    $thn->id_soal        = $request->id_soal;
    $thn->opsi           = $request->opsi;
    $thn->jawaban        = $request->jawaban;
    $thn->b_s            = $request->b_s;
    $thn->created_by     = Auth::user()->name;
    $thn->save();

    $idbank = $request->id_nomormaster;

    $jwbn = Jawaban::join('tb_soal', 'tb_jawaban.id_soal', '=', 'tb_soal.id_soal')
      ->join('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->join('tipe_test', 'tb_soal.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'tb_soal.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tb_soal.id_nomormaster', $idbank)
      ->where('tb_jawaban.status', 'ACTIVE')
      ->select('nomor_master_soal.id_nomormaster', 'tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tb_soal.id_soal', 'tb_jawaban.id_jawaban', 'tb_soal.soal', 'nomor_master_soal.nomor_master_soal', 'tb_jawaban.opsi', 'tb_jawaban.jawaban', 'tb_jawaban.b_s')
      ->orderBy('tb_jawaban.id_jawaban', 'DESC')
      ->get();

    $soal = Soal::join('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->where('tb_soal.id_nomormaster', $idbank)
      ->where('tb_soal.status', 'ACTIVE')
      ->select('tb_soal.soal', 'tb_soal.id_soal')
      ->get();

    $soal1 = Soal::join('nomor_master_soal', 'tb_soal.id_nomormaster', '=', 'nomor_master_soal.id_nomormaster')
      ->where('tb_soal.id_nomormaster', $idbank)
      ->where('tb_soal.status', 'ACTIVE')
      ->select('tb_soal.soal', 'tb_soal.id_soal')
      ->get();

    Session::flash('sukses', 'Data Jawaban Berhasil Disimpan!');
    return view('admin/soal/get_jawaban', ['jwbn' => $jwbn, 'idbnk' => $idbank, 'soal' => $soal, 'soal1' => $soal1]);
  }

  public function updatejawaban(Request $request, $id)
  {
    $thn                 = Jawaban::find($id);
    $thn->jawaban        = $request->jawaban;
    $thn->b_s            = $request->b_s;
    $thn->updated_by     = Auth::user()->name;

    if ($request->hasFile('jawab_gambar')) {

      $request->validate([
        'jawab_gambar' => 'mimes:jpeg,jpg,png|max:1024',
      ]);

      unlink('Jawaban_gambar/' . $thn->jawab_gambar);
      $file = $request->file('jawab_gambar');
      $name = time() . rand(1, 100) . '.' . $file->getClientOriginalName();
      $tujuan_upload    = 'Jawaban_gambar';
      $file->move($tujuan_upload, $name);

      $thn->jawab_gambar = $name;
    }

    $thn->save();

    Session::flash('sukses', 'Data Jawaban Berhasil Diedit!');
    return redirect('jawaban');
  }

  public function destroyjawaban(Request $request)
  {
    $akun = Jawaban::where('id_jawaban', $request->id_jawaban)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('sukses', 'Data Jawaban Berhasil Dihapus!');
    return redirect('jawaban');
  }

  //tentukan peserta test
  public function select_test()
  {
    $skl = Sekolah::where('status', 'ACTIVE')->get();

    $data = Pesertatest_record::join('sekolah', 'testsekolah_record.id_sekolah', '=', 'sekolah.id_sekolah')
      ->join('tipe_test', 'testsekolah_record.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('testsekolah_record.status', 'ACTIVE')
      ->select('testsekolah_record.id_testrecord', 'sekolah.nama_sekolah', 'tipe_test.tipe_test', 'tingkat.nama_tingkat')
      ->get();

    return view('admin/record_test/pilih', compact('skl', 'data'));
  }

  public function add_select(Request $request)
  {

    $idtes = $request->id_sekolah;

    $cek = Pesertatest_record::where('id_sekolah', $idtes)->get();

    $hasil = Pesertatest_record::leftJoin('tipe_test', 'testsekolah_record.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->where('testsekolah_record.id_sekolah', $idtes)
      ->where('testsekolah_record.status', 'ACTIVE')
      ->get();

    $skl = Sekolah::where('id_sekolah', $idtes)->get();
    foreach ($skl as $key) {
      // code...
    }
    $nama = $key->nama_sekolah;

    $tkt = Tipe_test::join('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tipe_test.status', 'ACTIVE')
      ->select('tipe_test.tipe_test', 'tipe_test.id_tipetest', 'tingkat.nama_tingkat')
      ->get();

    return view('admin/record_test/pilih_test', compact('tkt', 'idtes', 'nama'));
  }

  public function save_selected(Request $request)
  {

    $jml_pes = count($request->id_tipetest);

    for ($i = 0; $i < $jml_pes; $i++) {
      $pes = $request->id_tipetest[$i];

      $test                 = new Pesertatest_record;
      $test->id_tipetest    = $pes;
      $test->id_sekolah     = $request->id_sekolah;
      $test->created_by     = Auth::user()->name;
      $test->save();
    }

    Session::flash('sukses', 'Data Peserta Berhasil Disimpan!');
    return redirect('select_test');
  }

  public function destroytestrecord(Request $request)
  {
    $akun = Pesertatest_record::where('id_testrecord', $request->id_testrecord)
      ->update(['status' => 'NOT ACTIVE']);

    Session::flash('sukses', 'Data Jawaban Berhasil Dihapus!');
    return redirect('select_test');
  }

  public function result_test()
  {
    $filter = Peserta_recordtest::join('peserta', 'peserta_recordtest.id_peserta', '=', 'peserta.id_peserta')
      ->join('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_sekolah')
      ->join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      // ->select(DB::raw('DISTINCT(peserta.tgl_test)'), 'peserta.id_tingkat', 'tingkat.nama_tingkat')
      ->select(DB::raw('DISTINCT(peserta.tgl_test)'), 'peserta.kls_tujuan')
      ->orderBy('peserta.tgl_test', 'ASC')
      ->get();

    $hasil = Peserta_recordtest::join('peserta', 'peserta_recordtest.id_peserta', '=', 'peserta.id_peserta')
      ->join('sekolah', 'peserta.id_sekolah', '=', 'sekolah.id_Sekolah')
      ->join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->select(DB::raw('DISTINCT(peserta.id_peserta)'), 'peserta.nama_peserta', 'sekolah.nama_sekolah', 'peserta.tgl_test', 'tingkat.nama_tingkat', 'peserta.kls_tujuan')
      ->orderBy('peserta.id_peserta', 'DESC')
      ->get();

    return view('admin/hasil/data', compact('hasil', 'filter'));
  }

  public function filter_export_hasil(Request $request)
  {

    $hsl = $request->tgl_test;

    $bagi = explode(',', $hsl, 2);

    $tgl = $bagi[0];
    $tkt = $bagi[1];

    $datapeserta = Jawaban_peserta::join('peserta', 'tb_jawabanpeserta.id_peserta', '=', 'peserta.id_peserta')
      ->join('tipe_test', 'tb_jawabanpeserta.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'peserta.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('peserta.tgl_test', $tgl)
      ->where('peserta.kls_tujuan', $tkt)
      // ->where('peserta.id_tingkat', $tkt)
      ->select(DB::raw('DISTINCT(tipe_test.tipe_test)'), 'peserta.kls_tujuan', 'peserta.nama_peserta', 'peserta.tgl_test', 'tingkat.nama_tingkat', 'peserta.id_peserta', 'tipe_test.id_tipetest')
      ->get();

    $bnr = Jawaban_peserta::join('peserta', 'tb_jawabanpeserta.id_peserta', '=', 'peserta.id_peserta')
      ->where('peserta.tgl_test', $tgl)
      ->where('peserta.kls_tujuan', $tkt)
      // ->where('peserta.id_tingkat', $tkt)
      ->where('tb_jawabanpeserta.ket_bs', 'B')
      ->select('tb_jawabanpeserta.id_peserta', 'tb_jawabanpeserta.id_tipetest', DB::raw('COUNT(tb_jawabanpeserta.ket_bs) as jml_benar'))
      ->groupBy('tb_jawabanpeserta.id_peserta', 'tb_jawabanpeserta.id_tipetest')
      ->get();

    $slh = Jawaban_peserta::join('peserta', 'tb_jawabanpeserta.id_peserta', '=', 'peserta.id_peserta')
      ->where('peserta.tgl_test', $tgl)
      ->where('peserta.kls_tujuan', $tkt)
      // ->where('peserta.id_tingkat', $tkt)
      ->where('tb_jawabanpeserta.ket_bs', 'S')
      ->select('tb_jawabanpeserta.id_peserta', 'tb_jawabanpeserta.id_tipetest', DB::raw('COUNT(tb_jawabanpeserta.ket_bs) as jml_salah'))
      ->groupBy('tb_jawabanpeserta.id_peserta', 'tb_jawabanpeserta.id_tipetest')
      ->get();

    $soal = Soal::select(DB::raw('COUNT(tb_soal.id_tipetest) as jml_soal'), 'tb_soal.id_tipetest')
      ->where('tb_soal.status', 'ACTIVE')
      ->groupBy('tb_soal.id_tipetest')
      ->get();

    return view('admin/hasil/filter_hasil', compact('datapeserta', 'soal', 'bnr', 'slh', 'tgl', 'tkt'));
  }

  public function export_hasil(Request $request)
  {
    $tgl = $request->tgl_test;
    $tkt = $request->kls_tujuan;

    $nm_tkt = Tingkat::where('id_tingkat', $tkt)
      ->select('nama_tingkat')
      ->first();
    $ganti = str_replace('/', '-', $tgl);

    $nama_file = 'Data Hasil Test ' . $ganti . ' ' . $tkt . '.xlsx';
    return Excel::download(new HasilExport($tgl, $tkt), $nama_file);
  }

  public function cek_ujian($id)
  {
    $hsl = Jawaban_peserta::join('tipe_test', 'tb_jawabanpeserta.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tingkat', 'tipe_test.id_tingkat', '=', 'tingkat.id_tingkat')
      ->where('tb_jawabanpeserta.id_peserta', $id)
      ->select('tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tb_jawabanpeserta.id_tipetest')
      ->groupBy('tipe_test.tipe_test', 'tingkat.nama_tingkat', 'tb_jawabanpeserta.id_tipetest')
      ->get();

    $benar = Jawaban_peserta::where('tb_jawabanpeserta.ket_bs', 'B')
      ->where('tb_jawabanpeserta.id_peserta', $id)
      ->select(DB::raw('COUNT(tb_jawabanpeserta.ket_bs) as jml_benar'), 'tb_jawabanpeserta.id_tipetest')
      ->groupBy('tb_jawabanpeserta.id_tipetest')
      ->get();

    $salah = Jawaban_peserta::where('tb_jawabanpeserta.ket_bs', 'S')
      ->where('tb_jawabanpeserta.id_peserta', $id)
      ->select(DB::raw('COUNT(tb_jawabanpeserta.ket_bs) as jml_salah'), 'tb_jawabanpeserta.id_tipetest')
      ->groupBy('tb_jawabanpeserta.id_tipetest')
      ->get();

    $soal = Soal::select(DB::raw('COUNT(tb_soal.id_tipetest) as jml_soal'), 'tb_soal.id_tipetest')
      ->where('tb_soal.status', 'ACTIVE')
      ->groupBy('tb_soal.id_tipetest')
      ->get();

    //hasil jawaban
    $jawab = Jawaban_peserta::join('tipe_test', 'tb_jawabanpeserta.id_tipetest', '=', 'tipe_test.id_tipetest')
      ->join('tb_soal', 'tb_jawabanpeserta.id_soal', '=', 'tb_soal.id_soal')
      ->join('tb_jawaban', 'tb_jawabanpeserta.id_jawaban', '=', 'tb_jawaban.id_jawaban')
      ->where('tb_jawabanpeserta.id_peserta', $id)
      ->where('tb_soal.status', 'ACTIVE')
      ->select('tb_soal.soal', 'tb_jawaban.opsi', 'tb_jawaban.jawaban', 'tb_jawaban.b_s', 'tb_jawabanpeserta.id_tipetest', 'tipe_test.tipe_test')
      ->get();


    return view('admin/hasil/cek_ujian', compact('salah', 'benar', 'soal', 'jawab', 'id', 'hsl'));
  }

  public function exportlistujian($id)
  {
    $nama = Peserta::where('id_peserta', $id)
      ->select('nama_peserta')
      ->first();

    $nm = $nama->nama_peserta;

    $nama_file = 'Data List Jawaban ' . $nm . '.xlsx';
    return Excel::download(new ListJawabanExport($id), $nama_file);
  }

  public function exporthasiljawaban($id)
  {
    $nama = Peserta::where('id_peserta', $id)
      ->select('nama_peserta')
      ->get();

    foreach ($nama as $keyps) {
      // code...
    }

    $nm = $keyps->nama_peserta;

    $nama_file = 'Data Hasil Jawaban ' . $nm . '.xlsx';
    return Excel::download(new HasilJawabanExport($id), $nama_file);
  }
}
