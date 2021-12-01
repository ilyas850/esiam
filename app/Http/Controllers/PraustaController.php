<?php

namespace App\Http\Controllers;

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
use App\Student_record;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use App\Prausta_trans_bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PraustaController extends Controller
{
    public function nilai_prausta()
    {
        $makul = Matakuliah::where('active', 1)
            ->whereIn('idmakul', [180, 177, 135, 179, 178, 136])
            ->get();

        $prodi = Prodi::all();

        $angkatan = Angkatan::whereIn('idangkatan', [16, 17, 18, 19, 20, 21])->get();

        return view('prausta.nilai_prausta', compact('makul', 'prodi', 'angkatan'));
    }

    public function kode_prausta(Request $request)
    {
        $idmakul = $request->idmakul;
        $idprodi = $request->kodeprodi;
        $idangkatan = $request->idangkatan;

        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prodi', 'prausta_master_kode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('matakuliah', 'prausta_master_kode.kode_prausta', '=', 'matakuliah.kode')
            ->join('student_record', 'student_record.id_student', '=', 'prausta_setting_relasi.id_student')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->where('kurikulum_periode.id_makul', $idmakul)
            ->where('student.idangkatan', $idangkatan)
            ->where('student.kodeprodi', $idprodi)
            ->where('student_record.status', 'TAKEN')
            ->where('student.active', 1)
            ->select('student.idstudent', 'student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.id_studentrecord', 'student_record.nilai_AKHIR')
            ->get();

        return view('prausta/form_nilai_prausta', compact('data'));
    }

    public function save_nilai_prausta(Request $request)
    {
        $jml = count($request->nilai_AKHIR);

        for ($i = 0; $i < $jml; $i++) {
            $nilai = $request->nilai_AKHIR[$i];
            $nilaiusta = explode(',', $nilai, 2);
            $ids = $nilaiusta[0];
            $nsta = $nilaiusta[1];

            $akun = Student_record::where('id_studentrecord', $ids)->update(['nilai_AKHIR' => $nsta]);
        }
        Alert::success('', 'Niali berhasil diinput')->autoclose(3500);
        return redirect('nilai_prausta');
    }

    public function seminar_prakerin()
    {
        $id = Auth::user()->id_user;
        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-601', 'TI-601', 'TK-601'])
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();
        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Kerja Praktek/Prakerin', 'MAAF !!');
            return redirect('home');
        } elseif ($hasil_krs > 0) {
            $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                ->join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
                ->whereIn('prausta_master_kode.kode_prausta', ['FA-601', 'TI-601', 'TK-601'])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_setting_relasi.acc_seminar_sidang', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tempat_prausta', 'prausta_setting_relasi.acc_judul', 'prausta_setting_relasi.file_kartu_bim', 'prausta_setting_relasi.file_nilai_pembim', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_surat_balasan')
                ->get();

            $cekdata = count($data);

            // data untuk keuangan
            $maha = Student::where('idstudent', $id)
                ->select('student.idstudent', 'student.nama', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
                ->first();

            $idangkatan = $maha->idangkatan;
            $idstatus = $maha->idstatus;
            $kodeprodi = $maha->kodeprodi;

            $biaya = Biaya::where('idangkatan', $idangkatan)
                ->where('idstatus', $idstatus)
                ->where('kodeprodi', $kodeprodi)
                ->select('spp5')
                ->first();

            $biaya_spp5 = $biaya->spp5;

            //cek beasiswa mahasiswa
            $cekbeasiswa = Beasiswa::where('idstudent', $id)->get();

            if (count($cekbeasiswa) > 0) {
                foreach ($cekbeasiswa as $cb) {
                }

                $spp5 = $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100;

                $total_spp5 = $spp5;
            } else {
                $spp5 = $biaya->spp5;

                $total_spp5 = $spp5;
            }

            $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 8)
                ->sum('bayar.bayar');

            $hasil_spp5 = $sisaspp5 - $total_spp5;

            if ($hasil_spp5 == 0) {
                $validasi = 'Sudah Lunas';
            } else {
                $validasi = 'Belum Lunas';
            }

            $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                ->join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
                ->whereIn('prausta_master_kode.kode_prausta', ['FA-601', 'TI-601', 'TK-601'])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_trans_bimbingan.tanggal_bimbingan', 'prausta_trans_bimbingan.remark_bimbingan', 'prausta_trans_bimbingan.id_transbimb_prausta')
                ->get();

            $jml_bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->count();

            if ($cekdata == 0) {
                return view('mhs/prausta/seminar_prakerin', compact('cekdata'));
            } elseif ($cekdata > 0) {
                foreach ($data as $usta) {
                    // code...
                }

                return view('mhs/prausta/seminar_prakerin', compact('usta', 'cekdata', 'bim', 'validasi', 'jml_bim'));
            }
        }
    }

    public function pengajuan_seminar_prakerin()
    {
        $id = Auth::user()->id_user;
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->where('id_student', $id)
            ->select('prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'student.nama', 'student.nim', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->first();

        return view('mhs/prausta/ajuan_prakerin', compact('data'));
    }

    public function simpan_ajuan_prakerin(Request $request)
    {
        $this->validate($request, [
            'id_settingrelasi_prausta' => 'required',
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
        ]);

        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $request->id_settingrelasi_prausta)->update([
            'judul_prausta' => $request->judul_prausta,
            'tempat_prausta' => $request->tempat_prausta,
        ]);

        Alert::success('', 'Judul Prakerin Berhasil Diinput')->autoclose(3500);
        return redirect('seminar_prakerin');
    }

    public function edit_ajuan_prakerin(Request $request, $id)
    {
        $this->validate($request, [
            'judul_prausta' => 'required',
            'tempat_prausta' => 'required',
            'file_draft_laporan' => 'mimes:pdf|max:5000',
            'file_nilai_pembim' => 'mimes:pdf|max:5000',
            'file_kartu_bim' => 'mimes:pdf|max:5000',
            'file_surat_balasan' => 'mimes:pdf|max:5000',
        ]);

        $bap = Prausta_setting_relasi::find($id);
        $bap->judul_prausta = $request->judul_prausta;
        $bap->tempat_prausta = $request->tempat_prausta;

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

        if ($bap->file_nilai_pembim) {
            if ($request->hasFile('file_nilai_pembim')) {
                File::delete('File Nilai Pembimbing/' . Auth::user()->id_user . '/' . $bap->file_nilai_pembim);
                $file = $request->file('file_nilai_pembim');
                $nama_file = 'Nilai Pembimbing' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Nilai Pembimbing/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_nilai_pembim = $nama_file;
            }
        } else {
            if ($request->hasFile('file_nilai_pembim')) {
                $file = $request->file('file_nilai_pembim');
                $nama_file = 'Nilai Pembimbing' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Nilai Pembimbing/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_nilai_pembim = $nama_file;
            }
        }

        if ($bap->file_kartu_bim) {
            if ($request->hasFile('file_kartu_bim')) {
                File::delete('File Kartu Bimbingan/' . Auth::user()->id_user . '/' . $bap->file_kartu_bim);
                $file = $request->file('file_kartu_bim');
                $nama_file = 'Kartu Bimbingan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Kartu Bimbingan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_kartu_bim = $nama_file;
            }
        } else {
            if ($request->hasFile('file_kartu_bim')) {
                $file = $request->file('file_kartu_bim');
                $nama_file = 'Kartu Bimbingan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Kartu Bimbingan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_kartu_bim = $nama_file;
            }
        }

        if ($bap->file_surat_balasan) {
            if ($request->hasFile('file_surat_balasan')) {
                File::delete('File Surat Balasan/' . Auth::user()->id_user . '/' . $bap->file_surat_balasan);
                $file = $request->file('file_surat_balasan');
                $nama_file = 'Surat Balasan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Surat Balasan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_surat_balasan = $nama_file;
            }
        } else {
            if ($request->hasFile('file_surat_balasan')) {
                $file = $request->file('file_surat_balasan');
                $nama_file = 'Surat Balasan' . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Surat Balasan/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_surat_balasan = $nama_file;
            }
        }

        $bap->save();

        return redirect('seminar_prakerin');
    }

    public function data_prakerin()
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_master_kode.kode_prausta', ['FA-601', 'TI-601', 'TK-601'])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_master_kode.kode_prausta', 'prausta_master_kode.nama_prausta', 'prodi.prodi', 'prausta_setting_relasi.dosen_pembimbing', 'prausta_setting_relasi.dosen_penguji_1', 'prausta_setting_relasi.judul_prausta', 'prausta_setting_relasi.tanggal_mulai', 'prausta_setting_relasi.tanggal_selesai', 'prausta_setting_relasi.jam_mulai_sidang', 'prausta_setting_relasi.jam_selesai_sidang')
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('prausta/prakerin/data_prakerin', compact('data'));
    }

    public function atur_prakerin($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->first();

        $dosen = Dosen::where('idstatus', 1)
            ->where('active', 1)
            ->get();
        dd($dosen);

        return view('prausta/prakerin/atur_prakerin', compact('id', 'data'));
    }

    public function simpan_bimbingan(Request $request)
    {
        $usta = new Prausta_trans_bimbingan();
        $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
        $usta->tanggal_bimbingan = $request->tanggal_bimbingan;
        $usta->remark_bimbingan = $request->remark_bimbingan;
        $usta->added_by = Auth::user()->name;
        $usta->status = 'ACTIVE';
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
        $sch->save();

        Alert::success('', 'Data Bimbingan Prakerin Berhasil Diedit')->autoclose(3500);
        return redirect('seminar_prakerin');
    }

    public function ajukan_seminar_pkl($id)
    {
        $akun = Prausta_setting_relasi::where('id_settingrelasi_prausta', $id)->update(['acc_seminar_sidang' => 'PENGAJUAN']);

        return redirect('seminar_prakerin');
    }
}
