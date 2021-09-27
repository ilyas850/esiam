<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataNilaiIpkMhsProdiExport implements FromView, ShouldAutoSize
{
  use Exportable;

  public function __construct($id_angkatan, $id_prodi)
  {

      $this->prd = $id_angkatan;

      $this->ta = $id_prodi;
  }

  public function view(): View
  {
      return view('kaprodi.master.ipk_mhs_prodi', [
          'ipk' => DB::select('CALL filterIpk(?,?)', array($this->prd,$this->ta))
      ]);

  }
}
