<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagangSkripsiController extends Controller
{
    public function magang_mhs()
    {
        $id = Auth::user()->id_user;

        $data_mhs = Student::where('idstudent', $id)->first();
        dd($id);
    }
}
