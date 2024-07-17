<?php

namespace App\Helpers;

use App\Models\Biaya;
use App\Models\Kuitansi;

class Helper
{
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
