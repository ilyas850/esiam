<?php

namespace App\Http\Controllers;

use Alert;
use App\User;
use App\Angkatan;
use App\Prodi;
use App\Student;
use App\Matakuliah;
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
                        ->whereIn('idmakul', [180,177,135,179,178,136])
                        ->get();

      $prodi = Prodi::all();

      $angkatan = Angkatan::whereIn('idangkatan', [16,17,18,19,20,21])->get();



      return view('prausta.nilai_prausta', compact('makul','prodi','angkatan'));
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
                                    ->select('student.idstudent','student.nim','student.nama','prodi.prodi','kelas.kelas','angkatan.angkatan','student_record.id_studentrecord','student_record.nilai_AKHIR')
                                    ->get();

        return view('prausta/form_nilai_prausta', compact('data'));
    }

    public function save_nilai_prausta(Request $request)
    {
      $jml = count($request->nilai_AKHIR);

      for ($i=0; $i < $jml; $i++) {
        $nilai = $request->nilai_AKHIR[$i];
        $nilaiusta = explode(',',$nilai, 2);
        $ids = $nilaiusta[0];
        $nsta = $nilaiusta[1];

        $akun = Student_record::where('id_studentrecord', $ids)
                              ->update(['nilai_AKHIR' => $nsta]);

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
                            ->whereIn('matakuliah.kode', ['FA-601','TI-601','TK-601'])
                            ->where('student_record.id_student', $id)
                            ->where('student_record.status', 'TAKEN')
                            ->select('matakuliah.makul')
                            ->get();
      $hasil_krs = count($cek);

      if ($hasil_krs == 0) {

        Alert::error('Maaf anda belum melakukan pengisian KRS Kerja Praktek/Prakerin', 'MAAF !!');
        return redirect('home');
      }elseif ($hasil_krs > 0) {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                                      ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                                      ->join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
                                      ->whereIn('prausta_master_kode.kode_prausta', ['FA-601','TI-601','TK-601'])
                                      ->where('prausta_setting_relasi.id_student', $id)
                                      ->where('prausta_setting_relasi.status', 'ACTIVE')
                                      ->select('prausta_setting_relasi.id_settingrelasi_prausta','student.nama','student.nim','prausta_master_kode.kode_prausta','prausta_master_kode.nama_prausta','prodi.prodi','prausta_setting_relasi.dosen_pembimbing','prausta_setting_relasi.judul_prausta','prausta_setting_relasi.tempat_prausta',
                                                'prausta_setting_relasi.file_acc_dosen','prausta_setting_relasi.file_val_baku','prausta_setting_relasi.file_kartu_bim','prausta_setting_relasi.file_nilai_pembim','prausta_setting_relasi.file_draft_laporan','prausta_setting_relasi.file_surat_balasan')
                                      ->get();

        $cekdata = count($data);

        $bim = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
                                      ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                                      ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                                      ->join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
                                      ->whereIn('prausta_master_kode.kode_prausta', ['FA-601','TI-601','TK-601'])
                                      ->where('prausta_setting_relasi.id_student', $id)
                                      ->where('prausta_setting_relasi.status', 'ACTIVE')
                                      ->select('prausta_trans_bimbingan.tanggal_bimbingan','prausta_trans_bimbingan.remark_bimbingan')
                                      ->get();


        if ($cekdata == 0) {

          return view('mhs/prausta/seminar_prakerin', compact('cekdata'));

        }elseif ($cekdata > 0) {

          foreach ($data as $usta) {
            // code...
          }

          return view('mhs/prausta/seminar_prakerin', compact('usta','cekdata','bim'));
        }
      }


    }

    public function pengajuan_seminar_prakerin()
    {
      $id = Auth::user()->id_user;

      $data = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                      ->join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
                      ->where('student.idstudent', $id)
                      ->whereIn('prausta_master_kode.kode_prausta', ['FA-601','TI-601','TK-601'])
                      ->select('student.nama','student.nim','prodi.prodi','prodi.id_prodi','prausta_master_kode.kode_prausta','prausta_master_kode.nama_prausta','prausta_master_kode.id_masterkode_prausta','student.idstudent')
                      ->first();


      return view('mhs/prausta/ajuan_prakerin', compact('data'));
    }

    public function simpan_ajuan_prakerin(Request $request)
    {

      $message = [
        'max'       => ':attribute harus diisi maksimal :max KB',
        'required'  => ':attribute wajib diisi',
      ];
      $this->validate($request, [
        'id_masterkode_prausta' => 'required',
        'id_student'            => 'required',
        'dosen_pembimbing'      => 'required',
        'judul_prausta'         => 'required',
        'tempat_prausta'         => 'required',
        'file_acc_dosen'        => 'image|mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
        'file_kartu_bim'        => 'mimes:pdf|max:5120',
        'file_surat_balasan'    => 'mimes:pdf|max:5120',
        'file_val_baku'         => 'image|mimes:jpg,jpeg,JPG,JPEG,png,PNG|max:2048',
        'file_draft_laporan'    => 'mimes:pdf|max:5120',
        'file_nilai_pembim'     => 'mimes:pdf|max:5120',
      ], $message);

      $usta                         = new Prausta_setting_relasi;
      $usta->id_masterkode_prausta  = $request->id_masterkode_prausta;
      $usta->id_student             = $request->id_student;
      $usta->dosen_pembimbing       = $request->dosen_pembimbing;
      $usta->judul_prausta          = $request->judul_prausta;
      $usta->tempat_prausta         = $request->tempat_prausta;
      $usta->added_by               = Auth::user()->name;
      $usta->status                 = 'ACTIVE';

      if($request->hasFile('file_acc_dosen'))
      {
        $file                         = $request->file('file_acc_dosen');
        $nama_file                    = 'Acc Dosen'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Acc Dosen/'.Auth::user()->id_user;
        $file->move($tujuan_upload,$nama_file);
        $usta->file_acc_dosen          = $nama_file;
      }

      if($request->hasFile('file_kartu_bim'))
      {
        $file                         = $request->file('file_kartu_bim');
        $nama_file                    = 'Kartu Bimbingan'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Kartu Bimbingan/'.Auth::user()->id_user;
        $file->move($tujuan_upload,$nama_file);
        $usta->file_kartu_bim          = $nama_file;
      }

      if($request->hasFile('file_surat_balasan'))
      {
        $file                         = $request->file('file_surat_balasan');
        $nama_file                    = 'Surat Balasan'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Surat Balasan/'.Auth::user()->id_user;
        $file->move($tujuan_upload,$nama_file);
        $usta->file_surat_balasan      = $nama_file;
      }

      if($request->hasFile('file_val_baku'))
      {
        $file                         = $request->file('file_val_baku');
        $nama_file                    = 'Validasi BAKU'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Validasi BAKU/'.Auth::user()->id_user;
        $file->move($tujuan_upload,$nama_file);
        $usta->file_val_baku      = $nama_file;
      }

      if($request->hasFile('file_draft_laporan'))
      {
        $file                         = $request->file('file_draft_laporan');
        $nama_file                    = 'Draft Laporan'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Draft Laporan/'.Auth::user()->id_user;
        $file->move($tujuan_upload,$nama_file);
        $usta->file_draft_laporan      = $nama_file;
      }

      if($request->hasFile('file_nilai_pembim'))
      {
        $file                         = $request->file('file_nilai_pembim');
        $nama_file                    = 'Nilai Pembimbing'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Nilai Pembimbing/'.Auth::user()->id_user;
        $file->move($tujuan_upload,$nama_file);
        $usta->file_nilai_pembim       = $nama_file;
      }

      $usta->save();

      Alert::success('', 'Data Prakerin Berhasil Diinput')->autoclose(3500);
      return redirect('seminar_prakerin');
    }

    public function data_prakerin()
    {
      $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                                    ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                                    ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                                    ->whereIn('prausta_master_kode.kode_prausta', ['FA-601','TI-601','TK-601'])
                                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                                    ->where('student.active', 1)
                                    ->select('prausta_setting_relasi.id_settingrelasi_prausta','student.nama','student.nim','prausta_master_kode.kode_prausta','prausta_master_kode.nama_prausta','prodi.prodi','prausta_setting_relasi.dosen_pembimbing','prausta_setting_relasi.dosen_penguji_1','prausta_setting_relasi.judul_prausta','prausta_setting_relasi.tanggal_mulai','prausta_setting_relasi.tanggal_selesai','prausta_setting_relasi.jam_mulai_sidang','prausta_setting_relasi.jam_selesai_sidang')
                                    ->orderBy('student.nim', 'DESC')
                                    ->get();

        return view('mhs/prausta/data_prakerin', compact('data'));
    }

    public function atur_prakerin($id)
    {

      return view('mhs/prausta/atur_prakerin', compact('id'));
    }

    public function simpan_bimbingan(Request $request)
    {
      $usta     = new Prausta_trans_bimbingan;
      $usta->id_settingrelasi_prausta = $request->id_settingrelasi_prausta;
      $usta->tanggal_bimbingan        = $request->tanggal_bimbingan;
      $usta->remark_bimbingan         = $request->remark_bimbingan;
      $usta->added_by                 = Auth::user()->name;
      $usta->status                   = 'ACTIVE';
      $usta->save();

      Alert::success('', 'Data Bimbingan Prakerin Berhasil Diinput')->autoclose(3500);
      return redirect('seminar_prakerin');
    }
}
