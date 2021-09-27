<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataNilaiIpkMhsExport implements FromView, ShouldAutoSize
{
  public function view(): View
  {
      return view('sadmin.datamahasiswa.ipk_mhs', [
          'ipk' => DB::select("CALL getIpkMhs()")
      ]);
  }
}
