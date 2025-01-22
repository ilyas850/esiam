<?php

namespace App\Http\Controllers;

use Alert;
use File;
use PDF;
use App\Models\Absen_ujian;
use App\Models\Bap;
use App\Models\Absensi_mahasiswa;;

use App\Models\Pedoman_akademik;
use App\User;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Waktu;
use App\Models\Prodi;
use App\Models\Student;
use App\Models\Informasi;
use App\Models\Edom_transaction;
use App\Models\Waktu_krs;
use App\Models\Periode_tipe;
use App\Models\Periode_tahun;
use App\Models\Update_mahasiswa;
use App\Models\Kurikulum_periode;
use App\Models\Kurikulum_transaction;
use App\Models\Student_record;
use App\Models\Beasiswa;
use App\Models\Biaya;
use App\Models\DosenPembimbing;
use App\Models\Itembayar;
use App\Models\Kuitansi;
use App\Models\Prausta_setting_relasi;
use App\Models\Ujian_transaction;
use App\Models\Kuisioner_master;
use App\Models\Kuisioner_kategori;
use App\Models\Kuisioner_transaction;
use App\Models\Waktu_edom;
use App\Models\Sertifikat;
use App\Models\Angkatan;
use App\Models\Yudisium;
use App\Models\Wisuda;
use App\Models\Standar;
use App\Models\Pengalaman;
use App\Models\Penangguhan_kategori;
use App\Models\Penangguhan_trans;
use App\Models\Kritiksaran_kategori;
use App\Models\Kritiksaran_transaction;
use App\Models\Beasiswa_trans;
use App\Models\Permohonan_ujian;
use App\Models\Perwalian_trans_bimbingan;
use App\Models\Min_biaya;
use App\Models\Pengajuan_trans;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MhsController extends Controller
{
    function mhs_home()
    {
        $id = Auth::user()->id_user;

        $mhs = Student::leftJoin('update_mahasiswas', 'nim_mhs', '=', 'student.nim')
            ->leftjoin('microsoft_user', 'student.idstudent', '=', 'microsoft_user.id_student')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.idstudent', $id)
            ->select(
                'student.nama',
                'student.foto',
                'student.hp',
                'angkatan.angkatan',
                'kelas.kelas',
                'student.email',
                'prodi.prodi',
                'student.idstudent',
                'student.nim',
                'student.nisn',
                'update_mahasiswas.hp_baru',
                'update_mahasiswas.email_baru',
                'update_mahasiswas.id_mhs',
                'update_mahasiswas.id',
                'update_mahasiswas.nim_mhs',
                'microsoft_user.username',
                'microsoft_user.password',
                'prodi.id_prodi',
                'prodi.konsentrasi',
                'student.idangkatan',
                'student.kodeprodi',
                'student.virtual_account'
            )
            ->first();

        $tahun = Periode_tahun::leftjoin('kalender_akademik', 'periode_tahun.id_periodetahun', '=', 'kalender_akademik.id_periodetahun')
            ->where('periode_tahun.status', 'ACTIVE')
            ->select('periode_tahun.periode_tahun', 'kalender_akademik.file', 'periode_tahun.status', 'periode_tahun.id_periodetahun')
            ->first();

        $tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $waktu_edom = Waktu_edom::select('status', 'waktu_awal', 'waktu_Akhir')
            ->first();

        $angk = Angkatan::all();

        $edom = Waktu_edom::all();
        foreach ($edom as $keyedom) {
            // code...
        }

        $info = Informasi::orderBy('created_at', 'DESC')->paginate(5);

        $time = Waktu_krs::first();

        // if ($waktu_edom->status != 1) {
        $foto = $mhs->foto;
        $idprodi = $mhs->id_prodi;
        $idangkatan = $mhs->idangkatan;

        $data = DB::select('CALL standar_kurikulum(?,?,?)', array($idprodi, $idangkatan, $id));

        $data_mengulang = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->join('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
            ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
            ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
            ->where('student.idstudent', $id)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('student.active', [1, 5])
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR', 'semester.semester', 'kurikulum_master.nama_kurikulum')
            ->groupBy('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR', 'semester.semester', 'kurikulum_master.nama_kurikulum')
            ->get();

        toast('Selamat Datang di eSIAM!', 'success');
        return view('home', ['data_mengulang' => $data_mengulang, 'data' => $data, 'angk' => $angk, 'foto' => $foto, 'edom' => $keyedom, 'info' => $info, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $tahun, 'tipe' => $tipe]);
        // }

        #cek jumlah KRS makul kecuali PKL dan TA / Magang dan Skripsi
        // $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        //     ->where('student_record.id_student', $id)
        //     ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
        //     ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
        //     ->where('student_record.status', 'TAKEN')
        //     ->where('kurikulum_periode.status', 'ACTIVE')
        //     ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
        //     ->get();

        #cek jumlah pengisian EDOM
        // $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
        //     ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
        //     ->where('edom_transaction.id_student', $id)
        //     ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
        //     ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
        //     ->where('kurikulum_periode.status', 'ACTIVE')
        //     ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
        //     ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
        //     ->get();

        // $jml_krs = count($records);

        // $jml_isi_edom = count($cekedom);

        // if (($jml_krs - 2) <= $jml_isi_edom) {

        //     Alert::error('Maaf anda belum melakukan pengisian EDOM')->autoclose(3500);
        //     return redirect('kuisioner_mahasiswa');
        // }

        #cek kuisioner Pembimbing Akademik
        // $cek_kuis_pa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
        //     ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
        //     ->where('kuisioner_transaction.id_student', $id)
        //     ->where('kuisioner_master_kategori.id_kategori_kuisioner', 1)
        //     ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
        //     ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
        //     ->get();

        // if (count($cek_kuis_pa) == 0) {

        //     Alert::error('Maaf anda belum melakukan pengisian kuisioner Pembimbing Akademik', 'MAAF !!');
        //     return redirect('kuisioner_mahasiswa');
        // }

        #cek kuisioner BAAK
        // $cek_kuis_baak = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
        //     ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
        //     ->where('kuisioner_transaction.id_student', $id)
        //     ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
        //     ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
        //     ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
        //     ->get();

        // if (count($cek_kuis_baak) == 0) {

        //     Alert::error('Maaf anda belum melakukan pengisian kuisioner BAAK', 'MAAF !!');
        //     return redirect('kuisioner_mahasiswa');
        // }

        #cek kuisioner BAUK
        // $cek_kuis_bauk = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
        //     ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
        //     ->where('kuisioner_transaction.id_student', $id)
        //     ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
        //     ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
        //     ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
        //     ->get();

        // if (count($cek_kuis_bauk) == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian kuisioner BAUK', 'MAAF !!');
        //     return redirect('kuisioner_mahasiswa');
        // }

        #cek kuisioner PERPUS
        // $cek_kuis_perpus = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
        //     ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
        //     ->where('kuisioner_transaction.id_student', $id)
        //     ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
        //     ->where('kuisioner_transaction.id_periodetahun', $tahun->id_periodetahun)
        //     ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
        //     ->get();

        // if (count($cek_kuis_perpus) == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian kuisioner PERPUSTAKAAN', 'MAAF !!');
        //     return redirect('kuisioner_mahasiswa');
        // }

        #cek kuisioner Beasiswa
        // $cek_kuis_beasiswa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
        //     ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
        //     ->where('kuisioner_transaction.id_student', $id)
        //     ->where('kuisioner_master_kategori.id_kategori_kuisioner', 9)
        //     ->where('kuisioner_transaction.id_periodetahun',  $tahun->id_periodetahun)
        //     ->where('kuisioner_transaction.id_periodetipe', $tipe->id_periodetipe)
        //     ->get();

        // if (count($cek_kuis_beasiswa) == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian kuisioner BEASISWA', 'MAAF !!');
        //     return redirect('kuisioner_mahasiswa');
        // }

        // $foto = $mhs->foto;
        // $idprodi = $mhs->id_prodi;
        // $idangkatan = $mhs->idangkatan;

        // $data = DB::select('CALL standar_kurikulum(?,?,?)', array($idprodi, $idangkatan, $id));

        // $data_mengulang = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
        //     ->join('prodi', function ($join) {
        //         $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        //             ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        //     })
        //     ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
        //     ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
        //     ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
        //     ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
        //     ->join('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
        //     ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
        //     ->whereIn('student_record.nilai_AKHIR', ['D', 'E'])
        //     ->where('student.idstudent', $id)
        //     ->where('student_record.status', 'TAKEN')
        //     ->whereIn('student.active', [1, 5])
        //     ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR', 'semester.semester', 'kurikulum_master.nama_kurikulum')
        //     ->groupBy('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_AKHIR', 'semester.semester', 'kurikulum_master.nama_kurikulum')
        //     ->get();

        // return view('home', ['data_mengulang' => $data_mengulang, 'data' => $data, 'angk' => $angk, 'foto' => $foto, 'edom' => $keyedom, 'info' => $info, 'mhs' => $mhs, 'id' => $id, 'time' => $time, 'tahun' => $tahun, 'tipe' => $tipe]);
    }

    public function change($id)
    {
        return view('mhs/change_pwd', ['mhs' => $id]);
    }

    public function store_new_pwd(Request $request, $id)
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

            Alert::success('', 'Password anda berhasil dirubah')->autoclose(3500);
            return redirect('home');
        } else {
            Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
            return redirect('home');
        }
    }

    public function store_new_user(Request $request, $id)
    {
        $this->validate($request, [
            'role' => 'required',
            'oldpassword' => 'required',
            'password' => 'required|min:7|confirmed',
        ]);

        $sandi = bcrypt($request->password);

        $user = User::find($id);

        $pass = password_verify($request->oldpassword, $user->password);

        if ($pass) {
            $user->password = $sandi;
            $user->role = $request->role;
            $user->save();

            Alert::success('', 'Password anda berhasil dirubah')->autoclose(3500);
            return redirect('home');
        } else {
            Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
            return redirect('home');
        }
    }

    public function update($id)
    {
        $cek = Student::where('idstudent', $id)->get();
        //$maha = Student::find($id);
        foreach ($cek as $user) {
        }

        $foto = $user->foto;

        return view('mhs/update', ['mhs' => $user, 'foto' => $foto]);
    }

    public function store_update(Request $request)
    {
        $this->validate($request, [
            'id_mhs' => 'required',
            'nim_mhs' => 'required'
        ]);

        $users = new Update_mahasiswa();
        $users->id_mhs = $request->id_mhs;
        $users->nim_mhs = $request->nim_mhs;
        $users->hp_baru = $request->hp_baru;
        $users->email_baru = $request->email_baru;
        $users->save();

        return redirect('home');
    }

    public function change_update($id)
    {
        $user = Update_Mahasiswa::find($id);

        return view('mhs/change', ['mhs' => $user]);
    }

    public function store_change(Request $request, $id)
    {
        $this->validate($request, [
            'id_mhs' => 'required'
        ]);

        $user = Update_Mahasiswa::find($id);
        $user->id_mhs = $request->id_mhs;
        $user->hp_baru = $request->hp_baru;
        $user->email_baru = $request->email_baru;
        $user->save();

        return redirect('home');
    }

    public function pdf_krs()
    {
        $id = Auth::user()->id_user;

        $maha = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
            ->first();

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $idangkatan = $maha->idangkatan;
        $nama = $maha->nama;
        $prodi = $maha->prodi;
        $kelas = $maha->kelas;

        $sub_thn = substr($thn->periode_tahun, 6, 2);
        $idtp = $tp->id_periodetipe;
        $smt = $sub_thn . $idtp;
        $angk = $idangkatan;

        if ($smt % 2 != 0) {
            $a = ($smt + 10 - 1) / 10;
            $b = $a - $angk;
            $c = $b * 2 - 1;
        } else {
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $angk;
            $c = $b * 2;
        }

        $record = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('student_record.id_student', $id)
            ->where('kurikulum_periode.id_periodetipe', $idtp)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.tanggal_krs', 'semester.semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama')
            ->orderBy('kurikulum_periode.id_hari', 'ASC')
            ->orderBy('kurikulum_periode.id_jam', 'ASC')
            ->get();

        $recordas = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->where('student_record.id_student', $id)
            ->where('kurikulum_periode.id_periodetipe', $idtp)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
            ->groupBy('student_record.id_kurtrans', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
            ->get();

        //jumlah SKS
        $sks = 0;
        foreach ($recordas as $keysks) {
            $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
        }



        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $d = date('d');
        $m = $bulan[date('m')];
        $y = date('Y');

        $pdf = PDF::loadView('mhs/krs_pdf', ['d' => $d, 'm' => $m, 'y' => $y, 'mhs' => $maha, 'tp' => $tp, 'thn' => $thn, 'krs' => $record, 'sks' => $sks])->setPaper('a4', 'portrait');
        return $pdf->download('KRS' . ' ' . $nama . ' ' . $prodi . ' ' . $kelas . ' ' . '(' . $thn->periode_tahun . ' ' . $tp->periode_tipe . ')' . '.pdf');
    }

    public function jadwal()
    {
        $id = Auth::user()->id_user;

        $jadwal = DB::select('CALL persentase_absen_per_mhs(?)', [$id]);

        return view('mhs/jadwal', ['jadwal' => $jadwal]);
    }

    function history_perkuliahan()
    {
        $id = Auth::user()->id_user;
        $data = DB::select('CALL persentase_absen_per_mhs_all_makul(?)', [$id]);

        return view('mhs/perkuliahan/history_perkuliahan', compact('data'));
    }

    public function lihatabsen($id)
    {
        $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select(
                'kurikulum_periode.akt_sks_praktek',
                'kurikulum_periode.akt_sks_teori',
                'kurikulum_periode.id_kelas',
                'periode_tipe.periode_tipe',
                'periode_tahun.periode_tahun',
                'dosen.akademik',
                'dosen.nama',
                'ruangan.nama_ruangan',
                'kurikulum_jam.jam',
                'kurikulum_hari.hari',
                DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'),
                'kurikulum_periode.id_kurperiode',
                'matakuliah.makul',
                'prodi.prodi',
                'kelas.kelas',
                'semester.semester'
            )
            ->get();
        foreach ($bap as $key) {
            # code...
        }

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select(
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
                'bap.praktikum'
            )
            ->get();
        $d = count($data);
        if ($d > 0) {
            foreach ($data as $keybap) {
                # code...
            }

            $idb = $keybap->id_bap;

            return view('mhs/lihatabsen', ['idb' => $idb, 'data' => $key, 'bap' => $data]);
        } elseif ($d == 0) {
            Alert::warning('maaf mata kuliah belum ada absensi', 'MAAF !!');
            return redirect('jadwal');
        }
    }

    public function view_bap($id)
    {
        $bp = Bap::where('id_bap', $id)->get();
        foreach ($bp as $dtbp) {
            # code...
        }

        $bap = Kurikulum_periode::join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->where('kurikulum_periode.id_kurperiode', $dtbp->id_kurperiode)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('dosen.iddosen', 'semester.semester', 'kelas.kelas', 'prodi.prodi', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'matakuliah.makul', 'dosen.nama')
            ->get();
        foreach ($bap as $data) {
            # code...
        }
        $prd = $data->prodi;
        $tipe = $data->periode_tipe;
        $tahun = $data->periode_tahun;

        return view('mhs/view_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
    }

    public function view_abs($id)
    {
        $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select(
                'kurikulum_periode.akt_sks_praktek',
                'kurikulum_periode.akt_sks_teori',
                'kurikulum_periode.id_kelas',
                'periode_tipe.periode_tipe',
                'periode_tahun.periode_tahun',
                'dosen.akademik',
                'dosen.nama',
                'ruangan.nama_ruangan',
                'kurikulum_jam.jam',
                'kurikulum_hari.hari',
                DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'),
                'kurikulum_periode.id_kurperiode',
                'matakuliah.makul',
                'prodi.prodi',
                'kelas.kelas',
                'semester.semester'
            )
            ->get();
        foreach ($bap as $key) {
            # code...
        }

        $ids = Auth::user()->id_user;
        $kur = Student_record::where('id_kurperiode', $id)
            ->where('id_student', $ids)
            ->where('status', 'TAKEN')
            ->first();

        $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('absensi_mahasiswa.id_studentrecord', $kur->id_studentrecord)
            ->select(
                'student_record.id_studentrecord',
                'bap.pertemuan',
                'bap.tanggal',
                'bap.jam_mulai',
                'bap.jam_selsai',
                'student.nama',
                'student.nim',
                'absensi_mahasiswa.absensi'
            )
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        return view('mhs/rekap_absen', ['data' => $key, 'abs' => $abs]);
    }

    public function khs_mid()
    {
        $id = Auth::user()->id_user;
        $mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
            ->first();

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idangkatan = $mhs->idangkatan;
        $idstatus = $mhs->idstatus;
        $kodeprodi = $mhs->kodeprodi;

        $sub_thn = substr($periode_tahun->periode_tahun, 6, 2);
        $tp = $periode_tipe->id_periodetipe;
        $smt = $sub_thn . $tp;
        $angk = $mhs->idangkatan;

        if ($smt % 2 != 0) {
            if ($tp == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) - 1;
            } elseif ($tp == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) . '0' . '1';
            }
        } else {
            //genap
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $angk;
            $c = $b * 2;
        }

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14')
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        if ($cb != null) {
            $daftar = $biaya->daftar - ($biaya->daftar * $cb->daftar) / 100;
            $awal = $biaya->awal - ($biaya->awal * $cb->awal) / 100;
            $dsp = $biaya->dsp - ($biaya->dsp * $cb->dsp) / 100;
            $spp1 = $biaya->spp1 - ($biaya->spp1 * $cb->spp1) / 100;
            $spp2 = $biaya->spp2 - ($biaya->spp2 * $cb->spp2) / 100;
            $spp3 = $biaya->spp3 - ($biaya->spp3 * $cb->spp3) / 100;
            $spp4 = $biaya->spp4 - ($biaya->spp4 * $cb->spp4) / 100;
            $spp5 = $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100;
            $spp6 = $biaya->spp6 - ($biaya->spp6 * $cb->spp6) / 100;
            $spp7 = $biaya->spp7 - ($biaya->spp7 * $cb->spp7) / 100;
            $spp8 = $biaya->spp8 - ($biaya->spp8 * $cb->spp8) / 100;
            $spp9 = $biaya->spp9 - ($biaya->spp9 * $cb->spp9) / 100;
            $spp10 = $biaya->spp10 - ($biaya->spp10 * $cb->spp10) / 100;
            $spp11 = $biaya->spp11 - ($biaya->spp11 * $cb->spp11) / 100;
            $spp12 = $biaya->spp12 - ($biaya->spp12 * $cb->spp12) / 100;
            $spp13 = $biaya->spp13 - ($biaya->spp13 * $cb->spp13) / 100;
            $spp14 = $biaya->spp14 - ($biaya->spp14 * $cb->spp14) / 100;
        } elseif ($cb == null) {
            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
        }

        //total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->sum('bayar.bayar');


        if ($c == 1) {
            $cekbyr = $daftar + $awal + $spp1 / 2 - $total_semua_dibayar;
        } elseif ($c == 2) {
            $cekbyr = $daftar + $awal + ($dsp * 75) / 100 + $spp1 + $spp2 / 2 - $total_semua_dibayar;
        } elseif ($c == '201') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + ($spp2 * 82 / 100) - $total_semua_dibayar;
        } elseif ($c == 3) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 / 2 - $total_semua_dibayar;
        } elseif ($c == 4) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 33) / 100 - $total_semua_dibayar;
        } elseif ($c == '401') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82 / 100) - $total_semua_dibayar;
        } elseif ($c == 5) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 / 2 - $total_semua_dibayar;
        } elseif ($c == 6) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 / 2 - $total_semua_dibayar;
        } elseif ($c == '601') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82 / 100) - $total_semua_dibayar;
        } elseif ($c == 7) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 / 2 - $total_semua_dibayar;
        } elseif ($c == 8) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 / 2 - $total_semua_dibayar;
        } elseif ($c == '801') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82 / 100) - $total_semua_dibayar;
        } elseif ($c == 9) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 / 2 - $total_semua_dibayar;
        } elseif ($c == 10) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 / 2 - $total_semua_dibayar;
        } elseif ($c == '1001') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82 / 100) - $total_semua_dibayar;
        } elseif ($c == 11) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 / 2 - $total_semua_dibayar;
        } elseif ($c == 12) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 / 2 - $total_semua_dibayar;
        } elseif ($c == 13) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 / 2 - $total_semua_dibayar;
        } elseif ($c == 14) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + $spp14 / 2 - $total_semua_dibayar;
        }

        if ($cekbyr == 0 or $cekbyr < 1) {
            $record = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                ->where('student_record.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tp)
                ->where('kurikulum_periode.id_periodetahun', $periode_tahun->id_periodetahun)
                ->where('student_record.status', 'TAKEN')
                ->select('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                ->groupBy('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                ->get();

            $sks = 0;
            foreach ($record as $keysks) {
                $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
            }

            return view('mhs/khs_mid', ['periode_tahun' => $periode_tahun, 'periode_tipe' => $periode_tipe, 'mhs' => $mhs, 'krs' => $record, 'sks' => $sks]);
        } else {
            Alert::warning('Maaf anda tidak dapat melihat KHS karena keuangan Anda belum memenuhi syarat');
            return redirect('home');
        }
    }

    public function unduh_khs_mid()
    {
        $id = Auth::user()->id_user;
        $mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
            ->first();

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $nama = $mhs->nama;
        $prodi = $mhs->prodi;
        $kelas = $mhs->kelas;

        $tp = $periode_tipe->id_periodetipe;

        $record = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->where('student_record.id_student', $id)
            ->where('kurikulum_periode.id_periodetipe', $tp)
            ->where('kurikulum_periode.id_periodetahun', $periode_tahun->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
            ->groupBy('student_record.id_kurtrans', 'student_record.nilai_UTS', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
            ->get();

        $sks = 0;
        foreach ($record as $keysks) {
            $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
        }

        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $d = date('d');
        $m = $bulan[date('m')];
        $y = date('Y');

        $pdf = PDF::loadView('mhs/khs/khs_mid_pdf', ['periode_tahun' => $periode_tahun, 'periode_tipe' => $periode_tipe, 'd' => $d, 'm' => $m, 'y' => $y, 'mhs' => $mhs, 'krs' => $record, 'sks' => $sks])->setPaper('a4', 'portrait');
        return $pdf->download('KHS UTS' . ' ' . $nama . ' ' . $prodi . ' ' . $kelas . ' ' . '(' . $periode_tahun->periode_tahun . ' ' . $periode_tipe->periode_tipe . ')' . '.pdf');
    }

    public function khs_final()
    {
        $id = Auth::user()->id_user;
        $mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi')
            ->first();

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idangkatan = $mhs->idangkatan;
        $idstatus = $mhs->idstatus;
        $kodeprodi = $mhs->kodeprodi;

        $sub_thn = substr($periode_tahun->periode_tahun, 6, 2);
        $tp = $periode_tipe->id_periodetipe;
        $smt = $sub_thn . $tp;
        $angk = $idangkatan;

        if ($smt % 2 != 0) {
            if ($tp == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) - 1;
            } elseif ($tp == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) . '0' . '1';
            }
        } else {
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $angk;
            $c = $b * 2;
        }

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14', 'prakerin', 'seminar', 'sidang', 'wisuda')
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        if ($cb != null) {
            $daftar = $biaya->daftar - ($biaya->daftar * $cb->daftar) / 100;
            $awal = $biaya->awal - ($biaya->awal * $cb->awal) / 100;
            $dsp = $biaya->dsp - ($biaya->dsp * $cb->dsp) / 100;
            $spp1 = $biaya->spp1 - ($biaya->spp1 * $cb->spp1) / 100;
            $spp2 = $biaya->spp2 - ($biaya->spp2 * $cb->spp2) / 100;
            $spp3 = $biaya->spp3 - ($biaya->spp3 * $cb->spp3) / 100;
            $spp4 = $biaya->spp4 - ($biaya->spp4 * $cb->spp4) / 100;
            $spp5 = $biaya->spp5 - ($biaya->spp5 * $cb->spp5) / 100;
            $spp6 = $biaya->spp6 - ($biaya->spp6 * $cb->spp6) / 100;
            $spp7 = $biaya->spp7 - ($biaya->spp7 * $cb->spp7) / 100;
            $spp8 = $biaya->spp8 - ($biaya->spp8 * $cb->spp8) / 100;
            $spp9 = $biaya->spp9 - ($biaya->spp9 * $cb->spp9) / 100;
            $spp10 = $biaya->spp10 - ($biaya->spp10 * $cb->spp10) / 100;
            $spp11 = $biaya->spp11 - ($biaya->spp11 * $cb->spp11) / 100;
            $spp12 = $biaya->spp12 - ($biaya->spp12 * $cb->spp12) / 100;
            $spp13 = $biaya->spp13 - ($biaya->spp13 * $cb->spp13) / 100;
            $spp14 = $biaya->spp14 - ($biaya->spp14 * $cb->spp14) / 100;
        } elseif ($cb == null) {
            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
        }

        //total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->sum('bayar.bayar');

        if ($c == 1) {
            $cekbyr = $daftar + $awal + $spp1 - $total_semua_dibayar;
        } elseif ($c == 2) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 - $total_semua_dibayar;
        } elseif ($c == '201') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 - $total_semua_dibayar;
        } elseif ($c == 3) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 - $total_semua_dibayar;
        } elseif ($c == 4) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
        } elseif ($c == '401') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
        } elseif ($c == 5) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 - $total_semua_dibayar;
        } elseif ($c == 6) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
        } elseif ($c == '601') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
        } elseif ($c == 7) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 - $total_semua_dibayar;
        } elseif ($c == 8) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
        } elseif ($c == '801') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
        } elseif ($c == 9) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 - $total_semua_dibayar;
        } elseif ($c == 10) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
        } elseif ($c == '1001') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
        } elseif ($c == 11) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 - $total_semua_dibayar;
        } elseif ($c == 12) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 - $total_semua_dibayar;
        } elseif ($c == 13) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 - $total_semua_dibayar;
        } elseif ($c == 14) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + $spp14 - $total_semua_dibayar;
        }

        if ($cekbyr < 1) {
            //cek jumlah matakuliah diambil
            $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->where('student_record.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tp)
                ->where('kurikulum_periode.id_periodetahun', $periode_tahun->id_periodetahun)
                ->where('student_record.status', 'TAKEN')
                ->select('kurikulum_periode.id_makul')
                ->groupBy('kurikulum_periode.id_makul', 'kurikulum_periode.id_dosen')
                ->get();

            $hit = count($records);

            //cek jumlah pengisian edom
            $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->where('edom_transaction.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tp)
                ->where('kurikulum_periode.id_periodetahun', $periode_tahun->id_periodetahun)
                ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
                ->get();

            $sekhit = count($cekedom);

            if ($hit == $sekhit) {
                $recordas = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                    ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                    ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
                    ->where('student_record.id_student', $id)
                    ->where('kurikulum_periode.id_periodetipe', $tp)
                    ->where('kurikulum_periode.id_periodetahun', $periode_tahun->id_periodetahun)
                    ->where('student_record.status', 'TAKEN')
                    ->select('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_ANGKA', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                    ->groupBy('student_record.id_kurtrans', 'student_record.nilai_AKHIR', 'matakuliah.kode', 'matakuliah.makul', 'student_record.nilai_ANGKA', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                    ->get();

                //jumlah SKS
                $sks = 0;
                foreach ($recordas as $keysks) {
                    $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
                }

                //cek nilai x sks
                $nxsks = 0;
                foreach ($recordas as $totsks) {
                    $nxsks += ($totsks->akt_sks_teori + $totsks->akt_sks_praktek) * $totsks->nilai_ANGKA;
                }

                return view('mhs/khs_final', ['periode_tahun' => $periode_tahun, 'periode_tipe' => $periode_tipe, 'mhs' => $mhs, 'krs' => $recordas, 'sks' => $sks, 'nxsks' => $nxsks]);
            } else {
                Alert::error('maaf anda belum melakukan pengisian edom', 'MAAF !!');
                return redirect('home');
            }
        } else {
            Alert::warning('Maaf anda tidak dapat melihat KHS karena keuangan Anda belum memenuhi syarat');
            return redirect('home');
        }
    }

    public function uang()
    {
        $id = Auth::user()->id_user;

        $maha = Student::where('idstudent', $id)->first();

        $ky = $maha->idstudent;
        $idangkatan = $maha->idangkatan;
        $idstatus = $maha->idstatus;
        $kodeprodi = $maha->kodeprodi;

        $itembayar = Itembayar::all();

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'seminar', 'sidang', 'wisuda')
            ->get();

        foreach ($biaya as $value) {
            // code...
        }

        $totalbiaya = $value->daftar + $value->awal + $value->dsp + $value->spp1 + $value->spp2 + $value->spp3 + $value->spp4 + $value->spp5 + $value->spp6 + $value->spp7 + $value->spp8 + $value->spp9 + $value->spp10 + $value->seminar + $value->sidang + $value->wisuda;

        $cekbeasiswa = Beasiswa::where('idstudent', $ky)->get();

        if (count($cekbeasiswa) > 0) {
            foreach ($cekbeasiswa as $cb) {
                // code...
            }

            $bswsdftr = $value->daftar - ($value->daftar * $cb->daftar) / 100;
            $bswsawal = $value->awal - ($value->awal * $cb->awal) / 100;
            $bswsdsp = $value->dsp - ($value->dsp * $cb->dsp) / 100;
            $bswspp1 = $value->spp1 - ($value->spp1 * $cb->spp1) / 100;
            $bswspp2 = $value->spp2 - ($value->spp2 * $cb->spp2) / 100;
            $bswspp3 = $value->spp3 - ($value->spp3 * $cb->spp3) / 100;
            $bswspp4 = $value->spp4 - ($value->spp4 * $cb->spp4) / 100;
            $bswspp5 = $value->spp5 - ($value->spp5 * $cb->spp5) / 100;
            $bswspp6 = $value->spp6 - ($value->spp6 * $cb->spp6) / 100;
            $bswspp7 = $value->spp7 - ($value->spp7 * $cb->spp7) / 100;
            $bswspp8 = $value->spp8 - ($value->spp8 * $cb->spp8) / 100;
            $bswspp9 = $value->spp9 - ($value->spp9 * $cb->spp9) / 100;
            $bswspp10 = $value->spp10 - ($value->spp10 * $cb->spp10) / 100;
            $bswssmn = $value->seminar - ($value->seminar * $cb->seminar) / 100;
            $bswssdg = $value->sidang - ($value->sidang * $cb->sidang) / 100;
            $bswswsd = $value->wisuda - ($value->wisuda * $cb->wisuda) / 100;

            $totalall = $bswsdftr + $bswsawal + $bswsdsp + $bswspp1 + $bswspp2 + $bswspp3 + $bswspp4 + $bswspp5 + $bswspp6 + $bswspp7 + $bswspp8 + $bswspp9 + $bswspp10 + $bswssmn + $bswssdg + $bswswsd;
        } else {
            $bswsdftr = $value->daftar;
            $bswsawal = $value->awal;
            $bswsdsp = $value->dsp;
            $bswspp1 = $value->spp1;
            $bswspp2 = $value->spp2;
            $bswspp3 = $value->spp3;
            $bswspp4 = $value->spp4;
            $bswspp5 = $value->spp5;
            $bswspp6 = $value->spp6;
            $bswspp7 = $value->spp7;
            $bswspp8 = $value->spp8;
            $bswspp9 = $value->spp9;
            $bswspp10 = $value->spp10;
            $bswssmn = $value->seminar;
            $bswssdg = $value->sidang;
            $bswswsd = $value->wisuda;

            $totalall = $bswsdftr + $bswsawal + $bswsdsp + $bswspp1 + $bswspp2 + $bswspp3 + $bswspp4 + $bswspp5 + $bswspp6 + $bswspp7 + $bswspp8 + $bswspp9 + $bswspp10 + $bswssmn + $bswssdg + $bswswsd;
        }

        $kuitansi = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
            ->where('kuitansi.idstudent', $ky)
            ->select('bayar.bayar', 'kuitansi.tanggal', 'kuitansi.nokuit', 'bayar.iditem')
            ->orderBy('kuitansi.tanggal', 'ASC')
            ->get();

        $totalbayarmhs = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
            ->where('kuitansi.idstudent', $ky)
            ->sum('bayar.bayar');

        $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 1)
            ->sum('bayar.bayar');

        $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 2)
            ->sum('bayar.bayar');

        $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 3)
            ->sum('bayar.bayar');

        $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 4)
            ->sum('bayar.bayar');

        $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 5)
            ->sum('bayar.bayar');

        $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 6)
            ->sum('bayar.bayar');

        $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 7)
            ->sum('bayar.bayar');

        $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 8)
            ->sum('bayar.bayar');

        $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 9)
            ->sum('bayar.bayar');

        $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 10)
            ->sum('bayar.bayar');

        $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 11)
            ->sum('bayar.bayar');

        $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 12)
            ->sum('bayar.bayar');

        $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 13)
            ->sum('bayar.bayar');

        $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 14)
            ->sum('bayar.bayar');

        $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 15)
            ->sum('bayar.bayar');

        $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $ky)
            ->where('bayar.iditem', 16)
            ->sum('bayar.bayar');

        $totalsisa = $sisadaftar + $sisaawal + $sisadsp + $sisaspp1 + $sisaspp2 + $sisaspp3 + $sisaspp4 + $sisaspp5 + $sisaspp6 + $sisaspp7 + $sisaspp8 + $sisaspp9 + $sisaspp10 + $sisaseminar + $sisasidang + $sisawisuda;

        $kurangdaftar = $bswsdftr - $sisadaftar;
        $kurangawal = $bswsawal - $sisaawal;
        $kurangdsp = $bswsdsp - $sisadsp;
        $kurangspp1 = $bswspp1 - $sisaspp1;
        $kurangspp2 = $bswspp2 - $sisaspp2;
        $kurangspp3 = $bswspp3 - $sisaspp3;
        $kurangspp4 = $bswspp4 - $sisaspp4;
        $kurangspp5 = $bswspp5 - $sisaspp5;
        $kurangspp6 = $bswspp6 - $sisaspp6;
        $kurangspp7 = $bswspp7 - $sisaspp7;
        $kurangspp8 = $bswspp8 - $sisaspp8;
        $kurangspp9 = $bswspp9 - $sisaspp9;
        $kurangspp10 = $bswspp10 - $sisaspp10;
        $kurangseminar = $bswssmn - $sisaseminar;
        $kurangsidang = $bswssdg - $sisasidang;
        $kurangwisuda = $bswswsd - $sisawisuda;

        $thn = Periode_tahun::where('status', 'ACTIVE')->get();

        foreach ($thn as $tahun) {
            // code...
        }

        $tp = Periode_tipe::where('status', 'ACTIVE')->get();

        foreach ($tp as $tipe) {
            // code...
        }

        $sub_thn = substr($tahun->periode_tahun, 6, 2);
        $tp = $tipe->id_periodetipe;
        $smt = $sub_thn . $tp;
        $angk = $maha->idangkatan;

        if ($smt % 2 != 0) {
            $a = ($smt + 10 - 1) / 10;
            $b = $a - $angk;
            $c = $b * 2 - 1;
        } else {
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $angk;
            $c = $b * 2;
        }

        $item = Itembayar::all();

        $mhsbea = Beasiswa::where('idstudent', $ky)->get();

        if (count($mhsbea) > 0) {
            foreach ($mhsbea as $keybea) {
                // code...
            }

            $beadaftar = $keybea->daftar;
            $beaawal = $keybea->awal;
            $beadsp = $keybea->dsp;
            $beaspp1 = $keybea->spp1;
            $beaspp2 = $keybea->spp2;
            $beaspp3 = $keybea->spp3;
            $beaspp4 = $keybea->spp4;
            $beaspp5 = $keybea->spp5;
            $beaspp6 = $keybea->spp6;
            $beaspp7 = $keybea->spp7;
            $beaspp8 = $keybea->spp8;
            $beaspp9 = $keybea->spp9;
            $beaspp10 = $keybea->spp10;
            $beasmn = $keybea->seminar;
            $beasdg = $keybea->sidang;
            $beawsd = $keybea->wisuda;
        } else {
            $beadaftar = '0';
            $beaawal = '0';
            $beadsp = '0';
            $beaspp1 = '0';
            $beaspp2 = '0';
            $beaspp3 = '0';
            $beaspp4 = '0';
            $beaspp5 = '0';
            $beaspp6 = '0';
            $beaspp7 = '0';
            $beaspp8 = '0';
            $beaspp9 = '0';
            $beaspp10 = '0';
            $beasmn = '0';
            $beasdg = '0';
            $beawsd = '0';
        }

        return view('mhs/keuangan', [
            'beawsd' => $beawsd,
            'beasdg' => $beasdg,
            'beasmn' => $beasmn,
            'beaspp10' => $beaspp10,
            'beaspp9' => $beaspp9,
            'beaspp8' => $beaspp8,
            'beaspp7' => $beaspp7,
            'beaspp6' => $beaspp6,
            'beaspp5' => $beaspp5,
            'beaspp4' => $beaspp4,
            'beaspp3' => $beaspp3,
            'beaspp2' => $beaspp2,
            'beaspp1' => $beaspp1,
            'beadsp' => $beadsp,
            'beaawal' => $beaawal,
            'beadaftar' => $beadaftar,
            'totalbayarmhs' => $totalbayarmhs,
            'c' => $c,
            'items' => $item,
            'kurangwisuda' => $kurangwisuda,
            'kurangsidang' => $kurangsidang,
            'kurangseminar' => $kurangseminar,
            'kurangspp6' => $kurangspp6,
            'kurangspp5' => $kurangspp5,
            'kurangspp4' => $kurangspp1,
            'kurangspp3' => $kurangspp1,
            'kurangspp2' => $kurangspp2,
            'kurangspp1' => $kurangspp1,
            'kurangdsp' => $kurangdsp,
            '$kurangdaftar' => $kurangdaftar,
            'kurangawal' => $kurangawal,
            'totalsisa' => $totalsisa,
            'sisadsp' => $sisadsp,
            'sisaspp1' => $sisaspp1,
            'sisaspp2' => $sisaspp2,
            'sisaspp3' => $sisaspp3,
            'sisaspp4' => $sisaspp4,
            'sisaspp5' => $sisaspp5,
            'sisaspp6' => $sisaspp6,
            'sisaspp7' => $sisaspp7,
            'sisaspp8' => $sisaspp8,
            'sisaspp9' => $sisaspp9,
            'sisaspp10' => $sisaspp10,
            'sisaseminar' => $sisaseminar,
            'sisasidang' => $sisasidang,
            'sisawisuda' => $sisawisuda,
            'sisaawal' => $sisaawal,
            'sisadaftar' => $sisadaftar,
            'total' => $totalall,
            'daftar' => $bswsdftr,
            'awal' => $bswsawal,
            'dsp' => $bswsdsp,
            'spp1' => $bswspp1,
            'spp2' => $bswspp2,
            'spp3' => $bswspp3,
            'spp4' => $bswspp4,
            'spp5' => $bswspp5,
            'spp6' => $bswspp6,
            'spp7' => $bswspp7,
            'spp8' => $bswspp8,
            'spp9' => $bswspp9,
            'spp10' => $bswspp10,
            'seminar' => $bswssmn,
            'sidang' => $bswssdg,
            'wisuda' => $bswswsd,
            'biaya' => $value,
            'kuit' => $kuitansi,
            'itembayar' => $itembayar,
            'totalbiaya' => $totalbiaya,
        ]);
    }

    public function record_biaya()
    {
        $id = Auth::user()->id_user;

        $maha = Student::where('idstudent', $id)->first();

        $kuitansi = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
            ->where('kuitansi.idstudent', $id)
            ->select('bayar.bayar', 'kuitansi.tanggal', 'kuitansi.nokuit', 'bayar.iditem', 'itembayar.item')
            ->orderBy('kuitansi.tanggal', 'ASC')
            ->get();

        $totalbayarmhs = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->join('itembayar', 'bayar.iditem', '=', 'itembayar.iditem')
            ->where('kuitansi.idstudent', $id)
            ->sum('bayar.bayar');

        return view('mhs/keuangan/record_biaya', compact('kuitansi', 'totalbayarmhs'));
    }

    public function data_biaya()
    {
        $id = Auth::user()->id_user;

        $maha = Student::where('idstudent', $id)->first();

        $cek_study = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->where('student.idstudent', $id)
            ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        $biaya = Biaya::where('idangkatan', $maha->idangkatan)
            ->where('idstatus', $maha->idstatus)
            ->where('kodeprodi', $maha->kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14', 'prakerin', 'magang1', 'magang2', 'seminar', 'sidang', 'wisuda')
            ->first();

        if ($cek_study->study_year == '3') {
            $itembayar = Itembayar::where('study_year', '3')
                ->orderBy('iditem', 'ASC')
                ->get();

            $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 1)
                ->sum('bayar.bayar');

            $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 2)
                ->sum('bayar.bayar');

            $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 3)
                ->sum('bayar.bayar');

            $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 4)
                ->sum('bayar.bayar');

            $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 5)
                ->sum('bayar.bayar');

            $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 6)
                ->sum('bayar.bayar');

            $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 7)
                ->sum('bayar.bayar');

            $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 8)
                ->sum('bayar.bayar');

            $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 9)
                ->sum('bayar.bayar');

            $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 10)
                ->sum('bayar.bayar');

            $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 11)
                ->sum('bayar.bayar');

            $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 12)
                ->sum('bayar.bayar');

            $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 13)
                ->sum('bayar.bayar');

            $sisaprakerin = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 36)
                ->sum('bayar.bayar');

            $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 14)
                ->sum('bayar.bayar');

            $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 15)
                ->sum('bayar.bayar');

            $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 16)
                ->sum('bayar.bayar');

            return view('mhs/keuangan/tabel_biaya', compact('maha', 'itembayar', 'cb', 'biaya', 'sisadaftar', 'sisaawal', 'sisadsp', 'sisaspp1', 'sisaspp2', 'sisaspp3', 'sisaspp4', 'sisaspp5', 'sisaspp6', 'sisaspp7', 'sisaspp8', 'sisaspp9', 'sisaspp10', 'sisaprakerin', 'sisaseminar', 'sisasidang', 'sisawisuda'));
        } elseif ($cek_study->study_year == '4') {
            $itembayar = Itembayar::where('study_year', '4')
                ->orderBy('iditem', 'ASC')
                ->get();

            $sisadaftar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 1)
                        ->orWhere('bayar.iditem', 18);
                })
                ->sum('bayar.bayar');

            $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 2)
                        ->orWhere('bayar.iditem', 19);
                })
                ->sum('bayar.bayar');

            $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 3)
                        ->orWhere('bayar.iditem', 20);
                })
                ->sum('bayar.bayar');

            $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 4)
                        ->orWhere('bayar.iditem', 21);
                })
                ->sum('bayar.bayar');

            $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 5)
                        ->orWhere('bayar.iditem', 22);
                })
                ->sum('bayar.bayar');

            $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 6)
                        ->orWhere('bayar.iditem', 23);
                })
                ->sum('bayar.bayar');

            $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 7)
                        ->orWhere('bayar.iditem', 24);
                })
                ->sum('bayar.bayar');

            $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 8)
                        ->orWhere('bayar.iditem', 25);
                })
                ->sum('bayar.bayar');

            $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 9)
                        ->orWhere('bayar.iditem', 26);
                })
                ->sum('bayar.bayar');

            $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 10)
                        ->orWhere('bayar.iditem', 27);
                })
                ->sum('bayar.bayar');

            $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 11)
                        ->orWhere('bayar.iditem', 28);
                })
                ->sum('bayar.bayar');

            $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 12)
                        ->orWhere('bayar.iditem', 29);
                })
                ->sum('bayar.bayar');

            $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 13)
                        ->orWhere('bayar.iditem', 30);
                })
                ->sum('bayar.bayar');

            $sisaspp11 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 31)
                ->sum('bayar.bayar');

            $sisaspp12 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 32)
                ->sum('bayar.bayar');

            $sisaspp13 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 33)
                ->sum('bayar.bayar');

            $sisaspp14 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 34)
                ->sum('bayar.bayar');

            // $sisaprakerin = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            //     ->where('kuitansi.idstudent', $id)
            //     ->where(function ($query) {
            //         $query
            //             ->where('bayar.iditem', 36);
            //     })
            //     ->sum('bayar.bayar');


            $sisamagang1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 35)
                ->sum('bayar.bayar');

            $sisamagang2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 37)
                ->sum('bayar.bayar');

            $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where(function ($query) {
                    $query
                        ->where('bayar.iditem', 38);
                })
                ->sum('bayar.bayar');

            $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 39)
                ->sum('bayar.bayar');

            $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 40)
                ->sum('bayar.bayar');

            return view('mhs/keuangan/tabel_biaya', compact(
                'maha',
                'itembayar',
                'cb',
                'biaya',
                'sisadaftar',
                'sisaawal',
                'sisadsp',
                'sisaspp1',
                'sisaspp2',
                'sisaspp3',
                'sisaspp4',
                'sisaspp5',
                'sisaspp6',
                'sisaspp7',
                'sisaspp8',
                'sisaspp9',
                'sisaspp10',
                'sisaspp11',
                'sisaspp12',
                'sisaspp13',
                'sisaspp14',
                'sisamagang1',
                'sisamagang2',
                'sisaseminar',
                'sisasidang',
                'sisawisuda'
            ));
        }
    }

    public function lihat_semua()
    {
        $info = Informasi::orderBy('created_at', 'DESC')->get();

        return view('mhs/all_info', ['info' => $info]);
    }

    public function lihat($id)
    {
        $info = Informasi::find($id);

        return view('mhs/lihatinfo', ['info' => $info]);
    }

    public function ganti_foto($id)
    {
        $id = Auth::user()->username;
        $mhs = Student::where('nim', $id)->get();
        foreach ($mhs as $maha) {
            // code...
        }

        return view('mhs/ganti_foto', ['mhs' => $maha]);
    }

    public function simpanfoto(Request $request, $id)
    {

        $this->validate($request, [
            'foto' => 'required|mimes:jpeg,jpg|max:500',
        ]);

        $foto = Student::find($id);

        if ($foto->foto) {
            if ($request->hasFile('foto')) {
                File::delete('foto_mhs/' . $foto->foto);
                $file = $request->file('foto');
                $nama_file = Auth::user()->username . '.jpg';
                $tujuan_upload = 'foto_mhs';
                $file->move($tujuan_upload, $nama_file);
                $foto->foto = $nama_file;
                $foto->save();
                Alert::success('', 'Foto berhasil disimpan')->autoclose(3500);
                return redirect('home');
            }
        } else {
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $nama_file = Auth::user()->username . '.jpg';
                $tujuan_upload = 'foto_mhs';
                $file->move($tujuan_upload, $nama_file);
                $foto->foto = $nama_file;
                $foto->save();
                Alert::success('', 'Foto berhasil disimpan')->autoclose(3500);
                return redirect('home');
            }
        }
    }

    public function jdl_uts()
    {
        $id_mhs = Auth::user()->id_user;
        $data_mhs = Student::where('student.idstudent', $id_mhs)
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->select('student.idstatus', 'prodi.id_prodi')
            ->first();

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $jadwal_uts = DB::select('CALL jadwal_uts_uas_mhs(?,?,?,?,?,?)', ['UTS', $thn->id_periodetahun, $tp->id_periodetipe, $data_mhs->id_prodi, $data_mhs->idstatus, $id_mhs]);

        return view('mhs/jadwal_uts', compact('jadwal_uts'));
    }

    public function jdl_uas()
    {
        $id_mhs = Auth::user()->id_user;
        $data_mhs = Student::where('student.idstudent', $id_mhs)
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->select('student.idstatus', 'prodi.id_prodi')
            ->first();

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $jadwal_uas = DB::select('CALL jadwal_uts_uas_mhs(?,?,?,?,?,?)', ['UAS', $thn->id_periodetahun, $tp->id_periodetipe, $data_mhs->id_prodi, $data_mhs->idstatus, $id_mhs]);

        return view('mhs/jadwal_uas', compact('jadwal_uas'));
    }

    public function pedoman_akademik()
    {
        $thn = Periode_tahun::all();
        $pedoman = Pedoman_akademik::where('status', 'ACTIVE')->get();

        return view('mhs/pedoman_akademik', ['pedoman' => $pedoman, 'idhn' => $thn]);
    }

    public function download_pedoman($id)
    {
        $ped = Pedoman_akademik::where('id_pedomanakademik', $id)->get();
        foreach ($ped as $keyped) {
            // code...
        }
        //PDF file is stored under project/public/download/info.pdf
        $file = 'pedoman/' . $keyped->file;
        return Response::download($file);
    }

    public function put_nisn(Request $request, $id)
    {

        $nisn = $request->nisn;
        $ceknisn = Student::where('nisn', $nisn)->get();

        $message = [
            'max' => ':attribute harus diisi maksimal :max digit',
            'min' => ':attribute harus diisi minimal :min digit',
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute sudah terdaftar',
        ];

        $this->validate(
            $request,
            [
                'nisn' => 'required|max:10|min:5|unique:student',
            ],
            $message,
        );

        if (count($ceknisn) == 0) {
            $prd = Student::find($id);
            $prd->nisn = $nisn;
            $prd->save();

            Alert::success('', 'NISN anda berhasil diedit')->autoclose(3500);
            return redirect('home');
        } else {
            Alert::error('NISN sudah terdaftar !', 'MAAF !!');

            return redirect('home');
        }
    }

    public function dosbing()
    {

        $id = Auth::user()->id_user;

        $student = Student::with(['prodi'])
            ->where('idstudent', $id)
            ->first();

        $dosen_pa = DosenPembimbing::join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
            ->where('id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_pkl = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_magang1 = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [24, 27, 30])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_magang2 = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [33, 34, 35])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_sempro = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6, 13, 16, 19, 22, 25, 28, 31])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_ta = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_skripsi = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        return view('mhs/dosbing', compact(
            'dosen_pa',
            'dosen_pkl',
            'dosen_sempro',
            'dosen_ta',
            'dosen_magang1',
            'dosen_magang2',
            'dosen_skripsi',
            'student'
        ));
    }

    public function kuisioner()
    {
        $data = Kuisioner_kategori::where('status', 'ACTIVE')->get();

        return view('mhs/kuisioner/kuisioner_all', compact('data'));
    }

    public function isi_dosen_pa($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = DosenPembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
                ->join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->where('student.idstudent', $ids)
                ->where('dosen_pembimbing.status', 'ACTIVE')
                ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'dosen_pembimbing.id_dosen')
                ->first();

            $prodi = $mhs->prodi;
            $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            $periodetahun = $thn->periode_tahun;
            $periodetipe = $tp->periode_tipe;

            //untuk ke database
            $id_dsn = $mhs->id_dosen;
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cekkuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dsn)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master.id_kategori_kuisioner', $id)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 1)
                ->get();
            // dd($cekkuis);
            if (count($cekkuis) > 0) {
                Alert::warning('maaf kuisioner isi sudah diisi', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cekkuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner/kuisioner_dsn_pa', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
            }
        }
    }

    public function save_kuisioner_dsn_pa(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_pembimbing;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 1)
            ->get();
        // dd($cek_kuis->toArray());
        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_pembimbing = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_dosen_pkl($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', [
                'FA-601',
                'TI-601',
                'TK-601',
                'FA-5001',
                'TI-5001',
                'PL/6001',
                'PL/6002',
                'PL/6003',
                'PL/6004',
                'PL/6005',
                'PL/7001',
                'PL/7002',
                'PL/7003',
                'PL/7004',
                'PL/7005',
            ])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        // if ($hasil_krs == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian KRS Kerja Praktek/Prakerin', 'MAAF !!');
        //     return redirect('kuisioner');
        // } elseif ($hasil_krs > 0) {
        //cek nilai dan file seminar prakerin
        $cekdata_bim = Prausta_setting_relasi::where('prausta_setting_relasi.id_student', $ids)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21, 24, 27, 30, 33, 34, 35])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.id_dosen_pembimbing')
            ->get();

        if (count($cekdata_bim) == 0) {
            Alert::error('Maaf dosen pembimbbing anda belum disetting untuk Prakerin/Magang', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cekdata_bim) > 0) {
            $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->where('student.idstudent', $ids)
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21, 24, 27, 30, 33, 34, 35])
                ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_pembimbing')
                ->first();

            $prodi = $mhs->prodi;
            $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            $periodetahun = $thn->periode_tahun;
            $periodetipe = $tp->periode_tipe;

            //untuk ke database
            $id_dsn = $mhs->id_dosen_pembimbing;
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dsn)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master.id_kategori_kuisioner', $id)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner/kuisioner_dsn_pkl', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
            }
        }
        // }
    }

    public function save_kuisioner_dsn_pkl(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_pembimbing;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 2)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_pembimbing = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_dosen_ta($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602', 'PL/8001'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        // if ($hasil_krs == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
        //     return redirect('kuisioner');
        // } elseif ($hasil_krs > 0) {
        $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23, 26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $ids)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing')
            ->get();

        if (count($cekdata) == 0) {
            Alert::error('Maaf Dosen Pembimbing Tugas Akhir anda belum di setting', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cekdata) > 0) {
            $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->where('student.idstudent', $ids)
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23, 26, 29, 32])
                ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_pembimbing')
                ->first();

            $prodi = $mhs->prodi;
            $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            $periodetahun = $thn->periode_tahun;
            $periodetipe = $tp->periode_tipe;

            //untuk ke database
            $id_dsn = $mhs->id_dosen_pembimbing;
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dsn)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master.id_kategori_kuisioner', $id)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner/kuisioner_dsn_ta', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
            }
        }
        // }
    }

    public function save_kuisioner_dsn_ta(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_pembimbing;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 3)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_pembimbing = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_dosen_ta_peng1($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602', 'PL/8001'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        // if ($hasil_krs == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
        //     return redirect('kuisioner');
        // } elseif ($hasil_krs > 0) {
        $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23, 26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $ids)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing')
            ->get();
        if (count($cekdata) == 0) {
            Alert::error('Maaf Dosen Pembimbing Tugas Akhir anda belum di setting', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cekdata) > 0) {
            foreach ($cekdata as $cek_peng1) {
                # code...
            }

            if ($cek_peng1->id_dosen_penguji_1 == null) {
                Alert::error('Maaf Dosen Penguji 1 Sidang Tugas Akhir anda belum di setting', 'MAAF !!');
                return redirect('kuisioner');
            } elseif ($cek_peng1->id_dosen_penguji_1 != null) {
                $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_1', '=', 'dosen.iddosen')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->where('student.idstudent', $ids)
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23, 26, 29, 32])
                    ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_penguji_1')
                    ->first();

                $prodi = $mhs->prodi;
                $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

                $thn = Periode_tahun::where('status', 'ACTIVE')->first();

                $tp = Periode_tipe::where('status', 'ACTIVE')->first();

                $periodetahun = $thn->periode_tahun;
                $periodetipe = $tp->periode_tipe;

                //untuk ke database
                $id_dsn = $mhs->id_dosen_penguji_1;
                $idthn = $thn->id_periodetahun;
                $idtp = $tp->id_periodetipe;

                $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                    ->where('kuisioner_transaction.id_student', $ids)
                    ->where('kuisioner_transaction.id_dosen_penguji_1', $id_dsn)
                    ->where('kuisioner_transaction.id_periodetahun', $idthn)
                    ->where('kuisioner_transaction.id_periodetipe', $idtp)
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->get();

                if (count($cek_kuis) > 0) {
                    Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                    return redirect('kuisioner');
                } elseif (count($cek_kuis) == 0) {
                    $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                        ->where('kuisioner_master.id_kategori_kuisioner', $id)
                        ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                        ->get();

                    return view('mhs/kuisioner/kuisioner_dsn_ta_peng1', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
                }
            }
        }
        // }
    }

    public function save_kuisioner_dsn_ta_peng1(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_penguji_1;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_penguji_1', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 4)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_penguji_1 = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_dosen_ta_peng2($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602', 'PL/8001'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        // if ($hasil_krs == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
        //     return redirect('kuisioner');
        // } elseif ($hasil_krs > 0) {
        $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23, 26, 29, 32])
            ->where('prausta_setting_relasi.id_student', $ids)
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing')
            ->get();
        if (count($cekdata) == 0) {
            Alert::error('Maaf Dosen Pembimbing Tugas Akhir anda belum di setting', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cekdata) > 0) {
            foreach ($cekdata as $cek_peng1) {
                # code...
            }
            if ($cek_peng1->id_dosen_penguji_2 == null) {
                Alert::error('Maaf Dosen Penguji 2 Sidang Tugas Akhir anda belum di setting', 'MAAF !!');
                return redirect('kuisioner');
            } elseif ($cek_peng1->id_dosen_penguji_2 != null) {
                $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_2', '=', 'dosen.iddosen')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->where('student.idstudent', $ids)
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23, 26, 29, 32])
                    ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_penguji_2')
                    ->first();

                $prodi = $mhs->prodi;
                $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

                $thn = Periode_tahun::where('status', 'ACTIVE')->first();

                $tp = Periode_tipe::where('status', 'ACTIVE')->first();

                $periodetahun = $thn->periode_tahun;
                $periodetipe = $tp->periode_tipe;

                //untuk ke database
                $id_dsn = $mhs->id_dosen_penguji_2;
                $idthn = $thn->id_periodetahun;
                $idtp = $tp->id_periodetipe;

                $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                    ->where('kuisioner_transaction.id_student', $ids)
                    ->where('kuisioner_transaction.id_dosen_penguji_2', $id_dsn)
                    ->where('kuisioner_transaction.id_periodetahun', $idthn)
                    ->where('kuisioner_transaction.id_periodetipe', $idtp)
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->get();

                if (count($cek_kuis) > 0) {
                    Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                    return redirect('kuisioner');
                } elseif (count($cek_kuis) == 0) {
                    $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                        ->where('kuisioner_master.id_kategori_kuisioner', $id)
                        ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                        ->get();

                    return view('mhs/kuisioner/kuisioner_dsn_ta_peng2', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
                }
            }
        }
        // }
    }

    public function save_kuisioner_dsn_ta_peng2(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_penguji_2;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_penguji_2', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 5)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_penguji_2 = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_kuis_baak($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner/kuisioner_baak', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_baak(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_kuis_bauk($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner/kuisioner_bauk', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_bauk(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_kuis_perpus($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner/kuisioner_perpus', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_perpus(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function isi_kuis_beasiswa($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 9)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner/kuisioner_beasiswa', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_beasiswa(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 9)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('kuisioner');
    }

    public function kartu_uts()
    {
        $id = Auth::user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.id_prodi')
            ->first();

        $idangkatan = $datamhs->idangkatan;
        $idstatus = $datamhs->idstatus;
        $kodeprodi = $datamhs->kodeprodi;
        $idprodi = $datamhs->id_prodi;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        //cek semester
        $sub_thn = substr($thn->periode_tahun, 6, 2);
        $tipe = $tp->id_periodetipe;
        $smt = $sub_thn . $tipe;

        if ($smt % 2 != 0) {
            if ($tipe == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) - 1;
            } elseif ($tipe == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) . '0' . '1';
            }
        } else {
            //genap
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $idangkatan;
            $c = $b * 2;
        }

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14')
            ->first();

        $cek_bea = Beasiswa::where('idstudent', $id)->first();

        if ($cek_bea != null) {
            $daftar = $biaya->daftar - ($biaya->daftar * $cek_bea->daftar) / 100;
            $awal = $biaya->awal - ($biaya->awal * $cek_bea->awal) / 100;
            $dsp = $biaya->dsp - ($biaya->dsp * $cek_bea->dsp) / 100;
            $spp1 = $biaya->spp1 - ($biaya->spp1 * $cek_bea->spp1) / 100;
            $spp2 = $biaya->spp2 - ($biaya->spp2 * $cek_bea->spp2) / 100;
            $spp3 = $biaya->spp3 - ($biaya->spp3 * $cek_bea->spp3) / 100;
            $spp4 = $biaya->spp4 - ($biaya->spp4 * $cek_bea->spp4) / 100;
            $spp5 = $biaya->spp5 - ($biaya->spp5 * $cek_bea->spp5) / 100;
            $spp6 = $biaya->spp6 - ($biaya->spp6 * $cek_bea->spp6) / 100;
            $spp7 = $biaya->spp7 - ($biaya->spp7 * $cek_bea->spp7) / 100;
            $spp8 = $biaya->spp8 - ($biaya->spp8 * $cek_bea->spp8) / 100;
            $spp9 = $biaya->spp9 - ($biaya->spp9 * $cek_bea->spp9) / 100;
            $spp10 = $biaya->spp10 - ($biaya->spp10 * $cek_bea->spp10) / 100;
            $spp11 = $biaya->spp11 - ($biaya->spp11 * $cek_bea->spp11) / 100;
            $spp12 = $biaya->spp12 - ($biaya->spp12 * $cek_bea->spp12) / 100;
            $spp13 = $biaya->spp13 - ($biaya->spp13 * $cek_bea->spp13) / 100;
            $spp14 = $biaya->spp14 - ($biaya->spp14 * $cek_bea->spp14) / 100;
        } elseif ($cek_bea == null) {
            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
        }

        //total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->sum('bayar.bayar');

        if ($c == 1) {
            $cekbyr = $daftar + $awal + ($spp1 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == 2) {
            $cekbyr = $daftar + $awal + ($dsp * 25) / 100 + $spp1 + ($spp2 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == '201') {
            $cekbyr = ($daftar + $awal + ($dsp * 91 / 100) + $spp1 + ($spp2 * 82 / 100)) - $total_semua_dibayar;
        } elseif ($c == 3) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + ($spp3 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == 4) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == '401') {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82 / 100)) - $total_semua_dibayar;
        } elseif ($c == 5) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == 6) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == '601') {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82 / 100)) - $total_semua_dibayar;
        } elseif ($c == 7) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + ($spp7 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == 8) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == '801') {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82 / 100)) - $total_semua_dibayar;
        } elseif ($c == 9) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + ($spp9 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == 10) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == '1001') {
            $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82 / 100)) - $total_semua_dibayar;
        } elseif ($c == 11) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + ($spp11 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == 12) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11(($spp12 * 50) / 100) - $total_semua_dibayar;
        } elseif ($c == 13) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + ($spp13 * 50) / 100 - $total_semua_dibayar;
        } elseif ($c == 14) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + ($spp14 * 50) / 100 - $total_semua_dibayar;
        }

        $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
            ->first();

        if ($cekbyr == 0 or $cekbyr < 1) {
            $data_uts = DB::select('CALL jadwal_uts(?,?,?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

            return view('mhs/ujian/kartu_uts', compact('periodetahun', 'periodetipe', 'datamhs', 'data_uts'));
        } else {
            Alert::warning('Maaf anda tidak dapat mendownload Kartu Ujian UTS karena keuangan Anda belum memenuhi syarat');
            return redirect('home');
        }
    }

    public function kartu_uas()
    {
        $id = Auth::user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.id_prodi')
            ->first();

        $idangkatan = $datamhs->idangkatan;
        $idstatus = $datamhs->idstatus;
        $kodeprodi = $datamhs->kodeprodi;
        $idprodi = $datamhs->id_prodi;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        //cek semester
        $sub_thn = substr($thn->periode_tahun, 6, 2);
        $tipe = $tp->id_periodetipe;
        $smt = $sub_thn . $tipe;

        if ($smt % 2 != 0) {
            if ($tipe == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) - 1;
            } elseif ($tipe == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10;
                $b = $a - $idangkatan;
                $c = ($b * 2) . '0' . '1';
            }
        } else {
            //genap
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $idangkatan;
            $c = $b * 2;
        }

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14', 'prakerin')
            ->first();

        $cek_bea = Beasiswa::where('idstudent', $id)->first();

        if ($cek_bea != null) {
            $daftar = $biaya->daftar - ($biaya->daftar * $cek_bea->daftar) / 100;
            $awal = $biaya->awal - ($biaya->awal * $cek_bea->awal) / 100;
            $dsp = $biaya->dsp - ($biaya->dsp * $cek_bea->dsp) / 100;
            $spp1 = $biaya->spp1 - ($biaya->spp1 * $cek_bea->spp1) / 100;
            $spp2 = $biaya->spp2 - ($biaya->spp2 * $cek_bea->spp2) / 100;
            $spp3 = $biaya->spp3 - ($biaya->spp3 * $cek_bea->spp3) / 100;
            $spp4 = $biaya->spp4 - ($biaya->spp4 * $cek_bea->spp4) / 100;
            $spp5 = $biaya->spp5 - ($biaya->spp5 * $cek_bea->spp5) / 100;
            $spp6 = $biaya->spp6 - ($biaya->spp6 * $cek_bea->spp6) / 100;
            $spp7 = $biaya->spp7 - ($biaya->spp7 * $cek_bea->spp7) / 100;
            $spp8 = $biaya->spp8 - ($biaya->spp8 * $cek_bea->spp8) / 100;
            $spp9 = $biaya->spp9 - ($biaya->spp9 * $cek_bea->spp9) / 100;
            $spp10 = $biaya->spp10 - ($biaya->spp10 * $cek_bea->spp10) / 100;
            $spp11 = $biaya->spp11 - ($biaya->spp11 * $cek_bea->spp11) / 100;
            $spp12 = $biaya->spp12 - ($biaya->spp12 * $cek_bea->spp12) / 100;
            $spp13 = $biaya->spp13 - ($biaya->spp13 * $cek_bea->spp13) / 100;
            $spp14 = $biaya->spp14 - ($biaya->spp14 * $cek_bea->spp14) / 100;
            $prakerin = $biaya->prakerin - (($biaya->prakerin * ($cek_bea->prakerin)) / 100);
        } elseif ($cek_bea == null) {
            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
            $prakerin = $biaya->prakerin;
        }

        //total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->sum('bayar.bayar');

        if ($c == 1) {
            $cekbyr = $daftar + $awal + $spp1 - $total_semua_dibayar;
        } elseif ($c == 2) {
            $cekbyr = $daftar + $awal + ($dsp * 91) / 100 + $spp1 + $spp2 - $total_semua_dibayar;
        } elseif ($c == '201') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2  - $total_semua_dibayar;
        } elseif ($c == 3) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 - $total_semua_dibayar;
        } elseif ($c == 4) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
        } elseif ($c == '401') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
        } elseif ($c == 5) {
            if ($kodeprodi == 23 or $kodeprodi == 24) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $prakerin - $total_semua_dibayar;
            } elseif ($kodeprodi == 25) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 - $total_semua_dibayar;
            }
        } elseif ($c == 6) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
        } elseif ($c == '601') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
        } elseif ($c == 7) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 - $total_semua_dibayar;
        } elseif ($c == 8) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
        } elseif ($c == '801') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
        } elseif ($c == 9) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 - $total_semua_dibayar;
        } elseif ($c == 10) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
        } elseif ($c == '1001') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
        } elseif ($c == 11) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 - $total_semua_dibayar;
        } elseif ($c == 12) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 - $total_semua_dibayar;
        } elseif ($c == 13) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 - $total_semua_dibayar;
        } elseif ($c == 14) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + $spp14 - $total_semua_dibayar;
        }


        if ($cekbyr == 0 or $cekbyr < 100) {
            //cek jumlah matakuliah diambil
            $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->where('student_record.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                ->where('student_record.status', 'TAKEN')
                ->get();

            $hit = count($records);

            //cek jumlah pengisian edom
            $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->where('edom_transaction.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
                ->get();

            $sekhit = count($cekedom);

            if ($hit == $sekhit) {
                //cek kuisioner pembimbing akademik
                $cek_kuis_pa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                    ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                    ->where('kuisioner_transaction.id_student', $id)
                    ->where('kuisioner_master_kategori.id_kategori_kuisioner', 1)
                    ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                    ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                    ->get();

                if (count($cek_kuis_pa) > 0) {
                    //cek kuisioner BAAK
                    $cek_kuis_baak = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                        ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                        ->where('kuisioner_transaction.id_student', $id)
                        ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
                        ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                        ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                        ->get();

                    if (count($cek_kuis_baak) > 0) {
                        //cek kuisioner BAUK
                        $cek_kuis_bauk = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                            ->where('kuisioner_transaction.id_student', $id)
                            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
                            ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                            ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                            ->get();

                        if (count($cek_kuis_bauk) > 0) {
                            //cek kuisioner PERPUS
                            $cek_kuis_perpus = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                ->where('kuisioner_transaction.id_student', $id)
                                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
                                ->where('kuisioner_transaction.id_periodetahun', $thn->id_periodetahun)
                                ->where('kuisioner_transaction.id_periodetipe', $tp->id_periodetipe)
                                ->get();

                            if (count($cek_kuis_perpus) > 0) {

                                $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                    ->where('student_record.id_student', $id)
                                    ->where('student_record.status', 'TAKEN')
                                    ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                                    ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                                    ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
                                    ->first();

                                $data_uts = DB::select('CALL jadwal_uas(?,?,?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

                                return view('mhs/ujian/kartu_uas', compact('periodetahun', 'periodetipe', 'datamhs', 'data_uts'));
                            } elseif (count($cek_kuis_perpus) == 0) {
                                Alert::error('Maaf anda belum melakukan pengisian kuisioner PERPUSTAKAAN', 'MAAF !!');
                                return redirect('home');
                            }
                        } elseif (count($cek_kuis_bauk) == 0) {
                            Alert::error('Maaf anda belum melakukan pengisian kuisioner BAUK', 'MAAF !!');
                            return redirect('home');
                        }
                    } elseif (count($cek_kuis_baak) == 0) {
                        Alert::error('Maaf anda belum melakukan pengisian kuisioner BAAK', 'MAAF !!');
                        return redirect('home');
                    }
                } elseif (count($cek_kuis_pa) == 0) {
                    Alert::error('Maaf anda belum melakukan pengisian kuisioner Pembimbing Akademik', 'MAAF !!');
                    return redirect('home');
                }
            } else {
                Alert::error('Maaf anda belum melakukan pengisian edom', 'MAAF !!');
                return redirect('home');
            }
        } else {
            Alert::warning('Maaf anda tidak dapat mendownload Kartu Ujian UAS karena keuangan Anda belum memenuhi syarat');
            return redirect('home');
        }
    }

    public function unduh_kartu_uts()
    {
        $id = Auth::user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.id_prodi')
            ->first();

        $nama = $datamhs->nama;
        $prodi = $datamhs->prodi;
        $kelas = $datamhs->kelas;
        $idprodi = $datamhs->id_prodi;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
            ->first();

        $data_uts = DB::select('CALL jadwal_uts(?,?,?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $d = date('d');
        $m = $bulan[date('m')];
        $y = date('Y');

        $pdf = PDF::loadView('mhs/ujian/unduh_kartu_uts_pdf', compact('periodetahun', 'periodetipe', 'datamhs', 'data_uts', 'd', 'm', 'y'))->setPaper('a4', 'portrait');
        return $pdf->download('Kartu UTS' . ' ' . $nama . ' ' . $prodi . ' ' . $kelas . ' ' . '(' . $periodetahun . ' ' . $periodetipe . ')' . '.pdf');
    }

    public function unduh_kartu_uas()
    {
        $id = Auth::user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', 'student.idangkatan', 'student.idstatus', 'student.kodeprodi', 'prodi.id_prodi')
            ->first();

        $nama = $datamhs->nama;
        $prodi = $datamhs->prodi;
        $kelas = $datamhs->kelas;
        $idprodi = $datamhs->id_prodi;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
            ->first();

        //$data_uts = DB::select('CALL jadwal_uas(?,?,?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

        $kartu_uas = DB::select('CALL kartu_uas(?,?,?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $d = date('d');
        $m = $bulan[date('m')];
        $y = date('Y');

        $pdf = PDF::loadView('mhs/ujian/unduh_kartu_uas_pdf', compact('periodetahun', 'periodetipe', 'datamhs', 'kartu_uas', 'd', 'm', 'y'))->setPaper('a4', 'portrait');
        return $pdf->download('Kartu UAS' . ' ' . $nama . ' ' . $prodi . ' ' . $kelas . ' ' . '(' . $periodetahun . ' ' . $periodetipe . ')' . '.pdf');
    }

    public function upload_sertifikat()
    {
        $id_student = Auth::user()->id_user;

        $data = Sertifikat::where('id_student', $id_student)->get();

        return view('mhs/skpi/sertifikat', compact('data'));
    }

    public function post_sertifikat(Request $request)
    {

        $this->validate($request, [
            'nama_kegiatan' => 'required',
            'file_sertifikat' => 'mimes:jpeg,jpg,png,JPEG,JPG,PNG|max:4000',
            'prestasi' => 'required',
            'tingkat' => 'required',
            'tgl_pelaksanaan' => 'required',
        ]);

        $id_student = Auth::user()->id_user;

        $info = new Sertifikat();
        $info->nama_kegiatan = $request->nama_kegiatan;
        $info->prestasi = $request->prestasi;
        $info->tingkat = $request->tingkat;
        $info->tgl_pelaksanaan = $request->tgl_pelaksanaan;
        $info->id_student = $id_student;
        $info->created_by = Auth::user()->name;

        if ($request->hasFile('file_sertifikat')) {
            $file = $request->file('file_sertifikat');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'Sertifikat/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $info->file_sertifikat = $nama_file;
        }

        $info->save();
        Alert::success('', 'Sertifikat berhasil diupload')->autoclose(3500);
        return redirect()->back();
    }

    public function put_sertifikat(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kegiatan' => 'required',
            'file_sertifikat' => 'mimes:jpeg,jpg,png,JPEG,JPG,PNG|max:4000',
            'prestasi' => 'required',
            'tingkat' => 'required',
            'tgl_pelaksanaan' => 'required',
        ]);

        $info = Sertifikat::find($id);
        $info->nama_kegiatan = $request->nama_kegiatan;
        $info->prestasi = $request->prestasi;
        $info->tingkat = $request->tingkat;
        $info->tgl_pelaksanaan = $request->tgl_pelaksanaan;
        $info->created_by = Auth::user()->name;

        if ($info->file_sertifikat) {
            if ($request->hasFile('file_sertifikat')) {
                File::delete('Sertifikat/' . Auth::user()->id_user . '/' . $info->file_sertifikat);
                $file = $request->file('file_sertifikat');
                $nama_file = time() . '_' . $file->getClientOriginalName();
                $tujuan_upload = 'Sertifikat/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $info->file_sertifikat = $nama_file;
            }
        } else {
            if ($request->hasFile('file_sertifikat')) {
                $file = $request->file('file_sertifikat');
                $nama_file = time() . '_' . $file->getClientOriginalName();
                $tujuan_upload = 'Sertifikat/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $info->file_sertifikat = $nama_file;
            }
        }

        $info->save();

        Alert::success('', 'Sertifikat berhasil diedit')->autoclose(3500);
        return redirect('upload_sertifikat');
    }

    public function hapus_sertifikat($id)
    {
        $gambar = Sertifikat::where('id_sertifikat', $id)->first();
        File::delete('Sertifikat/' . Auth::user()->id_user . '/' . $gambar->file_sertifikat);

        Sertifikat::where('id_sertifikat', $id)->delete();

        Alert::success('', 'Sertifikat berhasil dihapus')->autoclose(3500);
        return redirect('upload_sertifikat');
    }

    public function yudisium()
    {
        $id = Auth::user()->id_user;

        $data_mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.idstudent',
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi',
                'prodi.study_year'
            )
            ->first();

        $idangkatan = $data_mhs->idangkatan;
        $idstatus = $data_mhs->idstatus;
        $kodeprodi = $data_mhs->kodeprodi;
        $idprodi = $data_mhs->id_prodi;

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select(
                'daftar',
                'awal',
                'dsp',
                'spp1',
                'spp2',
                'spp3',
                'spp4',
                'spp5',
                'spp6',
                'spp7',
                'spp8',
                'spp9',
                'spp10',
                'spp11',
                'spp12',
                'spp13',
                'spp14',
                'prakerin',
                'magang1',
                'magang2',
                'seminar',
                'sidang',
                'wisuda'
            )
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        //list biaya kuliah mahasiswa
        if (($cb) != null) {

            $daftar = $biaya->daftar - (($biaya->daftar * ($cb->daftar)) / 100);
            $awal = $biaya->awal - (($biaya->awal * ($cb->awal)) / 100);
            $dsp = $biaya->dsp - (($biaya->dsp * ($cb->dsp)) / 100);
            $spp1 = $biaya->spp1 - (($biaya->spp1 * ($cb->spp1)) / 100);
            $spp2 = $biaya->spp2 - (($biaya->spp2 * ($cb->spp2)) / 100);
            $spp3 = $biaya->spp3 - (($biaya->spp3 * ($cb->spp3)) / 100);
            $spp4 = $biaya->spp4 - (($biaya->spp4 * ($cb->spp4)) / 100);
            $spp5 = $biaya->spp5 - (($biaya->spp5 * ($cb->spp5)) / 100);
            $spp6 = $biaya->spp6 - (($biaya->spp6 * ($cb->spp6)) / 100);
            $spp7 = $biaya->spp7 - (($biaya->spp7 * ($cb->spp7)) / 100);
            $spp8 = $biaya->spp8 - (($biaya->spp8 * ($cb->spp8)) / 100);
            $spp9 = $biaya->spp9 - (($biaya->spp9 * ($cb->spp9)) / 100);
            $spp10 = $biaya->spp10 - (($biaya->spp10 * ($cb->spp10)) / 100);
            $spp11 = $biaya->spp11 - (($biaya->spp11 * ($cb->spp11)) / 100);
            $spp12 = $biaya->spp12 - (($biaya->spp12 * ($cb->spp12)) / 100);
            $spp13 = $biaya->spp13 - (($biaya->spp13 * ($cb->spp13)) / 100);
            $spp14 = $biaya->spp14 - (($biaya->spp14 * ($cb->spp14)) / 100);
            $prakerin = $biaya->prakerin - (($biaya->prakerin * ($cb->prakerin)) / 100);
            $magang1 = $biaya->magang1 - (($biaya->magang1 * ($cb->magang1)) / 100);
            $magang2 = $biaya->magang2 - (($biaya->magang2 * ($cb->magang2)) / 100);
            $seminar = $biaya->seminar - (($biaya->seminar * ($cb->seminar)) / 100);
            $sidang = $biaya->sidang - (($biaya->sidang * ($cb->sidang)) / 100);
            $wisuda = $biaya->wisuda - (($biaya->wisuda * ($cb->wisuda)) / 100);
        } elseif (($cb) == null) {

            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
            $prakerin = $biaya->prakerin;
            $magang1 = $biaya->magang1;
            $magang2 = $biaya->magang2;
            $seminar = $biaya->seminar;
            $sidang = $biaya->sidang;
            $wisuda = $biaya->wisuda;
        }

        //total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->sum('bayar.bayar');

        //total semua pembayaran kecuali wisuda
        $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + $spp14 + $prakerin + $magang1 + $magang2 + $seminar + $sidang);
        //hasil
        $hasil_semua = $cekbyr - $total_semua_dibayar;

        if ($data_mhs->study_year == 4) {
            $idItem = 39;
        } elseif ($data_mhs->study_year == 3) {
            $idItem = 15;
        }

        $cekBayarSidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->where('bayar.iditem', $idItem)
            ->sum('bayar.bayar');

        $syaratYudisium = $sidang - $cekBayarSidang;

        if ($syaratYudisium < 0 or $syaratYudisium == 0) {

            $cekdata_prausta = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23, 26, 29, 32])
                ->where('prausta_setting_relasi.id_student', $id)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select(
                    'prausta_setting_relasi.id_settingrelasi_prausta',
                    'prausta_setting_relasi.validasi_baak'
                )
                ->first();

            if ($cekdata_prausta->validasi_baak == 'SUDAH') {

                //cek nilai kosong atau tidak lulus
                $cek_kur = Kurikulum_transaction::join('student_record', 'kurikulum_transaction.idkurtrans', '=', 'student_record.id_kurtrans')
                    ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                    ->where('kurikulum_transaction.id_prodi', $idprodi)
                    ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                    ->where('kurikulum_transaction.status', 'ACTIVE')
                    ->where('student_record.id_student', $id)
                    ->where('student_record.status', 'TAKEN')
                    ->where(function ($query) {
                        $query
                            ->where('student_record.nilai_AKHIR', 'D')
                            ->orWhere('student_record.nilai_AKHIR', 'E');
                    })
                    ->select('kurikulum_transaction.id_makul', 'matakuliah.makul', 'student_record.nilai_AKHIR')
                    ->get();

                $hitjml_kur = count($cek_kur);

                if ($hitjml_kur == 0) {

                    $serti = Sertifikat::where('id_student', $id)->count();

                    if ($serti >= 10) {

                        //cek kuisioner dosen pembimbing pkl
                        $cek_kuis_dospem_pkl = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                            ->where('kuisioner_transaction.id_student', $id)
                            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 2)
                            ->count();
                        // dd($cek_kuis_dospem_pkl);
                        if (($cek_kuis_dospem_pkl) > 0) {

                            //cek kuisioner dosen pembimbing ta
                            $cek_kuis_dospem_ta = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                ->where('kuisioner_transaction.id_student', $id)
                                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 3)
                                ->count();

                            if (($cek_kuis_dospem_ta) > 0) {

                                //cek kuisioner dosen penguji 1 ta
                                $cek_kuis_dospeng_ta_1 = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                    ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                    ->where('kuisioner_transaction.id_student', $id)
                                    ->where('kuisioner_master_kategori.id_kategori_kuisioner', 4)
                                    ->count();

                                if (($cek_kuis_dospeng_ta_1) > 0) {

                                    //cek kuisioner dosen penguji 2 ta
                                    $cek_kuis_dospeng_ta_2 = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                        ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                        ->where('kuisioner_transaction.id_student', $id)
                                        ->where('kuisioner_master_kategori.id_kategori_kuisioner', 5)
                                        ->count();

                                    if (($cek_kuis_dospeng_ta_2) > 0) {
                                        $data = Yudisium::where('id_student', $id)->first();

                                        return view('mhs/pendaftaran/yudisium', compact('id', 'data'));
                                    } else {
                                        alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen Penguji 2 TA')->autoclose(5000);
                                        return redirect('home');
                                    }
                                } else {
                                    alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen Penguji 1 TA')->autoclose(5000);
                                    return redirect('home');
                                }
                            } else {
                                alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen pembimbing TA')->autoclose(5000);
                                return redirect('home');
                            }
                        } else {
                            alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena belum melakukan pengisian kuisioner dosen pembimbing PKL')->autoclose(5000);
                            return redirect('home');
                        }
                    } else {
                        alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena sertifikat anda kurang dari 10')->autoclose(5000);
                        return redirect('home');
                    }
                } else {
                    alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena ada nilai yang masih kosong / belum lulus')->autoclose(5000);
                    return redirect('home');
                }
            } else {
                alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena BAAK belum validasi Tugas Akhir anda')->autoclose(5000);
                return redirect('home');
            }
        } else {
            alert()->warning('Anda tidak dapat melakukan Pendaftaran Yudisium karena keuangan Anda belum memenuhi syarat')->autoclose(5000);
            return redirect('home');
        }
    }

    public function save_yudisium(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi'
        ];
        $this->validate(
            $request,
            [
                'nama_lengkap'  => 'required',
                'tmpt_lahir'    => 'required',
                'tgl_lahir'     => 'required',
                'nik'           => 'required',
                'file_ijazah'   => 'mimes:jpg,jpeg,JPG,JPEG|max:4000',
                'file_ktp'      => 'mimes:jpg,jpeg,JPG,JPEG|max:4000'
            ],
            $message,
        );

        $bap = new Yudisium();
        $bap->id_student = $request->id_student;
        $bap->nama_lengkap = $request->nama_lengkap;
        $bap->tmpt_lahir = $request->tmpt_lahir;
        $bap->tgl_lahir = $request->tgl_lahir;
        $bap->nik = $request->nik;

        if ($request->hasFile('file_ijazah')) {
            $file = $request->file('file_ijazah');
            $nama_file = 'File Ijazah' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
            $tujuan_upload = 'File Yudisium/' . $request->id_student;
            $file->move($tujuan_upload, $nama_file);
            $bap->file_ijazah = $nama_file;
        }

        if ($request->hasFile('file_ktp')) {
            $file = $request->file('file_ktp');
            $nama_file = 'File KTP' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
            $tujuan_upload = 'File Yudisium/' . $request->id_student;
            $file->move($tujuan_upload, $nama_file);
            $bap->file_ktp = $nama_file;
        }

        // if ($request->hasFile('file_foto')) {
        //     $file = $request->file('file_foto');
        //     $nama_file = 'File Foto' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
        //     $tujuan_upload = 'File Yudisium/' . $request->id_student;
        //     $file->move($tujuan_upload, $nama_file);
        //     $bap->file_foto = $nama_file;
        // }

        $bap->save();

        Alert::success('', 'Data Yudisium berhasil ditambahkan')->autoclose(3500);
        return redirect('yudisium');
    }

    public function put_yudisium(Request $request, $id)
    {
        $bap = Yudisium::find($id);
        $bap->id_student = Auth::user()->id_user;
        $bap->nama_lengkap = $request->nama_lengkap;
        $bap->tmpt_lahir = $request->tmpt_lahir;
        $bap->tgl_lahir = $request->tgl_lahir;
        $bap->nik = $request->nik;
        $bap->created_by = Auth::user()->name;

        if ($bap->file_ijazah) {
            if ($request->hasFile('file_ijazah')) {
                File::delete('File Yudisium/' . Auth::user()->id_user . '/' . $bap->file_ijazah);
                $file = $request->file('file_ijazah');
                $nama_file = 'File Ijazah' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ijazah = $nama_file;
            }
        } else {
            if ($request->hasFile('file_ijazah')) {
                $file = $request->file('file_ijazah');
                $nama_file = 'File Ijazah' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ijazah = $nama_file;
            }
        }

        if ($bap->file_ktp) {
            if ($request->hasFile('file_ktp')) {
                File::delete('File Yudisium/' . Auth::user()->id_user . '/' . $bap->file_ktp);
                $file = $request->file('file_ktp');
                $nama_file = 'File KTP' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ktp = $nama_file;
            }
        } else {
            if ($request->hasFile('file_ktp')) {
                $file = $request->file('file_ktp');
                $nama_file = 'File KTP' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_ktp = $nama_file;
            }
        }

        // if ($bap->file_foto) {
        //     if ($request->hasFile('file_foto')) {
        //         File::delete('File Yudisium/' . Auth::user()->id_user . '/' . $bap->file_foto);
        //         $file = $request->file('file_foto');
        //         $nama_file = 'File Foto' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
        //         $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
        //         $file->move($tujuan_upload, $nama_file);
        //         $bap->file_foto = $nama_file;
        //     }
        // } else {
        //     if ($request->hasFile('file_foto')) {
        //         $file = $request->file('file_foto');
        //         $nama_file = 'File Foto' . '-' . $request->nama_lengkap . '-' . $file->getClientOriginalName();
        //         $tujuan_upload = 'File Yudisium/' . Auth::user()->id_user;
        //         $file->move($tujuan_upload, $nama_file);
        //         $bap->file_foto = $nama_file;
        //     }
        // }

        $bap->save();

        Alert::success('', 'Data Yudisium berhasil diedit')->autoclose(3500);
        return redirect('yudisium');
    }

    public function wisuda()
    {
        $id = Auth::user()->id_user;

        $data_mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.idstudent',
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi'
            )
            ->first();

        $idangkatan = $data_mhs->idangkatan;
        $idstatus = $data_mhs->idstatus;
        $kodeprodi = $data_mhs->kodeprodi;

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select(
                'wisuda'
            )
            ->first();

        $cb = Beasiswa::where('idstudent', $id)->first();

        //list biaya kuliah mahasiswa
        if (($cb) != null) {

            $biayawisuda = $biaya->wisuda - (($biaya->wisuda * ($cb->wisuda)) / 100);
        } elseif (($cb) == null) {

            $biayawisuda = $biaya->wisuda;
        }

        //cek masa studi 
        $cek_study = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->where('student.idstudent', $id)
            ->select('prodi.study_year', 'student.idstudent', 'prodi.kodeprodi')
            ->first();

        $idItem = ($cek_study->study_year == 3) ? 16 : 40;

        $pembayaranwisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->where('bayar.iditem', $idItem)
            ->sum('bayar.bayar');

        $hasil_wisuda = $biayawisuda - $pembayaranwisuda;

        if ($hasil_wisuda < 0 or $hasil_wisuda == 0) {
            $data = Wisuda::join('prodi', 'wisuda.id_prodi', '=', 'prodi.id_prodi')
                ->where('wisuda.id_student', $id)
                ->first();

            $prodi = Prodi::all();

            return view('mhs/pendaftaran/wisuda', compact('id', 'data', 'prodi'));
        } else {
            alert()->warning('Anda tidak dapat melakukan Pendaftaran Wisuda karena keuangan Anda belum memenuhi syarat')->autoclose(5000);
            return redirect('home');
        }
    }

    public function save_wisuda(Request $request)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi'
        ];
        $this->validate(
            $request,
            [
                'ukuran_toga'       => 'required',
                'status_vaksin'     => 'required',
                'tahun_lulus'       => 'required',
                'nim'               => 'required',
                'nama_lengkap'      => 'required',
                'id_prodi'          => 'required',
                'no_hp'             => 'required',
                'email'             => 'required',
                'npwp'              => 'required',
                'alamat_ktp'        => 'required',
                'alamat_domisili'   => 'required',
                'nama_ayah'         => 'required',
                'nama_ibu'          => 'required',
                'no_hp_ayah'        => 'required',
                'alamat_ortu'       => 'required',
                'tempat_kerja'       => 'required',
                'file_foto'       => 'mimes:jpg,jpeg,JPG,JPEG,PNG,png|max:4000'
            ],
            $message,
        );

        $bap = new Wisuda();
        $bap->id_student        = $request->id_student;
        $bap->ukuran_toga       = $request->ukuran_toga;
        $bap->status_vaksin     = $request->status_vaksin;
        $bap->tahun_lulus       = $request->tahun_lulus;
        $bap->nim               = $request->nim;
        $bap->nama_lengkap      = $request->nama_lengkap;
        $bap->id_prodi          = $request->id_prodi;
        $bap->no_hp             = $request->no_hp;
        $bap->email             = $request->email;
        $bap->npwp              = $request->npwp;
        $bap->alamat_ktp        = $request->alamat_ktp;
        $bap->alamat_domisili   = $request->alamat_domisili;
        $bap->nama_ayah         = $request->nama_ayah;
        $bap->nama_ibu          = $request->nama_ibu;
        $bap->no_hp_ayah        = $request->no_hp_ayah;
        $bap->no_hp_ibu         = $request->no_hp_ibu;
        $bap->alamat_ortu       = $request->alamat_ortu;
        $bap->tempat_kerja       = $request->tempat_kerja;

        if ($request->hasFile('file_foto')) {
            $file = $request->file('file_foto');
            $nama_file = 'File Foto' .  '-' . $file->getClientOriginalName();
            $tujuan_upload = 'File Wisuda/' . Auth::user()->id_user;
            $file->move($tujuan_upload, $nama_file);
            $bap->file_foto = $nama_file;
        }

        $bap->save();

        Alert::success('', 'Data Wisuda berhasil ditambahkan')->autoclose(3500);
        return redirect('wisuda');
    }

    public function put_wisuda(Request $request, $id)
    {
        $message = [
            'max' => ':attribute harus diisi maksimal :max KB',
            'required' => ':attribute wajib diisi'
        ];
        $this->validate(
            $request,
            [
                'ukuran_toga'       => 'required',
                'status_vaksin'     => 'required',
                'tahun_lulus'       => 'required',
                'nim'               => 'required',
                'nama_lengkap'      => 'required',
                'id_prodi'          => 'required',
                'no_hp'             => 'required',
                'email'             => 'required',
                'npwp'              => 'required',
                'alamat_ktp'        => 'required',
                'alamat_domisili'   => 'required',
                'nama_ayah'         => 'required',
                'nama_ibu'          => 'required',
                'no_hp_ayah'        => 'required',
                'alamat_ortu'       => 'required',
                'tempat_kerja'       => 'required',
                'file_foto'         => 'mimes:jpg,jpeg,JPG,JPEG,PNG,png|max:4000'
            ],
            $message,
        );

        $bap = Wisuda::find($id);
        $bap->id_student = Auth::user()->id_user;
        $bap->ukuran_toga = $request->ukuran_toga;
        $bap->status_vaksin = $request->status_vaksin;
        $bap->tahun_lulus       = $request->tahun_lulus;
        $bap->nim               = $request->nim;
        $bap->nama_lengkap      = $request->nama_lengkap;
        $bap->id_prodi          = $request->id_prodi;
        $bap->no_hp             = $request->no_hp;
        $bap->email             = $request->email;
        // $bap->nik               = $request->nik;
        $bap->npwp              = $request->npwp;
        $bap->alamat_ktp        = $request->alamat_ktp;
        $bap->alamat_domisili   = $request->alamat_domisili;
        $bap->nama_ayah         = $request->nama_ayah;
        $bap->nama_ibu          = $request->nama_ibu;
        $bap->no_hp_ayah        = $request->no_hp_ayah;
        $bap->no_hp_ibu         = $request->no_hp_ibu;
        $bap->alamat_ortu       = $request->alamat_ortu;
        $bap->tempat_kerja       = $request->tempat_kerja;

        if ($bap->file_foto) {
            if ($request->hasFile('file_foto')) {
                File::delete('File Wisuda/' . Auth::user()->id_user . '/' . $bap->file_foto);
                $file = $request->file('file_foto');
                $nama_file = 'File Foto' .  '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Wisuda/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_foto = $nama_file;
            }
        } else {
            if ($request->hasFile('file_foto')) {
                $file = $request->file('file_foto');
                $nama_file = 'File Foto' .  '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Wisuda/' . Auth::user()->id_user;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_foto = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Data Wisuda berhasil diedit')->autoclose(3500);
        return redirect('wisuda');
    }

    public function sop()
    {
        $data = Standar::where('status', 'ACTIVE')->get();

        return view('mhs/sop', compact('data'));
    }

    public function pengalaman_kerja()
    {
        $id = Auth::user()->id_user;

        $data = Pengalaman::where('id_student', $id)
            ->where('status', 'ACTIVE')
            ->get();

        return view('mhs/pengalaman/pengalaman_kerja', compact('data'));
    }

    public function post_pengalaman(Request $request)
    {
        $info = new Pengalaman();
        $info->nama_pt = $request->nama_pt;
        $info->posisi = $request->posisi;
        $info->tahun_masuk = $request->tahun_masuk;
        $info->tahun_keluar = $request->tahun_keluar;
        $info->id_student = Auth::user()->id_user;
        $info->created_by = Auth::user()->name;
        $info->save();

        Alert::success('', 'Pengalaman berhasil ditambahkan')->autoclose(3500);
        return redirect('pengalaman_kerja');
    }

    public function put_pengalaman(Request $request, $id)
    {
        $info = Pengalaman::find($id);
        $info->nama_pt = $request->nama_pt;
        $info->posisi = $request->posisi;
        $info->tahun_masuk = $request->tahun_masuk;
        $info->tahun_keluar = $request->tahun_keluar;
        $info->id_student = Auth::user()->id_user;
        $info->updated_by = Auth::user()->name;
        $info->save();

        Alert::success('', 'Pengalaman berhasil diedit')->autoclose(3500);
        return redirect('pengalaman_kerja');
    }

    public function hapus_pengalaman($id)
    {
        Pengalaman::where('id_pengalaman', $id)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Pengalaman berhasil dihapus')->autoclose(3500);
        return redirect('pengalaman_kerja');
    }

    public function penangguhan_mhs()
    {
        $status_penangguhan = Waktu::where('tipe_waktu', 3)->first();

        $id = Auth::user()->id_user;

        $kategori_penangguhan = Penangguhan_kategori::where('status', 'ACTIVE')->get();

        $data = Penangguhan_trans::join('penangguhan_master_kategori', 'penangguhan_master_trans.id_penangguhan_kategori', '=', 'penangguhan_master_kategori.id_penangguhan_kategori')
            ->join('periode_tahun', 'penangguhan_master_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'penangguhan_master_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('penangguhan_master_trans.id_student', $id)
            ->select(
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
            ->where('penangguhan_master_trans.status', 'ACTIVE')
            ->get();

        return view('mhs/penangguhan/data_penangguhan', compact('data', 'kategori_penangguhan', 'status_penangguhan'));
    }

    public function post_penangguhan(Request $request)
    {
        $tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $tipe = Periode_tipe::where('status', 'ACTIVE')->first();
        $id_tipe = $tipe->id_periodetipe;
        $id = Auth::user()->id_user;
        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi',
                'student.intake'
            )
            ->first();

        $idangkatan = $datamhs->idangkatan;
        $intake = $datamhs->intake;
        $idstatus = $datamhs->idstatus;
        $kodeprodi = $datamhs->kodeprodi;

        $sub_thn = substr($tahun->periode_tahun, 6, 2);

        $smt = $sub_thn . $id_tipe;

        if ($smt % 2 != 0) {
            if ($id_tipe == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10; // ( 211 + 10 - 1 ) / 10 = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                if ($intake == 2) {
                    $c = ($b * 2) - 1 - 1;
                } elseif ($intake == 1) {
                    $c = ($b * 2) - 1;
                } // 2 * 2 - 1 = 3
            } elseif ($id_tipe == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10; // ( 213 + 10 - 3 ) / 10  = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                // $c = ($b * 2);
                if ($intake == 2) {
                    $c = $b * 2 - 1;
                } elseif ($intake == 1) {
                    $c = $b * 2;
                }
            }
        } else {
            //genap
            $a = (($smt + 10) - 2) / 10; // (212 + 10 - 2) / 10 = 22
            $b = $a - $idangkatan; // 22 - 20 = 2
            // 2 * 2 = 4
            if ($intake == 2) {
                $c = $b * 2 - 1;
            } elseif ($intake == 1) {
                $c = $b * 2;
            }
        }

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select(
                'daftar',
                'awal',
                'dsp',
                'spp1',
                'spp2',
                'spp3',
                'spp4',
                'spp5',
                'spp6',
                'spp7',
                'spp8',
                'spp9',
                'spp10',
                'spp11',
                'spp12',
                'spp13',
                'spp14',
                'prakerin',
                'seminar',
                'sidang'
            )
            ->first();

        $cek_bea = Beasiswa::where('idstudent', $id)->first();

        if ($cek_bea != null) {
            $daftar = $biaya->daftar - ($biaya->daftar * $cek_bea->daftar) / 100;
            $awal = $biaya->awal - ($biaya->awal * $cek_bea->awal) / 100;
            $dsp = $biaya->dsp - ($biaya->dsp * $cek_bea->dsp) / 100;
            $spp1 = $biaya->spp1 - ($biaya->spp1 * $cek_bea->spp1) / 100;
            $spp2 = $biaya->spp2 - ($biaya->spp2 * $cek_bea->spp2) / 100;
            $spp3 = $biaya->spp3 - ($biaya->spp3 * $cek_bea->spp3) / 100;
            $spp4 = $biaya->spp4 - ($biaya->spp4 * $cek_bea->spp4) / 100;
            $spp5 = $biaya->spp5 - ($biaya->spp5 * $cek_bea->spp5) / 100;
            $spp6 = $biaya->spp6 - ($biaya->spp6 * $cek_bea->spp6) / 100;
            $spp7 = $biaya->spp7 - ($biaya->spp7 * $cek_bea->spp7) / 100;
            $spp8 = $biaya->spp8 - ($biaya->spp8 * $cek_bea->spp8) / 100;
            $spp9 = $biaya->spp9 - ($biaya->spp9 * $cek_bea->spp9) / 100;
            $spp10 = $biaya->spp10 - ($biaya->spp10 * $cek_bea->spp10) / 100;
            $spp11 = $biaya->spp11 - ($biaya->spp11 * $cek_bea->spp11) / 100;
            $spp12 = $biaya->spp12 - ($biaya->spp12 * $cek_bea->spp12) / 100;
            $spp13 = $biaya->spp13 - ($biaya->spp13 * $cek_bea->spp13) / 100;
            $spp14 = $biaya->spp14 - ($biaya->spp14 * $cek_bea->spp14) / 100;
            $prakerin = $biaya->prakerin - (($biaya->prakerin * ($cek_bea->prakerin)) / 100);
            $seminar = $biaya->seminar - (($biaya->seminar * ($cek_bea->seminar)) / 100);
            $sidang = $biaya->sidang - (($biaya->sidang * ($cek_bea->sidang)) / 100);
        } elseif ($cek_bea == null) {
            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
            $prakerin = $biaya->prakerin;
            $seminar = $biaya->seminar;
            $sidang = $biaya->sidang;
        }

        #total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->whereNotIn('bayar.iditem', [14, 15, 16, 35, 36, 37, 38, 39])
            ->sum('bayar.bayar');

        #minimal pembayaran UTS
        $min_uts = Min_biaya::where('kategori', 'UTS')->first();
        $persen_uts = $min_uts->persentase;



        $cek_penangguhan_mhs = Penangguhan_trans::where('id_periodetahun', $tahun->id_periodetahun)
            ->where('id_periodetipe', $id_tipe)
            ->where('id_student', $id)
            ->where('id_penangguhan_kategori', $request->id_penangguhan_kategori)
            ->where('status', 'ACTIVE')
            ->get();

        if (count($cek_penangguhan_mhs) == 0) {

            if ($request->id_penangguhan_kategori == 2) {
                if ($c == 1) {
                    $cekbyr = $daftar + $awal + ($spp1 * $persen_uts) / 100 - $total_semua_dibayar;
                } elseif ($c == 2) {
                    $cekbyr = $daftar + $awal + ($dsp * 25) / 100 + $spp1 + ($spp2 * $persen_uts) / 100 - $total_semua_dibayar;
                } elseif ($c == '201') {
                    $cekbyr = ($daftar + $awal + ($dsp * 91 / 100) + $spp1 + ($spp2 * 82 / 100)) - $total_semua_dibayar;
                } elseif ($c == 3) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + ($spp3 * $persen_uts) / 100 - $total_semua_dibayar;
                } elseif ($c == 4) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * $persen_uts) / 100 - $total_semua_dibayar;
                } elseif ($c == '401') {
                    $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82 / 100)) - $total_semua_dibayar;
                } elseif ($c == 5) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5 * $persen_uts) / 100 - $total_semua_dibayar;
                } elseif ($c == 6) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * $persen_uts) / 100 - ($total_semua_dibayar - $prakerin - $seminar - $sidang);
                } elseif ($c == '601') {
                    $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82 / 100)) - $total_semua_dibayar;
                } elseif ($c == 7) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + ($spp7 * $persen_uts) / 100 - $total_semua_dibayar;
                } elseif ($c == 8) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * $persen_uts) / 100 - $total_semua_dibayar;
                } elseif ($c == '801') {
                    $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82 / 100)) - $total_semua_dibayar;
                } elseif ($c == 9) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + ($spp9 * 50) / 100 - $total_semua_dibayar;
                } elseif ($c == 10) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 50) / 100 - $total_semua_dibayar;
                } elseif ($c == '1001') {
                    $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82 / 100)) - $total_semua_dibayar;
                } elseif ($c == 11) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + ($spp11 * 50) / 100 - $total_semua_dibayar;
                } elseif ($c == 12) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11(($spp12 * 50) / 100) - $total_semua_dibayar;
                } elseif ($c == 13) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + ($spp13 * 50) / 100 - $total_semua_dibayar;
                } elseif ($c == 14) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + ($spp14 * 50) / 100 - $total_semua_dibayar;
                }


                if ($cekbyr == 0 or $cekbyr < 1000) {

                    Alert::success('Maaf anda tidak perlu melakukan penangguhan Absen UTS karena keuangan Anda SUDAH memenuhi syarat');
                    return redirect('home');
                } else {
                    $new = new Penangguhan_trans;
                    $new->id_periodetahun = $tahun->id_periodetahun;
                    $new->id_periodetipe = $tipe->id_periodetipe;
                    $new->id_student = $id;
                    $new->id_penangguhan_kategori = $request->id_penangguhan_kategori;
                    $new->rencana_bayar = $request->rencana_bayar;
                    $new->alasan = $request->alasan;
                    $new->save();

                    Alert::success('', 'Penangguhan berhasil ditambahkan')->autoclose(3500);
                    return redirect('penangguhan_mhs');
                }
            } elseif ($request->id_penangguhan_kategori == 3) {
                if ($c == 1) {
                    $cekbyr = $daftar + $awal + $spp1 - $total_semua_dibayar;
                } elseif ($c == 2) {
                    $cekbyr = $daftar + $awal + ($dsp * 91) / 100 + $spp1 + $spp2 - $total_semua_dibayar;
                } elseif ($c == '201') {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2  - $total_semua_dibayar;
                } elseif ($c == 3) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 - $total_semua_dibayar;
                } elseif ($c == 4) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
                } elseif ($c == '401') {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
                } elseif ($c == 5) {
                    if ($kodeprodi == 23 or $kodeprodi == 24) {
                        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $prakerin - $total_semua_dibayar;
                    } elseif ($kodeprodi == 25) {
                        $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 - $total_semua_dibayar;
                    }
                } elseif ($c == 6) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - ($total_semua_dibayar - $prakerin - $seminar - $sidang);
                } elseif ($c == '601') {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
                } elseif ($c == 7) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 - $total_semua_dibayar;
                } elseif ($c == 8) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
                } elseif ($c == '801') {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
                } elseif ($c == 9) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 - $total_semua_dibayar;
                } elseif ($c == 10) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
                } elseif ($c == '1001') {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
                } elseif ($c == 11) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 - $total_semua_dibayar;
                } elseif ($c == 12) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 - $total_semua_dibayar;
                } elseif ($c == 13) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 - $total_semua_dibayar;
                } elseif ($c == 14) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + $spp14 - $total_semua_dibayar;
                }

                if ($cekbyr == 0 or $cekbyr < 1000) {

                    Alert::success('Maaf anda tidak perlu melakukan penangguhan Absen UAS karena keuangan Anda SUDAH memenuhi syarat');
                    return redirect('home');
                } else {
                    $new = new Penangguhan_trans;
                    $new->id_periodetahun = $tahun->id_periodetahun;
                    $new->id_periodetipe = $tipe->id_periodetipe;
                    $new->id_student = $id;
                    $new->id_penangguhan_kategori = $request->id_penangguhan_kategori;
                    $new->rencana_bayar = $request->rencana_bayar;
                    $new->alasan = $request->alasan;
                    $new->save();

                    Alert::success('', 'Penangguhan berhasil ditambahkan')->autoclose(3500);
                    return redirect('penangguhan_mhs');
                }
            }
        } elseif (count($cek_penangguhan_mhs) == 1) {
            Alert::warning('', 'Maaf Penangguhan sudah ada')->autoclose(3500);
            return redirect('penangguhan_mhs');
        }
    }

    public function put_penangguhan(Request $request, $id)
    {
        $ids = Auth::user()->id_user;

        $new = Penangguhan_trans::find($id);
        $new->id_student = $ids;
        $new->id_penangguhan_kategori = $request->id_penangguhan_kategori;
        $new->rencana_bayar = $request->rencana_bayar;
        $new->alasan = $request->alasan;
        $new->save();

        Alert::success('', 'Penangguhan berhasil diedit')->autoclose(3500);
        return redirect('penangguhan_mhs');
    }

    public function batal_penangguhan($id)
    {
        Penangguhan_trans::where('id_penangguhan_trans', $id)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Penangguhan berhasil dihapus')->autoclose(3500);
        return redirect('penangguhan_mhs');
    }

    public function kritiksaran_mhs()
    {
        $ids = Auth::user()->id_user;

        $kategori = Kritiksaran_kategori::where('status', 'ACTIVE')->get();

        $data = Kritiksaran_transaction::join('kritiksaran_kategori', 'kritiksaran_trans.id_kategori_kritiksaran', '=', 'kritiksaran_kategori.id_kategori_kritiksaran')
            ->join('periode_tahun', 'kritiksaran_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kritiksaran_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('kritiksaran_trans.id_student', $ids)
            ->where('kritiksaran_trans.status', 'ACTIVE')
            ->where('kritiksaran_kategori.status', 'ACTIVE')
            ->select(
                'kritiksaran_trans.id_trans_kritiksaran',
                'kritiksaran_trans.id_periodetahun',
                'kritiksaran_trans.id_periodetahun',
                'kritiksaran_trans.id_kategori_kritiksaran',
                'kritiksaran_kategori.kategori_kritiksaran',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'kritiksaran_trans.kritik',
                'kritiksaran_trans.saran',
                'kritiksaran_trans.status',
                'periode_tahun.status as thn_status',
                'periode_tipe.status as tp_status'
            )
            ->get();

        return view('mhs/kritiksaran/kritiksaran', compact('data', 'kategori'));
    }

    public function post_kritiksaran(Request $request)
    {
        $prd_thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $prd_tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $ids = Auth::user()->id_user;

        $ang = new Kritiksaran_transaction();
        $ang->id_periodetahun = $prd_thn->id_periodetahun;
        $ang->id_periodetipe = $prd_tp->id_periodetipe;
        $ang->id_kategori_kritiksaran = $request->id_kategori_kritiksaran;
        $ang->id_student = $ids;
        $ang->kritik = $request->kritik;
        $ang->saran = $request->saran;
        $ang->created_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Kritik & Saran berhasil ditambahkan')->autoclose(3500);
        return redirect('kritiksaran_mhs');
    }

    public function put_kritiksaran(Request $request, $id)
    {
        $new = Kritiksaran_transaction::find($id);
        $new->id_kategori_kritiksaran = $request->id_kategori_kritiksaran;
        $new->kritik = $request->kritik;
        $new->saran = $request->saran;
        $new->created_by = Auth::user()->name;
        $new->save();

        Alert::success('', 'Kritik & Saran berhasil diedit')->autoclose(3500);
        return redirect('kritiksaran_mhs');
    }

    public function dosen_mip()
    {
        $data = Dosen::where('active', 1)
            ->whereIn('idstatus', [1, 2])
            ->orderBy('nama', 'ASC')
            ->get();

        return view('mhs/dosen/dosen_mip', compact('data'));
    }

    public function beasiswa_mhs()
    {
        $id = Auth::user()->id_user;

        $cek_beasiswa = Beasiswa::where('idstudent', $id)->count();

        if ($cek_beasiswa == 0) {
            Alert::warning('', 'Maaf anda bukan mahasiswa penerima Beasiswa')->autoclose(3500);
            return redirect('home');
        } elseif ($cek_beasiswa == 1) {
            $status_pengajuan = Waktu::where('tipe_waktu', 4)->first();

            $data = Beasiswa_trans::join('student', 'beasiswa_trans.id_student', '=', 'student.idstudent')
                ->leftJoin('prodi', function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                })
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->join('periode_tahun', 'beasiswa_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
                ->join('periode_tipe', 'beasiswa_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
                ->join('semester', 'beasiswa_trans.id_semester', '=', 'semester.idsemester')
                ->where('beasiswa_trans.id_student', $id)
                ->where('beasiswa_trans.status', 'ACTIVE')
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
                    'beasiswa_trans.ipk'
                )
                ->get();

            return view('mhs/beasiswa/pengajuan_beasiswa', compact('data', 'status_pengajuan'));
        }
    }

    public function pengajuan_beasiswa()
    {
        $id = Auth::user()->id_user;

        $mhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.idstudent',
                'student.nama',
                'student.nim',
                'student.tmptlahir',
                'student.tgllahir',
                'student.hp',
                'student.email',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'student.intake',
                'prodi.study_year'
            )
            ->first();

        $idangkatan = $mhs->idangkatan;
        $intake = $mhs->intake;
        $idstatus = $mhs->idstatus;
        $kodeprodi = $mhs->kodeprodi;
        $study_year = $mhs->study_year;

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $sub_thn = substr($periode_tahun->periode_tahun, 6, 2);
        $tp = $periode_tipe->id_periodetipe;
        $smt = $sub_thn . $tp;
        $angk = $idangkatan;

        // if ($smt % 2 != 0) {
        //     if ($tp == 1) {
        //         #ganjil
        //         $a = (($smt + 10) - 1) / 10;
        //         $b = $a - $idangkatan;

        //         if ($intake == 2) {
        //             $c = ($b * 2) - 1 - 1;
        //         } elseif ($intake == 1) {
        //             $c = ($b * 2) - 1;
        //         }
        //     }
        // } else {
        //     #genap
        //     $a = (($smt + 10) - 2) / 10;
        //     $b = $a - $idangkatan;
        //     if ($intake == 2) {
        //         $c = $b * 2 - 1;
        //     } elseif ($intake == 1) {
        //         $c = $b * 2;
        //     }
        // }

        if ($smt % 2 != 0) {
            if ($tp == 1) {
                #ganjil
                $a = (($smt + 10) - 1) / 10; // ( 211 + 10 - 1 ) / 10 = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                if ($intake == 2) {
                    $c = ($b * 2) - 1 - 1;
                } elseif ($intake == 1) {
                    $c = ($b * 2) - 1;
                } // 2 * 2 - 1 = 3
            } elseif ($tp == 3) {
                #pendek
                $a = (($smt + 10) - 3) / 10; // ( 213 + 10 - 3 ) / 10  = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                // $c = ($b * 2);
                if ($intake == 2) {
                    $c = $b * 2 - 1;
                } elseif ($intake == 1) {
                    $c = $b * 2;
                }
            }
        } else {
            #genap
            $a = (($smt + 10) - 2) / 10; // (212 + 10 - 2) / 10 = 22
            $b = $a - $idangkatan; // 22 - 20 = 2
            // 2 * 2 = 4
            if ($intake == 2) {
                $c = $b * 2 - 1;
            } elseif ($intake == 1) {
                $c = $b * 2;
            }
        }

        #cek id tahun untuk IPK
        if ($tp == 1) {
            $id_thn = $periode_tahun->id_periodetahun - 1;
            $id_tp = 2;
        } elseif ($tp == 2) {
            $id_thn = $periode_tahun->id_periodetahun;
            $id_tp = 1;
        }

        $data = DB::select('CALL ipk_pengajuan_beasiswa(?,?,?)', [$id, $id_thn, $id_tp]);

        $sks = 0;
        $ipkk = 0;
        foreach ($data as $ips) {
            $sks += $ips->akt_sks_teori + $ips->akt_sks_praktek;
            $ipkk += ($ips->akt_sks_teori + $ips->akt_sks_praktek) * ($ips->nilai_indeks);
        }
        #IPK mahasiswa minimal 3.25
        $hasil_ipk = $ipkk / $sks;

        #biaya kuliah
        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select(
                'spp1',
                'spp2',
                'spp3',
                'spp4',
                'spp5',
                'spp6',
                'spp7',
                'spp8'
            )
            ->first();

        #cek besiswa
        $cb = Beasiswa::where('idstudent', $id)->first();

        #list biaya kuliah mahasiswa
        if (($cb) != null) {

            $spp1 = $biaya->spp1 - (($biaya->spp1 * ($cb->spp1)) / 100);
            $spp2 = $biaya->spp2 - (($biaya->spp2 * ($cb->spp2)) / 100);
            $spp3 = $biaya->spp3 - (($biaya->spp3 * ($cb->spp3)) / 100);
            $spp4 = $biaya->spp4 - (($biaya->spp4 * ($cb->spp4)) / 100);
            $spp5 = $biaya->spp5 - (($biaya->spp5 * ($cb->spp5)) / 100);
            $spp6 = $biaya->spp6 - (($biaya->spp6 * ($cb->spp6)) / 100);
            $spp7 = $biaya->spp7 - (($biaya->spp7 * ($cb->spp7)) / 100);
            $spp8 = $biaya->spp8 - (($biaya->spp8 * ($cb->spp8)) / 100);
        } elseif (($cb) == null) {

            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
        }

        #biaya SPP per semester 
        if ($study_year == 4) {
            #jumlah telah dibayarkan SPP2 
            $jml_telah_dibayar_spp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 22)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP3
            $jml_telah_dibayar_spp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 23)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP4
            $jml_telah_dibayar_spp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 24)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP5
            $jml_telah_dibayar_spp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 25)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP6
            $jml_telah_dibayar_spp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 26)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP7
            $jml_telah_dibayar_spp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 27)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP8
            $jml_telah_dibayar_spp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 28)
                ->sum('bayar.bayar');
        } elseif ($study_year == 3) {
            #jumlah telah dibayarkan SPP2 
            $jml_telah_dibayar_spp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 5)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP3
            $jml_telah_dibayar_spp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 6)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP4
            $jml_telah_dibayar_spp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 7)
                ->sum('bayar.bayar');

            #jumlah telah dibayarkan SPP5
            $jml_telah_dibayar_spp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 8)
                ->sum('bayar.bayar');
        }

        #cek bayaran semester minimal 1 bulan pertama
        if ($c == 2) {
            $cekbyr = ($spp2 / 6) - $jml_telah_dibayar_spp2;
        } elseif ($c == 3) {
            $cekbyr = ($spp3 / 6) - $jml_telah_dibayar_spp3;
        } elseif ($c == 4) {
            $cekbyr = ($spp4 / 6) - $jml_telah_dibayar_spp4;
        } elseif ($c == 5) {
            $cekbyr = ($spp5 / 6)  - $jml_telah_dibayar_spp5;
        } elseif ($c == 6) {
            $cekbyr = ($spp6 / 6) - $jml_telah_dibayar_spp6;
        } elseif ($c == 7) {
            $cekbyr = ($spp7 / 6) - $jml_telah_dibayar_spp7;
        } elseif ($c == 8) {
            $cekbyr = ($spp8 / 6) - $jml_telah_dibayar_spp8;
        }

        #cek status penangguhan semester sebelumnya
        if ($tp == 1) {
            $id_thn1 = $periode_tahun->id_periodetahun - 1;
            $id_tp1 = 2;
        } elseif ($tp == 2) {
            $id_thn1 = $periode_tahun->id_periodetahun;
            $id_tp1 = 1;
        }

        $cek_penangguhan = Penangguhan_trans::where('id_periodetahun', $id_thn1)
            ->where('id_periodetipe', $id_tp1)
            ->where('id_student', $id)
            ->where(function ($query) {
                $query->where('status_penangguhan', 'OPEN')
                    ->orWhereNull('status_penangguhan');
            })
            ->get();


        if ($hasil_ipk < 3.25) {
            Alert::warning('', 'Maaf IPK anda tidak memenuhi persyaratan Beasiswa')->autoclose(3500);
            return redirect('beasiswa_mhs');
        } elseif ($hasil_ipk >= 3.25) {
            if ($cekbyr > 0) {
                Alert::warning('', 'Maaf Pembayaran anda tidak memenuhi persyaratan Beasiswa')->autoclose(3500);
                return redirect('beasiswa_mhs');
            } elseif ($cekbyr <= 0) {
                if (count($cek_penangguhan) == 0) {
                    Alert::warning('', 'Maaf anda masih ada Penangguhan belum CLOSE, silahkan hubungi BAUK')->autoclose(3500);
                    return redirect('beasiswa_mhs');
                } elseif (count($cek_penangguhan) > 0) {
                    if ($mhs->kodeprodi == 23 or $mhs->kodeprodi == 24) {
                        if ($c > 5) {
                            Alert::warning('', 'Maaf mahasiswa penerima Beasiswa hanya sampai Semester 5')->autoclose(3500);
                            return redirect('beasiswa_mhs');
                        } elseif ($c <= 5) {

                            return view('mhs/beasiswa/form_beasiswa', compact('id', 'mhs', 'id_thn', 'tp', 'c', 'hasil_ipk'));
                        }
                    } elseif ($mhs->kodeprodi == 25) {
                        if ($c > 7) {
                            Alert::warning('', 'Maaf mahasiswa penerima Beasiswa hanya sampai Semester 7')->autoclose(3500);
                            return redirect('beasiswa_mhs');
                        } elseif ($c <= 7) {

                            return view('mhs/beasiswa/form_beasiswa', compact('id', 'mhs', 'id_thn', 'tp', 'c', 'hasil_ipk'));
                        }
                    }
                }
            }
        }
    }

    public function save_pengajuan_beasiswa(Request $request)
    {
        $ang = new Beasiswa_trans();
        $ang->id_student = $request->id_student;
        $ang->id_periodetahun = $request->id_periodetahun;
        $ang->id_periodetipe = $request->id_periodetipe;
        $ang->id_semester = $request->id_semester;
        $ang->ipk = $request->ipk;
        $ang->created_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Data Pengajuan Beasiswa berhasil ditambahkan')->autoclose(3500);
        return redirect('beasiswa_mhs');
    }

    public function bim_perwalian()
    {
        $id = Auth::user()->id_user;

        $dsn_pa = DosenPembimbing::where('id_student', $id)->first();

        $data = Perwalian_trans_bimbingan::join('dosen_pembimbing', 'perwalian_trans_bimbingan.id_dosbim_pa', '=', 'dosen_pembimbing.id')
            ->join('periode_tahun', 'perwalian_trans_bimbingan.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'perwalian_trans_bimbingan.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('dosen_pembimbing.id_student', $id)
            ->where('perwalian_trans_bimbingan.status', 'ACTIVE')
            ->select(
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'perwalian_trans_bimbingan.id_transbim_perwalian',
                'dosen_pembimbing.id',
                'perwalian_trans_bimbingan.tanggal_bimbingan',
                'perwalian_trans_bimbingan.isi_bimbingan',
                'perwalian_trans_bimbingan.status',
                'perwalian_trans_bimbingan.validasi'
            )
            ->get();

        return view('mhs/perkuliahan/bimbingan_perwalian', compact('data', 'dsn_pa'));
    }

    public function post_bim_pa(Request $request)
    {
        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $ang = new Perwalian_trans_bimbingan();
        $ang->id_dosbim_pa = $request->id_dosbim_pa;
        $ang->id_periodetahun = $periode_tahun->id_periodetahun;
        $ang->id_periodetipe = $periode_tipe->id_periodetipe;
        $ang->tanggal_bimbingan = $request->tanggal_bimbingan;
        $ang->isi_bimbingan = $request->isi_bimbingan;
        $ang->created_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Data Bimbingan berhasil ditambahkan')->autoclose(3500);
        return redirect('bim_perwalian');
    }

    public function edit_bimbingan_perwalian(Request $request, $id)
    {
        $ang = Perwalian_trans_bimbingan::find($id);
        $ang->tanggal_bimbingan = $request->tanggal_bimbingan;
        $ang->isi_bimbingan = $request->isi_bimbingan;
        $ang->updated_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Data Bimbingan berhasil diedit')->autoclose(3500);
        return redirect('bim_perwalian');
    }

    public function hapus_bim_perwalian($id)
    {
        Perwalian_trans_bimbingan::where('id_transbim_perwalian', $id)->update([
            'status' => 'NOT ACTIVE'
        ]);

        Alert::success('', 'Data Bimbingan berhasil dihapus')->autoclose(3500);
        return redirect('bim_perwalian');
    }

    public function absen_ujian_mhs()
    {
        $id = Auth::user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi',
                'student.intake'
            )
            ->first();

        $idangkatan = $datamhs->idangkatan;
        $idstatus = $datamhs->idstatus;
        $kodeprodi = $datamhs->kodeprodi;
        $idprodi = $datamhs->id_prodi;
        $intake = $datamhs->intake;

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $id_tahun = $periode_tahun->id_periodetahun;
        $id_tipe = $periode_tipe->id_periodetipe;

        $cek_ujian = Ujian_transaction::where('ujian_transaction.id_periodetahun', $id_tahun)
            ->where('ujian_transaction.id_periodetipe', $id_tipe)
            ->where('ujian_transaction.status', 'ACTIVE')
            ->select(
                DB::raw('MAX(ujian_transaction.id_ujiantrans)'),
                'ujian_transaction.id_periodetahun',
                'ujian_transaction.id_periodetipe',
                'ujian_transaction.jenis_ujian'
            )
            ->groupBy(
                'ujian_transaction.id_periodetahun',
                'ujian_transaction.id_periodetipe',
                'ujian_transaction.jenis_ujian'
            )
            ->get();

        $hitung_ujian = count($cek_ujian);

        //cek semester
        $sub_thn = substr($periode_tahun->periode_tahun, 6, 2);

        $smt = $sub_thn . $id_tipe;

        if ($smt % 2 != 0) {
            if ($id_tipe == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10;
                $b = $a - $idangkatan;
                if ($intake == 2) {
                    $c = ($b * 2) - 1 - 1;
                } elseif ($intake == 1) {
                    $c = ($b * 2) - 1;
                } // 2 * 2 - 1 = 3
            } elseif ($id_tipe == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10;
                $b = $a - $idangkatan;

                if ($intake == 2) {
                    $c = $b * 2 - 1;
                } elseif ($intake == 1) {
                    $c = $b * 2;
                }
            }
        } else {
            //genap
            $a = (($smt + 10) - 2) / 10;
            $b = $a - $idangkatan;

            if ($intake == 2) {
                $c = $b * 2 - 1;
            } elseif ($intake == 1) {
                $c = $b * 2;
            }
        }

        $biaya = Biaya::where('idangkatan', $idangkatan)
            ->where('idstatus', $idstatus)
            ->where('kodeprodi', $kodeprodi)
            ->select(
                'daftar',
                'awal',
                'dsp',
                'spp1',
                'spp2',
                'spp3',
                'spp4',
                'spp5',
                'spp6',
                'spp7',
                'spp8',
                'spp9',
                'spp10',
                'spp11',
                'spp12',
                'spp13',
                'spp14',
                'prakerin',
                'seminar',
                'sidang'
            )
            ->first();

        $cek_bea = Beasiswa::where('idstudent', $id)->first();

        if ($cek_bea != null) {
            $daftar = $biaya->daftar - ($biaya->daftar * $cek_bea->daftar) / 100;
            $awal = $biaya->awal - ($biaya->awal * $cek_bea->awal) / 100;
            $dsp = $biaya->dsp - ($biaya->dsp * $cek_bea->dsp) / 100;
            $spp1 = $biaya->spp1 - ($biaya->spp1 * $cek_bea->spp1) / 100;
            $spp2 = $biaya->spp2 - ($biaya->spp2 * $cek_bea->spp2) / 100;
            $spp3 = $biaya->spp3 - ($biaya->spp3 * $cek_bea->spp3) / 100;
            $spp4 = $biaya->spp4 - ($biaya->spp4 * $cek_bea->spp4) / 100;
            $spp5 = $biaya->spp5 - ($biaya->spp5 * $cek_bea->spp5) / 100;
            $spp6 = $biaya->spp6 - ($biaya->spp6 * $cek_bea->spp6) / 100;
            $spp7 = $biaya->spp7 - ($biaya->spp7 * $cek_bea->spp7) / 100;
            $spp8 = $biaya->spp8 - ($biaya->spp8 * $cek_bea->spp8) / 100;
            $spp9 = $biaya->spp9 - ($biaya->spp9 * $cek_bea->spp9) / 100;
            $spp10 = $biaya->spp10 - ($biaya->spp10 * $cek_bea->spp10) / 100;
            $spp11 = $biaya->spp11 - ($biaya->spp11 * $cek_bea->spp11) / 100;
            $spp12 = $biaya->spp12 - ($biaya->spp12 * $cek_bea->spp12) / 100;
            $spp13 = $biaya->spp13 - ($biaya->spp13 * $cek_bea->spp13) / 100;
            $spp14 = $biaya->spp14 - ($biaya->spp14 * $cek_bea->spp14) / 100;
            $prakerin = $biaya->prakerin - (($biaya->prakerin * ($cek_bea->prakerin)) / 100);
            $seminar = $biaya->seminar - (($biaya->seminar * ($cek_bea->seminar)) / 100);
            $sidang = $biaya->sidang - (($biaya->sidang * ($cek_bea->sidang)) / 100);
        } elseif ($cek_bea == null) {
            $daftar = $biaya->daftar;
            $awal = $biaya->awal;
            $dsp = $biaya->dsp;
            $spp1 = $biaya->spp1;
            $spp2 = $biaya->spp2;
            $spp3 = $biaya->spp3;
            $spp4 = $biaya->spp4;
            $spp5 = $biaya->spp5;
            $spp6 = $biaya->spp6;
            $spp7 = $biaya->spp7;
            $spp8 = $biaya->spp8;
            $spp9 = $biaya->spp9;
            $spp10 = $biaya->spp10;
            $spp11 = $biaya->spp11;
            $spp12 = $biaya->spp12;
            $spp13 = $biaya->spp13;
            $spp14 = $biaya->spp14;
            $prakerin = $biaya->prakerin;
            $seminar = $biaya->seminar;
            $sidang = $biaya->sidang;
        }

        #total pembayaran kuliah
        $total_semua_dibayar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
            ->where('kuitansi.idstudent', $id)
            ->whereNotIn('bayar.iditem', [14, 15, 35, 36, 37, 38])
            ->sum('bayar.bayar');

        #minimal pembayaran UTS
        $min_uts = Min_biaya::where('kategori', 'UTS')->first();
        $persen_uts = $min_uts->persentase;
        $min_uas = Min_biaya::where('kategori', 'UAS')->first();
        $persen_uas = $min_uas->persentase;

        if ($hitung_ujian == 1) {
            if ($c == 1) {
                $cekbyr = $daftar + $awal + ($spp1 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == 2) {
                $cekbyr = $daftar + $awal + ($dsp * 25) / 100 + $spp1 + ($spp2 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == '201') {
                $cekbyr = ($daftar + $awal + ($dsp * 91 / 100) + $spp1 + ($spp2 * 82 / 100)) - $total_semua_dibayar;
            } elseif ($c == 3) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + ($spp3 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == 4) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == '401') {
                $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82 / 100)) - $total_semua_dibayar;
            } elseif ($c == 5) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == 6) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == '601') {
                $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82 / 100)) - $total_semua_dibayar;
            } elseif ($c == 7) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + ($spp7 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == 8) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * $persen_uts) / 100 - $total_semua_dibayar;
            } elseif ($c == '801') {
                $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82 / 100)) - $total_semua_dibayar;
            } elseif ($c == 9) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + ($spp9 * 50) / 100 - $total_semua_dibayar;
            } elseif ($c == 10) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 50) / 100 - $total_semua_dibayar;
            } elseif ($c == '1001') {
                $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82 / 100)) - $total_semua_dibayar;
            } elseif ($c == 11) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + ($spp11 * 50) / 100 - $total_semua_dibayar;
            } elseif ($c == 12) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11(($spp12 * 50) / 100) - $total_semua_dibayar;
            } elseif ($c == 13) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + ($spp13 * 50) / 100 - $total_semua_dibayar;
            } elseif ($c == 14) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + ($spp14 * 50) / 100 - $total_semua_dibayar;
            }

            if ($cekbyr == 0 or $cekbyr < 1000) {

                $data_ujian = DB::select('CALL absensi_ujian(?,?,?)', [$id_tahun, $id_tipe, $id]);

                return view('mhs/ujian/absensi_ujian', compact('periode_tahun', 'periode_tipe', 'datamhs', 'data_ujian'));
            } else {

                Alert::success('Maaf anda tidak dapat mengakses Absen Ujian UTS karena keuangan Anda belum memenuhi syarat')->autoclose(3500);
                return redirect('home');
            }
        } elseif ($hitung_ujian == 2) {

            if ($c == 1) {
                $cekbyr = $daftar + $awal + ($spp1 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == 2) {
                $cekbyr = $daftar + $awal + ($dsp * 91) / 100 + $spp1 + ($spp2 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == '201') {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2  - $total_semua_dibayar;
            } elseif ($c == 3) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + ($spp3 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == 4) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == '401') {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
            } elseif ($c == 5) {
                if ($kodeprodi == 23 or $kodeprodi == 24) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5 * $persen_uas / 100) - $total_semua_dibayar;
                } elseif ($kodeprodi == 25) {
                    $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5 * $persen_uas / 100) - $total_semua_dibayar;
                }
            } elseif ($c == 6) {
                $cekbyr = ($daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * $persen_uas / 100)) - ($total_semua_dibayar);
            } elseif ($c == '601') {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
            } elseif ($c == 7) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + ($spp7 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == 8) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == '801') {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
            } elseif ($c == 9) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + ($spp9 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == 10) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == '1001') {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
            } elseif ($c == 11) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + ($spp11 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == 12) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + ($spp12 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == 13) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + ($spp13 * $persen_uas / 100) - $total_semua_dibayar;
            } elseif ($c == 14) {
                $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + ($spp14 * $persen_uas / 100) - $total_semua_dibayar;
            }
            //    dd($cekbyr);
            if (empty($cekbyr == 0 or $cekbyr < 1000)) {

                Alert::error('Maaf anda tidak dapat mengakses Absen Ujian UAS karena keuangan Anda belum memenuhi syarat')->autoclose(3500);
                return redirect('home');
            }

            $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                ->where('student_record.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $id_tipe)
                ->where('kurikulum_periode.id_periodetahun', $id_tahun)
                ->where('student_record.status', 'TAKEN')
                ->where('kurikulum_periode.status', 'ACTIVE')
                ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
                ->get();

            $hit = count($records);

            #cek jumlah pengisian edom
            $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                // ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->where('edom_transaction.id_student', $id)
                ->where('kurikulum_periode.id_periodetipe', $id_tipe)
                ->where('kurikulum_periode.id_periodetahun', $id_tahun)
                ->where('kurikulum_periode.status', 'ACTIVE')
                ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
                ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
                ->get();

            $sekhit = count($cekedom);

            if (empty(($hit - 2) <= $sekhit)) {

                Alert::error('Maaf anda belum melakukan pengisian edom')->autoclose(3500);
                return redirect('home');
            }

            if ($cekbyr == 0 or $cekbyr < 1000) {

                $records = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                    ->where('student_record.id_student', $id)
                    ->where('kurikulum_periode.id_periodetipe', $id_tipe)
                    ->where('kurikulum_periode.id_periodetahun', $id_tahun)
                    ->where('student_record.status', 'TAKEN')
                    ->where('kurikulum_periode.status', 'ACTIVE')
                    ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
                    ->get();

                $hit = count($records);

                #cek jumlah pengisian edom
                $cekedom = Edom_transaction::join('kurikulum_periode', 'edom_transaction.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                    // ->join('kurikulum_transaction', 'edom_transaction.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                    ->where('edom_transaction.id_student', $id)
                    ->where('kurikulum_periode.id_periodetipe', $id_tipe)
                    ->where('kurikulum_periode.id_periodetahun', $id_tahun)
                    ->where('kurikulum_periode.status', 'ACTIVE')
                    ->whereNotIn('kurikulum_periode.id_makul', [281, 286, 235, 430, 478, 479, 480, 481, 482, 483, 484, 485, 486, 487, 488, 490])
                    ->select(DB::raw('DISTINCT(edom_transaction.id_kurperiode)'))
                    ->get();

                $sekhit = count($cekedom);

                if (($hit - 2) <= $sekhit) {
                    # cek kuisioner Pembimbing Akademik
                    $cek_kuis_pa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                        ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                        ->where('kuisioner_transaction.id_student', $id)
                        ->where('kuisioner_master_kategori.id_kategori_kuisioner', 1)
                        ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
                        ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
                        ->get();

                    if (count($cek_kuis_pa) > 0) {
                        #cek kuisioner BAAK
                        $cek_kuis_baak = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                            ->where('kuisioner_transaction.id_student', $id)
                            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
                            ->where('kuisioner_transaction.id_periodetahun',  $id_tahun)
                            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
                            ->get();

                        if (count($cek_kuis_baak) > 0) {
                            #cek kuisioner BAUK
                            $cek_kuis_bauk = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                ->where('kuisioner_transaction.id_student', $id)
                                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
                                ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
                                ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
                                ->get();

                            if (count($cek_kuis_bauk) > 0) {
                                #cek kuisioner PERPUS
                                $cek_kuis_perpus = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                    ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                    ->where('kuisioner_transaction.id_student', $id)
                                    ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
                                    ->where('kuisioner_transaction.id_periodetahun',  $id_tahun)
                                    ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
                                    ->get();

                                if (count($cek_kuis_perpus) > 0) {
                                    #cek kuisioner Beasiswa
                                    $cek_kuis_beasiswa = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                                        ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                                        ->where('kuisioner_transaction.id_student', $id)
                                        ->where('kuisioner_master_kategori.id_kategori_kuisioner', 9)
                                        ->where('kuisioner_transaction.id_periodetahun',  $id_tahun)
                                        ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
                                        ->get();

                                    if (count($cek_kuis_beasiswa) > 0) {
                                        $data_ujian = DB::select('CALL absensi_ujian(?,?,?)', [$id_tahun, $id_tipe, $id]);

                                        return view('mhs/ujian/absensi_ujian', compact('periode_tahun', 'periode_tipe', 'datamhs', 'data_ujian'));
                                    } elseif (count($cek_kuis_beasiswa) == 0) {

                                        Alert::error('Maaf anda belum melakukan pengisian kuisioner BEASISWA', 'MAAF !!');
                                        return redirect('home');
                                    }
                                } elseif (count($cek_kuis_perpus) == 0) {
                                    Alert::error('Maaf anda belum melakukan pengisian kuisioner PERPUSTAKAAN', 'MAAF !!');
                                    return redirect('home');
                                }
                            } elseif (count($cek_kuis_bauk) == 0) {
                                Alert::error('Maaf anda belum melakukan pengisian kuisioner BAUK', 'MAAF !!');
                                return redirect('home');
                            }
                        } elseif (count($cek_kuis_baak) == 0) {
                            Alert::error('Maaf anda belum melakukan pengisian kuisioner BAAK', 'MAAF !!');
                            return redirect('home');
                        }
                    } elseif (count($cek_kuis_pa) == 0) {
                        Alert::error('Maaf anda belum melakukan pengisian kuisioner Pembimbing Akademik', 'MAAF !!');
                        return redirect('home');
                    }
                } else {
                    Alert::error('Maaf anda belum melakukan pengisian edom', 'MAAF !!');
                    return redirect('home');
                }
            } else {
                Alert::success('Maaf anda tidak dapat mengakses Absen Ujian UAS karena keuangan Anda belum memenuhi syarat ')->autoclose(3500);
                //Alert::warning('Maaf anda tidak dapat mengakses Absen Ujian UAS karena keuangan Anda belum memenuhi syarat');
                return redirect('home');
            }
        } elseif ($hitung_ujian == 0) {
            Alert::warning('Maaf Jadwal Ujian Belum ada');
            return redirect('home');
        }
    }

    public function absen_ujian_uts($id)
    {
        $cek_data = Absen_ujian::where('id_studentrecord', $id)->first();

        $todayDate = date("Y-m-d");

        if ($cek_data == null) {
            $data = new Absen_ujian();
            $data->id_studentrecord = $id;
            $data->absen_uts = $todayDate;
            $data->created_by = Auth::user()->name;
            $data->save();
        } else {
            Absen_ujian::where('id_studentrecord', $id)->update([
                'absen_uts' => $todayDate,
                'updated_by' => Auth::user()->name
            ]);
        }

        Alert::success('', 'Absen berhasil disimpan')->autoclose(3500);
        return redirect()->back();
    }

    public function absen_ujian_uas_memenuhi($id)
    {
        $cek_data = Absen_ujian::where('id_studentrecord', $id)->first();

        $todayDate = date("Y-m-d");

        if ($cek_data == null) {

            $data = new Absen_ujian();
            $data->id_studentrecord = $id;
            $data->absen_uas = $todayDate;
            $data->ket_absensi = 'MEMENUHI';
            $data->created_by = Auth::user()->name;
            $data->save();
        } else {

            Absen_ujian::where('id_studentrecord', $id)->update([
                'absen_uas' => $todayDate,
                'ket_absensi' => 'MEMENUHI',
                'updated_by' => Auth::user()->name
            ]);
        }

        Alert::success('', 'Absen berhasil disimpan')->autoclose(3500);
        return redirect()->back();
    }

    public function absen_ujian_uas_tdk_memenuhi($id)
    {
        $cek_data = Absen_ujian::where('id_studentrecord', $id)->first();

        $todayDate = date("Y-m-d");

        if ($cek_data == null) {
            $data = new Absen_ujian();
            $data->id_studentrecord = $id;
            $data->absen_uas = $todayDate;
            $data->ket_absensi = 'TIDAK MEMENUHI';
            $data->created_by = Auth::user()->name;
            $data->save();
        } else {
            Absen_ujian::where('id_studentrecord', $id)->update([
                'absen_uas' => $todayDate,
                'ket_absensi' => 'TIDAK MEMENUHI',
                'updated_by' => Auth::user()->name
            ]);
        }

        Alert::success('', 'Absen berhasil disimpan')->autoclose(3500);
        return redirect()->back();
    }

    public function ajukan_keringanan_absen($id)
    {
        $cek_data = Permohonan_ujian::where('id_studentrecord', $id)->first();

        $cek_data1 = Absen_ujian::where('id_studentrecord', $id)->first();

        if ($cek_data == null) {
            $data = new Permohonan_ujian();
            $data->id_studentrecord = $id;
            $data->permohonan = 'MENGAJUKAN';
            $data->created_by = Auth::user()->name;
            $data->save();
        } else {
            Permohonan_ujian::where('id_studentrecord', $id)->update([
                'permohonan' => 'MENGAJUKAN',
                'updated_by' => Auth::user()->name
            ]);
        }

        if ($cek_data1 == null) {
            $data1 = new Absen_ujian();
            $data1->id_studentrecord = $id;
            $data1->permohonan = 'MENGAJUKAN';
            $data->created_by = Auth::user()->name;
            $data1->save();
        } else {
            Absen_ujian::where('id_studentrecord', $id)->update([
                'permohonan' => 'MENGAJUKAN',
                'updated_by' => Auth::user()->name
            ]);
        }

        Alert::success('', 'Pengajuan berhasil disimpan')->autoclose(3500);
        return redirect()->back();
    }

    function soal_ujian_mhs()
    {
        $id = Auth::user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi',
                'student.intake'
            )
            ->first();

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $soal_ujian = DB::select('CALL soal_ujian_uts_uas_mhs(?,?,?,?,?)', [$periode_tahun->id_periodetahun, $periode_tipe->id_periodetipe, $datamhs->id_prodi, $datamhs->idstatus, $id]);

        return view('mhs/ujian/soal_ujian', compact('datamhs', 'periode_tahun', 'periode_tipe', 'soal_ujian'));
    }

    public function kuisioner_mahasiswa()
    {
        $data = Kuisioner_kategori::where('status', 'ACTIVE')->get();

        return view('mhs/kuisioner_new/kuisioner_all', compact('data'));
    }

    public function isi_dosen_pa_new($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner_mahasiswa');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = DosenPembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
                ->join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->where('student.idstudent', $ids)
                ->where('dosen_pembimbing.status', 'ACTIVE')
                ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'dosen_pembimbing.id_dosen')
                ->first();

            $prodi = $mhs->prodi;
            $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            $periodetahun = $thn->periode_tahun;
            $periodetipe = $tp->periode_tipe;

            //untuk ke database
            $id_dsn = $mhs->id_dosen;
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cekkuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dsn)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master.id_kategori_kuisioner', $id)
                ->get();

            if (count($cekkuis) > 0) {
                Alert::warning('maaf kuisioner isi sudah diisi', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cekkuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner_new/kuisioner_dsn_pa', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
            }
        }
    }

    public function save_kuisioner_dsn_pa_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_pembimbing;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master.id_kategori_kuisioner', 1)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_pembimbing = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_dosen_pkl_new($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-601', 'TI-601', 'TK-601', 'FA-5001', 'TI-5001'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        // if ($hasil_krs == 0) {
        //     Alert::error('Maaf anda belum melakukan pengisian KRS Kerja Praktek/Prakerin', 'MAAF !!');
        //     return redirect('kuisioner_mahasiswa');
        // } elseif ($hasil_krs > 0) {
        //cek nilai dan file seminar prakerin
        $cekdata_bim = Prausta_setting_relasi::where('prausta_setting_relasi.id_student', $ids)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->select('prausta_setting_relasi.id_dosen_pembimbing')
            ->get();

        if (count($cekdata_bim) == 0) {
            Alert::error('Maaf dosen pembimbbing anda belum disetting untuk Kerja Praktek/Prakerin', 'MAAF !!');
            return redirect('_new');
        } elseif (count($cekdata_bim) > 0) {
            $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->where('student.idstudent', $ids)
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3, 12, 15, 18, 21])
                ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_pembimbing')
                ->first();

            $prodi = $mhs->prodi;
            $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            $periodetahun = $thn->periode_tahun;
            $periodetipe = $tp->periode_tipe;

            //untuk ke database
            $id_dsn = $mhs->id_dosen_pembimbing;
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dsn)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master.id_kategori_kuisioner', $id)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner_new/kuisioner_dsn_pkl', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
            }
        }
        // }
    }

    public function save_kuisioner_dsn_pkl_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_pembimbing;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 2)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_pembimbing = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_dosen_ta_new($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif ($hasil_krs > 0) {
            $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                ->where('prausta_setting_relasi.id_student', $ids)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing')
                ->get();

            if (count($cekdata) == 0) {
                Alert::error('Maaf Dosen Pembimbing Tugas Akhir anda belum di setting', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cekdata) > 0) {
                $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->where('student.idstudent', $ids)
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                    ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_pembimbing')
                    ->first();

                $prodi = $mhs->prodi;
                $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

                $thn = Periode_tahun::where('status', 'ACTIVE')->first();

                $tp = Periode_tipe::where('status', 'ACTIVE')->first();

                $periodetahun = $thn->periode_tahun;
                $periodetipe = $tp->periode_tipe;

                //untuk ke database
                $id_dsn = $mhs->id_dosen_pembimbing;
                $idthn = $thn->id_periodetahun;
                $idtp = $tp->id_periodetipe;

                $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                    ->where('kuisioner_transaction.id_student', $ids)
                    ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dsn)
                    ->where('kuisioner_transaction.id_periodetahun', $idthn)
                    ->where('kuisioner_transaction.id_periodetipe', $idtp)
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->get();

                if (count($cek_kuis) > 0) {
                    Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                    return redirect('kuisioner_mahasiswa');
                } elseif (count($cek_kuis) == 0) {
                    $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                        ->where('kuisioner_master.id_kategori_kuisioner', $id)
                        ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                        ->get();

                    return view('mhs/kuisioner_new/kuisioner_dsn_ta', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
                }
            }
        }
    }

    public function save_kuisioner_dsn_ta_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_pembimbing;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_pembimbing', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 3)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_pembimbing = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_dosen_ta_peng1_new($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif ($hasil_krs > 0) {
            $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                ->where('prausta_setting_relasi.id_student', $ids)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing')
                ->get();
            if (count($cekdata) == 0) {
                Alert::error('Maaf Dosen Pembimbing Tugas Akhir anda belum di setting', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cekdata) > 0) {
                foreach ($cekdata as $cek_peng1) {
                    # code...
                }

                if ($cek_peng1->id_dosen_penguji_1 == null) {
                    Alert::error('Maaf Dosen Penguji 1 Sidang Tugas Akhir anda belum di setting', 'MAAF !!');
                    return redirect('kuisioner_mahasiswa');
                } elseif ($cek_peng1->id_dosen_penguji_1 != null) {
                    $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                        ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_1', '=', 'dosen.iddosen')
                        ->leftJoin('prodi', (function ($join) {
                            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                        }))
                        ->where('student.idstudent', $ids)
                        ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                        ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_penguji_1')
                        ->first();

                    $prodi = $mhs->prodi;
                    $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

                    $thn = Periode_tahun::where('status', 'ACTIVE')->first();

                    $tp = Periode_tipe::where('status', 'ACTIVE')->first();

                    $periodetahun = $thn->periode_tahun;
                    $periodetipe = $tp->periode_tipe;

                    //untuk ke database
                    $id_dsn = $mhs->id_dosen_penguji_1;
                    $idthn = $thn->id_periodetahun;
                    $idtp = $tp->id_periodetipe;

                    $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                        ->where('kuisioner_transaction.id_student', $ids)
                        ->where('kuisioner_transaction.id_dosen_penguji_1', $id_dsn)
                        ->where('kuisioner_transaction.id_periodetahun', $idthn)
                        ->where('kuisioner_transaction.id_periodetipe', $idtp)
                        ->where('kuisioner_master.id_kategori_kuisioner', $id)
                        ->get();

                    if (count($cek_kuis) > 0) {
                        Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                        return redirect('kuisioner_mahasiswa');
                    } elseif (count($cek_kuis) == 0) {
                        $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                            ->where('kuisioner_master.id_kategori_kuisioner', $id)
                            ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                            ->get();

                        return view('mhs/kuisioner_new/kuisioner_dsn_ta_peng1', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
                    }
                }
            }
        }
    }

    public function save_kuisioner_dsn_ta_peng1_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_penguji_1;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_penguji_1', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 4)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_penguji_1 = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_dosen_ta_peng2_new($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif ($hasil_krs > 0) {
            $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                ->where('prausta_setting_relasi.id_student', $ids)
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_setting_relasi.id_dosen_penguji_1', 'prausta_setting_relasi.id_dosen_penguji_2', 'prausta_setting_relasi.id_dosen_pembimbing')
                ->get();
            if (count($cekdata) == 0) {
                Alert::error('Maaf Dosen Pembimbing Tugas Akhir anda belum di setting', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cekdata) > 0) {
                foreach ($cekdata as $cek_peng1) {
                    # code...
                }
                if ($cek_peng1->id_dosen_penguji_2 == null) {
                    Alert::error('Maaf Dosen Penguji 2 Sidang Tugas Akhir anda belum di setting', 'MAAF !!');
                    return redirect('kuisioner_mahasiswa');
                } elseif ($cek_peng1->id_dosen_penguji_2 != null) {
                    $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                        ->join('dosen', 'prausta_setting_relasi.id_dosen_penguji_2', '=', 'dosen.iddosen')
                        ->leftJoin('prodi', (function ($join) {
                            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                        }))
                        ->where('student.idstudent', $ids)
                        ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9, 14, 17, 20, 23])
                        ->select('dosen.nama', 'dosen.akademik', 'prodi.prodi', 'prausta_setting_relasi.id_dosen_penguji_2')
                        ->first();

                    $prodi = $mhs->prodi;
                    $nama_dsn = $mhs->nama . ',' . ' ' . $mhs->akademik;

                    $thn = Periode_tahun::where('status', 'ACTIVE')->first();

                    $tp = Periode_tipe::where('status', 'ACTIVE')->first();

                    $periodetahun = $thn->periode_tahun;
                    $periodetipe = $tp->periode_tipe;

                    //untuk ke database
                    $id_dsn = $mhs->id_dosen_penguji_2;
                    $idthn = $thn->id_periodetahun;
                    $idtp = $tp->id_periodetipe;

                    $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                        ->where('kuisioner_transaction.id_student', $ids)
                        ->where('kuisioner_transaction.id_dosen_penguji_2', $id_dsn)
                        ->where('kuisioner_transaction.id_periodetahun', $idthn)
                        ->where('kuisioner_transaction.id_periodetipe', $idtp)
                        ->where('kuisioner_master.id_kategori_kuisioner', $id)
                        ->get();

                    if (count($cek_kuis) > 0) {
                        Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                        return redirect('kuisioner_mahasiswa');
                    } elseif (count($cek_kuis) == 0) {
                        $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                            ->where('kuisioner_master.id_kategori_kuisioner', $id)
                            ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                            ->get();

                        return view('mhs/kuisioner_new/kuisioner_dsn_ta_peng2', compact('data', 'prodi', 'nama_dsn', 'periodetahun', 'periodetipe', 'ids', 'idthn', 'idtp', 'id_dsn'));
                    }
                }
            }
        }
    }

    public function save_kuisioner_dsn_ta_peng2_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_dosen = $request->id_dosen_penguji_2;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_dosen_penguji_2', $id_dosen)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 5)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_dosen_penguji_2 = $id_dosen;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_kuis_baak_new($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner_mahasiswa');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();

            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner_new/kuisioner_baak', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_baak_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 6)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_kuis_bauk_new($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner_mahasiswa');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();
            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner_new/kuisioner_bauk', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_bauk_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 7)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_kuis_perpus_new($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner_mahasiswa');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();
            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner_new/kuisioner_perpus', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_perpus_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 8)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function isi_kuis_beasiswa_new($id)
    {
        $waktu_edom = Waktu_edom::first();

        if ($waktu_edom->status == 0) {
            Alert::warning('', 'Maaf waktu pengisian kuisioner belum dibuka')->autoclose(3500);
            return redirect('kuisioner_mahasiswa');
        } elseif ($waktu_edom->status == 1) {
            $ids = Auth()->user()->id_user;

            $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
                ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
                ->where('student.idstudent', $ids)
                ->select('prodi.prodi', 'kelas.kelas')
                ->first();

            $prodi = $mhs->prodi;
            $kelas = $mhs->kelas;

            $thn = Periode_tahun::where('status', 'ACTIVE')->first();
            $tp = Periode_tipe::where('status', 'ACTIVE')->first();

            //untuk ke database
            $idthn = $thn->id_periodetahun;
            $idtp = $tp->id_periodetipe;

            $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
                ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
                ->where('kuisioner_transaction.id_student', $ids)
                ->where('kuisioner_transaction.id_periodetahun', $idthn)
                ->where('kuisioner_transaction.id_periodetipe', $idtp)
                ->where('kuisioner_master_kategori.id_kategori_kuisioner', 9)
                ->get();

            if (count($cek_kuis) > 0) {
                Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
                return redirect('kuisioner_mahasiswa');
            } elseif (count($cek_kuis) == 0) {
                $data = Kuisioner_master::join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
                    ->where('kuisioner_master.id_kategori_kuisioner', $id)
                    ->select('kuisioner_master.*', 'kuisioner_master_aspek.aspek_kuisioner', 'kuisioner_master.id_kuisioner')
                    ->get();

                return view('mhs/kuisioner_new/kuisioner_beasiswa', compact('data', 'prodi', 'kelas', 'ids', 'idthn', 'idtp'));
            }
        }
    }

    public function save_kuisioner_beasiswa_new(Request $request)
    {
        $id_student = $request->id_student;
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;
        $nilai = $request->nilai;
        $hitung = count($nilai);

        $mhs = Student::where('idstudent', $id_student)->first();

        $nama = $mhs->nama;
        $nama_ok = str_replace("'", '', $nama);

        $cek_kuis = Kuisioner_transaction::join('kuisioner_master', 'kuisioner_transaction.id_kuisioner', '=', 'kuisioner_master.id_kuisioner')
            ->join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->where('kuisioner_transaction.id_student', $id_student)
            ->where('kuisioner_transaction.id_periodetahun', $id_tahun)
            ->where('kuisioner_transaction.id_periodetipe', $id_tipe)
            ->where('kuisioner_master_kategori.id_kategori_kuisioner', 9)
            ->get();

        if (count($cek_kuis) > 0) {
            Alert::warning('maaf kuisioner ini sudah anda isi', 'MAAF !!');
            return redirect('kuisioner_mahasiswa');
        } elseif (count($cek_kuis) == 0) {
            for ($i = 0; $i < $hitung; $i++) {
                $nilai = $request->nilai[$i];
                $kuis = explode(',', $nilai, 2);
                $id1 = $kuis[0];
                $id2 = $kuis[1];

                $isi = new Kuisioner_transaction();
                $isi->id_kuisioner = $id1;
                $isi->id_student = $id_student;
                $isi->id_periodetahun = $id_tahun;
                $isi->id_periodetipe = $id_tipe;
                $isi->nilai = $id2;
                $isi->created_by = $nama_ok;
                $isi->save();
            }
        }
        Alert::success('', 'Pengisian Kuisioner anda berhasil ')->autoclose(3500);
        return redirect('home');
    }

    public function cuti_mhs()
    {
        $id = Auth()->user()->id_user;

        $tahun_aktif = Periode_tahun::where('status', 'ACTIVE')->first();

        $tahun = Periode_tahun::where('id_periodetahun', '>=', $tahun_aktif->id_periodetahun)->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();

        $data = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('pengajuan_trans.id_kategori_pengajuan', 1)
            ->where('pengajuan_trans.id_student', $id)
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->select(
                'pengajuan_trans.id_trans_pengajuan',
                'pengajuan_trans.id_periodetahun',
                'pengajuan_trans.id_periodetipe',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'pengajuan_trans.alasan',
                'pengajuan_trans.alamat',
                'pengajuan_trans.sks_ditempuh',
                'pengajuan_trans.tgl_pengajuan',
                'pengajuan_trans.no_hp',
                'pengajuan_trans.cuti_sebelumnya',
                'pengajuan_trans.val_bauk',
                'pengajuan_trans.val_dsn_pa',
                'pengajuan_trans.val_baak',
                'pengajuan_trans.val_kaprodi'
            )
            ->get();

        return view('mhs/pengajuan/pengajuan_cuti', compact('data', 'tahun', 'tipe'));
    }

    public function post_pengajuan_cuti(Request $request)
    {
        $id = Auth()->user()->id_user;

        #cek pengajuan cuti sebelumnya
        $data_cuti = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('pengajuan_trans.id_kategori_pengajuan', 1)
            ->where('pengajuan_trans.id_student', $id)
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->select(
                'pengajuan_trans.id_trans_pengajuan'
            )
            ->count();

        if ($data_cuti < 2) {

            if ($data_cuti == 1) {
                #cek pengajuan semester
                $data_semester_cuti = Pengajuan_trans::where('pengajuan_trans.id_kategori_pengajuan', 1)
                    ->where('pengajuan_trans.id_student', $id)
                    ->select(
                        'pengajuan_trans.id_trans_pengajuan',
                        'pengajuan_trans.id_periodetahun',
                        'pengajuan_trans.id_periodetipe',
                    )
                    ->first();

                $cek_tahun = $data_semester_cuti->id_periodetahun;
                $cek_tipe = $data_semester_cuti->id_periodetipe;

                if ($request->id_periodetahun == $cek_tahun && $request->id_periodetipe ==  $cek_tipe) {
                    Alert::warning('', 'Maaf Pengajuan Cuti berlaku 1 semester, jika ingin perpanjang harap ajukan semester berikutnya')->autoclose(3500);
                    return redirect('cuti_mhs');
                } else {
                    #cek nilai dan sks
                    $data_sks = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                        ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                        ->where('student_record.id_student', $id)
                        ->where('student_record.status', 'TAKEN')
                        ->whereNotIn('student_record.nilai_AKHIR', ['D', 'E'])
                        ->select(DB::raw('SUM(matakuliah.akt_sks_teori + matakuliah.akt_sks_praktek) as sks'))
                        ->first();

                    $new = new Pengajuan_trans;
                    $new->id_periodetahun = $request->id_periodetahun;
                    $new->id_periodetipe = $request->id_periodetipe;
                    $new->id_student = $id;
                    $new->id_kategori_pengajuan = 1;
                    $new->alasan = $request->alasan;
                    $new->cuti_sebelumnya = $request->cuti_sebelumnya;
                    $new->sks_ditempuh = $data_sks->sks;
                    $new->alamat = $request->alamat;
                    $new->no_hp = $request->no_hp;
                    $new->tgl_pengajuan = date('Y-m-d');
                    $new->save();

                    Alert::success('', 'Pengajuan Cuti berhasil ditambahkan')->autoclose(3500);
                    return redirect('cuti_mhs');
                }
            } elseif ($data_cuti == 0) {
                #cek nilai dan sks
                $data_sks = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                    ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                    ->where('student_record.id_student', $id)
                    ->where('student_record.status', 'TAKEN')
                    ->whereNotIn('student_record.nilai_AKHIR', ['D', 'E'])
                    ->select(DB::raw('SUM(matakuliah.akt_sks_teori + matakuliah.akt_sks_praktek) as sks'))
                    ->first();

                $new = new Pengajuan_trans;
                $new->id_periodetahun = $request->id_periodetahun;
                $new->id_periodetipe = $request->id_periodetipe;
                $new->id_student = $id;
                $new->id_kategori_pengajuan = 1;
                $new->alasan = $request->alasan;
                $new->cuti_sebelumnya = $request->cuti_sebelumnya;
                $new->sks_ditempuh = $data_sks->sks;
                $new->alamat = $request->alamat;
                $new->no_hp = $request->no_hp;
                $new->tgl_pengajuan = date('Y-m-d');
                $new->save();

                Alert::success('', 'Pengajuan Cuti berhasil ditambahkan')->autoclose(3500);
                return redirect('cuti_mhs');
            }
        } elseif ($data_cuti == 2) {
            Alert::warning('', 'Maaf Pengajuan Cuti Maksimal 2 kali selama perkuliahan')->autoclose(3500);
            return redirect('cuti_mhs');
        }
    }

    public function put_pengajuan_cuti(Request $request, $id)
    {
        $ids = Auth::user()->id_user;

        $cek_edit_cuti = Pengajuan_trans::where('pengajuan_trans.id_kategori_pengajuan', 1)
            ->where('pengajuan_trans.id_student', $ids)
            ->where('pengajuan_trans.id_periodetahun', $request->id_periodetahun)
            ->where('pengajuan_trans.id_periodetipe', $request->id_periodetipe)
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->select(
                'pengajuan_trans.id_trans_pengajuan',
                'pengajuan_trans.id_periodetahun',
                'pengajuan_trans.id_periodetipe',
            )
            ->count();

        if ($cek_edit_cuti == 0) {
            #cek nilai dan sks
            $data_sks = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
                ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                ->where('student_record.id_student', $ids)
                ->where('student_record.status', 'TAKEN')
                ->whereNotIn('student_record.nilai_AKHIR', ['D', 'E'])
                ->select(DB::raw('SUM(matakuliah.akt_sks_teori + matakuliah.akt_sks_praktek) as sks'))
                ->first();

            $new = Pengajuan_trans::find($id);
            $new->id_periodetahun = $request->id_periodetahun;
            $new->id_periodetipe = $request->id_periodetipe;
            $new->id_student = $ids;
            $new->id_kategori_pengajuan = 1;
            $new->alasan = $request->alasan;
            $new->cuti_sebelumnya = $request->cuti_sebelumnya;
            $new->alasan = $request->alasan;
            $new->sks_ditempuh = $data_sks->sks;
            $new->alamat = $request->alamat;
            $new->no_hp = $request->no_hp;
            $new->save();

            Alert::success('', 'Pengajuan Cuti berhasil diedit')->autoclose(3500);
            return redirect('cuti_mhs');
        } else {
            Alert::warning('', 'Maaf Pengajuan Cuti tidak boleh di Tahun Akademik yang sama')->autoclose(3500);
            return redirect('cuti_mhs');
        }
    }

    public function batal_pengajuan_cuti($id)
    {
        Pengajuan_trans::where('id_trans_pengajuan', $id)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Pengajuan Cuti berhasil dihapus')->autoclose(3500);
        return redirect('cuti_mhs');
    }

    public function mengundurkan_diri_mhs()
    {
        $id = Auth()->user()->id_user;

        $tahun_aktif = Periode_tahun::where('status', 'ACTIVE')->first();

        $tahun = Periode_tahun::where('id_periodetahun', '>=', $tahun_aktif->id_periodetahun)->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();

        $data = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('pengajuan_trans.id_kategori_pengajuan', 2)
            ->where('pengajuan_trans.id_student', $id)
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->select(
                'pengajuan_trans.id_trans_pengajuan',
                'pengajuan_trans.id_periodetahun',
                'pengajuan_trans.id_periodetipe',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'pengajuan_trans.alasan',
                'pengajuan_trans.alamat',
                'pengajuan_trans.tgl_pengajuan',
                'pengajuan_trans.no_hp',
                'pengajuan_trans.semester_keluar',
                'pengajuan_trans.val_bauk',
                'pengajuan_trans.val_dsn_pa',
                'pengajuan_trans.val_baak',
                'pengajuan_trans.val_kaprodi'
            )
            ->get();

        return view('mhs/pengajuan/pengajuan_mengundurkan_diri', compact('data', 'tahun', 'tipe'));
    }

    public function post_pengunduran_diri(Request $request)
    {
        $id = Auth()->user()->id_user;

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi',
                'student.intake'
            )
            ->first();

        $idangkatan = $datamhs->idangkatan;
        $idstatus = $datamhs->idstatus;
        $kodeprodi = $datamhs->kodeprodi;
        $idprodi = $datamhs->id_prodi;
        $intake = $datamhs->intake;

        $periode_tahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periode_tipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $id_tahun = $periode_tahun->id_periodetahun;
        $id_tipe = $periode_tipe->id_periodetipe;

        //cek semester
        $sub_thn = substr($periode_tahun->periode_tahun, 6, 2);

        $smt = $sub_thn . $id_tipe;

        if ($smt % 2 != 0) {
            if ($id_tipe == 1) {
                //ganjil
                $a = (($smt + 10) - 1) / 10; // ( 211 + 10 - 1 ) / 10 = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                if ($intake == 2) {
                    $c = ($b * 2) - 1 - 1;
                } elseif ($intake == 1) {
                    $c = ($b * 2) - 1;
                } // 2 * 2 - 1 = 3
            } elseif ($id_tipe == 3) {
                //pendek
                $a = (($smt + 10) - 3) / 10; // ( 213 + 10 - 3 ) / 10  = 22
                $b = $a - $idangkatan; // 22 - 20 = 2
                // $c = ($b * 2);
                if ($intake == 2) {
                    $c = $b * 2 - 1;
                } elseif ($intake == 1) {
                    $c = $b * 2;
                }
            }
        } else {
            //genap
            $a = (($smt + 10) - 2) / 10; // (212 + 10 - 2) / 10 = 22
            $b = $a - $idangkatan; // 22 - 20 = 2
            // 2 * 2 = 4
            if ($intake == 2) {
                $c = $b * 2 - 1;
            } elseif ($intake == 1) {
                $c = $b * 2;
            }
        }

        #cek pengajuan 
        $data_cuti = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('pengajuan_trans.id_kategori_pengajuan', 2)
            ->where('pengajuan_trans.id_student', $id)
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->select(
                'pengajuan_trans.id_trans_pengajuan'
            )
            ->count();

        if ($data_cuti == 0) {
            $new = new Pengajuan_trans;
            $new->id_periodetahun = $request->id_periodetahun;
            $new->id_periodetipe = $request->id_periodetipe;
            $new->id_student = $id;
            $new->id_kategori_pengajuan = 2;
            $new->alasan = $request->alasan;
            $new->no_hp = $request->no_hp;
            $new->semester_keluar = $c;
            $new->save();

            Alert::success('', 'Pengajuan Pengunduran Diri berhasil ditambahkan')->autoclose(3500);
            return redirect('mengundurkan_diri_mhs');
        } elseif ($data_cuti > 0) {
            Alert::warning('', 'Maaf anda telah mengajukan Pengunduran diri sebelumnya')->autoclose(3500);
            return redirect('mengundurkan_diri_mhs');
        }
    }

    public function put_pengajuan_resign(Request $request, $id)
    {
        $ids = Auth::user()->id_user;

        $new = Pengajuan_trans::find($id);
        $new->id_periodetahun = $request->id_periodetahun;
        $new->id_periodetipe = $request->id_periodetipe;
        $new->id_student = $ids;
        $new->id_kategori_pengajuan = 2;
        $new->alasan = $request->alasan;
        $new->no_hp = $request->no_hp;
        $new->save();

        Alert::success('', 'Pengajuan Pengunduran Diri berhasil diedit')->autoclose(3500);
        return redirect('mengundurkan_diri_mhs');
    }

    public function batal_pengajuan_resign($id)
    {
        Pengajuan_trans::where('id_trans_pengajuan', $id)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Pengajuan Pengunduran Diri berhasil dihapus')->autoclose(3500);
        return redirect('mengundurkan_diri_mhs');
    }

    public function perpindahan_kelas_mhs()
    {
        $id = Auth()->user()->id_user;

        $tahun_aktif = Periode_tahun::where('status', 'ACTIVE')->first();

        $tahun = Periode_tahun::where('id_periodetahun', '>=', $tahun_aktif->id_periodetahun)->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();

        $kelas = Kelas::orderBy('kelas', 'ASC')->get();

        $datamhs = Student::leftJoin('prodi', (function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select(
                'student.nama',
                'student.nim',
                'kelas.kelas',
                'prodi.prodi',
                'student.idangkatan',
                'student.idstatus',
                'student.kodeprodi',
                'prodi.id_prodi',
                'student.intake'
            )
            ->first();


        $data = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('pengajuan_trans.id_kategori_pengajuan', 3)
            ->where('pengajuan_trans.id_student', $id)
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->select(
                'pengajuan_trans.id_trans_pengajuan',
                'pengajuan_trans.id_periodetahun',
                'pengajuan_trans.id_periodetipe',
                'periode_tahun.periode_tahun',
                'periode_tipe.periode_tipe',
                'pengajuan_trans.alasan',
                'pengajuan_trans.kelas_sebelum',
                'pengajuan_trans.kelas_tujuan',
                'pengajuan_trans.tgl_pengajuan',
                'pengajuan_trans.no_hp',
                'pengajuan_trans.val_bauk',
                'pengajuan_trans.val_dsn_pa',
                'pengajuan_trans.val_baak',
                'pengajuan_trans.val_kaprodi'
            )
            ->get();

        return view('mhs/pengajuan/pengajuan_pindah_kelas', compact('kelas', 'data', 'tahun', 'tipe', 'datamhs'));
    }

    public function post_pindah_kelas(Request $request)
    {
        $id = Auth()->user()->id_user;
        #cek pengajuan 
        $data_pindah = Pengajuan_trans::join('periode_tahun', 'pengajuan_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'pengajuan_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('pengajuan_trans.id_kategori_pengajuan', 3)
            ->where('pengajuan_trans.id_student', $id)
            ->where('pengajuan_trans.status', 'ACTIVE')
            ->select(
                'pengajuan_trans.id_trans_pengajuan'
            )
            ->count();

        if ($data_pindah == 0) {
            $new = new Pengajuan_trans;
            $new->id_periodetahun = $request->id_periodetahun;
            $new->id_periodetipe = $request->id_periodetipe;
            $new->id_student = $id;
            $new->id_kategori_pengajuan = 3;
            $new->alasan = $request->alasan;
            $new->no_hp = $request->no_hp;
            $new->kelas_sebelum = $request->kelas_sebelum;
            $new->kelas_tujuan = $request->kelas_tujuan;
            $new->save();

            Alert::success('', 'Pengajuan Pindah Kelas berhasil ditambahkan')->autoclose(3500);
            return redirect('perpindahan_kelas_mhs');
        } elseif ($data_pindah > 0) {
            Alert::warning('', 'Maaf anda telah mengajukan Pindah Kelas sebelumnya')->autoclose(3500);
            return redirect('perpindahan_kelas_mhs');
        }
    }

    public function put_pengajuan_pindah_kelas(Request $request, $id)
    {
        $ids = Auth::user()->id_user;

        $new = Pengajuan_trans::find($id);
        $new->id_periodetahun = $request->id_periodetahun;
        $new->id_periodetipe = $request->id_periodetipe;
        $new->id_student = $ids;
        $new->id_kategori_pengajuan = 3;
        $new->alasan = $request->alasan;
        $new->no_hp = $request->no_hp;
        $new->kelas_sebelum = $request->kelas_sebelum;
        $new->kelas_tujuan = $request->kelas_tujuan;
        $new->save();

        Alert::success('', 'Pengajuan Pindah Kelas berhasil diedit')->autoclose(3500);
        return redirect('perpindahan_kelas_mhs');
    }

    public function batal_pengajuan_pindah_kelas($id)
    {
        Pengajuan_trans::where('id_trans_pengajuan', $id)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Pengajuan Pindah Kelas berhasil dihapus')->autoclose(3500);
        return redirect('perpindahan_kelas_mhs');
    }
}
