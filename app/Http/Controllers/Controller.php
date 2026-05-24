<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getValidasiKrsData($idDosen)
    {
        $krsSummary = DB::table('student_record as g')
            ->join('kurikulum_periode as h', function ($join) {
                $join->on('h.id_kurperiode', '=', 'g.id_kurperiode')
                    ->where('h.status', 'ACTIVE');
            })
            ->join('periode_tahun as i', function ($join) {
                $join->on('i.id_periodetahun', '=', 'h.id_periodetahun')
                    ->where('i.status', 'ACTIVE');
            })
            ->join('periode_tipe as j', function ($join) {
                $join->on('j.id_periodetipe', '=', 'h.id_periodetipe')
                    ->where('j.status', 'ACTIVE');
            })
            ->where('g.status', 'TAKEN')
            ->groupBy('g.id_student')
            ->selectRaw('g.id_student, COUNT(g.id_student) as jml, MAX(COALESCE(g.remark, 0)) as remark');

        return DB::table('dosen_pembimbing as a')
            ->join('student as b', function ($join) use ($idDosen) {
                $join->on('b.idstudent', '=', 'a.id_student')
                    ->where('a.id_dosen', $idDosen)
                    ->where('b.active', 1)
                    ->where('a.status', 'ACTIVE');
            })
            ->join('prodi as c', function ($join) {
                $join->on('c.kodeprodi', '=', 'b.kodeprodi')
                    ->on('c.kodekonsentrasi', '=', 'b.kodekonsentrasi');
            })
            ->join('kelas as d', 'd.idkelas', '=', 'b.idstatus')
            ->join('angkatan as e', 'e.idangkatan', '=', 'b.idangkatan')
            ->leftJoinSub($krsSummary, 'bb', function ($join) {
                $join->on('a.id_student', '=', 'bb.id_student');
            })
            ->orderBy('b.nim', 'ASC')
            ->select(
                'a.id_student',
                'b.nim',
                'b.nama',
                'c.prodi',
                'd.kelas',
                'e.angkatan',
                'b.hp'
            )
            ->selectRaw('COALESCE(bb.jml, 0) as jml_krs, COALESCE(bb.remark, 0) as remark')
            ->get();
    }

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (session('success')) {
                Alert::success(session('success'));
            }

            if (session('error')) {
                Alert::error(session('error'));
            }

            if (session('errorForm')) {
                $html = "<ul style='list-style: none;'>";
                foreach (session('errorForm') as $error) {
                    $html .= "<li>$error[0]</li>";
                }
                $html .= "</ul>";

                Alert::html('Error during the creation!', $html, 'error');
            }

            return $next($request);
        });
    }
}
