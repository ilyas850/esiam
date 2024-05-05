<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\Student;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoMhsController extends Controller
{
  public function get_new_user($id)
  {
    return view('mhs/new_pwd', ['id' => $id]);
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

      // Alert::success('', 'Password anda berhasil dirubah')->autoclose(3500);
      return redirect('home');
    } else {
      // Alert::error('password lama yang anda ketikan salah !', 'MAAF !!');
      return redirect('home');
    }
  }
}
