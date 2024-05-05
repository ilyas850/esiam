<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Student;
use App\Models\Angkatan;
use App\Models\Sertifikat;
use App\Models\Jenis_kegiatan;
use App\Models\Kritiksaran_kategori;
use App\Models\Kritiksaran_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\DataMhsExportPerAngkatan;
use Maatwebsite\Excel\Facades\Excel;

class Wadir3Controller extends Controller
{
    public function data_kritiksaran()
    {
        $data = Kritiksaran_kategori::leftjoin('kritiksaran_trans', 'kritiksaran_kategori.id_kategori_kritiksaran', '=', 'kritiksaran_trans.id_kategori_kritiksaran')
            ->where('kritiksaran_kategori.status', 'ACTIVE')
            ->select('kritiksaran_kategori.id_kategori_kritiksaran', 'kritiksaran_kategori.kategori_kritiksaran', DB::raw('COUNT(kritiksaran_trans.id_kategori_kritiksaran) as jml'))
            ->groupBy('kritiksaran_kategori.id_kategori_kritiksaran', 'kritiksaran_kategori.kategori_kritiksaran')
            ->get();

        return view('wadir3/kritiksaran/master_kritiksaran', compact('data'));
    }

    public function cek_kritiksaran($id)
    {
        $data = Kritiksaran_transaction::join('kritiksaran_kategori', 'kritiksaran_trans.id_kategori_kritiksaran', '=', 'kritiksaran_kategori.id_kategori_kritiksaran')
            ->join('periode_tahun', 'kritiksaran_trans.id_periodetahun', '=', 'periode_tahun.id_periodetahun')
            ->join('periode_tipe', 'kritiksaran_trans.id_periodetipe', '=', 'periode_tipe.id_periodetipe')
            ->join('student', 'kritiksaran_trans.id_student', '=', 'student.idstudent')
            ->where('kritiksaran_trans.id_kategori_kritiksaran', $id)
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
                'student.nim',
                'student.nama',
                'kritiksaran_trans.kritik',
                'kritiksaran_trans.saran',
                'kritiksaran_trans.status',
                'periode_tahun.status as thn_status',
                'periode_tipe.status as tp_status'
            )
            ->get();

        $kat = Kritiksaran_kategori::where('id_kategori_kritiksaran', $id)->first();

        return view('wadir3/kritiksaran/cek_kritiksaran', compact('data', 'kat'));
    }

    public function master_mhs_aktif_wadir3()
    {
        $angkatan = Angkatan::all();

        $data = Student::leftJoin('prodi', function ($join) {
            $join->on('prodi.kodeprodi', '=', 'student.kodeprodi')->on('prodi.kodekonsentrasi', '=', 'student.kodekonsentrasi');
        })
            ->join('kelas', 'student.idstatus', '=', 'kelas.idkelas')
            ->join('angkatan', 'student.idangkatan', '=', 'angkatan.idangkatan')
            ->where('student.active', 1)
            ->select('student.nim', 'student.nama', 'prodi.prodi', 'prodi.konsentrasi', 'kelas.kelas', 'angkatan.angkatan', 'student.nisn', 'student.intake')
            ->orderBy('student.nim', 'ASC')
            ->orderBy('prodi.prodi', 'ASC')
            ->orderBy('kelas.kelas', 'ASC')
            ->get();

        return view('wadir3/mahasiswa/mahasiswa_aktif', compact('data', 'angkatan'));
    }

    public function export_xls_data_mhs_wadir3(Request $request)
    {
        $idangkatan = $request->idangkatan;

        $angkatan = Angkatan::where('idangkatan', $idangkatan)->first();

        $nama_file = 'Data Mahasiswa Angkatan' . ' ' . $angkatan->angkatan . '.xlsx';

        return Excel::download(new DataMhsExportPerAngkatan($idangkatan), $nama_file);
    }

    public function master_sertifikat_mhs_wadir3()
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
            ->orderBy('prodi.prodi', 'ASC')
            ->orderBy('kelas.kelas', 'ASC')
            ->get();

        return view('wadir3/mahasiswa/sertifikat_mhs', compact('data'));
    }

    public function cek_sertifikat_mhs($id)
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

        return view('wadir3/mahasiswa/cek_sertifikat_mhs', compact('mhs', 'data', 'jenis'));
    }
}
