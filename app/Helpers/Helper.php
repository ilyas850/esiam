<?php

namespace App\Helpers;

use App\Models\Biaya;
use App\Models\Kuitansi;
use Illuminate\Support\Facades\DB;

class Helper
{
  public static function getMahasiswaDosen($id)
  {
    $kp = DB::table('kurikulum_periode')
      ->select('id_periodetahun', 'id_periodetipe', 'id_dosen', 'id_hari', 'id_jam', 'id_makul')
      ->where('id_kurperiode', $id)
      ->where('status', 'ACTIVE')
      ->first();

    if (!$kp) {
      return collect();
    }

    $kurperiodeIds = DB::table('kurikulum_periode')
      ->where('id_periodetahun', $kp->id_periodetahun)
      ->where('id_periodetipe', $kp->id_periodetipe)
      ->where('id_dosen', $kp->id_dosen)
      ->where('id_hari', $kp->id_hari)
      ->where('id_jam', $kp->id_jam)
      ->where('id_makul', $kp->id_makul)
      ->where('status', 'ACTIVE')
      ->pluck('id_kurperiode');

    return DB::table('student_record as c')
      ->join('student as d', 'd.idstudent', '=', 'c.id_student')
      ->join('prodi as e', function ($join) {
        $join->on('e.kodeprodi', '=', 'd.kodeprodi')
          ->on('e.kodekonsentrasi', '=', 'd.kodekonsentrasi');
      })
      ->join('kelas as f', 'f.idkelas', '=', 'd.idstatus')
      ->join('angkatan as g', 'g.idangkatan', '=', 'd.idangkatan')
      ->leftJoin('absen_ujian as h', 'h.id_studentrecord', '=', 'c.id_studentrecord')
      ->leftJoin('permohonan_ujian as i', 'i.id_studentrecord', '=', 'c.id_studentrecord')
      ->whereIn('c.id_kurperiode', $kurperiodeIds)
      ->where('c.status', 'TAKEN')
      ->select(
        'c.id_studentrecord',
        'c.id_kurperiode',
        'c.id_student',
        'c.id_kurtrans',
        'd.nim',
        'd.nama',
        'e.prodi',
        'f.kelas',
        'g.angkatan',
        'c.nilai_KAT',
        'c.nilai_UTS',
        'c.nilai_UAS',
        'c.nilai_AKHIR',
        'c.nilai_AKHIR_angka',
        'h.id_studentrecord as id_ujian',
        'h.absen_uts',
        'h.absen_uas',
        'h.ket_absensi',
        'h.permohonan',
        'i.id_studentrecord as id_mohon'
      )
      ->orderBy('e.prodi', 'asc')
      ->orderBy('f.kelas', 'asc')
      ->orderBy('d.nim', 'asc')
      ->get();
  }

  public static function cekSemesterMhs($periodeTahun, $idPeriodetipe, $idAngkatan, $intake)
  {
    $sub_thn = substr($periodeTahun, 6, 2);
    $tipe = $idPeriodetipe;
    $smt = $sub_thn . $tipe;

    if ($smt % 2 != 0) {
      if ($tipe == 1) {
        // ganjil
        $a = (($smt + 10) - 1) / 10;
        $b = $a - $idAngkatan;

        if ($intake == 2) {
          $c = ($b * 2) - 1 - 1;
        } elseif ($intake == 1) {
          $c = ($b * 2) - 1;
        }
      } elseif ($tipe == 3) {
        // pendek
        $a = (($smt + 10) - 3) / 10;
        $b = $a - $idAngkatan;
        if ($intake == 2) {
          $c = ($b * 2) - 1 . '0' . '1';
        } elseif ($intake == 1) {
          $c = ($b * 2) . '0' . '1';
        }
      }
    } else {
      // genap
      $a = (($smt + 10) - 2) / 10;
      $b = $a - $idAngkatan;
      if ($intake == 2) {
        $c = $b * 2 - 1;
      } elseif ($intake == 1) {
        $c = $b * 2;
      }
    }

    return $c;
  }

  public static function cekBiayaKuliah($idAngkatan, $idStatus, $kodeProdi)
  {
    return Biaya::where('idangkatan', $idAngkatan)
      ->where('idstatus', $idStatus)
      ->where('kodeprodi', $kodeProdi)
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
        'prakerin'
      )
      ->first();
  }

  function calculateBiaya($biaya, $cb)
  {
    $result = [];

    $fields = [
      'daftar', 'awal', 'dsp', 'spp1', 'spp2', 'spp3', 'spp4', 'spp5',
      'spp6', 'spp7', 'spp8', 'spp9', 'spp10', 'spp11', 'spp12', 'spp13', 'spp14', 'prakerin'
    ];

    foreach ($fields as $field) {
      if ($cb !== null) {
        $result[$field] = $biaya->$field - (($biaya->$field * ($cb->$field)) / 100);
      } else {
        $result[$field] = $biaya->$field;
      }
    }

    return $result;
  }
}
