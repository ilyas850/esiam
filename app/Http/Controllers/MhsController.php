<?php

namespace App\Http\Controllers;

use File;
use PDF;
use Alert;
use App\Bap;
use App\Absensi_mahasiswa;
use App\Pedoman_akademik;
use App\Kuliah_tipe;
use App\User;
use App\Dosen;
use App\Kelas;
use App\Prodi;
use App\Student;
use App\Informasi;
use App\Edom_transaction;
use App\Edom_master;
use App\Ruangan;
use App\Semester;
use App\Waktu_krs;
use App\Matakuliah;
use App\Periode_tipe;
use App\Periode_tahun;
use App\Update_mahasiswa;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_master;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Student_record;
use App\Bayar;
use App\Beasiswa;
use App\Biaya;
use App\Dosen_pembimbing;
use App\Itembayar;
use App\Kuitansi;
use App\Prausta_setting_relasi;
use App\Ujian_menit;
use App\Ujian_tipe;
use App\Ujian_transaction;
use App\Kuisioner_master;
use App\Kuisioner_kategori;
use App\Kuisioner_aspek;
use App\Kuisioner_transaction;
use App\Waktu_edom;
use App\Sertifikat;
use App\Skpi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MhsController extends Controller
{
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

        return view('mhs/update', ['mhs' => $user]);
    }

    public function store_update(Request $request)
    {
        $this->validate($request, [
            'id_mhs' => 'required',
            'nim_mhs' => 'required',
            'hp_baru' => 'required',
            'email_baru' => 'required',
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
            'id_mhs' => 'required',
            'hp_baru' => 'required',
            'email_baru' => 'required',
        ]);

        $user = Update_Mahasiswa::find($id);
        $user->id_mhs = $request->id_mhs;
        $user->hp_baru = $request->hp_baru;
        $user->email_baru = $request->email_baru;
        $user->save();

        return redirect('home');
    }

    public function simpan_krs(Request $request)
    {
        $this->validate($request, [
            'id_student' => 'required',
            'id_kurperiode' => 'required',
        ]);

        $jml = count($request->id_kurperiode);
        for ($i = 0; $i < $jml; $i++) {
            $kurp = $request->id_kurperiode[$i];
            $idr = explode(',', $kurp, 2);
            $tra = $idr[0];
            $trs = $idr[1];
            $cekkrs = Student_record::where('id_student', $request->id_student)
                ->where('id_kurperiode', $tra)
                ->where('id_kurtrans', $trs)
                ->where('status', 'TAKEN')
                ->get();
        }

        if (count($cekkrs) > 0) {
            Alert::warning('maaf mata kuliah sudah dipilih', 'MAAF !!');
            return redirect('isi_krs');
        } elseif (count($cekkrs) == 0) {
            $krs = new Student_record();
            $krs->tanggal_krs = date('Y-m-d');
            $krs->id_student = $request->id_student;
            $krs->data_origin = 'eSIAM';
            $krs->id_kurperiode = $tra;
            $krs->id_kurtrans = $trs;
            $krs->save();

            Alert::success('', 'Matakuliah berhasil ditambahkan')->autoclose(3500);
            return redirect('isi_krs');
        }
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
        $cek_waktu = Waktu_krs::all();
        foreach ($cek_waktu as $time) {
        }
        $id = Auth::user()->username;

        $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

        $prd_tp = Periode_tipe::where('status', 'ACTIVE')->get();

        $maha = Student::where('nim', Auth::user()->username)->get();

        foreach ($maha as $key) {
            # code...
        }

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
        $angk = $key->idangkatan;

        if ($smt % 2 != 0) {
            $a = ($smt + 10 - 1) / 10;
            $b = $a - $angk;
            $c = $b * 2 - 1;
        } else {
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $angk;
            $c = $b * 2;
        }

        $semester = Semester::all();
        $ruang = Ruangan::all();
        $dosen = Dosen::all();

        $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->leftjoin('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->leftjoin('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->leftjoin('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('student_record.id_student', $key->idstudent)
            ->where('kurikulum_periode.id_periodetipe', $tp)
            ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->select('kurikulum_periode.id_kurperiode', 'student_record.tanggal_krs', 'kurikulum_periode.id_semester', 'matakuliah.kode', 'matakuliah.makul', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'dosen.nama')
            ->orderBy('kurikulum_periode.id_hari', 'ASC')
            ->orderBy('kurikulum_periode.id_jam', 'ASC')
            ->get();

        return view('mhs/jadwal', ['mhs' => $key, 'tp' => $prd_tp, 'thn' => $prd_thn, 'jadwal' => $record, 'smt' => $semester, 'rng' => $ruang, 'dsn' => $dosen]);
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
            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
            ->get();
        foreach ($bap as $key) {
            # code...
        }

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select('bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
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
            ->select('kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.id_kelas', 'periode_tipe.periode_tipe', 'periode_tahun.periode_tahun', 'dosen.akademik', 'dosen.nama', 'ruangan.nama_ruangan', 'kurikulum_jam.jam', 'kurikulum_hari.hari', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
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
            ->select('student_record.id_studentrecord', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'student.nama', 'student.nim', 'absensi_mahasiswa.absensi')
            ->get();

        return view('mhs/rekap_absen', ['data' => $key, 'abs' => $abs]);
    }

    public function tambah_krs(Request $request)
    {
        $this->validate($request, [
            'id_periodetipe' => 'required',
            'id_periodetahun' => 'required',
            'id_kelas' => 'required',
            'id_prodi' => 'required',
            'idangkatan' => 'required',
        ]);

        $kur = Kurikulum_master::where('status', 'ACTIVE')->get();

        foreach ($kur as $krlm) {
            // code...
        }

        $prd_thn = Periode_tahun::where('status', 'ACTIVE')->get();

        foreach ($prd_thn as $thn) {
            // code...
        }
        $sub_thn = substr($thn->periode_tahun, 6, 2);
        $tp = $request->id_periodetipe;
        $smt = $sub_thn . $tp;
        $angk = $request->idangkatan;

        if ($smt % 2 != 0) {
            $a = ($smt + 10 - 1) / 10;
            $b = $a - $angk;
            $c = $b * 2 - 1;
        } else {
            $a = ($smt + 10 - 2) / 10;
            $b = $a - $angk;
            $c = $b * 2;
        }
        $semester = Semester::all();
        $makul = Matakuliah::all();
        $hari = Kurikulum_hari::all();
        $jam = Kurikulum_jam::all();
        $ruang = Ruangan::all();
        $dosen = Dosen::all();
        $mhs = $request->id_student;

        $krs = Kurikulum_transaction::join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
            ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
            ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_kelas', $request->id_kelas)
            ->where('kurikulum_transaction.id_prodi', $request->id_prodi)
            ->where('kurikulum_transaction.id_semester', $c)
            ->where('kurikulum_transaction.id_angkatan', $request->idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_periode.id_makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
            ->get();

        $cek_krs = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftjoin('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->leftjoin('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->where('student_record.id_student', $mhs)
            ->where('kurikulum_periode.id_semester', $c)
            ->whereNull('kurikulum_periode.id_kurperiode')
            // ->select('student_record.tanggal_krs', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
            ->get();

        $cek_kul = DB::table('student_record as sr')
            ->select('sr.*')
            ->leftJoin('kurikulum_periode as kp', function ($join) {
                $join->on('kp.id_kurperiode', '=', 'student_record.id_kurperiode');
            })
            ->whereNull('kp.id_kurperiode')
            ->get();
        dd($cek_kul);

        DB::table('item as i')
            ->select('i.*')
            ->leftJoin('qualifications as q', function ($join) {
                $join->on('q.item_id', '=', 'i.id')->on('q.user_id', '=', $user_id);
            })
            ->whereNull('q.item_id')
            ->get();

        return view('mhs/add_krs', ['mhs' => $mhs, 'add' => $krs, 'smt' => $semester, 'mk' => $makul, 'hr' => $hari, 'jm' => $jam, 'rg' => $ruang, 'dsn' => $dosen]);
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
            ->select('daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5', 'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14', 'prakerin', 'seminar', 'sidang', 'wisuda')
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
                ->where('bayar.iditem', 35)
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
                ->where('bayar.iditem', 18)
                ->sum('bayar.bayar');

            $sisaawal = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 19)
                ->sum('bayar.bayar');

            $sisadsp = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 20)
                ->sum('bayar.bayar');

            $sisaspp1 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 21)
                ->sum('bayar.bayar');

            $sisaspp2 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 22)
                ->sum('bayar.bayar');

            $sisaspp3 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 23)
                ->sum('bayar.bayar');

            $sisaspp4 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 24)
                ->sum('bayar.bayar');

            $sisaspp5 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 25)
                ->sum('bayar.bayar');

            $sisaspp6 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 26)
                ->sum('bayar.bayar');

            $sisaspp7 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 27)
                ->sum('bayar.bayar');

            $sisaspp8 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 28)
                ->sum('bayar.bayar');

            $sisaspp9 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 29)
                ->sum('bayar.bayar');

            $sisaspp10 = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 30)
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

            $sisaprakerin = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 36)
                ->sum('bayar.bayar');

            $sisaseminar = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 37)
                ->sum('bayar.bayar');

            $sisasidang = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 38)
                ->sum('bayar.bayar');

            $sisawisuda = Kuitansi::join('bayar', 'kuitansi.idkuit', '=', 'bayar.idkuit')
                ->where('kuitansi.idstudent', $id)
                ->where('bayar.iditem', 39)
                ->sum('bayar.bayar');

            return view('mhs/keuangan/tabel_biaya', compact('maha', 'itembayar', 'cb', 'biaya', 'sisadaftar', 'sisaawal', 'sisadsp', 'sisaspp1', 'sisaspp2', 'sisaspp3', 'sisaspp4', 'sisaspp5', 'sisaspp6', 'sisaspp7', 'sisaspp8', 'sisaspp9', 'sisaspp10', 'sisaspp11', 'sisaspp12', 'sisaspp13', 'sisaspp14', 'sisaprakerin', 'sisaseminar', 'sisasidang', 'sisawisuda'));
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
        $thn = Periode_tahun::where('status', 'ACTIVE')->get();
        foreach ($thn as $tahun) {
            // code...
        }

        $tp = Periode_tipe::where('status', 'ACTIVE')->get();
        foreach ($tp as $tipe) {
            // code...
        }

        $id_prd = Auth::user()->username;
        $mhs = Student::where('nim', $id_prd)->get();
        foreach ($mhs as $keymhs) {
            // code...
        }
        $prodi = $keymhs->kodeprodi;
        $prd = Prodi::where('kodeprodi', $prodi)->get();
        foreach ($prd as $keyprd) {
            // code...
        }
        $makul = Matakuliah::all();
        $ruang = Ruangan::all();
        $jam = Kurikulum_jam::all();
        $uts = Ujian_transaction::where('ujian_transaction.id_periodetahun', $tahun->id_periodetahun)
            ->where('ujian_transaction.id_periodetipe', $tipe->id_periodetipe)
            ->where('ujian_transaction.id_prodi', $keyprd->id_prodi)
            ->where('ujian_transaction.id_kelas', $keymhs->idstatus)
            ->where('ujian_transaction.jenis_ujian', 'UTS')
            //->where('student_record.id_student', $keymhs->idstudent)
            // ->where('student_record.status', 'TAKEN')
            // ->select('ujian_transaction.tanggal_ujian')
            ->get();

        $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->where('student_record.id_student', $keymhs->idstudent)
            ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            //->join('ujian_transaction', 'kurikulum_periode.id_makul', '=', 'ujian_transaction.id_makul')
            ->select('kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan')
            ->orderBy('kurikulum_periode.id_hari', 'ASC')
            ->get();

        return view('mhs/jadwal_uts', ['record' => $record, 'uts' => $uts, 'mk' => $makul, 'rng' => $ruang, 'jam' => $jam]);
    }

    public function jdl_uas()
    {
        $thn = Periode_tahun::where('status', 'ACTIVE')->get();
        foreach ($thn as $tahun) {
            // code...
        }

        $tp = Periode_tipe::where('status', 'ACTIVE')->get();
        foreach ($tp as $tipe) {
            // code...
        }

        $id_prd = Auth::user()->username;
        $mhs = Student::where('nim', $id_prd)->get();
        foreach ($mhs as $keymhs) {
            // code...
        }
        $prodi = $keymhs->kodeprodi;
        $prd = Prodi::where('kodeprodi', $prodi)->get();
        foreach ($prd as $keyprd) {
            // code...
        }
        $makul = Matakuliah::all();
        $ruang = Ruangan::all();
        $jam = Kurikulum_jam::all();
        $uts = Ujian_transaction::where('ujian_transaction.id_periodetahun', $tahun->id_periodetahun)
            ->where('ujian_transaction.id_periodetipe', $tipe->id_periodetipe)
            ->where('ujian_transaction.id_prodi', $keyprd->id_prodi)
            ->where('ujian_transaction.id_kelas', $keymhs->idstatus)
            ->where('ujian_transaction.jenis_ujian', 'UAS')
            ->get();

        $record = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->where('student_record.id_student', $keymhs->idstudent)
            ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            //->join('ujian_transaction', 'kurikulum_periode.id_makul', '=', 'ujian_transaction.id_makul')
            ->select('kurikulum_periode.id_makul', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan')
            ->orderBy('kurikulum_periode.id_hari', 'ASC')
            ->get();

        return view('mhs/jadwal_uas', ['record' => $record, 'uts' => $uts, 'mk' => $makul, 'rng' => $ruang, 'jam' => $jam]);
    }

    public function pedoman_akademik()
    {
        $thn = Periode_tahun::all();
        $pedoman = Pedoman_akademik::all();

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
                'nisn' => 'required|max:10|min:10|unique:student',
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

        $dosen_pa = Dosen_pembimbing::join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
            ->where('id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_pkl = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_sempro = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        $dosen_ta = Prausta_setting_relasi::join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->where('prausta_setting_relasi.id_student', $id)
            ->select('dosen.nama')
            ->first();

        return view('mhs/dosbing', compact('dosen_pa', 'dosen_pkl', 'dosen_sempro', 'dosen_ta'));
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

            $mhs = Dosen_pembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
                ->join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
                ->leftJoin('prodi', (function ($join) {
                    $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                }))
                ->where('student.idstudent', $ids)
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

        $cek_kuis = Kuisioner_transaction::where('id_student', $id_student)
            ->where('id_dosen_pembimbing', $id_dosen)
            ->where('id_periodetahun', $id_tahun)
            ->where('id_periodetipe', $id_tipe)
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

    public function isi_dosen_pkl($id)
    {
        $ids = Auth()->user()->id_user;

        //cek KRS prakerin mahasiswa
        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->whereIn('matakuliah.kode', ['FA-601', 'TI-601', 'TK-601'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Kerja Praktek/Prakerin', 'MAAF !!');
            return redirect('kuisioner');
        } elseif ($hasil_krs > 0) {
            //cek nilai dan file seminar prakerin
            $cekdata_bim = Prausta_setting_relasi::where('prausta_setting_relasi.id_student', $ids)
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
                ->where('prausta_setting_relasi.status', 'ACTIVE')
                ->select('prausta_setting_relasi.id_dosen_pembimbing')
                ->get();

            if (count($cekdata_bim) == 0) {
                Alert::error('Maaf dosen pembimbbing anda belum disetting untuk Kerja Praktek/Prakerin', 'MAAF !!');
                return redirect('kuisioner');
            } elseif (count($cekdata_bim) > 0) {
                $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                    ->join('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
                    ->leftJoin('prodi', (function ($join) {
                        $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                            ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
                    }))
                    ->where('student.idstudent', $ids)
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
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
        }
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

        $cek_kuis = Kuisioner_transaction::where('id_student', $id_student)
            ->where('id_dosen_pembimbing', $id_dosen)
            ->where('id_periodetahun', $id_tahun)
            ->where('id_periodetipe', $id_tipe)
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
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('kuisioner');
        } elseif ($hasil_krs > 0) {
            $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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
                    ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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
        }
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

        $cek_kuis = Kuisioner_transaction::where('id_student', $id_student)
            ->where('id_dosen_pembimbing', $id_dosen)
            ->where('id_periodetahun', $id_tahun)
            ->where('id_periodetipe', $id_tipe)
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
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('kuisioner');
        } elseif ($hasil_krs > 0) {
            $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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
                        ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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
        }
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

        $cek_kuis = Kuisioner_transaction::where('id_student', $id_student)
            ->where('id_dosen_penguji_1', $id_dosen)
            ->where('id_periodetahun', $id_tahun)
            ->where('id_periodetipe', $id_tipe)
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
            ->whereIn('matakuliah.kode', ['FA-602', 'TI-602', 'TK-602'])
            ->where('student_record.id_student', $ids)
            ->where('student_record.status', 'TAKEN')
            ->select('matakuliah.makul')
            ->get();

        $hasil_krs = count($cek);

        if ($hasil_krs == 0) {
            Alert::error('Maaf anda belum melakukan pengisian KRS Tugas Akhir', 'MAAF !!');
            return redirect('kuisioner');
        } elseif ($hasil_krs > 0) {
            $cekdata = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
                ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
                ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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
                        ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
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
        }
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

        $cek_kuis = Kuisioner_transaction::where('id_student', $id_student)
            ->where('id_dosen_penguji_2', $id_dosen)
            ->where('id_periodetahun', $id_tahun)
            ->where('id_periodetipe', $id_tipe)
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
            $cekbyr = $daftar + $awal + ($spp1 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 2) {
            $cekbyr = $daftar + $awal + ($dsp * 91) / 100 + $spp1 + ($spp2 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == '201') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2  - $total_semua_dibayar;
        } elseif ($c == 3) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + ($spp3 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 4) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + ($spp4 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == '401') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 - $total_semua_dibayar;
        } elseif ($c == 5) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + ($spp5 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 6) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + ($spp6 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == '601') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 - $total_semua_dibayar;
        } elseif ($c == 7) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + ($spp7 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 8) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + ($spp8 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == '801') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 - $total_semua_dibayar;
        } elseif ($c == 9) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + ($spp9 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 10) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + ($spp10 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == '1001') {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 - $total_semua_dibayar;
        } elseif ($c == 11) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + ($spp11 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 12) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + ($spp12 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 13) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + ($spp13 * 82) / 100 - $total_semua_dibayar;
        } elseif ($c == 14) {
            $cekbyr = $daftar + $awal + $dsp + $spp1 + $spp2 + $spp3 + $spp4 + $spp5 + $spp6 + $spp7 + $spp8 + $spp9 + $spp10 + $spp11 + $spp12 + $spp13 + ($spp14 * 82) / 100 - $total_semua_dibayar;
        }

        if ($cekbyr == 0 or $cekbyr < 1) {
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

                            $data_kelas = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
                                ->where('student_record.id_student', $id)
                                ->where('student_record.status', 'TAKEN')
                                ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
                                ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
                                ->select(DB::raw('DISTINCT(kurikulum_periode.id_kelas)'))
                                ->first();

                            if (count($cek_kuis_perpus) > 0) {
                                $data_uts = DB::select('CALL jadwal_uas(?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

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
            Alert::warning('Maaf anda tidak dapat mendownload Kartu Ujian UTS karena keuangan Anda belum memenuhi syarat');
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

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        $data_uts = DB::select('CALL jadwal_uts(?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe]);

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

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $periodetahun = $thn->periode_tahun;
        $periodetipe = $tp->periode_tipe;

        $data_uts = DB::select('CALL jadwal_uas(?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe]);

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

        $pdf = PDF::loadView('mhs/ujian/unduh_kartu_uas_pdf', compact('periodetahun', 'periodetipe', 'datamhs', 'data_uts', 'd', 'm', 'y'))->setPaper('a4', 'portrait');
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
}
