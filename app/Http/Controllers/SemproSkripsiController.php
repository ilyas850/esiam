<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prausta_setting_relasi;
use App\Models\Prausta_master_penilaian;
use App\Models\Prausta_trans_hasil;
use App\Models\Prausta_trans_penilaian;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SemproSkripsiController extends Controller
{
    public function penguji_sempro_dlm()
    {
        $id = Auth::user()->id_user;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')

            ->where(function ($query) use ($id) {
                $query
                    ->where('prausta_setting_relasi.id_dosen_penguji_1', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_pembimbing', $id)
                    ->orWhere('prausta_setting_relasi.id_dosen_penguji_2', $id);
            })
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [25, 28, 31])
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'prausta_setting_relasi.id_dosen_penguji_1',
                'prausta_setting_relasi.id_dosen_penguji_2',
                'prausta_setting_relasi.id_dosen_pembimbing',
                'prausta_trans_hasil.nilai_1',
                'prausta_trans_hasil.nilai_2',
                'prausta_trans_hasil.nilai_3',
                'prausta_trans_hasil.nilai_huruf',
                'student.nim',
                'student.nama',
                'prausta_master_kode.kode_prausta',
                'prausta_master_kode.nama_prausta',
                'prodi.prodi',
                'kelas.kelas',
                'prausta_setting_relasi.id_student',
                'prausta_setting_relasi.judul_prausta',
                'prausta_setting_relasi.tempat_prausta',
                'prausta_setting_relasi.acc_seminar_sidang',
                'prausta_setting_relasi.file_draft_laporan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_pembimbing',
                'prausta_setting_relasi.validasi_penguji_1',
                'prausta_setting_relasi.validasi_penguji_2',
                'prausta_trans_hasil.validasi'
            )
            ->get();
        // dd($data->toArray());
        return view('dosen/magang_skripsi/penguji_sempro', compact('data', 'id'));
    }

    public function isi_form_nilai_sempro_skripsi_dospem($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_dosbing = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Pembimbing')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosen/magang_skripsi/form_nilai_sempro', compact('data', 'id', 'form_dosbing'));
    }

    public function simpan_nilai_sempro_skripsi_dospem(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dospem = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = $nilai_dospem / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_1 = $nilai_dospem;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_1' => $nilai_dospem,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect('penguji_sempro_dlm');
    }

    public function isi_form_nilai_sempro_skripsi_dosji1($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng1 = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Penguji I')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosen/magang_skripsi/form_nilai_sempro_dosji1', compact('data', 'id', 'form_peng1'));
    }

    public function simpan_nilai_sempro_skripsi_dosji1(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dosji1 = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = $nilai_dosji1 / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_2 = $nilai_dosji1;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dosji1 + $cek_nilai->nilai_1 + $cek_nilai->nilai_3) / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_2' => $nilai_dosji1,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect('penguji_sempro_dlm');
    }

    public function isi_form_nilai_sempro_skripsi_dosji2($id)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng2 = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Penguji II')
            ->where('status', 'ACTIVE')
            ->get();

        return view('dosen/magang_skripsi/form_nilai_sempro_dosji2', compact('data', 'id', 'form_peng2'));
    }

    public function simpan_nilai_sempro_skripsi_dosji2(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = new Prausta_trans_penilaian();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->id_penilaian_prausta = $id_nilai;
            $usta->nilai = $n;
            $usta->created_by = Auth::user()->name;
            $usta->save();
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dosji2 = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        if ($cek_nilai == null) {
            $hasil = $nilai_dosji2 / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            $usta = new Prausta_trans_hasil();
            $usta->id_settingrelasi_prausta = $id_prausta;
            $usta->nilai_3 = $nilai_dosji2;
            $usta->nilai_huruf = $nilai_huruf;
            $usta->added_by = Auth::user()->name;
            $usta->status = 'ACTIVE';
            $usta->data_origin = 'eSIAM';
            $usta->save();
        } elseif ($cek_nilai != null) {
            $hasil = ($nilai_dosji2 + $cek_nilai->nilai_1 + $cek_nilai->nilai_2) / 3;
            $hasilavg = round($hasil, 2);

            if ($hasilavg >= 80) {
                $nilai_huruf = 'A';
            } elseif ($hasilavg >= 75) {
                $nilai_huruf = 'B+';
            } elseif ($hasilavg >= 70) {
                $nilai_huruf = 'B';
            } elseif ($hasilavg >= 65) {
                $nilai_huruf = 'C+';
            } elseif ($hasilavg >= 60) {
                $nilai_huruf = 'C';
            } elseif ($hasilavg >= 50) {
                $nilai_huruf = 'D';
            } elseif ($hasilavg >= 0) {
                $nilai_huruf = 'E';
            }

            Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_3' => $nilai_dosji2,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect('penguji_sempro_dlm');
    }

    public function edit_nilai_sempro_skripsi_by_dospem_dlm($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosen/magang_skripsi/edit_nilai_sempro_dospem', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_skripsi_dospem_dlm(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->first();

        $nilai_dospem = $ceknilai->nilai1;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_1' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('penguji_sempro_dlm');
    }

    public function edit_nilai_sempro_skripsi_by_dospeng1_dlm($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosen/magang_skripsi/edit_nilai_sempro_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_skripsi_dospeng1_dlm(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
            ->first();

        $nilai_dospem = $ceknilai->nilai2;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        $akun = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_2' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('penguji_sempro_dlm');
    }

    public function edit_nilai_sempro_skripsi_by_dospeng2_dlm($id)
    {
        $datadiri = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $nilai_pem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select('prausta_master_penilaian.komponen', 'prausta_master_penilaian.bobot', 'prausta_master_penilaian.acuan', 'prausta_trans_penilaian.nilai', 'prausta_trans_penilaian.id_trans_penilaian')
            ->get();

        return view('dosen/magang_skripsi/edit_nilai_sempro_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_skripsi_dospeng2_dlm(Request $request)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        $hit_jml_nilai = count($id_penilaian);

        for ($i = 0; $i < $hit_jml_nilai; $i++) {
            $id_nilai = $id_penilaian[$i];
            $n = $nilai[$i];

            $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $n,
                'updated_by' => Auth::user()->name,
            ]);
        }

        $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
            ->first();

        $nilai_dospem = $ceknilai->nilai3;

        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            $nilai_huruf = 'A';
        } elseif ($hasilavg >= 75) {
            $nilai_huruf = 'B+';
        } elseif ($hasilavg >= 70) {
            $nilai_huruf = 'B';
        } elseif ($hasilavg >= 65) {
            $nilai_huruf = 'C+';
        } elseif ($hasilavg >= 60) {
            $nilai_huruf = 'C';
        } elseif ($hasilavg >= 50) {
            $nilai_huruf = 'D';
        } elseif ($hasilavg >= 0) {
            $nilai_huruf = 'E';
        }

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_3' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect('penguji_sempro_dlm');
    }
}
