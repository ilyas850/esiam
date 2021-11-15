<?php

namespace App\Http\Controllers;

use App\Angkatan;
use App\Prodi;
use App\Student;
use App\Dosen;
use App\Prausta_setting_relasi;
use App\Prausta_master_kode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function dospem_pkl()
    {
      $angkatan = Angkatan::orderBy('idangkatan','DESC')->get();
      $prodi = Prodi::join('prausta_master_kode', 'prodi.id_prodi', '=', 'prausta_master_kode.id_prodi')
                    ->whereIn('prausta_master_kode.id_masterkode_prausta', [1,2,3])
                    ->select('prodi.kodeprodi', 'prodi.id_prodi', 'prodi.prodi', 'prausta_master_kode.id_masterkode_prausta')
                    ->get();

      return view ('adminprodi/dospem/pkl', compact('angkatan','prodi'));
    }

    public function view_mhs_bim_pkl(Request $request)
    {
      $angkatan = $request->idangkatan;
      $prodi = $request->kodeprodi;

      $user = explode(',',$prodi,2);
      $id1 = $user[0];
      $id2 = $user[1];

      $datas = Student::leftjoin('prausta_setting_relasi', 'student.idstudent', '=', 'prausta_setting_relasi.id_student')
                      ->where('student.idangkatan', $angkatan)
                      ->where('student.kodeprodi', $id1)
                      ->where('student.active', 1)
                      ->where( function ($query)  use ($id2)
                      {
                        $query->where('prausta_setting_relasi.id_masterkode_prausta', $id2)
                        ->orWhere('prausta_setting_relasi.id_masterkode_prausta', NULL);
                      })

                      ->orderBy('student.nim', 'ASC')
                      ->get();


      $dosen = Dosen::where('active', 1)
                    ->where('idstatus', 1)
                    ->get();

      return view('adminprodi/dospem/lihat_pkl', compact('datas','dosen','id2'));
    }

    public function save_dsn_bim_pkl(Request $request)
    {
      $dosen = $request->iddosen;
      $idms = $request->id_masterkode_prausta;

      $hitdsn = count($dosen);

      for ($i=0; $i < $hitdsn ; $i++) {
        $dta = $request->iddosen[$i];

        $user = explode(',',$dta, 3);
        $id1 = $user[0];
        $id2 = $user[1];
        $id3 = $user[2];

        $dt = new Prausta_setting_relasi;
        $dt->id_masterkode_prausta = $idms;
        $dt->id_student = $id1;
        $dt->dosen_pembimbing = $id3;
        $dt->id_dosen_pembimbing = $id2;
        $dt->added_by = Auth::user()->name;
        $dt->status = 'ACTIVE';
        $dt->save();

      }

      return redirect ('dospem_pkl');
    }
}
