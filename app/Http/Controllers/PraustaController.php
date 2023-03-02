<?php

namespace App\Http\Controllers;

use PDF;
use File;
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
use App\Kuliah_nilaihuruf;
use App\Ruangan;
use App\Student_record;
use App\Kurikulum_jam;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use App\Prausta_trans_bimbingan;
use App\Prausta_master_kategori;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PraustaController extends Controller
{
    public function nilai_prausta()
    {
        $list = Prausta_master_kode::join('prodi', 'prausta_master_kode.id_prodi', '=', 'prodi.id_prodi')
            ->whereNotIn('prausta_master_kode.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->select('prodi.id_prodi', 'prodi.prodi', 'prodi.konsentrasi', 'prausta_master_kode.id_masterkode_prausta', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta')
            ->orderBy('prodi.id_prodi', 'ASC')
            ->get();

        $listprausta = Prausta_master_kode::whereNotIn('id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->orderBy('kode_prausta', 'ASC')
            ->get();

        $prodi = Prodi::all();

        $angkatan = Angkatan::whereIn('idangkatan', [16, 17, 18, 19, 20, 21, 22])->get();

        return view('prausta.nilai_prausta', compact('listprausta', 'prodi', 'angkatan', 'list'));
    }

    public function kode_prausta(Request $request)
    {
        $kd_prausta = $request->id_masterkode_prausta;
        $id_angkatan = $request->idangkatan;

        $data_kode = Prausta_master_kode::join('prodi', 'prausta_master_kode.id_prodi', '=', 'prodi.id_prodi')
            ->join('matakuliah', 'prausta_master_kode.kode_prausta', '=', 'matakuliah.kode')
            ->where('prausta_master_kode.id_masterkode_prausta', $kd_prausta)
            ->select('prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prodi.kodeprodi', 'prodi.kodekonsentrasi', 'matakuliah.idmakul', 'matakuliah.kode', 'prausta_master_kode.id_masterkode_prausta')
            ->first();

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($leftjoin) {
                $leftjoin->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('student_record', 'student.idstudent', '=', 'student_record.id_student')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_masterkode_prausta', $data_kode->id_masterkode_prausta)
            ->where('student.idangkatan', $id_angkatan)
            ->where('kurikulum_periode.id_makul', $data_kode->idmakul)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('student.active', 1)
            ->select(
                'student.idstudent',
                'student.nim',
                'student.nama',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'student_record.id_studentrecord',
                'student_record.nilai_AKHIR',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->get();

        $kode_prausta = $data_kode->kode_prausta;
        $nama_prausta = $data_kode->nama_prausta;
        $prodi = $data_kode->prodi;
        $angkatan = Angkatan::where('idangkatan', $id_angkatan)->first();
        $nama_angkatan = $angkatan->angkatan;

        $cekdata = count($data);

        if ($cekdata > 0) {
            return view('prausta/form_nilai_prausta', compact('data', 'kode_prausta', 'nama_prausta', 'prodi', 'nama_angkatan'));
        } elseif ($cekdata == 0) {
            Alert::error('maaf mahasiswa tersebut belum ada', 'MAAF !!');
            return redirect()->back();
        }
    }

    public function save_nilai_prausta(Request $request)
    {
        $jml = count($request->nilai_AKHIR);

        for ($i = 0; $i < $jml; $i++) {
            $nilai = $request->nilai_AKHIR[$i];
            $nilaiusta = explode(',', $nilai, 2);
            $ids = $nilaiusta[0];
            $nsta = $nilaiusta[1];

            $angka = Kuliah_nilaihuruf::where('nilai_huruf', $nsta)
                ->where('status', 'ACTIVE')
                ->select('nilai_indeks')
                ->first();

            Student_record::where('id_studentrecord', $ids)->update([
                'nilai_AKHIR' => $nsta,
                'nilai_ANGKA' => $angka->nilai_indeks
            ]);
        }
        Alert::success('', 'Niali berhasil diinput')->autoclose(3500);
        return redirect('nilai_prausta');
    }

    public function seminar_prakerin()
    {
        $id = Auth::user()->id_user;

        $dt_mhs = Student::where('idstudent', $id)->first();
        $angkatan = $dt_mhs->idangkatan;

        if ($angkatan <= 19) {
            $kodemk = 'FA-601';
        } elseif ($angkatan > 19) {
            $kodemk = 'FA-5001';
        }

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', [$kodemk, 'TI-601', 'TK-601', 'TI-5001'])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Kerja Praktek/Prakerin', 'MAAF !!');
            return redirect('home');
        } elseif ($hasil_krs > 0) {
            //data prakerin
            $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select(
                    'prausta_setting_relasi.file_laporan_revisi',
                    'prausta_setting_relasi.file_draft_laporan',
                    'prausta_setting_relasi.jam_selesai_sidang',
                    'prausta_setting_relasi.jam_mulai_sidang',
                    'prausta_setting_relasi.ruangan',
                    'prausta_setting_relasi.tanggal_selesai',
                    'prausta_setting_relasi.dosen_penguji_1',
                    'prausta_setting_relasi.tanggal_mulai',
                    'prausta_master_kategori.kategori',
                    'prausta_setting_relasi.acc_seminar_sidang',
                    'prausta_setting_relasi.id_settingrelasi_prausta',
                    'student.nama',
                    'student.nim',
                    'prausta_master_kode.kode_prausta',
                    'prausta_master_kode.nama_prausta',
                    'prodi.prodi',
                    'prausta_setting_relasi.dosen_pembimbing',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tempat_prausta',
                    'prausta_setting_relasi.tempat_prausta2',
                    'prausta_setting_relasi.file_draft_laporan',
                    'prausta_setting_relasi.validasi_baak',
                    'prausta_setting_relasi.total_uang_saku',
                    'student.kodeprodi'
                )
                ->get();

            $cekdata = count($data);

            // data untuk keuangan
            $maha = Student::where('idstudent', $id)
                ->select('student.idstudent', 'student.nama', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
                ->first();

            $idangkatan = $maha->idangkatan;
            $idstatus = $maha->idstatus;
            $kodeprodi = $maha->kodeprodi;

            $cek_study = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->where('student.idstudent', $id)
                ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
                ->first();

            $biaya = Biaya::where('idangkatan', $idangkatan)
                ->where('idstatus', $idstatus)
                ->where('kodeprodi', $kodeprodi)
                ->select('spp5', 'spp6')
                ->first();

            //cek beasiswa mahasiswa
            $cb = Beasiswa::where('idstudent', $id)->first();

            if ($cek_study->study_year == 3) {
                $biaya_spp = $biaya->spp5;

                if (($cb) != null) {

                    $spp = $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100;
                } elseif (($cb) == null) {

                    $spp = $biaya->spp5;
                }

                $sisaspp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                    ->where('kuitansi.idstudent', $id)
                    ->where('bayar.iditem', 8)
                    ->sum('bayar.bayar');
            } elseif ($cek_study->study_year == 4) {
                $biaya_spp = $biaya->spp6;

                if (($cb) != null) {

                    $spp = $biaya->spp6 - ($biaya->spp6 * $cb->spp6) / 100;
                } elseif (($cb) == null) {

                    $spp = $biaya->spp6;
                }

                $sisaspp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                    ->where('kuitansi.idstudent', $id)
                    ->where('bayar.iditem', 26)
                    ->sum('bayar.bayar');
            }

            $hasil_spp = $sisaspp - $spp;

            if ($hasil_spp == 0 or $hasil_spp > 0) {
                $validasi = 'Sudah Lunas';
            } elseif ($hasil_spp < 0) {
                $validasi = 'Belum Lunas';
            }

            $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select(
                    'prausta_trans_bimbingan.id_transbimb_prausta',
                    'prausta_trans_bimbingan.file_bimbingan',
                    'prausta_trans_bimbingan.validasi',
                    'prausta_trans_bimbingan.tanggal_bimbingan',
                    'prausta_trans_bimbingan.remark_bimbingan',
                    'prausta_trans_bimbingan.komentar_bimbingan',
                    'prausta_trans_bimbingan.id_transbimb_prausta'
                )
                ->get();

            $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->count();

            $databimb = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->limit(1)
                ->orderByDesc('prausta_trans_bimbingan.id_transbimb_prausta')
                ->first();

            //cek nilai dan file seminar prakerin
            $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
                ->where('prausta_setting_relasi.id_student', $id)
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_setting_relasi.file_draft_laporan', 'prausta_trans_hasil.nilai_huruf', 'prausta_setting_relasi.file_laporan_revisi')
                ->first();

            if ($cekdata == 0) {
                Alert::error('Maaf dosen pembimbbing anda belum disetting untuk Kerja Praktek/Prakerin', 'MAAF !!');
                return redirect('home');
            } elseif ($cekdata > 0) {
                foreach ($data as $usta) {
                }

                return view('mhs/prausta/seminar_prakerin', compact('usta', 'cekdata', 'bim', 'validasi', 'jml_bim', 'databimb', 'hasil_spp', 'cekdata_nilai'));
            }
        }
    }

    public function put_prakerin(Request $request, $id)
    {
        $prd = Prausta_setting_relasi::find($id);
        $prd->judul_prausta = $request->judul_prausta;
        $prd->tempat_prausta = $request->tempat_prausta;
        $prd->tempat_prausta2 = $request->tempat_prausta2;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        Alert::success('', 'Data Prakerin Berhasil Diedit')->autoclose(3500);
        return redirect()->back();
    }

    public function pengajuan_seminar_prakerin($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_master_kategori.kategori',
                'prodi.id_prodi',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        $kategori = Prausta_master_kategori::join('prodi', 'prausta_master_kategori.id_prodi', '=', 'prodi.id_prodi')
            ->where('prodi.id_prodi', $data->id_prodi)->get();

        return view('mhs/prausta/ajuan_prakerin', compact('data', 'kategori'));
    }

    public function simpan_ajuan_prakerin(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'id_kategori_prausta' => 'required',
            'tanggal_mulai' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $judul = $request->judul_prausta;
        $judul_ok = str_replace("'", "", $judul);

        $tempat = $request->tempat_prausta;
        $tempat_ok = str_replace("'", "", $tempat);

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $judul_ok,
            'tempat_prausta' => $tempat_ok,
            'id_kategori_prausta' => $request->id_kategori_prausta,
            'tanggal_mulai' => $request->tanggal_mulai,
        ]);

        Alert::success('', 'Data Prakerin Berhasil Diinput')->autoclose(3500);
        return redirect('seminar_prakerin');
    }

    public function edit_ajuan_prakerin(Request $request, $id)
    {
        $this->validate($request, [
            'file_draft_laporan' => 'mimes:pdf|max:5000',
        ]);

        $bap = Prausta_setting_relasi::find($id);

        if ($bap->file_draft_laporan) {
            if ($request->hasFile('file_draft_laporan')) {
                File::delete('File Draft Laporan/' . Auth::user()->id_user . '/' . $bap->file_draft_laporan);
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_draft_laporan')) {
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        }

        $bap->save();

        return redirect('seminar_prakerin');
    }

    public function simpan_bimbingan(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,
            [
                'file_bimbingan' => 'file:pdf,PDF|max:4096',
            ],
            $message
        );

        $nama = Auth::user()->name;
        $nama_ok = str_replace("'", "", $nama);

        $bimbingan = $request->remark_bimbingan;
        $bim_ok = str_replace("'", "", $bimbingan);

        $usta = new Prausta_trans_bimbingan();
        $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
        $usta->tanggal_bimbingan = $request->tanggal_bimbingan;
        $usta->remark_bimbingan = $bim_ok;
        $usta->added_by = $nama_ok;
        $usta->status = 'ACTIVE';
        $usta->data_origin = 'eSIAM';

        if ($request->hasFile('file_bimbingan')) {
            $file = $request->file('file_bimbingan');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'File Bimbingan PKL/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $usta->file_bimbingan = $nama_file;
        }

        $usta->save();

        Alert::success('', 'Data Bimbingan Prakerin Berhasil Diinput')->autoclose(3500);
        return redirect('seminar_prakerin');
    }

    public function edit_bimbingan(Request $request, $id)
    {
        $sch = Prausta_trans_bimbingan::find($id);
        $sch->tanggal_bimbingan = $request->tanggal_bimbingan;
        $sch->remark_bimbingan = $request->remark_bimbingan;
        $sch->updated_by = Auth::user()->name;

        if ($sch->file_bimbingan) {
            if ($request->hasFile('file_bimbingan')) {

                File::delete('File Bimbingan PKL/' . Auth::user()->id_user . '/' . $sch->file_bimbingan);
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan PKL/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_bimbingan')) {
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan PKL/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        }

        $sch->save();

        Alert::success('', 'Data Bimbingan Prakerin Berhasil Diedit')->autoclose(3500);
        return redirect('seminar_prakerin');
    }

    public function ajukan_seminar_pkl(Request $request)
    {
        $this->validate($request, [
            'file_draft_laporan' => 'mimes:pdf|max:5000',
        ]);

        $date_now = date('Y-m-d');

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);
        $bap->acc_seminar_sidang = 'PENGAJUAN';
        $bap->tgl_pengajuan = $date_now;
        $bap->total_uang_saku = $request->total_uang_saku;

        if ($bap->file_draft_laporan) {
            if ($request->hasFile('file_draft_laporan')) {
                File::delete('File Draft Laporan/' . Auth::user()->id_user . '/' . $bap->file_draft_laporan);
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_draft_laporan')) {
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Draft Laporan Prakerin Berhasil upload')->autoclose(3500);
        return redirect('seminar_prakerin');
    }

    public function simpan_draft_prakerin(Request $request)
    {
        $this->validate($request, [
            'file_laporan_revisi' => 'mimes:pdf|max:6000',
        ]);

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);

        if ($bap->file_laporan_revisi) {
            if ($request->hasFile('file_laporan_revisi')) {
                File::delete('File Laporan Revisi/' . Auth::user()->id_user . '/' . $bap->file_laporan_revisi);
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        } else {
            if ($request->hasFile('file_laporan_revisi')) {
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect('seminar_prakerin');
    }

    public function seminar_proposal()
    {
        $id = Auth::user()->id_user;

        //cek id prausta
        $cekdata_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        if ($cekdata_prausta != null) {
            $idprausta = $cekdata_prausta->id_settingrelasi_prausta;
        }

        //cek krs tugas akhir
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-601', 'TI-602', 'TK-602', 'FA6003', 'TI-6001'])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        //cek nilai dan file seminar prakerin
        $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $id)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.file_draft_laporan', 'prausta_trans_hasil.nilai_huruf', 'prausta_setting_relasi.file_laporan_revisi')
            ->first();

        //cek pembimbing sempro
        $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing'
            )
            ->get();

        if ($hasil_krs == 0) {

            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('home');
        } elseif ($hasil_krs > 0) {

            // if (count($cekdata) == null) {

            //     Alert::error('Maaf Dosen Pembimbing Sempro anda belum di setting', 'MAAF !!');
            //     return redirect('home');
            // } elseif (count($cekdata) != null) {

            //     if ($cekdata_nilai == null) {
            //         Alert::error('Maaf anda belum melakukan Upload Laporan Prakerin', 'MAAF !!');
            //         return redirect('home');
            //     } elseif ($cekdata_nilai->file_draft_laporan != null) {

            //         if ($cekdata_nilai->nilai_huruf == null) {

            //             Alert::error('Maaf nilai Prakerin anda belum dientri oleh dosen', 'MAAF !!');
            //             return redirect('home');
            //         } elseif ($cekdata_nilai->nilai_huruf != null) {

            #data seminar proposal
            $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select(
                    'prausta_setting_relasi.file_laporan_revisi',
                    'prausta_setting_relasi.file_draft_laporan',
                    'prausta_setting_relasi.jam_selesai_sidang',
                    'prausta_setting_relasi.jam_mulai_sidang',
                    'prausta_setting_relasi.ruangan',
                    'prausta_setting_relasi.tanggal_selesai',
                    'prausta_setting_relasi.dosen_penguji_1',
                    'prausta_setting_relasi.dosen_penguji_2',
                    'prausta_setting_relasi.tanggal_mulai',
                    'prausta_master_kategori.kategori',
                    'prausta_setting_relasi.acc_seminar_sidang',
                    'prausta_setting_relasi.id_settingrelasi_prausta',
                    'student.nama',
                    'student.nim',
                    'prausta_master_kode.kode_prausta',
                    'prausta_master_kode.nama_prausta',
                    'prodi.prodi',
                    'prausta_setting_relasi.dosen_pembimbing',
                    'prausta_setting_relasi.judul_prausta',
                    'prausta_setting_relasi.tempat_prausta',
                    'prausta_setting_relasi.acc_judul_dospem',
                    'prausta_setting_relasi.acc_judul_kaprodi',
                    'prausta_setting_relasi.validasi_pembimbing',
                    'prausta_setting_relasi.validasi_penguji_1',
                    'prausta_setting_relasi.validasi_penguji_2',
                    'prausta_setting_relasi.validasi_baak'
                )
                ->first();

            // data untuk keuangan
            $maha = Student::where('idstudent', $id)
                ->select('student.idstudent', 'student.nama', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
                ->first();

            $idangkatan = $maha->idangkatan;
            $idstatus = $maha->idstatus;
            $kodeprodi = $maha->kodeprodi;

            $cek_study = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->where('student.idstudent', $id)
                ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
                ->first();

            $biaya = Biaya::where('idangkatan', $idangkatan)
                ->where('idstatus', $idstatus)
                ->where('kodeprodi', $kodeprodi)
                ->select('seminar')
                ->first();

            //cek beasiswa mahasiswa
            $cb = Beasiswa::where('idstudent', $id)->first();

            if (($cb) != null) {

                $seminar = $biaya->seminar - ($biaya->seminar * $cb->seminar) / 100;
            } elseif (($cb) == null) {

                $seminar = $biaya->seminar;
            }

            if ($cek_study->study_year == 3) {

                $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                    ->where('kuitansi.idstudent', $id)
                    ->where('bayar.iditem', 14)
                    ->sum('bayar.bayar');
            } elseif ($cek_study->study_year == 4) {

                $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                    ->where('kuitansi.idstudent', $id)
                    ->where('bayar.iditem', 37)
                    ->sum('bayar.bayar');
            }

            $hasil_seminar = $sisaseminar - $seminar;

            if ($hasil_seminar == 0 or $hasil_seminar > 0) {
                $validasi = 'Sudah Lunas';
            } else {
                $validasi = 'Belum Lunas';
            }

            $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
                ->where('prausta_setting_relasi.id_student', $id)
                //->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $idprausta)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select(
                    'prausta_trans_bimbingan.id_transbimb_prausta',
                    'prausta_trans_bimbingan.file_bimbingan',
                    'prausta_trans_bimbingan.validasi',
                    'prausta_trans_bimbingan.tanggal_bimbingan',
                    'prausta_trans_bimbingan.remark_bimbingan',
                    'prausta_trans_bimbingan.komentar_bimbingan',
                    'prausta_trans_bimbingan.id_transbimb_prausta',
                    'prausta_trans_bimbingan.id_settingrelasi_prausta'
                )
                ->get();

            $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->count();

            $databimb = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->limit(1)
                ->orderByDesc('prausta_trans_bimbingan.id_transbimb_prausta')
                ->first();

            //cek nilai dan file seminar prakerin
            $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
                ->where('prausta_setting_relasi.id_student', $id)
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_setting_relasi.file_draft_laporan', 'prausta_trans_hasil.nilai_huruf', 'prausta_setting_relasi.file_laporan_revisi')
                ->first();


            return view('mhs/prausta/seminar_proposal', compact('data', 'validasi', 'bim', 'jml_bim', 'databimb', 'hasil_seminar', 'cekdata_nilai'));
        }
        //         }
        //     }
        // }
    }

    public function put_proposal(Request $request, $id)
    {
        $prd = Prausta_setting_relasi::find($id);
        $prd->judul_prausta = $request->judul_prausta;
        $prd->tempat_prausta = $request->tempat_prausta;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        Alert::success('', 'Data Seminar Proposal Berhasil Diedit')->autoclose(3500);
        return redirect()->back();
    }

    public function pengajuan_seminar_proposal($id)
    {
        $idstudent = Auth::user()->id_user;
        $tgl_seminar_pkl = Prausta_setting_relasi::where('id_student', $idstudent)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
            ->select('prausta_setting_relasi.tanggal_selesai')
            ->first();

        $tgl1 = $tgl_seminar_pkl->tanggal_selesai; // pendefinisian tanggal awal
        $tgl_awal_sempro = date('Y-m-d', strtotime('+1 days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 1 hari


        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_master_kategori.kategori',
                'prodi.id_prodi',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        $kategori = Prausta_master_kategori::where('id_prodi', $data->id_prodi)->get();

        return view('mhs/prausta/ajuan_proposal', compact('data', 'kategori'));
    }

    public function simpan_ajuan_proposal(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'id_kategori_prausta' => 'required',
            'tanggal_mulai' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $judul = $request->judul_prausta;
        $judul_ok = str_replace("'", "", $judul);

        $tempat = $request->tempat_prausta;
        $tempat_ok = str_replace("'", "", $tempat);

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $judul_ok,
            'tempat_prausta' => $tempat_ok,
            'id_kategori_prausta' => $request->id_kategori_prausta,
            'tanggal_mulai' => $request->tanggal_mulai,
        ]);

        Alert::success('', 'Data Seminar Proposal Berhasil Diinput')->autoclose(3500);
        return redirect('seminar_proposal');
    }

    public function simpan_bimbingan_sempro(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,
            [
                'file_bimbingan' => 'file:pdf,PDF|max:4096',
            ],
            $message,
        );

        $bimbingan = $request->remark_bimbingan;
        $bim_ok = str_replace("'", "", $bimbingan);

        $usta = new Prausta_trans_bimbingan();
        $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
        $usta->tanggal_bimbingan = $request->tanggal_bimbingan;
        $usta->remark_bimbingan = $bim_ok;
        $usta->added_by = Auth::user()->name;
        $usta->status = 'ACTIVE';
        $usta->data_origin = 'eSIAM';

        if ($request->hasFile('file_bimbingan')) {
            $file = $request->file('file_bimbingan');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'File Bimbingan SEMPRO/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $usta->file_bimbingan = $nama_file;
        }

        $usta->save();

        Alert::success('', 'Data Bimbingan Seminar Proposal Berhasil Diinput')->autoclose(3500);
        return redirect('seminar_proposal');
    }

    public function edit_bimbingan_sempro(Request $request, $id)
    {
        $sch = Prausta_trans_bimbingan::find($id);
        $sch->tanggal_bimbingan = $request->tanggal_bimbingan;
        $sch->remark_bimbingan = $request->remark_bimbingan;
        $sch->updated_by = Auth::user()->name;
        if ($sch->file_bimbingan) {
            if ($request->hasFile('file_bimbingan')) {

                File::delete('File Bimbingan SEMPRO/' . Auth::user()->id_user . '/' . $sch->file_bimbingan);
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan SEMPRO/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_bimbingan')) {
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan SEMPRO/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        }
        $sch->save();

        Alert::success('', 'Data Bimbingan Seminar Proposal Berhasil Diedit')->autoclose(3500);
        return redirect('seminar_proposal');
    }

    public function ajukan_seminar_proposal(Request $request)
    {

        $this->validate($request, [
            'file_draft_laporan' => 'mimes:pdf|max:5000',
        ]);

        $date_now = date('Y-m-d');

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);
        $bap->acc_seminar_sidang = 'PENGAJUAN';
        $bap->tgl_pengajuan = $date_now;

        if ($bap->file_draft_laporan) {
            if ($request->hasFile('file_draft_laporan')) {
                File::delete('File Draft Laporan/' . Auth::user()->id_user . '/' . $bap->file_draft_laporan);
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_draft_laporan')) {
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Draft Laporan Sempro Berhasil upload')->autoclose(3500);
        return redirect('seminar_proposal');
    }

    public function ajukan_seminar_lagi($id)
    {
        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['acc_seminar_sidang' => 'PENGAJUAN']);

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function simpan_draft_sempro(Request $request)
    {
        $this->validate($request, [
            'file_laporan_revisi' => 'mimes:pdf|max:5000',
        ]);

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);

        if ($bap->file_laporan_revisi) {
            if ($request->hasFile('file_laporan_revisi')) {
                File::delete('File Laporan Revisi/' . Auth::user()->id_user . '/' . $bap->file_laporan_revisi);
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        } else {
            if ($request->hasFile('file_laporan_revisi')) {
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect('seminar_proposal');
    }

    public function sidang_ta()
    {
        $id = Auth::user()->id_user;

        //cek id prausta
        $cekdata_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        if ($cekdata_prausta != null) {
            $idprausta = $cekdata_prausta->id_settingrelasi_prausta;
        }

        //cek krs tugas akhir
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-601', 'TI-602', 'TK-602', 'FA6003', 'TI-6001'])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        //cek nilai dan file sempro
        $cekdata_nilai = Prausta_setting_relasi::join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('prausta_setting_relasi.id_student', $id)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_trans_hasil.nilai_huruf',
                'prausta_setting_relasi.validasi_pembimbing',
                'prausta_setting_relasi.validasi_penguji_1',
                'prausta_setting_relasi.validasi_penguji_2'
            )
            ->first();

        //cek pembimbing tugas akhir
        $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('prausta_setting_relasi.id_student', $id)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select(
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing'
            )
            ->get();

        if ($hasil_krs == 0) {

            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('home');
        } elseif ($hasil_krs > 0) {

            if (count($cekdata) == null) {

                Alert::error('Maaf Dosen Pembimbing Tugas Akhir anda belum di setting', 'MAAF !!');
                return redirect('home');
            } elseif (count($cekdata) != null) {

                // if ($cekdata_nilai == null) {
                //     Alert::error('Maaf anda belum melakukan upload draft laporan Sempro', 'MAAF !!');
                //     return redirect('home');
                // } elseif ($cekdata_nilai->file_draft_laporan != null) {

                //     if ($cekdata_nilai->nilai_huruf == null) {

                //         Alert::error('Maaf nilai Sempro anda belum dientri oleh dosen', 'MAAF !!');
                //         return redirect('home');
                //     } elseif ($cekdata_nilai->nilai_huruf != null) {

                //         if ($cekdata_nilai->validasi_pembimbing == 'BELUM' && $cekdata_nilai->validasi_penguji_1 == 'BELUM' && $cekdata_nilai->validasi_penguji_2 == 'BELUM') {

                //             Alert::error('Maaf pembimbing dan penguji belum validasi SEMPRO anda', 'MAAF !!');
                //             return redirect('home');
                //         } elseif ($cekdata_nilai->validasi_pembimbing == 'BELUM' or $cekdata_nilai->validasi_penguji_1 == 'BELUM' or $cekdata_nilai->validasi_penguji_2 == 'BELUM') {

                //             Alert::error('Maaf pembimbing atau penguji belum validasi SEMPRO anda', 'MAAF !!');
                //             return redirect('home');
                //         } elseif ($cekdata_nilai->validasi_pembimbing == 'SUDAH' or $cekdata_nilai->validasi_penguji_1 == 'SUDAH' or $cekdata_nilai->validasi_penguji_2 == 'SUDAH') {

                //data sidang tugas akhir
                $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                    ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select(
                        'prausta_setting_relasi.file_laporan_revisi',
                        'prausta_setting_relasi.file_draft_laporan',
                        'prausta_setting_relasi.jam_selesai_sidang',
                        'prausta_setting_relasi.jam_mulai_sidang',
                        'prausta_setting_relasi.ruangan',
                        'prausta_setting_relasi.tanggal_selesai',
                        'prausta_setting_relasi.dosen_penguji_1',
                        'prausta_setting_relasi.dosen_penguji_2',
                        'prausta_setting_relasi.tanggal_mulai',
                        'prausta_master_kategori.kategori',
                        'prausta_setting_relasi.acc_seminar_sidang',
                        'prausta_setting_relasi.id_settingrelasi_prausta',
                        'student.nama',
                        'student.nim',
                        'prausta_master_kode.kode_prausta',
                        'prausta_master_kode.nama_prausta',
                        'prodi.prodi',
                        'prausta_setting_relasi.dosen_pembimbing',
                        'prausta_setting_relasi.judul_prausta',
                        'prausta_setting_relasi.tempat_prausta',
                        'prausta_setting_relasi.acc_judul_dospem',
                        'prausta_setting_relasi.acc_judul_kaprodi',
                        'prausta_setting_relasi.validasi_baak',
                        'prausta_setting_relasi.file_plagiarisme'
                    )
                    ->first();

                // data untuk keuangan
                $maha = Student::where('idstudent', $id)
                    ->select('student.idstudent', 'student.nama', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
                    ->first();

                $idangkatan = $maha->idangkatan;
                $idstatus = $maha->idstatus;
                $kodeprodi = $maha->kodeprodi;

                $cek_study = Student::leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                    ->where('student.idstudent', $id)
                    ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
                    ->first();

                $biaya = Biaya::where('idangkatan', $idangkatan)
                    ->where('idstatus', $idstatus)
                    ->where('kodeprodi', $kodeprodi)
                    ->select('sidang')
                    ->first();

                //cek beasiswa mahasiswa
                $cb = Beasiswa::where('idstudent', $id)->first();

                if (($cb) != null) {

                    $sidang = $biaya->sidang - ($biaya->sidang * $cb->sidang) / 100;
                } elseif (($cb) == null) {

                    $sidang = $biaya->sidang;
                }

                if ($cek_study->study_year == 3) {

                    $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                        ->where('kuitansi.idstudent', $id)
                        ->where('bayar.iditem', 15)
                        ->sum('bayar.bayar');
                } elseif ($cek_study->study_year == 4) {

                    $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                        ->where('kuitansi.idstudent', $id)
                        ->where('bayar.iditem', 38)
                        ->sum('bayar.bayar');
                }

                $hasil_sidang = $sisasidang - $sidang;

                if ($hasil_sidang == 0) {
                    $validasi = 'Sudah Lunas';
                } else {
                    $validasi = 'Belum Lunas';
                }

                $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                    ->where('prausta_setting_relasi.id_student', $id)
                    //->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $idprausta)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->select(
                        'prausta_trans_bimbingan.id_transbimb_prausta',
                        'prausta_trans_bimbingan.file_bimbingan',
                        'prausta_trans_bimbingan.validasi',
                        'prausta_trans_bimbingan.tanggal_bimbingan',
                        'prausta_trans_bimbingan.remark_bimbingan',
                        'prausta_trans_bimbingan.komentar_bimbingan',
                        'prausta_trans_bimbingan.id_transbimb_prausta',
                        'prausta_trans_bimbingan.id_settingrelasi_prausta'
                    )
                    ->get();

                $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->count();

                $databimb = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                    ->where('prausta_setting_relasi.id_student', $id)
                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                    ->limit(1)
                    ->orderByDesc('prausta_trans_bimbingan.id_transbimb_prausta')
                    ->first();

                return view('mhs/prausta/sidang_ta', compact('data', 'validasi', 'bim', 'jml_bim', 'databimb'));
                //         }
                //     }
                // }
            }
        }
    }

    public function put_ta(Request $request, $id)
    {
        $prd = Prausta_setting_relasi::find($id);
        $prd->judul_prausta = $request->judul_prausta;
        $prd->tempat_prausta = $request->tempat_prausta;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        Alert::success('', 'Data Tugas Akhir Berhasil Diedit')->autoclose(3500);
        return redirect()->back();
    }

    public function pengajuan_sidang_ta($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->leftjoin('prausta_master_kategori', 'prausta_setting_relasi.id_kategori_prausta', '=', 'prausta_master_kategori.id')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_master_kategori.kategori',
                'prodi.id_prodi',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.id_settingrelasi_prausta'
            )
            ->first();

        $kategori = Prausta_master_kategori::where('id_prodi', $data->id_prodi)->get();

        return view('mhs/prausta/ajuan_ta', compact('data', 'kategori'));
    }

    public function simpan_ajuan_ta(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'id_kategori_prausta' => 'required',
            'tanggal_mulai' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $judul = $request->judul_prausta;
        $judul_ok = str_replace("'", "", $judul);

        $tempat = $request->tempat_prausta;
        $tempat_ok = str_replace("'", "", $tempat);

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $judul_ok,
            'tempat_prausta' => $tempat_ok,
            'id_kategori_prausta' => $request->id_kategori_prausta,
            'tanggal_mulai' => $request->tanggal_mulai
        ]);

        Alert::success('', 'Data Tugas Akhir Berhasil Diinput')->autoclose(3500);
        return redirect('sidang_ta');
    }

    public function simpan_bimbingan_ta(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi',
        ];
        $this->validate(
            $request,
            [
                'file_bimbingan' => 'file:pdf,PDF|max:4096',
            ],
            $message,
        );

        $bimbingan = $request->remark_bimbingan;
        $bim_ok = str_replace("'", "", $bimbingan);

        $usta = new Prausta_trans_bimbingan();
        $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
        $usta->tanggal_bimbingan = $request->tanggal_bimbingan;
        $usta->remark_bimbingan = $bim_ok;
        $usta->added_by = Auth::user()->name;
        $usta->status = 'ACTIVE';
        $usta->data_origin = 'eSIAM';

        if ($request->hasFile('file_bimbingan')) {
            $file = $request->file('file_bimbingan');
            $nama_file = $file->getClientOriginalName();
            $tujuan_upload = 'File Bimbingan TA/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $usta->file_bimbingan = $nama_file;
        }

        $usta->save();

        Alert::success('', 'Data Bimbingan Tugas Akhir Berhasil Diinput')->autoclose(3500);
        return redirect('sidang_ta');
    }

    public function edit_bimbingan_ta(Request $request, $id)
    {
        $sch = Prausta_trans_bimbingan::find($id);
        $sch->tanggal_bimbingan = $request->tanggal_bimbingan;
        $sch->remark_bimbingan = $request->remark_bimbingan;
        $sch->updated_by = Auth::user()->name;

        if ($sch->file_bimbingan) {
            if ($request->hasFile('file_bimbingan')) {

                File::delete('File Bimbingan TA/' . Auth::user()->id_user . '/' . $sch->file_bimbingan);
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan TA/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_bimbingan')) {
                $file = $request->file('file_bimbingan');
                $nama_file = $file->getClientOriginalName();
                $tujuan_upload = 'File Bimbingan TA/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $sch->file_bimbingan = $nama_file;
            }
        }

        $sch->save();

        Alert::success('', 'Data Bimbingan Tugas Akhir Berhasil Diedit')->autoclose(3500);
        return redirect('sidang_ta');
    }

    public function ajukan_sidang_ta(Request $request)
    {
        $this->validate($request, [
            'file_draft_laporan' => 'mimes:pdf|max:5000',
        ]);

        $date_now = date('Y-m-d');

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);
        $bap->acc_seminar_sidang = 'PENGAJUAN';
        $bap->tgl_pengajuan = $date_now;

        if ($bap->file_draft_laporan) {
            if ($request->hasFile('file_draft_laporan')) {
                File::delete('File Draft Laporan/' . Auth::user()->id_user . '/' . $bap->file_draft_laporan);
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_draft_laporan')) {
                $file = $request->file('file_draft_laporan');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Draft Laporan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_draft_laporan = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Draft Laporan TA Berhasil upload')->autoclose(3500);
        return redirect('sidang_ta');
    }


    public function simpan_draft_ta(Request $request)
    {
        $this->validate($request, [
            'file_laporan_revisi' => 'mimes:pdf|max:5000',
        ]);

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);

        if ($bap->file_laporan_revisi) {
            if ($request->hasFile('file_laporan_revisi')) {
                File::delete('File Laporan Revisi/' . Auth::user()->id_user . '/' . $bap->file_laporan_revisi);
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        } else {
            if ($request->hasFile('file_laporan_revisi')) {
                $file = $request->file('file_laporan_revisi');
                $nama_file = 'Draft Laporan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Laporan Revisi/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_laporan_revisi = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect('sidang_ta');
    }

    public function simpan_file_plagiarisme(Request $request)
    {
        $this->validate($request, [
            'file_plagiarisme' => 'mimes:pdf|max:5000',
        ]);

        $id = $request->id_settingrelasi_prausta;

        $bap = Prausta_setting_relasi::find($id);

        if ($bap->file_plagiarisme) {
            if ($request->hasFile('file_plagiarisme')) {
                File::delete('File Plagiarisme/' . Auth::user()->id_user . '/' . $bap->file_plagiarisme);
                $file = $request->file('file_plagiarisme');
                $nama_file = 'File Plagiarisme' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Plagiarisme/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_plagiarisme = $nama_file;
            }
        } else {
            if ($request->hasFile('file_plagiarisme')) {
                $file = $request->file('file_plagiarisme');
                $nama_file = 'File Plagiarisme' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Plagiarisme/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_plagiarisme = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Berhasil')->autoclose(3500);
        return redirect('sidang_ta');
    }

    public function download_bimbingan_prakerin_mhs($id)
    {
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
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'dosen.akademik'
            )
            ->first();

        $nama = $mhs->nama;
        $nim = $mhs->nim;
        $kelas = $mhs->kelas;

        $data = Prausta_trans_bimbingan::where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)->get();

        $pdf = PDF::loadView('prausta/prakerin/unduh_bim_prakerin', compact('mhs', 'data'))->setPaper('a4');
        return $pdf->download('Kartu Bimbingan Prakerin' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function download_bimbingan_sempro_mhs($id)
    {
        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2'
            )
            ->first();

        $nama = $mhs->nama;
        $nim = $mhs->nim;
        $kelas = $mhs->kelas;

        $dospem = Dosen::where('iddosen', $mhs->id_dosen_pembimbing)->first();

        $dospeng1 = Dosen::where('iddosen', $mhs->id_dosen_penguji_1)->first();

        $dospeng2 = Dosen::where('iddosen', $mhs->id_dosen_penguji_2)->first();

        $data = Prausta_trans_bimbingan::where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)->get();

        $pdf = PDF::loadView('prausta/sempro/unduh_bim_sempro', compact('mhs', 'data', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
        return $pdf->download('Kartu Bimbingan Sempro' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }

    public function download_bimbingan_ta_mhs($id)
    {
        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select(
                'student.nama',
                'student.nim',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.dosen_pembimbing',
                'prausta_setting_relasi.dosen_penguji_1',
                'prausta_setting_relasi.dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2'
            )
            ->first();

        $nama = $mhs->nama;
        $nim = $mhs->nim;
        $kelas = $mhs->kelas;

        $dospem = Dosen::where('iddosen', $mhs->id_dosen_pembimbing)->first();

        $dospeng1 = Dosen::where('iddosen', $mhs->id_dosen_penguji_1)->first();

        $dospeng2 = Dosen::where('iddosen', $mhs->id_dosen_penguji_2)->first();

        $data = Prausta_trans_bimbingan::where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)->get();

        $pdf = PDF::loadView('prausta/ta/unduh_bim_ta', compact('mhs', 'data', 'dospem', 'dospeng1', 'dospeng2'))->setPaper('a4');
        return $pdf->download('Kartu Bimbingan TA' . ' ' . $nama . ' ' . $nim . ' ' . $kelas . '.pdf');
    }
}
