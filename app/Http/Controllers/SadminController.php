<?php

namespace App\Http\Controllers;

use PDF;
use File;
use Alert;
use App\Bap;
use App\Pedoman;
use App\Pedoman_akademik;
use App\Angkatan;
use App\User;
use App\Dosen;
use App\Kaprodi;
use App\Prodi;
use App\Kelas;
use App\Ruangan;
use App\Kurikulum_master;
use App\Student;
use App\Password;
use App\Informasi;
use App\Periode_tahun;
use App\Periode_tipe;
use App\Waktu_krs;
use App\Student_record;
use App\Matakuliah;
use App\Semester;
use App\Dosen_pembimbing;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Transkrip_nilai;
use App\Transkrip_final;
use App\Absensi_mahasiswa;
use App\Visimisi;
use App\Kuliah_nilaihuruf;
use App\Prausta_master_kategori;
use App\Prausta_master_kode;
use App\Prausta_setting_relasi;
use App\Prausta_trans_bimbingan;
use App\Prausta_trans_hasil;
use App\Wadir;
use App\Wrkpersonalia;
use App\Exports\DataNilaiIpkMhsExport;
use App\Exports\DataNilaiKHSExport;
use App\Exports\DataKRSMhsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SadminController extends Controller
{
    public function change($id)
    {
        return view('sadmin/change_pwd', ['adm' => $id]);
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

            Alert::success('', 'Password anda berhasil dirubah')->autoclose(3500);
            return redirect('home');
        } else {
            Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
            return redirect('home');
        }
    }

    public function show_mhs()
    {
        $mhs = Student::where('active', 1)->get();
        return view('sadmin/data_mhs', ['mhss' => $mhs]);
    }

    public function show_user()
    {
        $usermhs = Student::leftJoin('passwords', 'user', '=', 'student.nim')
            ->leftJoin('users', 'username', '=', 'passwords.user')
            ->select('users.id', 'users.id_user', 'users.username', 'users.deleted_at', 'passwords.pwd', 'student.nim', 'student.nama', 'student.idstatus', 'student.kodeprodi', 'student.idangkatan', 'users.role')
            ->where('student.active', 1)
            ->orderBy('student.idangkatan', 'DESC')
            ->orderBy('student.nim', 'ASC')
            ->get();

        $dsn = Dosen::leftJoin('passwords', 'user', '=', 'dosen.nik')
            ->leftJoin('users', 'username', '=', 'passwords.user')
            ->select('users.id', 'users.id_user', 'users.username', 'dosen.nik', 'dosen.nama', 'dosen.iddosen', 'dosen.akademik', 'users.role')
            ->get();

        return view('sadmin/data_user', ['users' => $usermhs, 'dsn' => $dsn]);
    }

    public function add_user_mhs($id)
    {
        $cek = User::where('username', $id)->get();

        if (count($cek) > 0) {
            return redirect('show_user')->with('fatal', 'anda tidak dapat melakukan proses ini!');
        }

        $mhs = Student::where('nim', $id)->get();

        foreach ($mhs as $user) {
        }

        $pwd = $id;

        return view('sadmin/add_user_mhs', ['mhss' => $user, 'pwds' => $pwd]);
    }

    public function store_user_mhs(Request $request)
    {
        $this->validate($request, [
            'id_user' => 'required',
            'name' => 'required|max:255',
            'role' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $users = new User();
        $users->id_user = $request->id_user;
        $users->name = $request->name;
        $users->password = bcrypt($request->password);
        $users->role = $request->role;
        $users->username = $request->username;
        $users->save();

        $sadmin = new Password();
        $sadmin->id_user = $request->id_user;
        $sadmin->user = $request->username;
        $sadmin->pwd = $request->password;
        $sadmin->save();

        return redirect('show_user')->with('pesan', 'user mahasiswa baru sudah terdaftar...');
    }

    public function resetuser(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::success('', 'User berhasil direset')->autoclose(3500);
        return redirect('show_user');
    }

    public function hapususer($id)
    {
        $user = User::where('id_user', $id)->forceDelete();
        $admin = Password::where('id_user', $id)->forceDelete();

        Alert::success('', 'User berhasil dihapus')->autoclose(3500);
        return redirect('show_user');
    }

    public function show_ta()
    {
        $ta_thn = Periode_tahun::where('status', 'ACTIVE')->get();
        $ta_tp = Periode_tipe::where('status', 'ACTIVE')->get();

        return view('sadmin/ta', ['thn' => $ta_thn, 'tp' => $ta_tp]);
    }

    public function save_krs_time(Request $request)
    {
        $cektgl = strtotime($request->waktu_akhir);
        $cektglawal = strtotime('now');

        if ($cektgl < $cektglawal) {
            Alert::error('maaf waktu salah', 'maaf');
            return redirect('home');
        } else {
            $id = $request->id;
            $time_nya = Waktu_krs::find($id);
            $time_nya->waktu_awal = $request->waktu_awal;
            $time_nya->waktu_akhir = $request->waktu_akhir;
            $time_nya->status = $request->status;
            $time_nya->save();

            Alert::success('Pembukaan KRS', 'Berhasil')->autoclose(3500);
            return redirect('home');
        }
    }

    public function delete_time_krs(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
            'id' => 'required',
        ]);

        $id = $request->id;

        $time_nya = Waktu_krs::find($id);
        $time_nya->waktu_awal = $request->waktu_awal;
        $time_nya->waktu_akhir = $request->waktu_akhir;
        $time_nya->status = $request->status;
        $time_nya->save();

        Alert::success('Penutupan KRS', 'Berhasil');
        return redirect('home');
    }

    public function change_ta_thn(Request $request)
    {
        $akun = Periode_tahun::where('status', 'ACTIVE')->update(['status' => 'NOT ACTIVE']);

        $id = $request->id_periodetahun;
        $thn = Periode_tahun::find($id);
        $thn->status = $request->status;
        $thn->save();

        return redirect('home');
    }

    public function change_ta_tp(Request $request)
    {
        $akun = Periode_tipe::where('status', 'ACTIVE')->update(['status' => 'NOT ACTIVE']);

        $id = $request->id_periodetipe;
        $thn = Periode_tipe::find($id);
        $thn->status = $request->status;
        $thn->save();

        return redirect('home');
    }

    public function add_ta(Request $request)
    {
        $this->validate($request, [
            'periode_tahun' => 'required',
            'status' => 'required',
        ]);

        $thn = new Periode_tahun();
        $thn->periode_tahun = $request->periode_tahun;
        $thn->status = $request->status;
        $thn->save();

        return redirect('home');
    }

    public function info()
    {
        $info = Informasi::get();

        return view('sadmin/informasi', ['info' => $info]);
    }

    public function simpan_info(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'file' => 'mimes:jpeg,jpg,doc,docx,pdf,png|max:2000',
            'deskripsi' => 'required',
        ]);

        $info = new Informasi();
        $info->judul = $request->judul;
        $info->deskripsi = $request->deskripsi;

        if ($request->hasFile('file')) {
            // ada file yang diupload
            // menyimpan data file yang diupload ke variabel $file
            $file = $request->file('file');

            $nama_file = time() . '_' . $file->getClientOriginalName();

            // isi dengan nama folder tempat kemana file diupload
            $tujuan_upload = 'data_file';
            $file->move($tujuan_upload, $nama_file);
            $info->file = $nama_file;
        }

        $info->save();
        Alert::success('', 'Informasi berhasil ditambahkan')->autoclose(3500);
        return redirect()->back();
    }

    public function hapusinfo($id)
    {
        // hapus file
        //$gambar = Informasi::find($id);

        // if (($gambar->file)) {
        //   Storage::delete('public/posts_image/'. $gambar->file);
        //   // Storage::delete($gambar->file);
        // }
        // $gambar->delete();

        // hapus file
        $gambar = Informasi::where('id_informasi', $id)->first();
        File::delete('data_file/' . $gambar->file);

        // hapus data
        Informasi::where('id_informasi', $id)->delete();
        Alert::success('', 'Informasi berhasil dihapus')->autoclose(3500);
        return redirect()->back();
    }

    public function editinfo($id)
    {
        $info = Informasi::find($id);

        return view('sadmin/editinfo', ['info' => $info]);
    }

    public function simpanedit(Request $request, $id)
    {
        $this->validate($request, [
            'judul' => 'required',
            'deskripsi' => 'required',
            'file' => 'mimes:jpeg,jpg,png|max:1024',
        ]);

        $info = Informasi::find($id);
        $info->judul = $request->judul;
        $info->deskripsi = $request->deskripsi;

        if ($info->file) {
            if ($request->hasFile('file')) {
                // Storage::delete('public/posts_image/'. $info->file);
                // $filenameWithExt = $request->file('file')->getClientOriginalName();
                // $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // $extension = $request->file('file')->getClientOriginalExtension();
                // $filenameSimpan = $filename.'_'.time().'.'.$extension;
                // $path = $request->file('file')->storeAs('public/posts_image', $filenameSimpan);
                // $info->file        = $filenameSimpan;
                File::delete('data_file/' . $info->file);
                // menyimpan data file yang diupload ke variabel $file
                $file = $request->file('file');

                $nama_file = time() . '_' . $file->getClientOriginalName();

                // isi dengan nama folder tempat kemana file diupload
                $tujuan_upload = 'data_file';
                $file->move($tujuan_upload, $nama_file);
                $info->file = $nama_file;
            }
        } else {
            if ($request->hasFile('file')) {
                // $filenameWithExt = $request->file('file')->getClientOriginalName();
                // $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // $extension = $request->file('file')->getClientOriginalExtension();
                // $filenameSimpan = $filename.'_'.time().'.'.$extension;
                // $path = $request->file('file')->storeAs('public/posts_image', $filenameSimpan);
                // $info->file        = $filenameSimpan;
                // menyimpan data file yang diupload ke variabel $file
                $file = $request->file('file');

                $nama_file = time() . '_' . $file->getClientOriginalName();

                // isi dengan nama folder tempat kemana file diupload
                $tujuan_upload = 'data_file';
                $file->move($tujuan_upload, $nama_file);
                $info->file = $nama_file;
            }
        }

        $info->save();

        Alert::success('', 'Informasi berhasil diedit')->autoclose(3500);
        return redirect('info');
    }

    public function data_foto()
    {
        $angk = Angkatan::all();

        $fototk = Student::where('kodeprodi', 22)
            ->where('active', 1)
            ->orderBy('idangkatan', 'DESC')
            ->paginate(12);

        $fototi = Student::where('kodeprodi', 23)
            ->where('active', 1)
            ->orderBy('idangkatan', 'DESC')
            ->paginate(12);

        $fotofm = Student::where('kodeprodi', 24)
            ->where('active', 1)
            ->orderBy('idangkatan', 'DESC')
            ->paginate(12);

        $jmltk = Student::where('kodeprodi', 22)
            ->where('active', 1)
            ->get();
        $jmlti = Student::where('kodeprodi', 23)
            ->where('active', 1)
            ->get();
        $jmlfm = Student::where('kodeprodi', 24)
            ->where('active', 1)
            ->get();
        $jmltk = count($jmltk);
        $jmlti = count($jmlti);
        $jmlfm = count($jmlfm);

        return view('sadmin/foto', ['jmltk' => $jmltk, 'jmlti' => $jmlti, 'jmlfm' => $jmlfm, 'angk' => $angk, 'fototk' => $fototk, 'fototi' => $fototi, 'fotofm' => $fotofm]);
    }

    public function lihat_foto_ti()
    {
        $angk = Angkatan::all();
        $fototi = Student::where('kodeprodi', 23)
            ->where('active', 1)
            ->orderBy('idangkatan', 'DESC')
            ->get();

        $jmlti = Student::where('kodeprodi', 23)
            ->where('active', 1)
            ->get();

        $jmlti = count($jmlti);
        return view('sadmin/lihat_foto_ti', ['angk' => $angk, 'jmlti' => $jmlti, 'fototi' => $fototi]);
    }

    public function lihat_foto_tk()
    {
        $angk = Angkatan::all();
        $fototk = Student::where('kodeprodi', 22)
            ->where('active', 1)
            ->orderBy('idangkatan', 'DESC')
            ->get();

        $jmltk = Student::where('kodeprodi', 22)
            ->where('active', 1)
            ->get();

        $jmltk = count($jmltk);
        return view('sadmin/lihat_foto_tk', ['angk' => $angk, 'jmltk' => $jmltk, 'fototk' => $fototk]);
    }

    public function lihat_foto_fm()
    {
        $angk = Angkatan::all();
        $fotofm = Student::where('kodeprodi', 24)
            ->where('active', 1)
            ->orderBy('idangkatan', 'DESC')
            ->get();

        $jmlfm = Student::where('kodeprodi', 24)
            ->where('active', 1)
            ->get();

        $jmlfm = count($jmlfm);
        return view('sadmin/lihat_foto_fm', ['angk' => $angk, 'jmlfm' => $jmlfm, 'fotofm' => $fotofm]);
    }

    public function data_nilai()
    {
        $nilai = student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->where('student_record.status', 'TAKEN')
            ->where('student.active', 1)
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'student.idstatus', 'student.nim', 'student.idangkatan', 'student.kodeprodi', 'student.nama')
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('sadmin/nilai/data_nilai', ['nilai' => $nilai]);
    }

    public function cek_nilai(Request $request)
    {
        $id = $request->id_student;
        //data mahasiswa
        $mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas')
            ->first();

        $cek = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student_record.id_student', 'matakuliah.makul', 'matakuliah.akt_sks_praktek', 'matakuliah.akt_sks_teori', 'student_record.id_studentrecord', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA')
            ->groupBy('student_record.id_student', 'matakuliah.akt_sks_praktek', 'matakuliah.akt_sks_teori', 'matakuliah.makul', 'student_record.id_studentrecord', 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA')
            ->get();
        foreach ($cek as $key) {
            // code...
        }
        return view('sadmin/nilai/ceknilai', ['cek' => $cek, 'key' => $key, 'mhs' => $mhs]);
    }

    public function save_nilai_angka(Request $request)
    {
        $jml = count($request->nilai_ANGKA);

        for ($i = 0; $i < $jml; $i++) {
            $nilai = $request->nilai_ANGKA[$i];
            $nilaiangka = explode(',', $nilai, 2);
            $ids = $nilaiangka[0];
            $niak = $nilaiangka[1];
            $nl = Student_record::where('id_studentrecord', $ids)->first();

            if ($niak == '4') {
                $n = 'A';
            } elseif ($niak == '3.5') {
                $n = 'B+';
            } elseif ($niak == '3') {
                $n = 'B';
            } elseif ($niak == '2.5') {
                $n = 'C+';
            } elseif ($niak == '2') {
                $n = 'C';
            } elseif ($niak == '1') {
                $n = 'D';
            } else {
                $n = 'E';
            }

            $akun = Student_record::where('id_student', $nl->id_student)
                ->where('id_kurtrans', $nl->id_kurtrans)
                ->update(['nilai_ANGKA' => $niak, 'nilai_AKHIR' => $n]);
            // $id = $ids;
            // $ceknilai = Student_record::find($id);
            // $ceknilai->nilai_ANGKA = $niak;
            // $ceknilai->save();
        }
        return redirect('data_nilai');
    }

    public function pembimbing()
    {
        $pem = Dosen_pembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
            ->join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
            ->where('student.active', 1)
            ->select('student.nama', 'student.nim', 'student.kodeprodi', 'student.idstatus', 'dosen_pembimbing.id_dosen')
            ->get();

        $dsn = Dosen::all();

        return view('sadmin/pembimbing', ['dosbing' => $pem, 'dsn' => $dsn]);
    }

    public function data_admin()
    {
        $dsn = Dosen::leftJoin('passwords', 'user', '=', 'dosen.nik')
            ->leftJoin('users', 'username', '=', 'passwords.user')
            ->where('dosen.idstatus', 1)
            ->select('users.id', 'users.id_user', 'users.username', 'passwords.pwd', 'dosen.nik', 'dosen.nama', 'dosen.iddosen', 'dosen.akademik', 'users.role')
            ->get();

        return view('sadmin/data_user_dosen', ['user_dsn' => $dsn]);
    }

    public function saveuser_dsn(Request $request)
    {
        $users = new User();
        $users->id_user = $request->id_user;
        $users->name = $request->name;
        $users->password = bcrypt($request->username);
        $users->role = $request->role;
        $users->username = $request->username;
        $users->save();

        $sadmin = new Password();
        $sadmin->id_user = $request->id_user;
        $sadmin->user = $request->username;
        $sadmin->pwd = $request->username;
        $sadmin->save();

        return redirect('data_admin')->with('pesan', 'user dosen baru sudah terdaftar...');
    }

    public function resetuserdsn(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::success('', 'User berhasil direset')->autoclose(3500);
        return redirect('data_admin');
    }

    public function hapususerdsn($id)
    {
        $user = User::where('id_user', $id)->forceDelete();
        $admin = Password::where('id_user', $id)->forceDelete();

        Alert::success('', 'User berhasil dihapus')->autoclose(3500);
        return redirect('data_admin');
    }

    public function approve_krs()
    {
        $thn = Periode_tahun::where('status', 'ACTIVE')->get();

        foreach ($thn as $tahun) {
            // code...
        }

        $tp = Periode_tipe::where('status', 'ACTIVE')->get();

        foreach ($tp as $tipe) {
            // code...
        }

        $angk = Angkatan::all();
        $dsn = Dosen::all();
        $appr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->leftjoin('dosen_pembimbing', 'student.idstudent', 'dosen_pembimbing.id_student')
            ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->where('student.active', 1)

            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idangkatan', 'dosen_pembimbing.id_dosen', 'student.idstatus', 'student_record.remark')
            ->get();

        return view('sadmin/approv', ['appr' => $appr, 'angk' => $angk, 'dsn' => $dsn]);
    }

    public function cek_krs($id)
    {
        $semester = Semester::all();
        $dosen = Dosen::all();
        $makul = Matakuliah::all();
        $hari = Kurikulum_hari::all();
        $jam = Kurikulum_jam::all();
        $ruang = Ruangan::all();
        $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('id_student', $id)
            ->select('student_record.remark', 'student.idstudent', 'student_record.id_studentrecord', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen', 'kurikulum_periode.id_makul', 'student_record.remark', 'student.idstatus', 'student.nim', 'student.idangkatan', 'student.kodeprodi', 'student.nama', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.id_semester')
            ->get();

        foreach ($val as $key) {
            // code...
        }

        $valkrs = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('id_student', $id)
            ->select(DB::raw('DISTINCT(student_record.remark)'), 'student.idstudent')
            ->get();

        foreach ($valkrs as $valuekrs) {
            // code...
        }

        $b = $valuekrs->remark;

        $kur = Kurikulum_master::where('status', 'ACTIVE')->get();
        foreach ($kur as $krlm) {
            // code...
        }

        $tp = Periode_tipe::where('status', 'ACTIVE')->get();
        foreach ($tp as $tipe) {
            // code...
        }
        $tp = $tipe->id_periodetipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->get();

        foreach ($thn as $tahun) {
            // code...
        }

        $maha = Student::where('idstudent', $id)->get();

        foreach ($maha as $key) {
            # code...
        }
        $mhs = $key->kodeprodi;
        $prod = Prodi::where('kodeprodi', $key->kodeprodi)->get();
        foreach ($prod as $value) {
            // code...
        }
        $mhs = $key->idstudent;
        $krs = Kurikulum_transaction::join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
            ->where('kurikulum_transaction.id_kurikulum', $krlm->id_kurikulum)
            ->where('kurikulum_periode.id_periodetipe', $tp)
            ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
            ->where('kurikulum_periode.id_kelas', $key->idstatus)
            ->where('kurikulum_transaction.id_prodi', $value->id_prodi)
            ->where('kurikulum_transaction.id_angkatan', $key->idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_periode.id_makul', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'kurikulum_periode.id_semester', 'kurikulum_periode.id_hari', 'kurikulum_periode.id_jam', 'kurikulum_periode.id_ruangan', 'kurikulum_periode.akt_sks_teori', 'kurikulum_periode.akt_sks_praktek', 'kurikulum_periode.id_dosen')
            ->get();

        return view('sadmin/cek_krs_admin', ['b' => $b, 'hr' => $hari, 'jm' => $jam, 'rng' => $ruang, 'smt' => $semester, 'mhss' => $mhs, 'add' => $krs, 'val' => $val, 'key' => $key, 'mk' => $makul, 'dsn' => $dosen]);
    }

    public function batalkrsmhs(Request $request)
    {
        $id = $request->id_studentrecord;
        $cek = Student_record::find($id);
        $cek->remark = $request->remark;
        $cek->save();

        Alert::success('', 'Matakuliah berhasil validasi')->autoclose(3500);
        return redirect()->back();
    }

    public function view_krs(Request $request)
    {
        $thn = Periode_tahun::where('status', 'ACTIVE')->get();

        foreach ($thn as $tahun) {
            // code...
        }

        $tp = Periode_tipe::where('status', 'ACTIVE')->get();

        foreach ($tp as $tipe) {
            // code...
        }

        $angk = Angkatan::all();
        $dsn = Dosen::all();
        $appr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->leftjoin('dosen_pembimbing', 'student.idstudent', 'dosen_pembimbing.id_student')
            ->where('kurikulum_periode.id_periodetipe', $tipe->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->where('student.active', 1)
            ->where('student_record.remark', $request->remark)
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'student.nama', 'student.nim', 'student.kodeprodi', 'student.idangkatan', 'dosen_pembimbing.id_dosen', 'student.idstatus', 'student_record.remark')
            ->get();

        return view('sadmin/approv', ['appr' => $appr, 'angk' => $angk, 'dsn' => $dsn]);
    }

    public function data_dosen_luar()
    {
        $dsn = Dosen::leftJoin('passwords', 'user', '=', 'dosen.nik')
            ->leftJoin('users', 'username', '=', 'passwords.user')
            ->whereIn('dosen.idstatus', [2, 3])
            ->select('users.id', 'users.id_user', 'users.username', 'passwords.pwd', 'dosen.nik', 'dosen.nama', 'dosen.iddosen', 'dosen.akademik', 'users.role')
            ->get();

        return view('sadmin/data_user_dosen_luar', ['user_dsn' => $dsn]);
    }

    public function saveuser_dsn_luar(Request $request)
    {
        $users = new User();
        $users->id_user = $request->id_user;
        $users->name = $request->name;
        $users->password = bcrypt($request->username);
        $users->role = $request->role;
        $users->username = $request->username;
        $users->save();

        $sadmin = new Password();
        $sadmin->id_user = $request->id_user;
        $sadmin->user = $request->username;
        $sadmin->pwd = $request->username;
        $sadmin->save();

        return redirect('data_dosen_luar')->with('pesan', 'user dosen baru sudah terdaftar...');
    }

    public function resetuserdsn_luar(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::success('', 'User berhasil direset')->autoclose(3500);
        return redirect('data_dosen_luar');
    }

    public function hapususerdsn_luar($id)
    {
        $user = User::where('id_user', $id)->forceDelete();
        $admin = Password::where('id_user', $id)->forceDelete();

        Alert::success('', 'User berhasil dihapus')->autoclose(3500);
        return redirect('data_dosen_luar');
    }

    public function pedoman()
    {
        $tahun = Periode_tahun::all();
        $pedoman = Pedoman_akademik::all();

        return view('sadmin/pedoman', ['tahun' => $tahun, 'pedoman' => $pedoman]);
    }

    public function save_pedoman(Request $request)
    {
        $this->validate($request, [
            'nama_file' => 'required',
            'file_pedoman' => 'mimes:jpg|max:10000',
            'id_periodetahun' => 'required',
        ]);
        dd($request);
        $info = new Pedoman();
        $info->nama_file = $request->nama_file;
        $info->id_periodetahun = $request->id_periodetahun;
        // $info->file_pedoman         = $request->file_pedoman;
        $file = $request->file('file_pedoman');
        $filename = time() . '_' . $file->getClientOriginalName();
        $tujuan_upload = 'Pedoman';
        $file->move($tujuan_upload, $filename);
        $info->file_pedoman = $filename;

        $info->save();
        Alert::success('', 'Pedoman berhasil ditambahkan')->autoclose(3500);
        return redirect('pdm_aka');
    }

    public function save_pedoman_akademik(Request $request)
    {
        $this->validate($request, [
            'nama_pedoman' => 'required',
            'file' => 'mimes:jpeg,jpg,pdf,png|max:10000',
            'id_periodetahun' => 'required',
        ]);

        $pedoman = new Pedoman_akademik();
        $pedoman->nama_pedoman = $request->nama_pedoman;
        $pedoman->id_periodetahun = $request->id_periodetahun;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'pedoman';
            $file->move($tujuan_upload, $nama_file);
            $pedoman->file = $nama_file;
        }

        $pedoman->save();
        Alert::success('', 'Informasi berhasil ditambahkan')->autoclose(3500);
        return redirect()->back();
    }

    public function data_ktm()
    {
        $ang = Angkatan::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $cekangk = Student::where('active', 1)
            ->select(DB::raw('DISTINCT(idangkatan)'))
            ->get();

        return view('sadmin/cek_data_ktm', ['cekangk' => $cekangk, 'ang' => $ang, 'prd' => $prd, 'kls' => $kls]);
    }

    public function view_ktm(Request $request)
    {
        $ang = Angkatan::all();
        $prd = Prodi::all();
        $kls = Kelas::all();
        $mhs = Student::where('idangkatan', $request->idangkatan)
            ->where('kodeprodi', $request->kodeprodi)
            ->where('idstatus', $request->idstatus)
            ->get();

        return view('sadmin/data_ktm', ['mhs' => $mhs, 'ang' => $ang, 'prd' => $prd, 'kls' => $kls]);
    }

    public function downloadktm($id)
    {
        $prd = Prodi::all();
        $mhs = Student::where('idstudent', $id)->get();
        foreach ($mhs as $keymhs) {
            // code...
        }
        $thn = $keymhs->idangkatan;
        $ttl = $thn + 3;
        $t1 = $ttl - 1;

        $hs = '20' . $t1 . '-' . '20' . $ttl;

        //return view('sadmin/ktm', ['mhs'=>$keymhs, 'prd'=>$prd]);
        //return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('sadmin/ktm', ['mhs'=>$keymhs, 'prd'=>$prd]);
        $pdf = PDF::loadView('sadmin/ktm', ['mhs' => $keymhs, 'prd' => $prd, 'hs' => $hs])->setPaper('a4', 'landscape');
        return $pdf->download('KTM_' . $keymhs->nim . '_' . $keymhs->nama . '.pdf');
    }

    public function nilai_khs()
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'ASC')->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2, 3])->get();
        $prodi = Prodi::all();

        return view('sadmin/nilai_khs', ['thn' => $tahun, 'tp' => $tipe, 'prd' => $prodi]);
    }

    public function export_nilai_khs(Request $request)
    {
        $prd = $request->id_prodi;
        $ta = $request->id_periodetahun;
        $tp = $request->id_periodetipe;

        $prodi = Prodi::where('id_prodi', $prd)
            ->select('prodi', 'kodeprodi')
            ->first();
        $pro = $prodi->prodi;
        $kd = $prodi->kodeprodi;

        $tahun = Periode_tahun::where('id_periodetahun', $ta)
            ->select('periode_tahun')
            ->first();
        $thn = $tahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);

        $tipe = Periode_tipe::where('id_periodetipe', $tp)
            ->select('periode_tipe')
            ->first();
        $tpe = $tipe->periode_tipe;

        $nilai = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
            ->where('kurikulum_periode.id_prodi', $request->id_prodi)
            ->where('student.active', 1)
            ->select(DB::raw('DISTINCT(student_record.id_kurtrans)'), 'prodi.prodi', 'kelas.kelas', 'student.nim', 'student.nama', 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as akt_sks_hasil'))
            ->get();

        $nama_file = 'Nilai' . ' ' . $pro . ' ' . $ganti . ' ' . $tpe . '.xlsx';
        return Excel::download(new DataNilaiKHSExport($prd, $ta, $tp, $kd), $nama_file);
    }

    public function nilai_prausta()
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'ASC')->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();
        $prodi = Prodi::all();

        return view('sadmin/prausta/nilai_prausta', compact('tahun', 'tipe', 'prodi'));
    }

    public function export_nilai_prausta(Request $request)
    {
        $prd = $request->id_prodi;
        $ta = $request->id_periodetahun;
        $tp = $request->id_periodetipe;

        $data = Prausta_setting_relasi::join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('student_record', 'student.idstudent', '=', 'student_record.id_student')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'prausta_master_kode.kode_prausta', '=', 'matakuliah.kode')
            ->where('kurikulum_periode.id_periodetahun', $ta)
            ->where('kurikulum_periode.id_periodetipe', $tp)
            ->where('kurikulum_periode.id_prodi', $prd)
            ->select('student.nim', 'student.nama', 'matakuliah.makul')
            ->get();
        dd($data);
        $data1 = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prausta_master_kode', 'matakuliah.kode', '=', 'prausta_master_kode.kode_prausta')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            //->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            //->join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->where('kurikulum_periode.id_periodetahun', $ta)
            ->where('kurikulum_periode.id_periodetipe', $tp)
            ->where('kurikulum_periode.id_prodi', $prd)
            ->where('student_record.status', 'TAKEN')
            ->select('prodi.prodi', 'kelas.kelas', 'student.nim', 'student.nama', 'matakuliah.makul')
            ->orderBy('student.nim', 'ASC')
            ->get();

        $datas = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('matakuliah', 'prausta_master_kode.kode_prausta', '=', 'matakuliah.kode')
            //->join('kuliah_nilaihuruf', 'prausta_trans_hasil.nilai_huruf', '=', 'kuliah_nilaihuruf.nilai_huruf')
            ->where('prausta_trans_hasil.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select('prodi.prodi', 'kelas.kelas', 'student.nim', 'student.nama', 'matakuliah.makul', DB::raw('sum(matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek) as sks'), 'prausta_trans_hasil.nilai_huruf')
            ->groupBy('prodi.prodi', 'kelas.kelas', 'student.nim', 'student.nama', 'matakuliah.makul', 'prausta_trans_hasil.nilai_huruf')
            ->orderBy('student.nim', 'ASC')
            ->get();
    }

    public function data_krs()
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'ASC')->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();
        $prodi = Prodi::all();

        return view('sadmin/master_krs/data_krs', ['thn' => $tahun, 'tp' => $tipe, 'prd' => $prodi]);
    }

    public function export_krs_mhs(Request $request)
    {
        $prd = $request->id_prodi;
        $ta = $request->id_periodetahun;
        $tp = $request->id_periodetipe;

        $prodi = Prodi::where('id_prodi', $prd)
            ->select('prodi', 'kodeprodi')
            ->first();
        $pro = $prodi->prodi;
        $kd = $prodi->kodeprodi;

        $tahun = Periode_tahun::where('id_periodetahun', $ta)
            ->select('periode_tahun')
            ->first();
        $thn = $tahun->periode_tahun;
        $ganti = str_replace('/', '_', $thn);

        $tipe = Periode_tipe::where('id_periodetipe', $tp)
            ->select('periode_tipe')
            ->first();
        $tpe = $tipe->periode_tipe;

        $nilai = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->where('kurikulum_periode.id_periodetahun', $request->id_periodetahun)
            ->where('kurikulum_periode.id_periodetipe', $request->id_periodetipe)
            ->where('kurikulum_periode.id_prodi', $request->id_prodi)
            ->select('prodi.prodi', 'kelas.kelas', 'student.nim', 'student.nama', 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as akt_sks_hasil'))
            ->get();

        $nama_file = 'KRS Mahasiswa' . ' ' . $pro . ' ' . $ganti . ' ' . $tpe . '.xlsx';
        return Excel::download(new DataKRSMhsExport($prd, $ta, $tp, $kd), $nama_file);
    }

    public function data_ipk()
    {
        $ipk = DB::select('CALL getIpkMhs()');

        return view('sadmin/datamahasiswa/data_ipk', compact('ipk'));
    }

    public function export_nilai_ipk()
    {
        $nama_file = 'Nilai IPK Mahasiswa.xlsx';

        return Excel::download(new DataNilaiIpkMhsExport(), $nama_file);
    }

    public function kaprodi()
    {
        $kaprodi = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
            ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
            ->where('kaprodi.status', 'ACTIVE')
            ->select('dosen.nik', 'dosen.nama', 'prodi.prodi', 'kaprodi.id_kaprodi', 'kaprodi.id_dosen', 'kaprodi.id_prodi')
            ->get();

        $dosen = Dosen::where('idstatus', 1)->get();

        $prodi = Prodi::all();

        return view('sadmin/datadosen/kaprodi', compact('kaprodi', 'dosen', 'prodi'));
    }

    public function post_kaprodi(Request $request)
    {
        $kpr = new Kaprodi();
        $kpr->id_dosen = $request->id_dosen;
        $kpr->id_prodi = $request->id_prodi;
        $kpr->created_by = Auth::user()->name;
        $kpr->save();

        $akun = User::where('id_user', $request->id_dosen)->update(['role' => 6]);

        return redirect('kaprodi');
    }

    public function put_kaprodi(Request $request, $id)
    {
        $prd = Kaprodi::find($id);
        $prd->id_dosen = $request->id_dosen;
        $prd->id_prodi = $request->id_prodi;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        return redirect('kaprodi');
    }

    public function hapuskaprodi(Request $request)
    {
        $akun = Kaprodi::where('id_kaprodi', $request->id_kaprodi)->update(['status' => 'NOT ACTIVE']);

        return redirect('kaprodi');
    }

    public function transkrip_nilai()
    {
        $nilai = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.active', 1)
            ->select('student.nim', 'student.idstudent', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan')
            ->orderBy('student.nim', 'desc')
            ->get();

        return view('sadmin/nilai/transkrip', compact('nilai'));
    }

    public function cek_transkrip($id)
    {
        $mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi')
            ->get();

        foreach ($mhs as $item) {
            // code...
        }

        return view('sadmin/nilai/hasil_transkrip', compact('item'));
    }

    public function lihat_transkrip(Request $request)
    {
        $id = $request->id_student;
        $nomor = $request->no_transkrip;

        $tns = new Transkrip_nilai();
        $tns->no_transkrip = $nomor;
        $tns->id_student = $id;
        $tns->created_by = Auth::user()->name;
        $tns->save();

        $mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('transkrip_nilai', 'student.idstudent', '=', 'transkrip_nilai.id_student')
            ->where('student.idstudent', $id)
            ->select('transkrip_nilai.id_transkrip', 'transkrip_nilai.no_transkrip', 'student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi')
            ->get();

        foreach ($mhs as $item) {
            // code...
        }

        $data = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->where('student_record.nilai_AKHIR', '!=', '0')
            ->where('student_record.nilai_ANGKA', '!=', '0')
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as nilai_sks'))
            ->orderBy('kurikulum_transaction.id_semester', 'ASC')
            ->orderBy('matakuliah.kode', 'ASC')
            ->get();

        $users = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->where('student_record.id_student', $id)
            ->where('student_record.status', 'TAKEN')
            ->where('student_record.nilai_AKHIR', '!=', '0')
            ->where('student_record.nilai_ANGKA', '!=', '0')
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as nilai_sks'))
            ->first();

        $sks = DB::select('CALL transkripsmt(' . $id . ')');
        foreach ($sks as $keysks) {
            // code...
        }
        return view('sadmin/nilai/transkrip_sementara', compact('data', 'item', 'keysks'));
    }

    public function print_transkrip($id)
    {
        $cekid = Transkrip_nilai::where('id_transkrip', $id)->get();
        foreach ($cekid as $keyid) {
            // code...
        }
        $trans = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('transkrip_nilai', 'student.idstudent', '=', 'transkrip_nilai.id_student')
            ->where('student.idstudent', $keyid->id_student)
            ->select('transkrip_nilai.id_transkrip', 'transkrip_nilai.no_transkrip', 'student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi', 'student.kodeprodi')
            ->get();

        foreach ($trans as $item) {
            // code...
        }
        $data = Student_record::join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->where('student_record.id_student', $item->idstudent)
            ->where('student_record.status', 'TAKEN')
            ->where('student_record.nilai_AKHIR', '!=', '0')
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'matakuliah.kode', 'matakuliah.makul', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)) as akt_sks'), 'student_record.nilai_AKHIR', 'student_record.nilai_ANGKA', DB::raw('((matakuliah.akt_sks_teori+matakuliah.akt_sks_praktek)*student_record.nilai_ANGKA) as nilai_sks'))
            ->get();

        $sks = DB::select('CALL transkripsmt(' . $item->idstudent . ')');
        foreach ($sks as $keysks) {
            // code...
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

        $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
            ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
            ->where('prodi.kodeprodi', $item->kodeprodi)
            ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
            ->first();

        return view('sadmin/nilai/print_transkrip_smt', compact('item', 'data', 'keysks', 'y', 'm', 'd', 'cekkprd'));
    }

    public function no_transkrip()
    {
        $nomor = Transkrip_nilai::join('student', 'transkrip_nilai.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->select('student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'transkrip_nilai.no_transkrip')
            ->get();

        return view('sadmin/nilai/nomor_transkrip', compact('nomor'));
    }

    public function transkrip_nilai_final()
    {
        $nilai = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('transkrip_final', 'prausta_setting_relasi.id_student', '=', 'transkrip_final.id_student')
            ->whereIn('prausta_master_kode.kode_prausta', ['FA-602', 'TI-602', 'TK-602'])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            ->where('student.active', 1)
            ->select('transkrip_final.id_transkrip_final', 'prausta_setting_relasi.id_settingrelasi_prausta', 'student.nim', 'student.idstudent', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan')
            ->orderBy('student.nim', 'desc')
            ->get();

        return view('sadmin/nilai/transkrip_final', compact('nilai'));
    }

    public function input_transkrip_final($id)
    {
        $mhs = Student::join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('prausta_setting_relasi', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi', 'prausta_setting_relasi.judul_prausta')
            ->get();

        foreach ($mhs as $item) {
            // code...
        }

        return view('sadmin/transkrip/form_transkrip_final', compact('item'));
    }

    public function simpan_transkrip_final(Request $request)
    {
        $id = $request->id_student;
        $no_tr = $request->no_transkrip_final;
        $no_ij = $request->no_ijazah;
        $yudisium = $request->tgl_yudisium;
        $wisuda = $request->tgl_wisuda;

        $tns = new Transkrip_final();
        $tns->no_transkrip_final = $no_tr;
        $tns->no_ijazah = $no_ij;
        $tns->id_student = $id;
        $tns->tgl_yudisium = $yudisium;
        $tns->tgl_wisuda = $wisuda;
        $tns->created_by = Auth::user()->name;
        $tns->save();

        return redirect('transkrip_nilai_final');
    }

    public function lihat_transkrip_final($id)
    {
        dd($id);
    }

    public function nilai_mhs()
    {
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $tipe = $tp->id_periodetipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tahun = $thn->id_periodetahun;

        $nilai = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', DB::raw('COUNT(student_record.id_student) as jml_mhs'), 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
            ->groupBy('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'student_record.id_kurperiode', 'prodi.prodi')
            ->get();

        return view('sadmin/nilai/rekap_nilai', compact('nilai'));
    }

    public function cek_nilai_mhs($id)
    {
        $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student_record.id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.nilai_KAT', 'student_record.nilai_UTS', 'student_record.nilai_UAS', 'student_record.nilai_AKHIR', 'student_record.nilai_AKHIR_angka')
            ->get();

        return view('sadmin/nilai/cek_nilai_mhs', compact('data'));
    }

    public function soal_uts()
    {
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $tipe = $tp->id_periodetipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tahun = $thn->id_periodetahun;

        $soal = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('bap.jenis_kuliah', 'UTS')
            ->select('bap.id_bap', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'prodi.prodi', 'kelas.kelas', 'dosen.nama', 'bap.file_materi_kuliah', 'dosen.iddosen', 'bap.id_kurperiode')
            ->get();

        return view('sadmin/nilai/cek_soal_uts', compact('soal'));
    }

    public function download_soal_uts($id)
    {
        $uts = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('bap.id_bap', $id)
            ->select('dosen.iddosen', 'bap.id_kurperiode', 'bap.file_materi_kuliah')
            ->get();

        foreach ($uts as $soal) {
            // code...
        }
        //PDF file is stored under project/public/download/info.pdf
        $file_uts = 'File_BAP/' . $soal->iddosen . '/' . $soal->id_kurperiode . '/Materi Kuliah/' . $soal->file_materi_kuliah;

        return Response::download($file_uts);
    }

    public function soal_uas()
    {
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $tipe = $tp->id_periodetipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tahun = $thn->id_periodetahun;

        $soal = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('bap.jenis_kuliah', 'UAS')
            ->select('bap.id_bap', 'matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'prodi.prodi', 'kelas.kelas', 'dosen.nama', 'bap.file_materi_kuliah', 'dosen.iddosen', 'bap.id_kurperiode')
            ->get();

        return view('sadmin/nilai/cek_soal_uas', compact('soal'));
    }

    public function download_soal_uas($id)
    {
        $uas = Bap::join('kurikulum_periode', 'bap.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('bap.id_bap', $id)
            ->select('dosen.iddosen', 'bap.id_kurperiode', 'bap.file_materi_kuliah')
            ->get();

        foreach ($uas as $soal) {
        }
        //PDF file is stored under project/public/download/info.pdf
        $file_uas = 'File_BAP/' . $soal->iddosen . '/' . $soal->id_kurperiode . '/Materi Kuliah/' . $soal->file_materi_kuliah;

        return Response::download($file_uas);
    }

    public function rekap_perkuliahan()
    {
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $tipe = $tp->id_periodetipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tahun = $thn->id_periodetahun;

        $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'kurikulum_periode.id_kurperiode', 'prodi.prodi')
            ->get();

        $jml = Kurikulum_periode::join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
            ->where('kurikulum_periode.id_periodetipe', $tipe)
            ->where('kurikulum_periode.id_periodetahun', $tahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('bap.status', 'ACTIVE')
            ->select(DB::raw('COUNT(bap.id_kurperiode) as jml_per'), 'bap.id_kurperiode')
            ->groupBy('bap.id_kurperiode')
            ->get();

        return view('sadmin/perkuliahan/rekap_perkuliahan', compact('data', 'jml'));
    }

    public function cek_rekapan($id)
    {
        $bap = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->where('kurikulum_periode.id_kurperiode', $id)
            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester')
            ->get();
        foreach ($bap as $key) {
            # code...
        }

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select('kuliah_transaction.kurang_jam', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
            ->get();

        return view('sadmin/perkuliahan/cek_bap', ['bap' => $key, 'data' => $data]);
    }

    public function sum_absen($id)
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

        $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
            ->get();

        $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 2)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 1)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 3)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 4)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 5)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 6)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 7)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 8)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 9)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 10)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 11)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 12)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 13)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 14)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 15)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 16)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        return view('sadmin/perkuliahan/cek_absensi_perkuliahan', ['abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
    }

    public function print_absensi($id)
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

        $abs = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select(DB::raw('DISTINCT(student_record.id_studentrecord)'), 'student.nama', 'student.nim')
            ->get();

        $abs2 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 2)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs1 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 1)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs3 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 3)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs4 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 4)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs5 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 5)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs6 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 6)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs7 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 7)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs8 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 8)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs9 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 9)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs10 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 10)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs11 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 11)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs12 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 12)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs13 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 13)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs14 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 14)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs15 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 15)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

        $abs16 = Absensi_mahasiswa::join('bap', 'absensi_mahasiswa.id_bap', '=', 'bap.id_bap')
            ->join('student_record', 'absensi_mahasiswa.id_studentrecord', '=', 'student_record.id_studentrecord')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->where('bap.pertemuan', 16)
            ->select('absensi_mahasiswa.absensi', 'absensi_mahasiswa.id_studentrecord', 'bap.pertemuan')
            ->get();

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

        return view('sadmin/perkuliahan/cek_cetak_absensi', ['d' => $d, 'm' => $m, 'y' => $y, 'abs16' => $abs16, 'abs15' => $abs15, 'abs14' => $abs14, 'abs13' => $abs13, 'abs12' => $abs12, 'abs11' => $abs11, 'abs10' => $abs10, 'abs9' => $abs9, 'abs8' => $abs8, 'abs7' => $abs7, 'abs6' => $abs6, 'abs5' => $abs5, 'abs4' => $abs4, 'abs' => $abs, 'abs1' => $abs1, 'abs2' => $abs2, 'abs3' => $abs3, 'bap' => $key]);
    }

    public function jurnal_bap($id)
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
            ->select('kuliah_transaction.val_jam_selesai', 'kuliah_transaction.val_jam_mulai', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

        return view('sadmin/perkuliahan/cek_jurnal_perkuliahan', ['bap' => $key, 'data' => $data]);
    }

    public function print_jurnal($id)
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

        $cekkprd = Kaprodi::join('dosen', 'kaprodi.id_dosen', '=', 'dosen.iddosen')
            ->join('prodi', 'kaprodi.id_prodi', '=', 'prodi.id_prodi')
            ->where('prodi.prodi', $key->prodi)
            ->select('dosen.nama', 'dosen.akademik', 'dosen.nik')
            ->first();

        $data = Bap::join('kuliah_tipe', 'bap.id_tipekuliah', '=', 'kuliah_tipe.id_tipekuliah')
            ->join('kuliah_transaction', 'bap.id_bap', '=', 'kuliah_transaction.id_bap')
            ->where('bap.id_kurperiode', $id)
            ->where('bap.status', 'ACTIVE')
            ->select('kuliah_transaction.val_jam_selesai', 'kuliah_transaction.val_jam_mulai', 'kuliah_transaction.tanggal_validasi', 'kuliah_transaction.payroll_check', 'bap.id_bap', 'bap.pertemuan', 'bap.tanggal', 'bap.jam_mulai', 'bap.jam_selsai', 'bap.materi_kuliah', 'bap.metode_kuliah', 'kuliah_tipe.tipe_kuliah', 'bap.jenis_kuliah', 'bap.hadir', 'bap.tidak_hadir')
            ->orderBy('bap.tanggal', 'ASC')
            ->get();

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

        return view('sadmin/perkuliahan/cek_cetak_jurnal', ['cekkprd' => $cekkprd, 'd' => $d, 'm' => $m, 'y' => $y, 'bap' => $key, 'data' => $data]);
    }

    public function cek_view_bap($id)
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

        return view('sadmin/perkuliahan/view_bap', ['prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
    }

    public function cek_print_bap($id)
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

        return view('sadmin/perkuliahan/cetak_bap', ['d' => $d, 'm' => $m, 'y' => $y, 'prd' => $prd, 'tipe' => $tipe, 'tahun' => $tahun, 'data' => $data, 'dtbp' => $dtbp]);
    }

    public function visimisi()
    {
        $vm = Visimisi::all();

        return view('sadmin/visimisi/visimisi', compact('vm'));
    }

    public function add_visimisi()
    {
        return view('sadmin/visimisi/add_visimisi');
    }

    public function save_visimisi(Request $request)
    {
        $this->validate($request, [
            'visi' => 'required',
            'misi' => 'required',
            'tujuan' => 'required',
        ]);

        $vm = new Visimisi();
        $vm->visi = $request->visi;
        $vm->misi = $request->misi;
        $vm->tujuan = $request->tujuan;
        $vm->created_by = Auth::user()->name;
        $vm->save();

        Alert::success('', 'Visi Misi Berhasil ditambahkan')->autoclose(3500);
        return redirect('visimisi');
    }

    public function editvisimisi($id)
    {
        $vm = Visimisi::find($id);
        return view('sadmin/visimisi/edit_visimisi', compact('vm'));
    }

    public function simpaneditvisimisi(Request $request, $id)
    {
        $this->validate($request, [
            'visi' => 'required',
            'misi' => 'required',
            'tujuan' => 'required',
        ]);

        $vm = Visimisi::find($id);
        $vm->visi = $request->visi;
        $vm->misi = $request->misi;
        $vm->tujuan = $request->tujuan;
        $vm->updated_by = Auth::user()->name;
        $vm->save();

        Alert::success('', 'Visi misi berhasil diedit')->autoclose(3500);
        return redirect('visimisi');
    }

    public function data_prausta()
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'ASC')->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2])->get();

        return view('sadmin.prausta.data', compact('tahun', 'tipe'));
    }

    public function filter_prausta(Request $request)
    {
        $id_tahun = $request->id_periodetahun;
        $id_tipe = $request->id_periodetipe;

        $data = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prausta_master_kode', 'matakuliah.kode', '=', 'prausta_master_kode.kode_prausta')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('prodi', 'student.kodeprodi', '=', 'prodi.kodeprodi')
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('kurikulum_periode.id_periodetahun', $id_tahun)
            ->where('kurikulum_periode.id_periodetipe', $id_tipe)
            ->whereIn('matakuliah.idmakul', [136, 178, 179])
            ->where('student_record.status', 'TAKEN')
            ->select('prodi.prodi', 'kelas.kelas', 'student.nim', 'student.nama', 'matakuliah.makul')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin.prausta.hasil', compact('data'));
    }

    public function wadir()
    {
        $wadir = Wadir::join('dosen', 'wadir.id_dosen', '=', 'dosen.iddosen')
            ->where('wadir.status', 'ACTIVE')
            ->select('dosen.nik', 'dosen.nama', 'wadir.id_wadir', 'wadir.id_dosen', 'wadir.wadir')
            ->get();

        $dosen = Dosen::where('idstatus', 1)->get();

        return view('sadmin/wadir/data_wadir', compact('wadir', 'dosen'));
    }

    public function post_wadir(Request $request)
    {
        $pisah = $request->wadir;
        $nama = $request->id_dosen;

        $wadir = explode(',', $pisah, 2);
        $id1 = $wadir[0];
        $id2 = $wadir[1];

        $dosen = explode(',', $nama, 2);
        $dsn1 = $dosen[0];
        $dsn2 = $dosen[1];

        $kpr = new Wadir();
        $kpr->id_dosen = $dsn1;
        $kpr->wadir = $id1;
        $kpr->created_by = Auth::user()->name;
        $kpr->save();

        $user = new User();
        $user->id_user = $dsn1;
        $user->name = $dsn2;
        $user->username = $id1;
        $user->role = $id2;
        $user->password = bcrypt($id1);
        $user->save();

        Alert::success('', 'Wadir berhasil ditambahkan')->autoclose(3500);
        return redirect('wadir');
    }

    public function data_admin_prodi()
    {
        $data = User::join('wrkpersonalia', 'users.id_user', '=', 'wrkpersonalia.idstaff')
            ->where('users.role', 9)
            ->get();

        $staff = Wrkpersonalia::where('active', 1)->get();

        return view('sadmin/user/adminprodi', compact('data', 'staff'));
    }

    public function post_adminprodi(Request $request)
    {
        $role = $request->role;
        $data = $request->id_user;
        $usern = $request->username;
        $pass = $request->password;

        $user = explode(',', $data, 2);
        $id1 = $user[0];
        $id2 = $user[1];

        $users = new User();
        $users->id_user = $id1;
        $users->name = $id2;
        $users->username = $usern;
        $users->role = $role;
        $users->password = bcrypt($pass);
        $users->save();

        Alert::success('', 'Admin Prodi berhasil ditambahkan')->autoclose(3500);
        return redirect('data_admin_prodi');
    }

    public function put_adminprodi(Request $request, $id)
    {
        $data = $request->id_user;
        $usern = $request->username;

        $user = explode(',', $data, 2);
        $id1 = $user[0];
        $id2 = $user[1];

        $prd = User::find($id);
        $prd->id_user = $id1;
        $prd->name = $id2;
        $prd->username = $usern;
        $prd->save();

        return redirect('data_admin_prodi');
    }

    public function hapusadminprodi($id)
    {
        $user = User::where('id', $id)->forceDelete();

        Alert::success('', 'User berhasil dihapus')->autoclose(3500);
        return redirect('data_admin_prodi');
    }
}
