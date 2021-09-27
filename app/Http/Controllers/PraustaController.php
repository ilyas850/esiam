<?php

namespace App\Http\Controllers;

use App\User;
use App\Angkatan;
use App\Prodi;
use App\Student;
use App\Matakuliah;
use App\Student_record;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
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
      return redirect('nilai_prausta');
    }

    public function seminar_prakerin()
    {
      $id = Auth::user()->id_user;
      $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                                    ->where('prausta_setting_relasi.id_student', $id)
                                    ->where('prausta_setting_relasi.status', 'ACTIVE')
                                    ->get();
      $cekdata = count($data);

      return view('mhs/prausta/seminar_prakerin', compact('data','cekdata'));
    }

    public function pengajuan_seminar_prakerin()
    {
      $id = Auth::user()->id_user;

      $data = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
                      ->join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
                      ->where('student.idstudent', $id)
                      ->whereIn('kode_prausta', ['FA-601','TI-601','TK-601'])
                      ->select('student.nama','student.nim','prodi.prodi','prodi.id_prodi','prausta_master_kode.kode_prausta','prausta_master_kode.nama_prausta','prausta_master_kode.id_masterkode_prausta','student.idstudent')
                      ->first();


      return view('mhs/prausta/ajuan_prakerin', compact('data'));
    }

    public function simpan_ajuan_prakerin(Request $request)
    {
      dd($request);
      $message = [
        'max'       => ':attribute harus diisi maksimal :max KB',
        'required'  => ':attribute wajib diisi',
      ];
      $this->validate($request, [
        'id_masterkode_prausta' => 'required',
        'id_student'            => 'required',
        'dosen_pembimbing'      => 'required',
        'judul_prausta'         => 'required',
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

      if($request->hasFile('file_acc_dosen'))
      {
        $file                         = $request->file('file_acc_dosen');
        $nama_file                    = 'Acc Dosen'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Acc Dosen/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Kuliah Tatap Muka';
        $file->move($tujuan_upload,$nama_file);
        $bap->file_kuliah_tatapmuka   = $nama_file;
      }

      if($request->hasFile('file_acc_dosen'))
      {
        $file                         = $request->file('file_acc_dosen');
        $nama_file                    = 'Acc Dosen'."-".$file->getClientOriginalName();
        $tujuan_upload                = 'File Acc Dosen/'.Auth::user()->id_user.'/'.$request->id_kurperiode.'/'.'Kuliah Tatap Muka';
        $file->move($tujuan_upload,$nama_file);
        $bap->file_kuliah_tatapmuka   = $nama_file;
      }
    }
}
