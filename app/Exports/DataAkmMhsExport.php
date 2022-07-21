<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataAkmMhsExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct($idprodi, $idperiodetahun, $idperiodetipe)
    {
        $this->idprodi = $idprodi;

        $this->idperiodetahun = $idperiodetahun;

        $this->idperiodetipe = $idperiodetipe;

    }

    public function view(): View
    {
        return view('sadmin.export.data_akm_xls', [
            'data' => DB::select('CALL data_akm(?,?,?)', array($this->idprodi, $this->idperiodetahun, $this->idperiodetipe))
        ]);
    }
}
