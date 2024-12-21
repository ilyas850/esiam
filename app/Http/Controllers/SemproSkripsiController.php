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
    public function penguji_sempro($view)
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

        return view($view, compact('data', 'id'));
    }

    public function penguji_sempro_dlm()
    {
        return $this->penguji_sempro('dosen/magang_skripsi/penguji_sempro');
    }

    public function penguji_sempro_skripsi_kprd()
    {
        return $this->penguji_sempro('kaprodi/magang_skripsi/penguji_sempro');
    }

    public function isi_form_nilai_sempro_skripsi($id, $view)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_dosbing = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Pembimbing')
            ->where('status', 'ACTIVE')
            ->get();

        return view($view, compact('data', 'id', 'form_dosbing'));
    }

    public function isi_form_nilai_sempro_skripsi_dospem($id)
    {
        return $this->isi_form_nilai_sempro_skripsi($id, 'dosen/magang_skripsi/form_nilai_sempro');
    }

    public function isi_form_nilai_sempro_skripsi_dospem_kprd($id)
    {
        return $this->isi_form_nilai_sempro_skripsi($id, 'kaprodi/magang_skripsi/form_nilai_sempro');
    }

    public function simpan_nilai_sempro_skripsi(Request $request, $redirectRoute)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        foreach ($id_penilaian as $i => $id_nilai) {
            Prausta_trans_penilaian::create([
                'id_settingrelasi_prausta' => $id_prausta,
                'id_penilaian_prausta' => $id_nilai,
                'nilai' => $nilai[$i],
                'created_by' => Auth::user()->name
            ]);
        }

        $nilai_dospem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->sum(DB::raw('prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100'));

        $cek_nilai = Prausta_trans_hasil::firstOrNew(['id_settingrelasi_prausta' => $id_prausta]);
        $hasil = $cek_nilai->exists ? ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3 : $nilai_dospem / 3;
        $hasilavg = round($hasil, 2);

        $nilai_huruf = $this->tentukan_nilai_huruf($hasilavg);

        $cek_nilai->fill([
            'nilai_1' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'added_by' => Auth::user()->name,
            'status' => 'ACTIVE',
            'data_origin' => 'eSIAM'
        ])->save();

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect($redirectRoute);
    }

    private function tentukan_nilai_huruf($hasilavg)
    {
        if ($hasilavg >= 80) return 'A';
        if ($hasilavg >= 75) return 'B+';
        if ($hasilavg >= 70) return 'B';
        if ($hasilavg >= 65) return 'C+';
        if ($hasilavg >= 60) return 'C';
        if ($hasilavg >= 50) return 'D';
        return 'E';
    }

    public function simpan_nilai_sempro_skripsi_dospem(Request $request)
    {
        return $this->simpan_nilai_sempro_skripsi($request, 'penguji_sempro_dlm');
    }

    public function simpan_nilai_sempro_skripsi_dospem_kprd(Request $request)
    {
        return $this->simpan_nilai_sempro_skripsi($request, 'penguji_sempro_skripsi_kprd');
    }

    public function isi_form_nilai_sempro_skripsi_dosji1($id, $view)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng1 = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Penguji I')
            ->where('status', 'ACTIVE')
            ->get();

        return view($view, compact('data', 'id', 'form_peng1'));
    }

    public function isi_form_nilai_sempro_skripsi_dosji1_dlm($id)
    {
        return $this->isi_form_nilai_sempro_skripsi_dosji1($id, 'dosen/magang_skripsi/form_nilai_sempro_dosji1');
    }

    public function isi_form_nilai_sempro_skripsi_dosji1_kprd($id)
    {
        return $this->isi_form_nilai_sempro_skripsi_dosji1($id, 'kaprodi/magang_skripsi/form_nilai_sempro_dosji1');
    }

    // public function simpan_nilai_sempro_skripsi_dosji1(Request $request)
    // {
    //     $id_prausta = $request->id_settingrelasi_prausta;
    //     $id_penilaian = $request->id_penilaian_prausta;
    //     $nilai = $request->nilai;

    //     $hit_jml_nilai = count($id_penilaian);

    //     for ($i = 0; $i < $hit_jml_nilai; $i++) {
    //         $id_nilai = $id_penilaian[$i];
    //         $n = $nilai[$i];

    //         $usta = new Prausta_trans_penilaian();
    //         $usta->id_settingrelasi_prausta = $id_prausta;
    //         $usta->id_penilaian_prausta = $id_nilai;
    //         $usta->nilai = $n;
    //         $usta->created_by = Auth::user()->name;
    //         $usta->save();
    //     }

    //     $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
    //         ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
    //         ->where('prausta_master_penilaian.kategori', 2)
    //         ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
    //         ->where('prausta_master_penilaian.status', 'ACTIVE')
    //         ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
    //         ->first();

    //     $nilai_dosji1 = $ceknilai->nilai1;

    //     $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    //     if ($cek_nilai == null) {
    //         $hasil = $nilai_dosji1 / 3;
    //         $hasilavg = round($hasil, 2);

    //         if ($hasilavg >= 80) {
    //             $nilai_huruf = 'A';
    //         } elseif ($hasilavg >= 75) {
    //             $nilai_huruf = 'B+';
    //         } elseif ($hasilavg >= 70) {
    //             $nilai_huruf = 'B';
    //         } elseif ($hasilavg >= 65) {
    //             $nilai_huruf = 'C+';
    //         } elseif ($hasilavg >= 60) {
    //             $nilai_huruf = 'C';
    //         } elseif ($hasilavg >= 50) {
    //             $nilai_huruf = 'D';
    //         } elseif ($hasilavg >= 0) {
    //             $nilai_huruf = 'E';
    //         }

    //         $usta = new Prausta_trans_hasil();
    //         $usta->id_settingrelasi_prausta = $id_prausta;
    //         $usta->nilai_2 = $nilai_dosji1;
    //         $usta->nilai_huruf = $nilai_huruf;
    //         $usta->added_by = Auth::user()->name;
    //         $usta->status = 'ACTIVE';
    //         $usta->data_origin = 'eSIAM';
    //         $usta->save();
    //     } elseif ($cek_nilai != null) {
    //         $hasil = ($nilai_dosji1 + $cek_nilai->nilai_1 + $cek_nilai->nilai_3) / 3;
    //         $hasilavg = round($hasil, 2);

    //         if ($hasilavg >= 80) {
    //             $nilai_huruf = 'A';
    //         } elseif ($hasilavg >= 75) {
    //             $nilai_huruf = 'B+';
    //         } elseif ($hasilavg >= 70) {
    //             $nilai_huruf = 'B';
    //         } elseif ($hasilavg >= 65) {
    //             $nilai_huruf = 'C+';
    //         } elseif ($hasilavg >= 60) {
    //             $nilai_huruf = 'C';
    //         } elseif ($hasilavg >= 50) {
    //             $nilai_huruf = 'D';
    //         } elseif ($hasilavg >= 0) {
    //             $nilai_huruf = 'E';
    //         }

    //         Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
    //             'nilai_2' => $nilai_dosji1,
    //             'nilai_huruf' => $nilai_huruf,
    //         ]);
    //     }

    //     Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
    //     return redirect('penguji_sempro_dlm');
    // }

    // public function simpan_nilai_sempro_skripsi_dosji1_kprd(Request $request)
    // {
    //     $id_prausta = $request->id_settingrelasi_prausta;
    //     $id_penilaian = $request->id_penilaian_prausta;
    //     $nilai = $request->nilai;

    //     $hit_jml_nilai = count($id_penilaian);

    //     for ($i = 0; $i < $hit_jml_nilai; $i++) {
    //         $id_nilai = $id_penilaian[$i];
    //         $n = $nilai[$i];

    //         $usta = new Prausta_trans_penilaian();
    //         $usta->id_settingrelasi_prausta = $id_prausta;
    //         $usta->id_penilaian_prausta = $id_nilai;
    //         $usta->nilai = $n;
    //         $usta->created_by = Auth::user()->name;
    //         $usta->save();
    //     }

    //     $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
    //         ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
    //         ->where('prausta_master_penilaian.kategori', 2)
    //         ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
    //         ->where('prausta_master_penilaian.status', 'ACTIVE')
    //         ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
    //         ->first();

    //     $nilai_dosji1 = $ceknilai->nilai1;

    //     $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    //     if ($cek_nilai == null) {
    //         $hasil = $nilai_dosji1 / 3;
    //         $hasilavg = round($hasil, 2);

    //         if ($hasilavg >= 80) {
    //             $nilai_huruf = 'A';
    //         } elseif ($hasilavg >= 75) {
    //             $nilai_huruf = 'B+';
    //         } elseif ($hasilavg >= 70) {
    //             $nilai_huruf = 'B';
    //         } elseif ($hasilavg >= 65) {
    //             $nilai_huruf = 'C+';
    //         } elseif ($hasilavg >= 60) {
    //             $nilai_huruf = 'C';
    //         } elseif ($hasilavg >= 50) {
    //             $nilai_huruf = 'D';
    //         } elseif ($hasilavg >= 0) {
    //             $nilai_huruf = 'E';
    //         }

    //         $usta = new Prausta_trans_hasil();
    //         $usta->id_settingrelasi_prausta = $id_prausta;
    //         $usta->nilai_2 = $nilai_dosji1;
    //         $usta->nilai_huruf = $nilai_huruf;
    //         $usta->added_by = Auth::user()->name;
    //         $usta->status = 'ACTIVE';
    //         $usta->data_origin = 'eSIAM';
    //         $usta->save();
    //     } elseif ($cek_nilai != null) {
    //         $hasil = ($nilai_dosji1 + $cek_nilai->nilai_1 + $cek_nilai->nilai_3) / 3;
    //         $hasilavg = round($hasil, 2);

    //         if ($hasilavg >= 80) {
    //             $nilai_huruf = 'A';
    //         } elseif ($hasilavg >= 75) {
    //             $nilai_huruf = 'B+';
    //         } elseif ($hasilavg >= 70) {
    //             $nilai_huruf = 'B';
    //         } elseif ($hasilavg >= 65) {
    //             $nilai_huruf = 'C+';
    //         } elseif ($hasilavg >= 60) {
    //             $nilai_huruf = 'C';
    //         } elseif ($hasilavg >= 50) {
    //             $nilai_huruf = 'D';
    //         } elseif ($hasilavg >= 0) {
    //             $nilai_huruf = 'E';
    //         }

    //         Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
    //             'nilai_2' => $nilai_dosji1,
    //             'nilai_huruf' => $nilai_huruf,
    //         ]);
    //     }

    //     Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
    //     return redirect('penguji_sempro_skripsi_kprd');
    // }

    public function simpan_nilai_sempro(Request $request, $redirectRoute)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        // Simpan nilai ke Prausta_trans_penilaian
        foreach ($id_penilaian as $index => $id_nilai) {
            Prausta_trans_penilaian::create([
                'id_settingrelasi_prausta' => $id_prausta,
                'id_penilaian_prausta' => $id_nilai,
                'nilai' => $nilai[$index],
                'created_by' => Auth::user()->name,
            ]);
        }

        // Hitung nilai dosji1
        $nilai_dosji1 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->value('nilai1');

        // Periksa apakah ada data di Prausta_trans_hasil
        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $nilai_huruf = $this->hitung_nilai_huruf($nilai_dosji1, $cek_nilai);

        if (!$cek_nilai) {
            Prausta_trans_hasil::create([
                'id_settingrelasi_prausta' => $id_prausta,
                'nilai_2' => $nilai_dosji1,
                'nilai_huruf' => $nilai_huruf,
                'added_by' => Auth::user()->name,
                'status' => 'ACTIVE',
                'data_origin' => 'eSIAM',
            ]);
        } else {
            Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_2' => $nilai_dosji1,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect($redirectRoute);
    }

    private function hitung_nilai_huruf($nilai_dosji1, $cek_nilai = null)
    {
        if ($cek_nilai) {
            $hasil = ($nilai_dosji1 + $cek_nilai->nilai_1 + $cek_nilai->nilai_3) / 3;
        } else {
            $hasil = $nilai_dosji1 / 3;
        }

        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            return 'A';
        } elseif ($hasilavg >= 75) {
            return 'B+';
        } elseif ($hasilavg >= 70) {
            return 'B';
        } elseif ($hasilavg >= 65) {
            return 'C+';
        } elseif ($hasilavg >= 60) {
            return 'C';
        } elseif ($hasilavg >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }


    public function simpan_nilai_sempro_skripsi_dosji1(Request $request)
    {
        return $this->simpan_nilai_sempro($request, 'penguji_sempro_dlm');
    }

    public function simpan_nilai_sempro_skripsi_dosji1_kprd(Request $request)
    {
        return $this->simpan_nilai_sempro($request, 'penguji_sempro_skripsi_kprd');
    }

    public function isi_form_nilai_sempro_skripsi_dosji2_dosen($id, $viewPath)
    {
        $data = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('prausta_setting_relasi.id_settingrelasi_prausta', 'student.nama', 'student.nim', 'prausta_setting_relasi.tempat_prausta')
            ->first();

        $form_peng2 = Prausta_master_penilaian::where('kategori', 2)
            ->where('jenis_form', 'Form Penguji II')
            ->where('status', 'ACTIVE')
            ->get();

        return view($viewPath, compact('data', 'id', 'form_peng2'));
    }

    public function isi_form_nilai_sempro_skripsi_dosji2($id)
    {
        return $this->isi_form_nilai_sempro_skripsi_dosji2_dosen($id, 'dosen/magang_skripsi/form_nilai_sempro_dosji2');
    }

    public function isi_form_nilai_sempro_skripsi_dosji2_kprd($id)
    {
        return $this->isi_form_nilai_sempro_skripsi_dosji2_dosen($id, 'kaprodi/magang_skripsi/form_nilai_sempro_dosji2');
    }

    public function simpan_nilai_sempro_skripsi_dosji2(Request $request, $redirectRoute)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_penilaian_prausta;
        $nilai = $request->nilai;

        // Simpan nilai ke Prausta_trans_penilaian
        foreach ($id_penilaian as $index => $id_nilai) {
            Prausta_trans_penilaian::create([
                'id_settingrelasi_prausta' => $id_prausta,
                'id_penilaian_prausta' => $id_nilai,
                'nilai' => $nilai[$index],
                'created_by' => Auth::user()->name,
            ]);
        }

        // Hitung nilai dosji2
        $nilai_dosji2 = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->value('nilai1');

        // Periksa apakah ada data di Prausta_trans_hasil
        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $nilai_huruf = $this->hitung_nilai_huruf_dosji2($nilai_dosji2, $cek_nilai, 'nilai_3');

        if (!$cek_nilai) {
            Prausta_trans_hasil::create([
                'id_settingrelasi_prausta' => $id_prausta,
                'nilai_3' => $nilai_dosji2,
                'nilai_huruf' => $nilai_huruf,
                'added_by' => Auth::user()->name,
                'status' => 'ACTIVE',
                'data_origin' => 'eSIAM',
            ]);
        } else {
            Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
                'nilai_3' => $nilai_dosji2,
                'nilai_huruf' => $nilai_huruf,
            ]);
        }

        Alert::success('', 'Nilai Sempro berhasil disimpan')->autoclose(3500);
        return redirect($redirectRoute);
    }

    private function hitung_nilai_huruf_dosji2($nilai_dosji, $cek_nilai, $field)
    {
        if ($cek_nilai) {
            $hasil = ($nilai_dosji + $cek_nilai->nilai_1 + $cek_nilai->nilai_2) / 3;
        } else {
            $hasil = $nilai_dosji / 3;
        }

        $hasilavg = round($hasil, 2);

        if ($hasilavg >= 80) {
            return 'A';
        } elseif ($hasilavg >= 75) {
            return 'B+';
        } elseif ($hasilavg >= 70) {
            return 'B';
        } elseif ($hasilavg >= 65) {
            return 'C+';
        } elseif ($hasilavg >= 60) {
            return 'C';
        } elseif ($hasilavg >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }

    public function simpan_nilai_sempro_skripsi_dosji2_dlm(Request $request)
    {
        return $this->simpan_nilai_sempro_skripsi_dosji2($request, 'penguji_sempro_dlm');
    }

    public function simpan_nilai_sempro_skripsi_dosji2_kprd(Request $request)
    {
        return $this->simpan_nilai_sempro_skripsi_dosji2($request, 'penguji_sempro_skripsi_kprd');
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

    public function edit_nilai_sempro_skripsi_by_dospem_kprd($id)
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

        return view('kaprodi/magang_skripsi/edit_nilai_sempro_dospem', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_skripsi_dospem(Request $request, $redirectRoute)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        // Update nilai pada Prausta_trans_penilaian
        foreach ($id_penilaian as $index => $id_nilai) {
            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $nilai[$index],
                'updated_by' => Auth::user()->name,
            ]);
        }

        // Hitung nilai dospem
        $nilai_dospem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Pembimbing')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai1'))
            ->value('nilai1');

        // Update hasil pada Prausta_trans_hasil
        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        $nilai_huruf = $this->determine_grade($hasilavg);

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_1' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect($redirectRoute);
    }

    private function determine_grade($hasilavg)
    {
        if ($hasilavg >= 80) {
            return 'A';
        } elseif ($hasilavg >= 75) {
            return 'B+';
        } elseif ($hasilavg >= 70) {
            return 'B';
        } elseif ($hasilavg >= 65) {
            return 'C+';
        } elseif ($hasilavg >= 60) {
            return 'C';
        } elseif ($hasilavg >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }

    public function put_nilai_sempro_skripsi_dospem_dlm(Request $request)
    {
        return $this->put_nilai_sempro_skripsi_dospem($request, 'penguji_sempro_dlm');
    }

    public function put_nilai_sempro_skripsi_dospem_kprd(Request $request)
    {
        return $this->put_nilai_sempro_skripsi_dospem($request, 'penguji_sempro_skripsi_kprd');
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

    public function edit_nilai_sempro_skripsi_by_dospeng1_kprd($id)
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

        return view('kaprodi/magang_skripsi/edit_nilai_sempro_dospeng1', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_skripsi_dospeng1(Request $request, $redirectRoute)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        // Update nilai pada Prausta_trans_penilaian
        foreach ($id_penilaian as $index => $id_nilai) {
            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $nilai[$index],
                'updated_by' => Auth::user()->name,
            ]);
        }

        // Hitung nilai dospeng
        $nilai_dospem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji I')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai2'))
            ->value('nilai2');

        // Update hasil pada Prausta_trans_hasil
        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        $nilai_huruf = $this->determine_grade($hasilavg);

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_2' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect($redirectRoute);
    }

    public function put_nilai_sempro_skripsi_dospeng1_dlm(Request $request)
    {
        return $this->put_nilai_sempro_skripsi_dospeng1($request, 'penguji_sempro_dlm');
    }

    public function put_nilai_sempro_skripsi_dospeng1_kprd(Request $request)
    {
        return $this->put_nilai_sempro_skripsi_dospeng1($request, 'penguji_sempro_skripsi_kprd');
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

    public function edit_nilai_sempro_skripsi_by_dospeng2_kprd($id)
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

        return view('kaprodi/magang_skripsi/edit_nilai_sempro_dospeng2', compact('nilai_pem', 'datadiri', 'id'));
    }

    public function put_nilai_sempro_skripsi_dospeng2(Request $request, $redirectRoute)
    {
        $id_prausta = $request->id_settingrelasi_prausta;
        $id_penilaian = $request->id_trans_penilaian;
        $nilai = $request->nilai;

        // Update nilai pada Prausta_trans_penilaian
        foreach ($id_penilaian as $index => $id_nilai) {
            Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
                'nilai' => $nilai[$index],
                'updated_by' => Auth::user()->name,
            ]);
        }

        // Hitung nilai dospeng
        $nilai_dospem = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
            ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
            ->where('prausta_master_penilaian.kategori', 2)
            ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
            ->where('prausta_master_penilaian.status', 'ACTIVE')
            ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
            ->value('nilai3');

        // Update hasil pada Prausta_trans_hasil
        $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

        $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
        $hasilavg = round($hasil, 2);

        $nilai_huruf = $this->determine_grade_if($hasilavg);

        Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
            'nilai_3' => $nilai_dospem,
            'nilai_huruf' => $nilai_huruf,
            'updated_by' => Auth::user()->name,
        ]);

        Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
        return redirect($redirectRoute);
    }

    private function determine_grade_if($hasilavg)
    {
        if ($hasilavg >= 80) {
            return 'A';
        } elseif ($hasilavg >= 75) {
            return 'B+';
        } elseif ($hasilavg >= 70) {
            return 'B';
        } elseif ($hasilavg >= 65) {
            return 'C+';
        } elseif ($hasilavg >= 60) {
            return 'C';
        } elseif ($hasilavg >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }

    public function put_nilai_sempro_skripsi_dospeng2_dlm(Request $request)
    {
        return $this->put_nilai_sempro_skripsi_dospeng2($request, 'penguji_sempro_dlm');
    }

    public function put_nilai_sempro_skripsi_dospeng2_kprd(Request $request)
    {
        return $this->put_nilai_sempro_skripsi_dospeng2($request, 'penguji_sempro_skripsi_kprd');
    }


    // public function put_nilai_sempro_skripsi_dospeng2_dlm(Request $request)
    // {
    //     $id_prausta = $request->id_settingrelasi_prausta;
    //     $id_penilaian = $request->id_trans_penilaian;
    //     $nilai = $request->nilai;

    //     $hit_jml_nilai = count($id_penilaian);

    //     for ($i = 0; $i < $hit_jml_nilai; $i++) {
    //         $id_nilai = $id_penilaian[$i];
    //         $n = $nilai[$i];

    //         $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
    //             'nilai' => $n,
    //             'updated_by' => Auth::user()->name,
    //         ]);
    //     }

    //     $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
    //         ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
    //         ->where('prausta_master_penilaian.kategori', 2)
    //         ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
    //         ->where('prausta_master_penilaian.status', 'ACTIVE')
    //         ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
    //         ->first();

    //     $nilai_dospem = $ceknilai->nilai3;

    //     $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    //     $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
    //     $hasilavg = round($hasil, 2);

    //     if ($hasilavg >= 80) {
    //         $nilai_huruf = 'A';
    //     } elseif ($hasilavg >= 75) {
    //         $nilai_huruf = 'B+';
    //     } elseif ($hasilavg >= 70) {
    //         $nilai_huruf = 'B';
    //     } elseif ($hasilavg >= 65) {
    //         $nilai_huruf = 'C+';
    //     } elseif ($hasilavg >= 60) {
    //         $nilai_huruf = 'C';
    //     } elseif ($hasilavg >= 50) {
    //         $nilai_huruf = 'D';
    //     } elseif ($hasilavg >= 0) {
    //         $nilai_huruf = 'E';
    //     }

    //     Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
    //         'nilai_3' => $nilai_dospem,
    //         'nilai_huruf' => $nilai_huruf,
    //         'updated_by' => Auth::user()->name,
    //     ]);

    //     Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
    //     return redirect('penguji_sempro_dlm');
    // }

    // public function put_nilai_sempro_skripsi_dospeng2_kprd(Request $request)
    // {
    //     $id_prausta = $request->id_settingrelasi_prausta;
    //     $id_penilaian = $request->id_trans_penilaian;
    //     $nilai = $request->nilai;

    //     $hit_jml_nilai = count($id_penilaian);

    //     for ($i = 0; $i < $hit_jml_nilai; $i++) {
    //         $id_nilai = $id_penilaian[$i];
    //         $n = $nilai[$i];

    //         $usta = Prausta_trans_penilaian::where('id_trans_penilaian', $id_nilai)->update([
    //             'nilai' => $n,
    //             'updated_by' => Auth::user()->name,
    //         ]);
    //     }

    //     $ceknilai = Prausta_trans_penilaian::join('prausta_master_penilaian', 'prausta_trans_penilaian.id_penilaian_prausta', '=', 'prausta_master_penilaian.id_penilaian_prausta')
    //         ->where('prausta_trans_penilaian.id_settingrelasi_prausta', $id_prausta)
    //         ->where('prausta_master_penilaian.kategori', 2)
    //         ->where('prausta_master_penilaian.jenis_form', 'Form Penguji II')
    //         ->where('prausta_master_penilaian.status', 'ACTIVE')
    //         ->select(DB::raw('sum(prausta_trans_penilaian.nilai * prausta_master_penilaian.bobot / 100) as nilai3'))
    //         ->first();

    //     $nilai_dospem = $ceknilai->nilai3;

    //     $cek_nilai = Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->first();

    //     $hasil = ($nilai_dospem + $cek_nilai->nilai_2 + $cek_nilai->nilai_3) / 3;
    //     $hasilavg = round($hasil, 2);

    //     if ($hasilavg >= 80) {
    //         $nilai_huruf = 'A';
    //     } elseif ($hasilavg >= 75) {
    //         $nilai_huruf = 'B+';
    //     } elseif ($hasilavg >= 70) {
    //         $nilai_huruf = 'B';
    //     } elseif ($hasilavg >= 65) {
    //         $nilai_huruf = 'C+';
    //     } elseif ($hasilavg >= 60) {
    //         $nilai_huruf = 'C';
    //     } elseif ($hasilavg >= 50) {
    //         $nilai_huruf = 'D';
    //     } elseif ($hasilavg >= 0) {
    //         $nilai_huruf = 'E';
    //     }

    //     Prausta_trans_hasil::where('id_settingrelasi_prausta', $id_prausta)->update([
    //         'nilai_3' => $nilai_dospem,
    //         'nilai_huruf' => $nilai_huruf,
    //         'updated_by' => Auth::user()->name,
    //     ]);

    //     Alert::success('', 'Nilai Sempro berhasil diedit')->autoclose(3500);
    //     return redirect('penguji_sempro_skripsi_kprd');
    // }
}