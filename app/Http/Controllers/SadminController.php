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
use App\Matakuliah_bom;
use App\Semester;
use App\Dosen_pembimbing;
use App\Kurikulum_hari;
use App\Kurikulum_jam;
use App\Kurikulum_periode;
use App\Kurikulum_transaction;
use App\Transkrip_nilai;
use App\Transkrip_final;
use App\Absensi_mahasiswa;
use App\Prausta_master_penilaian;
use App\Visimisi;
use App\Kuliah_nilaihuruf;
use App\Prausta_master_kategori;
use App\Prausta_master_kode;
use App\Prausta_setting_relasi;
use App\Prausta_trans_bimbingan;
use App\Prausta_trans_hasil;
use App\Kuisioner_master;
use App\Kuisioner_kategori;
use App\Kuisioner_aspek;
use App\Kuisioner_transaction;
use App\Microsoft_user;
use App\Wadir;
use App\Wrkpersonalia;
use App\Sertifikat;
use App\Skpi;
use App\Soal_ujian;
use App\Yudisium;
use App\Waktu;
use App\Standar;
use App\Jenis_kegiatan;
use App\Pengalaman;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Exports\DataNilaiIpkMhsExport;
use App\Exports\DataNilaiKHSExport;
use App\Exports\DataKRSMhsExport;
use App\Exports\DataPrakerinExport;
use App\Exports\DataAkmMhsExport;

use App\Imports\ImportMicrosoftUser;
use App\Wisuda;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;

class SadminController extends Controller
{
    public function master_angkatan()
    {
        $data = Angkatan::all();
        $pass = decrypt(12345678);

        return view('sadmin/masterakademik/master_angkatan', compact('data'));
    }

    public function simpan_angkatan(Request $request)
    {
        $ang = new Angkatan();
        $ang->idangkatan = $request->idangkatan;
        $ang->angkatan = $request->angkatan;
        $ang->save();

        Alert::success('', 'Master angkatan berhasil ditambahkan')->autoclose(3500);
        return redirect('master_angkatan');
    }

    public function put_angkatan(Request $request, $id)
    {
        $ang = Angkatan::find($id);
        $ang->idangkatan = $request->idangkatan;
        $ang->angkatan = $request->angkatan;
        $ang->save();

        Alert::success('', 'Master angkatan berhasil diedit')->autoclose(3500);
        return redirect('master_angkatan');
    }

    public function hapusangkatan(Request $request)
    {
        $id = $request->idangkatan;
        Angkatan::where('idangkatan', $id)->delete();

        Alert::success('', 'Master angkatan berhasil dihapus')->autoclose(3500);
        return redirect('master_angkatan');
    }

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
        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.active', 1)
            ->select('student.nim', 'student.nama', 'prodi.prodi', 'prodi.konsentrasi', 'kelas.kelas', 'angkatan.angkatan', 'student.nisn', 'student.intake')
            ->get();

        return view('sadmin/data_mhs', ['mhss' => $mhs]);
    }

    public function show_user()
    {
        $usermhs = Student::leftJoin('passwords', 'user', '=', 'student.nim')
            ->leftJoin('users', 'username', '=', 'passwords.user')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->select(
                'users.id',
                'users.id_user',
                'users.username',
                'users.deleted_at',
                'passwords.pwd',
                'student.nim',
                'student.nama',
                'kelas.kelas',
                'prodi.prodi',
                'student.idstudent',
                'users.role',
                'angkatan.angkatan'
            )
            ->where('student.active', 1)
            ->orderBy('student.idangkatan', 'DESC')
            ->orderBy('student.nim', 'ASC')
            ->get();


        return view('sadmin/data_user', ['users' => $usermhs]);
    }

    public function saveuser_mhs(Request $request)
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

        Alert::success('', 'User berhasil didaftarkan')->autoclose(3500);
        return redirect('show_user');
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
        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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
        }

        return redirect('data_nilai');
    }

    public function pembimbing()
    {
        $pem = Dosen_pembimbing::join('student', 'dosen_pembimbing.id_student', '=', 'student.idstudent')
            ->join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.active', 1)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'dosen.nama as nama_dsn')
            ->orderBy('student.nim', 'DESC')
            ->get();

        return view('sadmin/pembimbing', ['dosbing' => $pem]);
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
        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $appr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('dosen_pembimbing', 'student.idstudent', 'dosen_pembimbing.id_student')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->where('student.active', 1)
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'student.nama', 'student.nim', 'prodi.prodi', 'angkatan.angkatan', 'dosen.nama as nama_dsn', 'kelas.kelas', 'student_record.remark')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/approv', ['appr' => $appr]);
    }

    public function cek_krs($id)
    {
        $datamhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.idstudent', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas')
            ->first();

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

        $kur = Kurikulum_master::where('status', 'ACTIVE')->first();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();

        $maha = Student::where('idstudent', $id)->first();

        $prod = Prodi::where('kodeprodi', $maha->kodeprodi)->first();

        $mhs = $maha->idstudent;

        $krs = Kurikulum_transaction::join('kurikulum_periode', 'kurikulum_transaction.id_makul', '=', 'kurikulum_periode.id_makul')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_transaction.id_kurikulum', $kur->id_kurikulum)
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('kurikulum_periode.id_kelas', $maha->idstatus)
            ->where('kurikulum_transaction.id_prodi', $prod->id_prodi)
            ->where('kurikulum_transaction.id_angkatan', $maha->idangkatan)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('matakuliah.makul', 'matakuliah.kode', 'kurikulum_transaction.idkurtrans', 'kurikulum_periode.id_kurperiode', 'semester.semester', 'dosen.nama')
            ->get();

        $val = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kurikulum_hari', 'kurikulum_periode.id_hari', '=', 'kurikulum_hari.id_hari')
            ->join('kurikulum_jam', 'kurikulum_periode.id_jam', '=', 'kurikulum_jam.id_jam')
            ->join('ruangan', 'kurikulum_periode.id_ruangan', '=', 'ruangan.id_ruangan')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->where('id_student', $id)
            ->select('student_record.remark', 'student.idstudent', 'student_record.id_studentrecord', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'matakuliah.makul', 'matakuliah.kode', 'student_record.remark', 'kurikulum_hari.hari', 'kurikulum_jam.jam', 'ruangan.nama_ruangan', 'semester.semester')
            ->get();

        return view('sadmin/cek_krs_admin', ['datamhs' => $datamhs, 'b' => $b, 'mhss' => $mhs, 'add' => $krs, 'val' => $val]);
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

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();

        $appr = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->leftjoin('dosen_pembimbing', 'student.idstudent', 'dosen_pembimbing.id_student')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('dosen', 'dosen_pembimbing.id_dosen', '=', 'dosen.iddosen')
            ->where('kurikulum_periode.id_periodetipe', $tp->id_periodetipe)
            ->where('kurikulum_periode.id_periodetahun', $thn->id_periodetahun)
            ->where('student_record.status', 'TAKEN')
            ->where('student.active', 1)
            ->where('student_record.remark', $request->remark)
            ->select(DB::raw('DISTINCT(student_record.id_student)'), 'student.nama', 'student.nim', 'prodi.prodi', 'angkatan.angkatan', 'dosen.nama as nama_dsn', 'kelas.kelas', 'student_record.remark')
            ->get();

        return view('sadmin/approv', ['appr' => $appr]);
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
        $pedoman = Pedoman_akademik::join('periode_tahun', 'pedoman_akademik.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->where;

        return view('sadmin/pedoman', ['tahun' => $tahun, 'pedoman' => $pedoman]);
    }

    public function save_pedoman(Request $request)
    {
        $this->validate($request, [
            'nama_file' => 'required',
            'file_pedoman' => 'mimes:jpg|max:10000',
            'id_periodetahun' => 'required',
        ]);

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
        $mhs = Student::join('prodi', 'prodi.kodeprodi', '=', 'student.kodeprodi')
            ->join('angkatan', 'angkatan.idangkatan', '=', 'student.idangkatan')
            ->join('kelas', 'kelas.idkelas', '=', 'student.idstatus')
            ->where('student.idangkatan', $request->idangkatan)
            ->where('student.kodeprodi', $request->kodeprodi)
            ->where('student.idstatus', $request->idstatus)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'prodi.prodi', 'angkatan.angkatan', 'kelas.kelas')
            ->get();

        return view('sadmin/data_ktm', ['mhs' => $mhs]);
    }

    public function downloadktm($id)
    {
        $mhs = Student::join('prodi', 'prodi.kodeprodi', '=', 'student.kodeprodi')
            ->where('student.idstudent', $id)
            ->first();

        $thn = $mhs->idangkatan;
        $ttl = $thn + 3;
        $t1 = $ttl - 1;

        $hs = '20' . $t1 . '-' . '20' . $ttl;

        //return view('sadmin/ktm', ['mhs'=>$keymhs, 'prd'=>$prd]);
        //return PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('sadmin/ktm', ['mhs'=>$keymhs, 'prd'=>$prd]);
        $pdf = PDF::loadView('sadmin/ktm', ['mhs' => $mhs, 'hs' => $hs])->setPaper('a4', 'landscape');
        return $pdf->download('KTM_' . $mhs->nim . '_' . $mhs->nama . '.pdf');
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

        $nama_file = 'Nilai' . ' ' . $pro . ' ' . $ganti . ' ' . $tpe . '.xlsx';
        return Excel::download(new DataNilaiKHSExport($prd, $ta, $tp, $kd), $nama_file);
    }

    public function data_krs()
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'ASC')->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2, 3])->get();
        $prodi = Prodi::all();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $idtipe = $tp->id_periodetipe;
        $namaperiodetipe = $tp->periode_tipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $idtahun = $thn->id_periodetahun;
        $namaperiodetahun = $thn->periode_tahun;

        $nilai = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student_record', 'kurikulum_periode.id_kurperiode', '=', 'student_record.id_kurperiode')
            ->join('kurikulum_transaction', 'student_record.id_kurtrans', '=', 'kurikulum_transaction.idkurtrans')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->where('kurikulum_periode.id_periodetipe', $idtipe)
            ->where('kurikulum_periode.id_periodetahun', $idtahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('student_record.status', 'TAKEN')
            ->select(
                'matakuliah.kode',
                'matakuliah.makul',
                'matakuliah.akt_sks_teori',
                'matakuliah.akt_sks_praktek',
                DB::raw('COUNT(student_record.id_student) as jml_mhs'),
                'dosen.nama',
                'kelas.kelas',
                'student_record.id_kurperiode',
                'prodi.prodi',
                'prodi.konsentrasi'
            )
            ->groupBy(
                'matakuliah.kode',
                'matakuliah.makul',
                'matakuliah.akt_sks_teori',
                'matakuliah.akt_sks_praktek',
                'dosen.nama',
                'kelas.kelas',
                'student_record.id_kurperiode',
                'prodi.prodi',
                'prodi.konsentrasi'
            )
            ->get();

        return view('sadmin/master_krs/data_krs', ['thn' => $tahun, 'tp' => $tipe, 'prd' => $prodi, 'krs' => $nilai, 'namaperiodetipe' => $namaperiodetipe, 'namaperiodetahun' => $namaperiodetahun]);
    }

    public function cek_krs_mhs($id)
    {
        $data = Student_record::join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student_record.id_kurperiode', $id)
            ->where('student_record.status', 'TAKEN')
            ->select('student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'student_record.status', 'student_record.id_studentrecord')
            ->get();

        return view('sadmin/master_krs/cek_krs_mhs', compact('data'));
    }

    public function batalkrs($id)
    {
        $akun = Student_record::where('id_studentrecord', $id)->update(['status' => 'DROPPED']);

        Alert::success('', 'KRS berhasil dihapus')->autoclose(3500);
        return redirect()->back();
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

        $akun1 = User::where('id_user', $request->id_dosen)->update(['role' => 2]);

        return redirect('kaprodi');
    }

    public function transkrip_nilai()
    {
        $nilai = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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
        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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

        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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
        $trans = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->select('student.nim', 'student.nama', 'prodi.prodi', 'kelas.kelas', 'transkrip_nilai.no_transkrip')
            ->get();

        return view('sadmin/nilai/nomor_transkrip', compact('nomor'));
    }

    public function transkrip_nilai_final()
    {
        $nilai = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->join('prausta_master_kode', 'prausta_setting_relasi.id_masterkode_prausta', '=', 'prausta_master_kode.id_masterkode_prausta')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
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
        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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
        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('transkrip_final', 'student.idstudent', '=', 'transkrip_final.id_student')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->where('student.idstudent', $id)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->select('prausta_setting_relasi.judul_prausta', 'transkrip_final.id_transkrip_final', 'transkrip_final.no_ijazah', 'transkrip_final.tgl_yudisium', 'transkrip_final.tgl_wisuda', 'transkrip_final.no_transkrip_final', 'student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi')
            ->get();

        foreach ($mhs as $item) {
            // code...
        }

        $nama = strtoupper($item->nama);

        $yudisium = $item->tgl_yudisium;

        $wisuda = $item->tgl_wisuda;

        $pisahyudi = explode('-', $yudisium);

        $pisahwisu = explode('-', $wisuda);

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

        $blnyudi = $bulan[$pisahyudi[1]];
        $blnwisu = $bulan[$pisahwisu[1]];
        $tglyudi = $pisahyudi[2] . ' ' . $blnyudi . ' ' . $pisahyudi[0];
        $tglwisu = $pisahwisu[2] . ' ' . $blnwisu . ' ' . $pisahwisu[0];

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

        $sks = DB::select('CALL transkripsmt(' . $id . ')');
        foreach ($sks as $keysks) {
            // code...
        }

        return view('sadmin/transkrip/hasil_transkrip_final', compact('item', 'nama', 'data', 'keysks', 'tglyudi', 'tglwisu'));
    }

    public function print_transkrip_final($id)
    {
        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('transkrip_final', 'student.idstudent', '=', 'transkrip_final.id_student')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->where('student.idstudent', $id)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->select('prausta_setting_relasi.judul_prausta', 'transkrip_final.id_transkrip_final', 'transkrip_final.no_ijazah', 'transkrip_final.tgl_yudisium', 'transkrip_final.tgl_wisuda', 'transkrip_final.no_transkrip_final', 'student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi')
            ->get();

        foreach ($mhs as $item) {
            // code...
        }

        $nama = strtoupper($item->nama);

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

        $sks = DB::select('CALL transkripsmt(' . $id . ')');
        foreach ($sks as $keysks) {
            // code...
        }

        return view('sadmin/transkrip/print_transkrip_final', compact('item', 'data', 'keysks', 'nama'));
    }

    public function downloadAbleFile($id)
    {
        $mhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('transkrip_final', 'student.idstudent', '=', 'transkrip_final.id_student')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->where('student.idstudent', $id)
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->select('prausta_setting_relasi.judul_prausta', 'transkrip_final.id_transkrip_final', 'transkrip_final.no_ijazah', 'transkrip_final.tgl_yudisium', 'transkrip_final.tgl_wisuda', 'transkrip_final.no_transkrip_final', 'student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi')
            ->get();

        foreach ($mhs as $item) {
            // code...
        }
        $tgllahir = $item->tgllahir->isoFormat('D MMMM Y');

        $nama = strtoupper($item->nama);

        $yudisium = $item->tgl_yudisium;

        $wisuda = $item->tgl_wisuda;

        $pisahyudi = explode('-', $yudisium);

        $pisahwisu = explode('-', $wisuda);

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

        $blnyudi = $bulan[$pisahyudi[1]];
        $blnwisu = $bulan[$pisahwisu[1]];
        $tglyudi = $pisahyudi[2] . ' ' . $blnyudi . ' ' . $pisahyudi[0];
        $tglwisu = $pisahwisu[2] . ' ' . $blnwisu . ' ' . $pisahwisu[0];

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

        $sks = DB::select('CALL transkripsmt(' . $id . ')');
        foreach ($sks as $keysks) {
            // code...
        }
        if ($keysks->IPK >= 3.51) {
            $predikat = 'Cumlaude';
        } elseif ($keysks->IPK >= 3.0) {
            $predikat = 'Sangat Memuaskan';
        } elseif ($keysks->IPK >= 2.0) {
            $predikat = 'Memuaskan';
        }

        $template = new TemplateProcessor('word-template/transkrip_final.docx');
        $template->setValue('idstudent', $item->idstudent);
        $template->setValue('nama', $nama);
        $template->setValue('no_ijazah', $item->no_ijazah);
        $template->setValue('no_transkrip_final', $item->no_transkrip_final);
        $template->setValue('nim', $item->nim);
        $template->setValue('tgllahir', $tgllahir);
        $template->setValue('tmptlahir', $item->tmptlahir);
        $template->setValue('prodi', $item->prodi);
        $template->setValue('kelulusan', $tglyudi);
        $template->setValue('wisuda', $tglwisu);
        $template->setValue('judul', $item->judul_prausta);
        $template->setValue('totalsks', $keysks->total_sks);
        $template->setValue('nilai_sks', $keysks->nilai_sks);
        $template->setValue('ipk', $keysks->IPK);
        $template->setValue('predikat', $predikat);

        $new_data = [];

        for ($i = 0; $i < count($data); $i++) {
            //remove not allow characters %^&({}+-/ ]['''
            $data[$i]->makul = str_replace('&', 'dan', $data[$i]->makul);

            $dt_array = [
                'kode' => $data[$i]->kode,
                'makul' => $data[$i]->makul,
                'akt_sks' => $data[$i]->akt_sks,
                'nilai_AKHIR' => $data[$i]->nilai_AKHIR,
                'nilai_ANGKA' => $data[$i]->nilai_ANGKA,
                'nilaisks' => $data[$i]->nilai_sks,
                'no' => $i + 1,
            ];

            array_push($new_data, (object) $dt_array);
        }

        $template->cloneRowAndSetValues('kode', $new_data);

        $fileName = $item->nama;

        $template->saveAs($fileName . '.docx');
        return response()
            ->download($fileName . '.docx')
            ->deleteFileAfterSend(true);
    }

    public function edit_transkrip_final($id)
    {
        $item = Transkrip_final::join('student', 'transkrip_final.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('prausta_setting_relasi', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('transkrip_final.id_transkrip_final', $id)
            ->select('transkrip_final.tgl_yudisium', 'transkrip_final.tgl_wisuda', 'transkrip_final.no_ijazah', 'transkrip_final.no_transkrip_final', 'transkrip_final.id_transkrip_final', 'student.idstudent', 'student.nama', 'student.nim', 'student.tmptlahir', 'student.tgllahir', 'prodi.prodi', 'prausta_setting_relasi.judul_prausta')
            ->first();

        $lahir = $item->tgllahir;

        $pisahlahir = explode('-', $lahir);

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

        $blnlahir = $bulan[$pisahlahir[1]];

        $tgllhr = $pisahlahir[2] . ' ' . $blnlahir . ' ' . $pisahlahir[0];

        return view('sadmin/transkrip/edit_transkrip_final', compact('item', 'tgllhr'));
    }

    public function simpanedit_transkrip_final(Request $request, $id)
    {
        $tns = Transkrip_final::find($id);
        $tns->no_transkrip_final = $request->no_transkrip_final;
        $tns->no_ijazah = $request->no_ijazah;
        $tns->tgl_yudisium = $request->tgl_yudisium;
        $tns->tgl_wisuda = $request->tgl_wisuda;
        $tns->updated_by = Auth::user()->name;
        $tns->save();

        Alert::success('Transkrip final berhasil diedit')->autoclose(3500);
        return redirect('transkrip_nilai_final');
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
            // ->where('kurikulum_periode.id_periodetipe', $tipe)
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
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
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
        $tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tipe = Periode_tipe::all();

        $tp = Periode_tipe::where('status', 'ACTIVE')->first();
        $idtipe = $tp->id_periodetipe;
        $namaperiodetipe = $tp->periode_tipe;

        $thn = Periode_tahun::where('status', 'ACTIVE')->first();
        $idtahun = $thn->id_periodetahun;
        $namaperiodetahun = $thn->periode_tahun;

        $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->where('kurikulum_periode.id_periodetipe', $idtipe)
            ->where('kurikulum_periode.id_periodetahun', $idtahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'kurikulum_periode.id_kurperiode', 'prodi.prodi')
            ->get();

        $jml = Kurikulum_periode::join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
            ->where('kurikulum_periode.id_periodetipe', $idtipe)
            ->where('kurikulum_periode.id_periodetahun', $idtahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('bap.status', 'ACTIVE')
            ->select(DB::raw('COUNT(bap.id_kurperiode) as jml_per'), 'bap.id_kurperiode')
            ->groupBy('bap.id_kurperiode')
            ->get();

        return view('sadmin/perkuliahan/rekap_perkuliahan', compact('data', 'jml', 'tahun', 'tipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function filter_rekap_perkuliahan(Request $request)
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tipe = Periode_tipe::all();

        $tp = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();
        $idtipe = $tp->id_periodetipe;
        $namaperiodetipe = $tp->periode_tipe;

        $thn = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $idtahun = $thn->id_periodetahun;
        $namaperiodetahun = $thn->periode_tahun;

        $data = Kurikulum_periode::join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->where('kurikulum_periode.id_periodetipe', $idtipe)
            ->where('kurikulum_periode.id_periodetahun', $idtahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('matakuliah.kode', 'matakuliah.makul', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek', 'dosen.nama', 'kelas.kelas', 'kurikulum_periode.id_kurperiode', 'prodi.prodi')
            ->get();

        $jml = Kurikulum_periode::join('bap', 'kurikulum_periode.id_kurperiode', '=', 'bap.id_kurperiode')
            ->where('kurikulum_periode.id_periodetipe', $idtipe)
            ->where('kurikulum_periode.id_periodetahun', $idtahun)
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->where('bap.status', 'ACTIVE')
            ->select(DB::raw('COUNT(bap.id_kurperiode) as jml_per'), 'bap.id_kurperiode')
            ->groupBy('bap.id_kurperiode')
            ->get();

        return view('sadmin/perkuliahan/rekap_perkuliahan', compact('data', 'jml', 'tahun', 'tipe', 'namaperiodetahun', 'namaperiodetipe'));
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
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
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

    public function put_wadir(Request $request, $id)
    {
        $data_dsn = Dosen::where('iddosen', $request->id_dosen)->first();
        $nama_dsn = $data_dsn->nama;

        User::where('username', 'wadir1')->update([
            'id_user' => $request->id_dosen,
            'name' => $nama_dsn
        ]);

        $prd = Wadir::find($id);
        $prd->id_dosen = $request->id_dosen;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        return redirect('wadir');
    }

    public function hapus_wadir($id)
    {
        Wadir::where('id_wadir', $id)->delete();

        User::where('username', 'wadir1')->delete();

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

    public function master_bom()
    {
        $data = Matakuliah_bom::join('matakuliah', 'matakuliah_bom.master_idmakul', '=', 'matakuliah.idmakul')
            ->where('matakuliah_bom.status', 'ACTIVE')
            ->select('matakuliah.makul', 'matakuliah_bom.master_idmakul', 'matakuliah_bom.slave_idmakul')
            ->get();

        $makul = Matakuliah::where('active', 1)->get();

        return view('sadmin/masterakademik/master_bom', compact('data', 'makul'));
    }

    public function penilaian_prausta()
    {
        $data = Prausta_master_penilaian::where('status', 'ACTIVE')->get();

        return view('sadmin/masterakademik/master_penilaian_prausta', compact('data'));
    }

    public function simpan_penilaian_prausta(Request $request)
    {
        $ang = new Prausta_master_penilaian();
        $ang->komponen = $request->komponen;
        $ang->bobot = $request->bobot;
        $ang->acuan = $request->acuan;
        $ang->kategori = $request->kategori;
        $ang->jenis_form = $request->jenis_form;
        $ang->save();

        Alert::success('', 'Master Penilaian PraUSTA berhasil ditambahkan')->autoclose(3500);
        return redirect('master_penilaianprausta');
    }

    public function put_penilaian_prausta(Request $request, $id)
    {
        $ang = Prausta_master_penilaian::find($id);
        $ang->komponen = $request->komponen;
        $ang->bobot = $request->bobot;
        $ang->acuan = $request->acuan;
        $ang->kategori = $request->kategori;
        $ang->jenis_form = $request->jenis_form;
        $ang->save();

        Alert::success('', 'Master Penilaian PraUSTA berhasil diedit')->autoclose(3500);
        return redirect('master_penilaianprausta');
    }

    public function hapus_penilaian_prausta(Request $request)
    {
        $akun = Prausta_master_penilaian::where('id_penilaian_prausta', $request->id_penilaian_prausta)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Master Penilaian PraUSTA berhasil dihapus')->autoclose(3500);
        return redirect('master_penilaianprausta');
    }

    public function master_kategorikuisioner()
    {
        $data = Kuisioner_kategori::where('status', 'ACTIVE')->get();

        return view('sadmin/masterakademik/master_kategori_kuisioner', compact('data'));
    }

    public function simpan_kategori_kuisioner(Request $request)
    {
        $ang = new Kuisioner_kategori();
        $ang->kategori_kuisioner = $request->kategori_kuisioner;
        $ang->created_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Master Kategori Kuisioner berhasil ditambahkan')->autoclose(3500);
        return redirect('master_kategorikuisioner');
    }

    public function put_kategori_kuisioner(Request $request, $id)
    {
        $ang = Kuisioner_kategori::find($id);
        $ang->kategori_kuisioner = $request->kategori_kuisioner;
        $ang->updated_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Master Kategori Kuisioner berhasil diedit')->autoclose(3500);
        return redirect('master_kategorikuisioner');
    }

    public function hapus_kategori_kuisioner(Request $request)
    {
        $akun = Kuisioner_kategori::where('id_kategori_kuisioner', $request->id_kategori_kuisioner)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Master Kategori Kuisioner berhasil dihapus')->autoclose(3500);
        return redirect('master_kategorikuisioner');
    }

    public function master_aspekkuisioner()
    {
        $data = Kuisioner_aspek::where('status', 'ACTIVE')->get();

        return view('sadmin/masterakademik/master_aspek_kuisioner', compact('data'));
    }

    public function simpan_aspek_kuisioner(Request $request)
    {
        $ang = new Kuisioner_aspek();
        $ang->aspek_kuisioner = $request->aspek_kuisioner;
        $ang->created_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Master Aspek Kuisioner berhasil ditambahkan')->autoclose(3500);
        return redirect('master_aspekkuisioner');
    }

    public function put_aspek_kuisioner(Request $request, $id)
    {
        $ang = Kuisioner_aspek::find($id);
        $ang->aspek_kuisioner = $request->aspek_kuisioner;
        $ang->updated_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Master Aspek Kuisioner berhasil diedit')->autoclose(3500);
        return redirect('master_aspekkuisioner');
    }

    public function hapus_aspek_kuisioner(Request $request)
    {
        $akun = Kuisioner_aspek::where('id_aspek_kuisioner', $request->id_aspek_kuisioner)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Master Kategori Kuisioner berhasil dihapus')->autoclose(3500);
        return redirect('master_aspekkuisioner');
    }

    public function master_kuisioner()
    {
        $data = Kuisioner_master::join('kuisioner_master_kategori', 'kuisioner_master.id_kategori_kuisioner', '=', 'kuisioner_master_kategori.id_kategori_kuisioner')
            ->join('kuisioner_master_aspek', 'kuisioner_master.id_aspek_kuisioner', '=', 'kuisioner_master_aspek.id_aspek_kuisioner')
            ->where('kuisioner_master.status', 'ACTIVE')
            ->get();

        $kategori = Kuisioner_kategori::where('status', 'ACTIVE')->get();

        $aspek = Kuisioner_aspek::where('status', 'ACTIVE')->get();

        return view('sadmin/masterakademik/master_kuisioner', compact('data', 'kategori', 'aspek'));
    }

    public function simpan_master_kuisioner(Request $request)
    {
        $ang = new Kuisioner_master();
        $ang->id_kategori_kuisioner = $request->id_kategori_kuisioner;
        $ang->id_aspek_kuisioner = $request->id_aspek_kuisioner;
        $ang->komponen_kuisioner = $request->komponen_kuisioner;
        $ang->created_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Master Kuisioner berhasil ditambahkan')->autoclose(3500);
        return redirect('master_kuisioner');
    }

    public function put_kuisioner_master(Request $request, $id)
    {
        $ang = Kuisioner_master::find($id);
        $ang->id_kategori_kuisioner = $request->id_kategori_kuisioner;
        $ang->id_aspek_kuisioner = $request->id_aspek_kuisioner;
        $ang->komponen_kuisioner = $request->komponen_kuisioner;
        $ang->updated_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Master Kuisioner berhasil diedit')->autoclose(3500);
        return redirect('master_kuisioner');
    }

    public function hapus_kuisioner_master(Request $request)
    {
        $akun = Kuisioner_master::where('id_kuisioner', $request->id_kuisioner)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Master Kuisioner berhasil dihapus')->autoclose(3500);
        return redirect('master_kuisioner');
    }

    public function user_microsoft()
    {
        $data = Microsoft_user::join('student', 'microsoft_user.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('microsoft_user.status', 'ACTIVE')
            ->select('student.nama', 'student.nim', 'microsoft_user.username', 'microsoft_user.password', 'prodi.prodi', 'kelas.kelas')
            ->get();

        return view('sadmin/microsoft/akun', compact('data'));
    }

    public function post_microsoft_user(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
        $file->move('File Microsoft User', $nama_file);

        // import data
        Excel::import(new ImportMicrosoftUser(), public_path('/File Microsoft User/' . $nama_file));

        // notifikasi dengan session
        Alert::success('', 'Data Microsoft User Berhasil Diimport!')->autoclose(3500);
        return redirect('user_microsoft');
    }

    public function skpi()
    {
        $prodi = Prodi::groupBy('kodeprodi', 'prodi')
            ->select('kodeprodi', 'prodi')
            ->get();

        $angkatan = Angkatan::where('angkatan', '>', 2016)->get();

        $data = Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
            ->leftjoin('skpi', 'yudisium.id_student', '=', 'skpi.id_student')
            ->where('student.active', 1)
            ->where('yudisium.validasi', 'SUDAH')
            ->select('yudisium.id_student', 'student.nim', 'yudisium.nama_lengkap', 'yudisium.tmpt_lahir', 'yudisium.tgl_lahir', 'skpi.id_skpi', 'skpi.no_skpi', 'skpi.date_masuk', 'skpi.date_lulus', 'skpi.no_ijazah')
            ->get();

        $data1 = Student::leftjoin('skpi', 'student.idstudent', '=', 'skpi.id_student')
            ->where('student.active', 1)
            ->select('student.nim', 'student.nama', 'student.idstudent', 'skpi.id_skpi', 'skpi.no_skpi', 'student.tmptlahir', 'student.tgllahir', 'skpi.gelar')
            ->get();

        return view('sadmin/skpi/skpi', compact('data', 'prodi', 'angkatan'));
    }

    public function filter_skpi(Request $request)
    {
        $kodeprodi = $request->kodeprodi;
        $idangkatan = $request->idangkatan;

        $prodi = Prodi::where('kodeprodi', $kodeprodi)->first();
        $angkatan = Angkatan::where('idangkatan', $idangkatan)->first();

        $data = Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
            ->leftjoin('skpi', 'yudisium.id_student', '=', 'skpi.id_student')
            ->where('student.active', 1)
            ->where('yudisium.validasi', 'SUDAH')
            ->where('student.kodeprodi', $kodeprodi)
            ->where('student.idangkatan', $idangkatan)
            ->select('yudisium.id_student', 'student.nim', 'yudisium.nama_lengkap', 'yudisium.tmpt_lahir', 'yudisium.tgl_lahir', 'skpi.id_skpi', 'skpi.no_skpi', 'skpi.date_masuk', 'skpi.date_lulus', 'skpi.no_ijazah')
            ->get();

        if (count($data) == 0) {
            Alert::warning('', 'Maaf belum ada yang mendaftar Yudisium!')->autoclose(3500);
            return redirect('skpi');
        } elseif (count($data) > 0) {
            return view('sadmin/skpi/filter_skpi', compact('data', 'prodi', 'angkatan'));
        }
    }

    public function download_skpi($id)
    {
        $mhs = Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
            ->join('skpi', 'yudisium.id_student', '=', 'skpi.id_student')
            ->where('skpi.id_skpi', $id)
            ->select(
                'yudisium.nama_lengkap',
                'student.nim',
                'yudisium.tmpt_lahir',
                'yudisium.tgl_lahir',
                'skpi.date_masuk',
                'skpi.date_lulus',
                'skpi.no_skpi',
                'skpi.no_ijazah',
                'yudisium.id_student',
                'student.kodeprodi'
            )
            ->first();

        $nama_mhs = strtolower($mhs->nama_lengkap);
        $new_name = ucwords($nama_mhs);
        $nim = $mhs->nim;
        $tmptlahir = strtolower($mhs->tmpt_lahir);
        $new_tmptlahir = ucwords($tmptlahir);

        $tgllahir = $mhs->tgl_lahir->isoFormat('D MMMM Y');

        $datemasuk = $mhs->date_masuk;
        $updatedDateMasuk =  Carbon::createFromFormat('Y-m-d', $datemasuk)->isoFormat('D MMMM Y');

        $datelulus = $mhs->date_lulus;
        $updatedDateLulus =  Carbon::createFromFormat('Y-m-d', $datelulus)->isoFormat('D MMMM Y');

        $ijazah = $mhs->no_ijazah;
        $skpi = $mhs->no_skpi;

        $prodi = $mhs->kodeprodi;

        if ($prodi == 24) {
            $pathTemplate = new TemplateProcessor('word-template/template SKPI FA.docx');
        } elseif ($prodi == 22) {
            # code...
        } elseif ($prodi == 23) {
            # code...
        }

        $template = $pathTemplate;
        $template->setValue('nama', $new_name);
        $template->setValue('tempat', $new_tmptlahir);
        $template->setValue('tanggal', $tgllahir);
        $template->setValue('nim', $nim);
        $template->setValue('tanggal_masuk', $updatedDateMasuk);
        $template->setValue('tanggal_lulus', $updatedDateLulus);
        $template->setValue('no_ijazah', $ijazah);
        $template->setValue('no_skpi', $skpi);

        $dataA = Sertifikat::where('id_jeniskegiatan', 1)
            ->where('validasi', 'SUDAH')
            ->where('id_student', $mhs->id_student)
            ->get();

        $dataB =  Sertifikat::where('id_jeniskegiatan', 2)
            ->where('validasi', 'SUDAH')
            ->where('id_student', $mhs->id_student)
            ->get();

        $dataC =  Sertifikat::where('id_jeniskegiatan', 3)
            ->where('validasi', 'SUDAH')
            ->where('id_student', $mhs->id_student)
            ->get();

        $dataD =  Sertifikat::where('id_jeniskegiatan', 4)
            ->where('validasi', 'SUDAH')
            ->where('id_student', $mhs->id_student)
            ->get();

        $dataE =  Sertifikat::where('id_jeniskegiatan', 5)
            ->where('validasi', 'SUDAH')
            ->where('id_student', $mhs->id_student)
            ->get();

        $pengalaman = Pengalaman::where('id_student', $mhs->id_student)
            ->where('status', 'ACTIVE')
            ->get();

        $data_baruA = [];

        for ($i = 0; $i < count($dataA); $i++) {
            $dataA[$i]->nama_kegiatan = str_replace('&', 'dan', $dataA[$i]->nama_kegiatan);
            $updatedDatePelaksanaanA =  Carbon::createFromFormat('Y-m-d', $dataA[$i]->tgl_pelaksanaan)->isoFormat('D MMMM Y');

            $dA = [
                'nama_kegiatan' => $dataA[$i]->nama_kegiatan,
                'prestasi' => $dataA[$i]->prestasi,
                'tingkat' => $dataA[$i]->tingkat,
                'tgl_pelaksanaan' => $updatedDatePelaksanaanA,
                'no' => $i + 1
            ];

            array_push($data_baruA, (object) $dA);
        }

        $data_baruB = [];

        for ($i = 0; $i < count($dataB); $i++) {
            $dataB[$i]->nama_kegiatan = str_replace('&', 'dan', $dataB[$i]->nama_kegiatan);
            $updatedDatePelaksanaanB =  Carbon::createFromFormat('Y-m-d', $dataB[$i]->tgl_pelaksanaan)->isoFormat('D MMMM Y');
            $dB = [
                'nama_kegiatanB' => $dataB[$i]->nama_kegiatan,
                'prestasiB' => $dataB[$i]->prestasi,
                'tingkatB' => $dataB[$i]->tingkat,
                'tgl_pelaksanaanB' => $updatedDatePelaksanaanB,
                'noB' => $i + 1
            ];

            array_push($data_baruB, (object) $dB);
        }

        $data_baruC = [];

        for ($i = 0; $i < count($dataC); $i++) {
            $dataC[$i]->nama_kegiatan = str_replace('&', 'dan', $dataC[$i]->nama_kegiatan);
            $updatedDatePelaksanaanC =  Carbon::createFromFormat('Y-m-d', $dataC[$i]->tgl_pelaksanaan)->isoFormat('D MMMM Y');
            $dC = [
                'nama_kegiatanC' => $dataC[$i]->nama_kegiatan,
                'prestasiC' => $dataC[$i]->prestasi,
                'tingkatC' => $dataC[$i]->tingkat,
                'tgl_pelaksanaanC' => $updatedDatePelaksanaanC,
                'noC' => $i + 1
            ];

            array_push($data_baruC, (object) $dC);
        }

        $data_baruD = [];

        for ($i = 0; $i < count($dataD); $i++) {
            $dataD[$i]->nama_kegiatan = str_replace('&', 'dan', $dataD[$i]->nama_kegiatan);
            $updatedDatePelaksanaanD =  Carbon::createFromFormat('Y-m-d', $dataD[$i]->tgl_pelaksanaan)->isoFormat('D MMMM Y');
            $dD = [
                'nama_kegiatanD' => $dataD[$i]->nama_kegiatan,
                'prestasiD' => $dataD[$i]->prestasi,
                'tingkatD' => $dataD[$i]->tingkat,
                'tgl_pelaksanaanD' => $updatedDatePelaksanaanD,
                'noD' => $i + 1
            ];

            array_push($data_baruD, (object) $dD);
        }

        $data_baruE = [];

        for ($i = 0; $i < count($dataE); $i++) {
            $dataE[$i]->nama_kegiatan = str_replace('&', 'dan', $dataE[$i]->nama_kegiatan);
            $updatedDatePelaksanaanE =  Carbon::createFromFormat('Y-m-d', $dataE[$i]->tgl_pelaksanaan)->isoFormat('D MMMM Y');
            $dE = [
                'nama_kegiatanE' => $dataE[$i]->nama_kegiatan,
                'prestasiE' => $dataE[$i]->prestasi,
                'tingkatE' => $dataE[$i]->tingkat,
                'tgl_pelaksanaanE' => $updatedDatePelaksanaanE,
                'noE' => $i + 1
            ];

            array_push($data_baruE, (object) $dE);
        }

        $data_pengalaman = [];

        for ($i = 0; $i < count($pengalaman); $i++) {
            $pengalaman[$i]->nama_pt = str_replace('&', 'dan', $pengalaman[$i]->nama_pt);

            $peng = [
                'nama_pt' => $pengalaman[$i]->nama_pt,
                'posisi' => $pengalaman[$i]->posisi,
                'tahun_masuk' => $pengalaman[$i]->tahun_masuk,
                'tahun_keluar' =>  $pengalaman[$i]->tahun_keluar,
                'noP' => $i + 1
            ];

            array_push($data_pengalaman, (object) $peng);
        }

        $template->cloneRowAndSetValues('nama_kegiatan', $data_baruA);

        $template->cloneRowAndSetValues('nama_kegiatanB', $data_baruB);

        $template->cloneRowAndSetValues('nama_kegiatanC', $data_baruC);

        $template->cloneRowAndSetValues('nama_kegiatanD', $data_baruD);

        $template->cloneRowAndSetValues('nama_kegiatanE', $data_baruE);

        $template->cloneRowAndSetValues('nama_pt', $data_pengalaman);

        $fileName = $new_name;

        $template->saveAs($fileName . '.docx');
        return response()
            ->download($fileName . '.docx')
            ->deleteFileAfterSend(true);
    }

    public function save_skpi_prodi(Request $request)
    {
        $data_student = $request->id_student;
        $data_skpi = $request->no_skpi;
        $data_ijazah = $request->no_ijazah;
        $tgl_masuk = $request->date_masuk;
        $tgl_lulus = $request->date_lulus;

        $jml_id = count($data_student);

        for ($i = 0; $i <  $jml_id; $i++) {
            $idstudent = $data_student[$i];
            $noskpi = $data_skpi[$i];
            $noijazah = $data_ijazah[$i];

            $cek = Skpi::where('id_student', $idstudent)->get();

            if (count($cek) == 0) {
                $abs = new Skpi();
                $abs->id_student = $idstudent;
                $abs->no_skpi = $noskpi;
                $abs->no_ijazah = $noijazah;
                $abs->date_masuk = $tgl_masuk;
                $abs->date_lulus = $tgl_lulus;
                $abs->save();
            } elseif (count($cek) > 0) {
                Skpi::where('id_student', $idstudent)
                    ->update([
                        'no_skpi' => $noskpi,
                        'no_ijazah' => $noijazah,
                        'date_masuk' => $tgl_masuk,
                        'date_lulus' => $tgl_lulus
                    ]);
            }
        }

        return redirect('skpi');
    }

    public function kartu_ujian_mhs()
    {
        $data = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')

            ->where('student.active', 1)
            ->select('student.nim', 'student.nama', 'student.idstudent', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan')
            ->get();

        return view('sadmin/datamahasiswa/kartu_ujian', compact('data'));
    }

    public function kartu_uts_mhs($id)
    {
        $datamhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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

    public function kartu_uas_mhs($id)
    {
        $datamhs = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
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

        $data_uts = DB::select('CALL jadwal_uas(?,?,?,?,?)', [$id, $thn->id_periodetahun, $tp->id_periodetipe, $data_kelas->id_kelas, $idprodi]);

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

    public function report_kuisioner()
    {
        $data = Kuisioner_kategori::all();

        $thn = Periode_tahun::all();

        $tp = Periode_tipe::all();

        return view('sadmin/kuisioner/report_kuisioner', compact('data', 'thn', 'tp'));
    }

    public function report_kuisioner_kategori($id)
    {
        if ($id == 1) {
            return $this->report_dospem_aka($id);
        } elseif ($id == 2) {
            return $this->report_dospem_pkl($id);
        } elseif ($id == 3) {
            return $this->report_dospem_ta($id);
        } elseif ($id == 4) {
            return $this->report_dospeng1_ta($id);
        } elseif ($id == 5) {
            return $this->report_dospeng2_ta($id);
        } elseif ($id == 6) {
            return $this->report_kuis_baak($id);
        } elseif ($id == 7) {
            return $this->report_kuis_bauk($id);
        } elseif ($id == 8) {
            return $this->report_kuis_perpus($id);
        } elseif ($id == 9) {
            return $this->report_kuis_beasiswa($id);
        }
    }

    public function report_dospem_aka($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospem_aka', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_pa(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospem_aka', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_pa(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pa(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('sadmin/kuisioner/detail_kuisioner_dsn_pa', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_pa(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospem_aka', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing Akademik' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_pa(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pa(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_pa', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing Akademik' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospem_pkl($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pkl(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospem_pkl', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_pkl(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pkl(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospem_pkl', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_pkl(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pkl(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('sadmin/kuisioner/detail_kuisioner_dsn_pkl', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_pkl(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_pkl(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospem_pkl', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing PKL' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_pkl(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_pkl(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_pkl', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing PKL' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospem_ta($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospem_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_ta(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospem_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_ta(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('sadmin/kuisioner/detail_kuisioner_dsn_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_ta(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospem_ta', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing TA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_ta(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Pembimbing TA' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospeng1_ta($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji1_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospeng1_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_peng1_ta(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji1_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospeng1_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_peng1_ta(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji1_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('sadmin/kuisioner/detail_kuisioner_dsn_peng1_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_peng1_ta(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji1_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospeng1_ta', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 1 TA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_peng1_ta(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji1_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_peng1_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 1 TA' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_dospeng2_ta($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji2_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospeng2_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_dsn_peng2_ta(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji2_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_dospeng2_ta', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_dsn_peng2_ta(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji2_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        return view('sadmin/kuisioner/detail_kuisioner_dsn_peng2_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_dsn_peng2_ta(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_dsn_penguji2_ta(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_dospeng2_ta', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 2 TA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_dsn_peng2_ta(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $iddosen = $request->id_dosen;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Dosen::where('iddosen', $iddosen)->first();
        $nama_dosen = $dosen->nama;

        $data = DB::select('CALL detail_kuisioner_dsn_penguji2_ta(?,?,?)', [$iddosen, $idperiodetahun, $idperiodetipe]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_dsn_peng2_ta', compact('data', 'nama_dosen', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner Dosen Penguji 2 TA' . ' ' . $nama_dosen . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_baak($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_baak', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_baak(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_baak', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_baak(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('sadmin/kuisioner/detail_kuisioner_baak', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_baak(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_baak', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAAK' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_baak(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_baak(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_baak', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAAK' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_bauk($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_bauk', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_bauk(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_bauk', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_bauk(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('sadmin/kuisioner/detail_kuisioner_bauk', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_bauk(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_bauk', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAUK' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_bauk(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_bauk(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_bauk', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BAUK' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_perpus($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_perpus', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_perpus(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_perpus', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_perpus(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('sadmin/kuisioner/detail_kuisioner_perpus', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_perpus(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_perpus', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner PERPUS' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_perpus(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_perpus(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_perpus', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner PERPUS' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function report_kuis_beasiswa($id)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_beasiswa', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function post_report_kuisioner_beasiswa(Request $request)
    {
        $data_prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $data_prd_tp = Periode_tipe::all();

        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        return view('sadmin/kuisioner/report_kuisioner_beasiswa', compact('data_prd_tp', 'data_prd_thn', 'id', 'data', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe'));
    }

    public function detail_kuisioner_beasiswa(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        return view('sadmin/kuisioner/detail_kuisioner_beasiswa', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'));
    }

    public function download_kuisioner_beasiswa(Request $request)
    {
        $id = $request->id_kategori_kuisioner;
        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data = DB::select('CALL kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $id]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_report_kuisioner_beasiswa', compact('data', 'namaperiodetahun', 'namaperiodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BEASISWA' . ' ' . $namaperiodetahun . ' ' . $namaperiodetipe . '.pdf');
    }

    public function download_detail_kuisioner_beasiswa(Request $request)
    {
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;
        $idprodi = $request->id_prodi;
        $periodetahun = $request->periodetahun;
        $periodetipe = $request->periodetipe;

        $dosen = Prodi::where('id_prodi', $idprodi)->first();
        $nama_prodi = $dosen->prodi;

        $data = DB::select('CALL detail_kuisioner_beasiswa(?,?,?)', [$idperiodetahun, $idperiodetipe, $idprodi]);

        $pdf = PDF::loadView('sadmin/kuisioner/pdf_detail_kuisioner_beasiswa', compact('data', 'nama_prodi', 'periodetahun', 'periodetipe'))->setPaper('a4', 'potrait');
        return $pdf->download('Report Kuisioner BEASISWA' . ' ' . $nama_prodi . ' ' . $periodetahun . ' ' . $periodetipe . '.pdf');
    }

    public function soal_uts_uas()
    {
        $data = Kurikulum_periode::join('periode_tipe', 'kurikulum_periode.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('periode_tahun', 'kurikulum_periode.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('prodi', 'kurikulum_periode.id_prodi', '=', 'prodi.id_prodi')
            ->join('kelas', 'kurikulum_periode.id_kelas', '=', 'kelas.idkelas')
            ->join('semester', 'kurikulum_periode.id_semester', '=', 'semester.idsemester')
            ->leftjoin('soal_ujian', 'kurikulum_periode.id_kurperiode', '=', 'soal_ujian.id_kurperiode')
            ->leftjoin('dosen', 'kurikulum_periode.id_dosen', '=', 'dosen.iddosen')
            ->where('periode_tahun.status', 'ACTIVE')
            ->where('periode_tipe.status', 'ACTIVE')
            ->where('kurikulum_periode.status', 'ACTIVE')
            ->select('kurikulum_periode.id_kurperiode', 'matakuliah.kode', 'matakuliah.makul', 'prodi.prodi', 'kelas.kelas', 'semester.semester', 'soal_ujian.soal_uts', 'soal_ujian.soal_uas', 'dosen.nama')
            ->orderBy('prodi.prodi', 'asc')
            ->orderBy('kelas.kelas', 'asc')
            ->orderBy('matakuliah.kode', 'asc')
            ->get();

        return view('sadmin/soal/soal_ujian', compact('data'));
    }

    public function master_kurikulum_standar()
    {
        $data = Kurikulum_transaction::join('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
            ->join('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
            ->join('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
            ->join('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
            ->join('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
            ->where('kurikulum_transaction.status', 'ACTIVE')
            ->get();

        $kurikulum = Kurikulum_master::all();
        $prodi = Prodi::all();
        $semester = Semester::all();
        $angkatan = Angkatan::orderBy('angkatan', 'desc')->get();

        return view('sadmin/kurikulum/standar_kurikulum', compact('kurikulum', 'prodi', 'semester', 'angkatan'));
    }

    public function lihat_kurikulum_standar(Request $request)
    {
        $kurikulum = Kurikulum_master::all();
        $prodi = Prodi::all();
        $angkatan = Angkatan::orderBy('idangkatan', 'DESC')->get();
        $semester = Semester::all();

        $idkurikulum = $request->id_kurikulum;
        $idprodi = $request->id_prodi;
        $idangkatan = $request->id_angkatan;
        $idsemester = $request->id_semester;
        $status = $request->status;
        $paket = $request->pelaksanaan_paket;

        $krlm = Kurikulum_master::where('id_kurikulum', $idkurikulum)->first();
        $prd = Prodi::where('id_prodi', $idprodi)->first();
        $angk = Angkatan::where('idangkatan', $idangkatan)->first();
        $smtr = Semester::where('idsemester', $idsemester)->first();

        if ($idsemester != null) {
            $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
                ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
                ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
                ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
                ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                ->where('kurikulum_transaction.id_kurikulum', $idkurikulum)
                ->where('kurikulum_transaction.id_prodi', $idprodi)
                ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                ->where('kurikulum_transaction.id_semester', $idsemester)
                ->where('kurikulum_transaction.status', $status)
                ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
                ->orderBy('semester.semester', 'ASC')
                ->orderBy('matakuliah.kode', 'ASC')
                ->select('kurikulum_transaction.idkurtrans', 'kurikulum_transaction.id_kurikulum', 'kurikulum_transaction.id_prodi', 'kurikulum_transaction.id_kurikulum', 'kurikulum_transaction.id_semester', 'kurikulum_transaction.id_angkatan', 'kurikulum_transaction.id_makul', 'kurikulum_transaction.pelaksanaan_paket', 'kurikulum_transaction.validasi', 'kurikulum_transaction.status', 'kurikulum_master.nama_kurikulum', 'prodi.prodi', 'angkatan.angkatan', 'semester.semester', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                ->get();

            $sks = 0;
            foreach ($data as $keysks) {
                $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
            }
        } elseif ($idsemester == null) {
            $data = Kurikulum_transaction::leftjoin('kurikulum_master', 'kurikulum_transaction.id_kurikulum', '=', 'kurikulum_master.id_kurikulum')
                ->leftjoin('prodi', 'kurikulum_transaction.id_prodi', '=', 'prodi.id_prodi')
                ->leftjoin('angkatan', 'kurikulum_transaction.id_angkatan', '=', 'angkatan.idangkatan')
                ->leftjoin('semester', 'kurikulum_transaction.id_semester', '=', 'semester.idsemester')
                ->leftjoin('matakuliah', 'kurikulum_transaction.id_makul', '=', 'matakuliah.idmakul')
                ->where('kurikulum_transaction.id_kurikulum', $idkurikulum)
                ->where('kurikulum_transaction.id_prodi', $idprodi)
                ->where('kurikulum_transaction.id_angkatan', $idangkatan)
                ->where('kurikulum_transaction.status', $status)
                ->where('kurikulum_transaction.pelaksanaan_paket', $paket)
                ->orderBy('semester.semester', 'ASC')
                ->orderBy('matakuliah.kode', 'ASC')
                ->select('kurikulum_transaction.idkurtrans', 'kurikulum_transaction.id_kurikulum', 'kurikulum_transaction.id_prodi', 'kurikulum_transaction.id_kurikulum', 'kurikulum_transaction.id_semester', 'kurikulum_transaction.id_angkatan', 'kurikulum_transaction.id_makul', 'kurikulum_transaction.pelaksanaan_paket', 'kurikulum_transaction.validasi', 'kurikulum_transaction.status', 'kurikulum_master.nama_kurikulum', 'prodi.prodi', 'angkatan.angkatan', 'semester.semester', 'matakuliah.makul', 'matakuliah.kode', 'matakuliah.akt_sks_teori', 'matakuliah.akt_sks_praktek')
                ->get();

            $sks = 0;
            foreach ($data as $keysks) {
                $sks += $keysks->akt_sks_teori + $keysks->akt_sks_praktek;
            }
        }

        return view('sadmin/kurikulum/lihat_standar_kurikulum', compact('sks', 'data', 'kurikulum', 'prodi', 'angkatan', 'semester', 'krlm', 'prd', 'angk', 'smtr', 'status', 'paket'));
    }

    public function master_yudisium()
    {
        $data = Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->where('student.active', 1)
            ->select('yudisium.id_yudisium', 'yudisium.nama_lengkap', 'yudisium.tmpt_lahir', 'yudisium.tgl_lahir', 'yudisium.nik', 'student.nim', 'prodi.prodi', 'yudisium.id_student', 'yudisium.file_ijazah', 'yudisium.file_ktp', 'yudisium.file_foto', 'yudisium.validasi')
            ->get();

        return view('sadmin/masterakademik/master_yudisium', compact('data'));
    }

    public function validate_yudisium($id)
    {
        Yudisium::where('id_yudisium', $id)->update(['validasi' => 'SUDAH']);

        Alert::success('', 'Data Yudisium berhasil divalidasi')->autoclose(3500);
        return redirect()->back();
    }

    public function unvalidate_yudisium($id)
    {
        Yudisium::where('id_yudisium', $id)->update(['validasi' => 'BELUM']);

        Alert::success('', 'Data Yudisium batal divalidasi')->autoclose(3500);
        return redirect()->back();
    }

    public function saveedit_yudisium(Request $request, $id)
    {
        $prd = Yudisium::find($id);
        $prd->nama_lengkap = $request->nama_lengkap;
        $prd->tmpt_lahir = $request->tmpt_lahir;
        $prd->tgl_lahir = $request->tgl_lahir;
        $prd->nik = $request->nik;
        $prd->updated_by = Auth::user()->name;
        $prd->save();

        Alert::success('', 'Data Yudisium berhasil diedit')->autoclose(3500);
        return redirect()->back();
    }

    public function unduh_ijazah($id)
    {
        $data_mhs = Yudisium::join('student', 'yudisium.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->leftjoin('transkrip_final', 'yudisium.id_student', '=', 'transkrip_final.id_student')
            ->where('student.active', 1)
            ->where('yudisium.id_yudisium', $id)
            ->select(
                'yudisium.nama_lengkap',
                'student.nim',
                'yudisium.tmpt_lahir',
                'yudisium.tgl_lahir',
                'yudisium.nik',
                'prodi.prodi',
                'prodi.study_year',
                'prodi.kodeprodi',
                'transkrip_final.no_ijazah',
                'transkrip_final.tgl_yudisium',
                'transkrip_final.tgl_wisuda'
            )
            ->first();

        //no ijazah
        $no_ijazah = $data_mhs->no_ijazah;
        //nama mahasiswa
        $nama = $data_mhs->nama_lengkap;
        $ubah_nama = strtolower($nama);
        $new_nama = ucwords($ubah_nama);
        //tempat lahir
        $tmptlahir = $data_mhs->tmpt_lahir;
        //tanggal lahir
        $tgllahir = $data_mhs->tgl_lahir->isoFormat('D MMMM Y');
        //nik
        $nik = $data_mhs->nik;
        //nim
        $nim = $data_mhs->nim;
        //jenjang mahasiswa
        if ($data_mhs->study_year == 3) {
            $jenjang = 'Diploma III (D-III)';
        } elseif ($data_mhs->study_year == 4) {
            $jenjang = 'Diploma IV (D-IV)';
        }
        //prodi mhs
        $prodi = $data_mhs->prodi;
        //gelar mahasiswa
        if ($data_mhs->kodeprodi == 22) {
            $gelar = 'Ahli Madya Teknik (A.Md.T.)';
        } elseif ($data_mhs->kodeprodi == 23) {
            $gelar = 'Ahli Madya Teknik (A.Md.T.)';
        } elseif ($data_mhs->kodeprodi == 24) {
            $gelar = 'Ahli Madya Farmasi (A.Md.Farm.)';
        } elseif ($data_mhs->kodeprodi == 25) {
            $gelar = 'Sarjana Terapan Komputer (S.Tr.Kom.)';
        }

        if ($data_mhs->tgl_yudisium == null) {
            $yudisium = '2001-01-01';
        } elseif ($data_mhs->tgl_yudisium != null) {
            $yudisium = $data_mhs->tgl_yudisium;
        }

        if ($data_mhs->tgl_wisuda == null) {
            $wisuda = '2001-01-01';
        } elseif ($data_mhs->tgl_wisuda != null) {
            $wisuda = $data_mhs->tgl_wisuda;
        }


        $pisahyudi = explode('-', $yudisium);

        $pisahwisu = explode('-', $wisuda);

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

        $blnyudi = $bulan[$pisahyudi[1]];
        $blnwisu = $bulan[$pisahwisu[1]];
        //tanggal yudisium
        $tglyudisium = $pisahyudi[2] . ' ' . $blnyudi . ' ' . $pisahyudi[0];
        //tanggal wisuda
        $tglwisuda = $pisahwisu[2] . ' ' . $blnwisu . ' ' . $pisahwisu[0];


        $template = new TemplateProcessor('word-template/Template Ijazah 2022 New.docx');

        $template->setValue('nama', $new_nama);
        $template->setValue('no_ijazah', $no_ijazah);
        $template->setValue('nim', $nim);
        $template->setValue('nik', $nik);
        $template->setValue('tgllahir', $tgllahir);
        $template->setValue('tmptlahir', $tmptlahir);
        $template->setValue('prodi', $prodi);
        $template->setValue('yudisium', $tglyudisium);
        $template->setValue('wisuda', $tglwisuda);
        $template->setValue('jenjang', $jenjang);
        $template->setValue('gelar', $gelar);


        $fileName =  $new_nama . ' ' . $nim . ' ' . $prodi;

        $template->saveAs($fileName . '.docx');
        return response()
            ->download($fileName . '.docx')
            ->deleteFileAfterSend(true);
    }

    public function master_wisuda()
    {
        $prodi = Prodi::all();

        $data  = Wisuda::join('student', 'wisuda.id_student', '=', 'student.idstudent')
            ->leftjoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->where('student.active', 1)
            ->select(
                'wisuda.id_wisuda',
                'wisuda.nama_lengkap',
                'wisuda.nim',
                'wisuda.tahun_lulus',
                'wisuda.ukuran_toga',
                'wisuda.no_hp',
                'wisuda.email',
                'wisuda.nik',
                'wisuda.alamat_ktp',
                'wisuda.alamat_domisili',
                'wisuda.nama_ayah',
                'wisuda.nama_ibu',
                'wisuda.no_hp_ayah',
                'wisuda.no_hp_ibu',
                'wisuda.alamat_ortu',
                'wisuda.status_vaksin',
                'wisuda.file_vaksin',
                'wisuda.npwp',
                'wisuda.validasi',
                'wisuda.id_student',
                'wisuda.id_prodi',
                'prodi.prodi',
                'kelas.kelas'
            )
            ->get();

        return view('sadmin/masterakademik/master_wisuda', compact('data', 'prodi'));
    }

    public function saveedit_wisuda(Request $request, $id)
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
                'nik'               => 'required',
                'npwp'              => 'required',
                'alamat_ktp'        => 'required',
                'alamat_domisili'   => 'required',
                'nama_ayah'         => 'required',
                'nama_ibu'          => 'required',
                'no_hp_ayah'        => 'required',
                'alamat_ortu'       => 'required',
                'file_vaksin'       => 'mimes:jpg,jpeg,JPG,JPEG|max:4000'
            ],
            $message,
        );

        $bap = Wisuda::find($id);
        $bap->id_student = $request->id_student;
        $bap->ukuran_toga = $request->ukuran_toga;
        $bap->status_vaksin = $request->status_vaksin;
        $bap->tahun_lulus       = $request->tahun_lulus;
        $bap->nim               = $request->nim;
        $bap->nama_lengkap      = $request->nama_lengkap;
        $bap->id_prodi          = $request->id_prodi;
        $bap->no_hp             = $request->no_hp;
        $bap->email             = $request->email;
        $bap->nik               = $request->nik;
        $bap->npwp              = $request->npwp;
        $bap->alamat_ktp        = $request->alamat_ktp;
        $bap->alamat_domisili   = $request->alamat_domisili;
        $bap->nama_ayah         = $request->nama_ayah;
        $bap->nama_ibu          = $request->nama_ibu;
        $bap->no_hp_ayah        = $request->no_hp_ayah;
        $bap->no_hp_ibu         = $request->no_hp_ibu;
        $bap->alamat_ortu       = $request->alamat_ortu;

        if ($bap->file_vaksin) {
            if ($request->hasFile('file_vaksin')) {
                File::delete('File Vaksin/' . $request->id_student . '/' . $bap->file_vaksin);
                $file = $request->file('file_vaksin');
                $nama_file = 'File Vaksin' .  '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Vaksin/' . $request->id_student;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_vaksin = $nama_file;
            }
        } else {
            if ($request->hasFile('file_vaksin')) {
                $file = $request->file('file_vaksin');
                $nama_file = 'File Vaksin' .  '-' . $file->getClientOriginalName();
                $tujuan_upload = 'File Vaksin/' . $request->id_student;
                $file->move($tujuan_upload, $nama_file);
                $bap->file_vaksin = $nama_file;
            }
        }

        $bap->save();

        Alert::success('', 'Data Wisuda berhasil diedit')->autoclose(3500);
        return redirect('master_wisuda');
    }

    public function validate_wisuda($id)
    {
        Wisuda::where('id_wisuda', $id)->update(['validasi' => 'SUDAH']);

        Alert::success('', 'Data Wisuda berhasil divalidasi')->autoclose(3500);
        return redirect()->back();
    }

    public function unvalidate_wisuda($id)
    {
        Wisuda::where('id_wisuda', $id)->update(['validasi' => 'BELUM']);

        Alert::success('', 'Data Wisuda batal divalidasi')->autoclose(3500);
        return redirect()->back();
    }

    public function master_kodeprausta()
    {
        $data = Prausta_master_kode::join('prodi', 'prausta_master_kode.id_prodi', '=', 'prodi.id_prodi')
            ->select('prausta_master_kode.*', 'prodi.prodi')
            ->get();

        return view('sadmin/prausta/prausta_master_kode', compact('data'));
    }

    public function master_kategoriprausta()
    {
        $data = Prausta_master_kategori::join('prodi', 'prausta_master_kategori.id_prodi', '=', 'prodi.id_prodi')
            ->select('prausta_master_kategori.*', 'prodi.prodi')
            ->get();

        return view('sadmin/prausta/prausta_master_kategori', compact('data'));
    }

    public function master_prakerin()
    {
        $prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data_krs = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            // ->where('student.active', 1)
            ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
            ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [135, 177, 180, 205, 235, 281])
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/prausta/master_prakerin', compact('data_krs', 'namaperiodetahun', 'namaperiodetipe', 'prd_thn', 'prd_tp'));
    }

    public function filter_master_prakerin(Request $request)
    {
        $prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data_krs = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [1, 2, 3])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            // ->where('student.active', 1)
            ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
            ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [135, 177, 180, 205, 235, 281])
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/prausta/master_prakerin', compact('data_krs', 'namaperiodetahun', 'namaperiodetipe', 'prd_thn', 'prd_tp'));
    }

    public function master_sempro()
    {
        $prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data_krs = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            // ->where('student.active', 1)
            ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
            ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [136, 178, 179, 206, 286, 316])
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/prausta/master_sempro', compact('data_krs', 'namaperiodetahun', 'namaperiodetipe', 'prd_thn', 'prd_tp'));
    }

    public function filter_master_sempro(Request $request)
    {
        $prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data_krs = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [4, 5, 6])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            // ->where('student.active', 1)
            ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
            ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [136, 178, 179, 206, 286, 316])
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/prausta/master_sempro', compact('data_krs', 'namaperiodetahun', 'namaperiodetipe', 'prd_thn', 'prd_tp'));
    }

    public function master_ta()
    {
        $prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('status', 'ACTIVE')->first();
        $periodetipe = Periode_tipe::where('status', 'ACTIVE')->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data_krs = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            // ->where('student.active', 1)
            ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
            ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [136, 178, 179, 206, 286, 316])
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/prausta/master_ta', compact('data_krs', 'namaperiodetahun', 'namaperiodetipe', 'prd_thn', 'prd_tp'));
    }

    public function filter_master_ta(Request $request)
    {
        $prd_thn = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $prd_tp = Periode_tipe::all();

        $periodetahun = Periode_tahun::where('id_periodetahun', $request->id_periodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $request->id_periodetipe)->first();

        $idperiodetahun = $periodetahun->id_periodetahun;
        $idperiodetipe = $periodetipe->id_periodetipe;
        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $data_krs = Student_record::join('kurikulum_periode', 'student_record.id_kurperiode', '=', 'kurikulum_periode.id_kurperiode')
            ->join('matakuliah', 'kurikulum_periode.id_makul', '=', 'matakuliah.idmakul')
            ->join('student', 'student_record.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->join('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
            ->leftjoin('prausta_trans_bimbingan', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_bimbingan.id_settingrelasi_prausta')
            ->leftjoin('prausta_trans_hasil', 'prausta_setting_relasi.id_settingrelasi_prausta', '=', 'prausta_trans_hasil.id_settingrelasi_prausta')
            ->whereIn('prausta_setting_relasi.id_masterkode_prausta', [7, 8, 9])
            ->where('prausta_setting_relasi.status', 'ACTIVE')
            // ->where('student.active', 1)
            ->where('kurikulum_periode.id_periodetahun', $idperiodetahun)
            ->where('kurikulum_periode.id_periodetipe', $idperiodetipe)
            ->where('student_record.status', 'TAKEN')
            ->whereIn('matakuliah.idmakul', [136, 178, 179, 206, 286, 316])
            ->select(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                DB::raw('COUNT(prausta_trans_bimbingan.id_settingrelasi_prausta) as jml_bim'),
                'prausta_trans_hasil.nilai_huruf'
            )
            ->groupBy(
                'prausta_setting_relasi.id_settingrelasi_prausta',
                'student.nama',
                'student.nim',
                'student.idstudent',
                'prodi.prodi',
                'kelas.kelas',
                'angkatan.angkatan',
                'prausta_setting_relasi.file_laporan_revisi',
                'prausta_setting_relasi.validasi_baak',
                'prausta_trans_bimbingan.validasi_baak',
                'prausta_trans_hasil.nilai_huruf'
            )
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/prausta/master_ta', compact('data_krs', 'namaperiodetahun', 'namaperiodetipe', 'prd_thn', 'prd_tp'));
    }

    public function cek_master_prakerin($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select('prausta_trans_bimbingan.id_transbimb_prausta', 'prausta_trans_bimbingan.tanggal_bimbingan', 'prausta_trans_bimbingan.file_bimbingan', 'prausta_trans_bimbingan.remark_bimbingan', 'prausta_trans_bimbingan.validasi', 'prausta_trans_bimbingan.komentar_bimbingan', 'student.idstudent')
            ->get();

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'student.idstudent', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.dosen_pembimbing', 'dosen.akademik')
            ->first();

        return view('sadmin/prausta/cek_master_prakerin', compact('data', 'mhs'));
    }

    public function cek_master_sempro($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select('prausta_trans_bimbingan.id_transbimb_prausta', 'prausta_trans_bimbingan.tanggal_bimbingan', 'prausta_trans_bimbingan.file_bimbingan', 'prausta_trans_bimbingan.remark_bimbingan', 'prausta_trans_bimbingan.validasi', 'prausta_trans_bimbingan.komentar_bimbingan', 'student.idstudent')
            ->get();

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'student.idstudent', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.dosen_pembimbing', 'dosen.akademik')
            ->first();

        return view('sadmin/prausta/cek_master_sempro', compact('data', 'mhs'));
    }

    public function cek_master_ta($id)
    {
        $data = Prausta_trans_bimbingan::join('prausta_setting_relasi', 'prausta_trans_bimbingan.id_settingrelasi_prausta', '=', 'prausta_setting_relasi.id_settingrelasi_prausta')
            ->join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->where('prausta_trans_bimbingan.id_settingrelasi_prausta', $id)
            ->select('prausta_trans_bimbingan.id_transbimb_prausta', 'prausta_trans_bimbingan.tanggal_bimbingan', 'prausta_trans_bimbingan.file_bimbingan', 'prausta_trans_bimbingan.remark_bimbingan', 'prausta_trans_bimbingan.validasi', 'prausta_trans_bimbingan.komentar_bimbingan', 'student.idstudent')
            ->get();

        $mhs = Prausta_setting_relasi::join('student', 'prausta_setting_relasi.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->leftjoin('dosen', 'prausta_setting_relasi.id_dosen_pembimbing', '=', 'dosen.iddosen')
            ->where('prausta_setting_relasi.id_settingrelasi_prausta', $id)
            ->select('student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan', 'prausta_setting_relasi.file_draft_laporan', 'prausta_setting_relasi.file_laporan_revisi', 'student.idstudent', 'prausta_setting_relasi.id_settingrelasi_prausta', 'prausta_setting_relasi.dosen_pembimbing', 'dosen.akademik')
            ->first();

        return view('sadmin/prausta/cek_master_ta', compact('data', 'mhs'));
    }

    public function export_data_akm()
    {
        $tahun = Periode_tahun::orderBy('periode_tahun', 'DESC')->get();
        $tipe = Periode_tipe::whereIn('id_periodetipe', [1, 2, 3])->get();
        $prodi = Prodi::all();

        return view('sadmin/export/data_akm', compact('tahun', 'tipe', 'prodi'));
    }

    public function filter_export_akm(Request $request)
    {
        $idprodi = $request->id_prodi;
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;

        $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
        $prodi = Prodi::where('id_prodi', $idprodi)->first();

        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;
        $namaprodi = $prodi->prodi;

        $data_akm = DB::select('CALL data_akm(?,?,?)', [$idprodi, $idperiodetahun, $idperiodetipe]);

        return view('sadmin/export/hasil_akm', compact('data_akm', 'idprodi', 'idperiodetahun', 'idperiodetipe', 'namaperiodetahun', 'namaperiodetipe', 'namaprodi'));
    }

    public function export_data_akm_xls(Request $request)
    {
        $idprodi = $request->id_prodi;
        $idperiodetahun = $request->id_periodetahun;
        $idperiodetipe = $request->id_periodetipe;

        $periodetahun = Periode_tahun::where('id_periodetahun', $idperiodetahun)->first();
        $periodetipe = Periode_tipe::where('id_periodetipe', $idperiodetipe)->first();
        $prodi = Prodi::where('id_prodi', $idprodi)->first();

        $namaperiodetahun = $periodetahun->periode_tahun;
        $namaperiodetipe = $periodetipe->periode_tipe;

        $ganti_tahun = str_replace('/', '_', $namaperiodetahun);
        $namaprodi = $prodi->prodi;

        $nama_file = 'Data AKM Mahasiswa' . ' ' . $ganti_tahun . ' ' . $namaperiodetipe . ' ' . $namaprodi . '.xlsx';

        return Excel::download(new DataAkmMhsExport($idprodi, $idperiodetahun, $idperiodetipe), $nama_file);
    }

    public function summary_krs()
    {
        $data = DB::select('CALL summary_krs()');

        return view('sadmin/master_krs/data_rekap_krs', compact('data'));
    }

    public function record_pembayaran_mahasiswa()
    {
        $data1 = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->select('student.idstudent', 'student.nama', 'student.nim', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi')
            ->whereIn('student.active', [1, 5])
            ->orderBy('student.nim', 'ASC')
            ->get();

        $data = DB::select('CALL data_pembayaran_mhs()');

        return view('sadmin/pembayaran/data_pembayaran', compact('data'));
    }

    public function detail_pembayaran_mhs_admin($id)
    {
        $mhs = Student::join('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'angkatan.angkatan', 'kelas.kelas', 'prodi.prodi')
            ->first();

        $data = DB::select('CALL detail_pembayaran_mhs(?)', [$id]);

        $detail_beasiswa = DB::select('CALL detail_beasiswa_mhs(?)', [$id]);

        foreach ($detail_beasiswa as $key_beasiswa) {
            # code...
        }

        $total_byr_mhs = DB::select('CALL detail_totalbayar_mhs(?)', [$id]);

        foreach ($total_byr_mhs as $key_total) {
            # code...
        }

        return view('sadmin/pembayaran/detail_pembayaran', compact('data', 'mhs', 'key_beasiswa', 'key_total'));
    }

    public function record_sertifikat_mahasiswa()
    {
        $data = Sertifikat::join('student', 'sertifikat.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.active', 1)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi', DB::raw('COUNT(sertifikat.id_student) as jml_sertifikat'))
            ->groupBy('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi')
            ->orderBy('student.nim', 'ASC')
            ->get();

        return view('sadmin/datamahasiswa/data_sertifikat', compact('data'));
    }

    public function cek_sertifikat($id)
    {
        $mhs = Student::leftjoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.idstudent', $id)
            ->select('student.idstudent', 'student.nama', 'student.nim', 'kelas.kelas', 'prodi.prodi')
            ->first();

        $data = Sertifikat::leftjoin('jenis_kegiatan', 'sertifikat.id_jeniskegiatan', '=', 'jenis_kegiatan.id_jeniskegiatan')
            ->where('sertifikat.id_student', $id)
            ->select('sertifikat.*', 'jenis_kegiatan.deskripsi')
            ->get();

        $jenis = Jenis_kegiatan::where('status', 'ACTIVE')->get();

        return view('sadmin/datamahasiswa/cek_sertifikat', compact('mhs', 'data', 'jenis'));
    }

    public function save_jenis_sertifikat(Request $request)
    {
        $jenis_kegiatan = $request->id_jeniskegiatan;
        $jml_jenis = count($jenis_kegiatan);

        for ($i = 0; $i < $jml_jenis; $i++) {
            $id_jenis = $jenis_kegiatan[$i];
            if ($id_jenis != null) {
                $idj = explode(',', $id_jenis, 2);
                $tra = $idj[0];
                $trs = $idj[1];

                Sertifikat::where('id_sertifikat', $tra)
                    ->update(['id_jeniskegiatan' => $trs]);
            }
        }

        Alert::success('', 'Jenis Kegiatan berhasil ditambahkan')->autoclose(3500);
        return redirect('record_sertifikat_mahasiswa');
    }

    public function setting_waktu()
    {
        $data = Waktu::all();
        $date = date('Y-m-d');

        return view('sadmin/waktu/setting_waktu', compact('data', 'date'));
    }

    public function post_waktu(Request $request)
    {
        $kpr = new Waktu();
        $kpr->tipe_waktu = $request->tipe_waktu;
        $kpr->deskripsi = $request->deskripsi;
        $kpr->waktu_awal = $request->waktu_awal;
        $kpr->waktu_akhir = $request->waktu_akhir;
        $kpr->created_by = Auth::user()->name;
        $kpr->save();

        Alert::success('Berhasil')->autoclose(3500);
        return redirect()->back();
    }

    public function tutup_yudisium($id)
    {
        Waktu::where('id_waktu', $id)->update([
            'status' => '0'
        ]);

        return redirect()->back();
    }

    public function standar_pendidikan_nasional()
    {
        $data = Standar::where('status', 'ACTIVE')->get();

        return view('sadmin/masterakademik/master_standar', compact('data'));
    }

    public function save_standar_pendidikan_nasional(Request $request)
    {
        $this->validate($request, [
            'nama_standar' => 'required',
            'file_sop' => 'mimes:pdf,docx,PDF,DOCX|max:10000',
            'nama_sop' => 'required'
        ]);

        $info = new Standar();
        $info->nama_standar = $request->nama_standar;
        $info->nama_sop = $request->nama_sop;

        if ($request->hasFile('file_sop')) {
            $file = $request->file('file_sop');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $tujuan_upload = 'Standar' . '/' . $request->nama_standar;
            $file->move($tujuan_upload, $nama_file);
            $info->file_sop = $nama_file;
        }

        $info->save();
        Alert::success('', 'Standar berhasil ditambahkan')->autoclose(3500);
        return redirect('standar_pendidikan_nasional');
    }

    public function put_standar_pendidikan_nasional(Request $request, $id)
    {
        $this->validate($request, [
            'nama_standar' => 'required',
            'file_sop' => 'mimes:pdf,docx,PDF,DOCX|max:10000',
            'nama_sop' => 'required'
        ]);

        $info = Standar::find($id);
        $info->nama_standar = $request->nama_standar;
        $info->nama_sop = $request->nama_sop;

        if ($info->file_sop) {
            if ($request->hasFile('file_sop')) {
                File::delete('Standar/' . $request->nama_standar . '/' . $info->file_sop);

                $file = $request->file('file_sop');
                $nama_file = time() . '_' . $file->getClientOriginalName();
                $tujuan_upload = 'Standar' . '/' . $request->nama_standar;
                $file->move($tujuan_upload, $nama_file);
                $info->file_sop = $nama_file;
            }
        } else {
            if ($request->hasFile('file_sop')) {
                $file = $request->file('file_sop');

                $nama_file = time() . '_' . $file->getClientOriginalName();
                $tujuan_upload = 'Standar' . '/' . $request->nama_standar;
                $file->move($tujuan_upload, $nama_file);
                $info->file_sop = $nama_file;
            }
        }

        $info->save();

        Alert::success('', 'Standar berhasil diedit')->autoclose(3500);
        return redirect('standar_pendidikan_nasional');
    }

    public function hapus_standar_pendidikan_nasional($id)
    {
        // hapus data
        Standar::where('id_standar', $id)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Standar berhasil dihapus')->autoclose(3500);
        return redirect()->back();
    }

    public function jenis_kegiatan()
    {
        $data = Jenis_kegiatan::where('status', 'ACTIVE')->get();

        return view('sadmin/masterakademik/master_jenis_kegiatan', compact('data'));
    }

    public function simpan_jeniskegiatan(Request $request)
    {
        $ang = new Jenis_kegiatan();
        $ang->deskripsi = $request->deskripsi;
        $ang->created_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Jenis Kegiatan berhasil ditambahkan')->autoclose(3500);
        return redirect('jenis_kegiatan');
    }

    public function put_jeniskegiatan(Request $request, $id)
    {
        $ang = Jenis_kegiatan::find($id);
        $ang->deskripsi = $request->deskripsi;
        $ang->updated_by = Auth::user()->name;
        $ang->save();

        Alert::success('', 'Jenis Kegiatan berhasil diedit')->autoclose(3500);
        return redirect('jenis_kegiatan');
    }

    public function hapus_jeniskegiatan($id)
    {
        Jenis_kegiatan::where('id_jeniskegiatan', $id)->update(['status' => 'NOT ACTIVE']);

        Alert::success('', 'Jenis Kegiatan berhasil dihapus')->autoclose(3500);
        return redirect('jenis_kegiatan');
    }

    public function pengalaman_kerja_mahasiswa()
    {
        $data = Pengalaman::leftjoin('student', 'pengalaman_kerja.id_student', '=', 'student.idstudent')
            ->leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('pengalaman_kerja.status', 'ACTIVE')
            ->select(DB::raw('COUNT(pengalaman_kerja.id_student) as jml_pengalaman'), 'pengalaman_kerja.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan')
            ->groupBy('pengalaman_kerja.id_student', 'student.nama', 'student.nim', 'prodi.prodi', 'kelas.kelas', 'angkatan.angkatan')
            ->get();

        return view('sadmin/datamahasiswa/pengalaman_kerja', compact('data'));
    }

    public function detail_pengalaman($id)
    {
        $mhs = Student::leftJoin('prodi', (function ($join) {
                $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
                    ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
            }))
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.idstudent', $id)
            ->first();

        $data = Pengalaman::where('id_student', $id)->get();

        return view('sadmin/datamahasiswa/detail_pengalaman_kerja', compact('data', 'mhs'));
    }
}
