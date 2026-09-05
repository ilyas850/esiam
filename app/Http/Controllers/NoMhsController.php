<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NoMhsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function get_new_user($id)
  {
    // Arahkan langsung ke home karena form onboarding ganti password sudah terintegrasi di home
    if (Auth::id() == $id && Auth::user()->role == 4) {
      return redirect('home');
    }

    $id_student = Auth::user()->id_user;
    $mhs = Student::leftJoin('prodi', function ($join) {
      $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')
        ->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
    })
      ->where('student.idstudent', $id_student)
      ->select('student.nama', 'student.nim', 'prodi.prodi')
      ->first();

    return view('mhs/new_pwd', ['id' => $id, 'mhs' => $mhs]);
  }

  public function store_new_user(Request $request, $id)
  {
    // Pastikan user hanya dapat mengubah password akunnya sendiri
    $currentUserId = Auth::id();
    if ($currentUserId != $id) {
      Alert::error('Anda tidak memiliki akses untuk mengubah akun ini!', 'Akses Ditolak');
      return redirect('home');
    }

    $messages = [
      'oldpassword.required' => 'Password lama wajib diisi.',
      'password.required' => 'Password baru wajib diisi.',
      'password.min' => 'Password baru minimal harus 7 karakter.',
      'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
    ];

    $this->validate($request, [
      'oldpassword' => 'required',
      'password' => 'required|min:7|confirmed',
    ], $messages);

    $user = User::find($currentUserId);

    if (!$user) {
      Alert::error('Pengguna tidak ditemukan!', 'Error');
      return redirect('home');
    }

    // Verifikasi password lama
    if (Hash::check($request->oldpassword, $user->password) || password_verify($request->oldpassword, $user->password)) {
      $user->password = Hash::make($request->password);
      // Tetapkan role secara aman ke 3 (Mahasiswa Aktif) di backend
      $user->role = 3;
      $user->save();

      Alert::success('Password Anda berhasil diperbarui! Selamat datang di ESIAM.', 'Aktivasi Berhasil')->autoclose(3500);
      return redirect('home');
    } else {
      Alert::error('Password lama yang Anda masukkan salah. Default password adalah NIM Anda.', 'Verifikasi Gagal');
      return back()->withInput()->withErrors(['oldpassword' => 'Password lama yang Anda ketikkan salah!']);
    }
  }
}
