<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RincianAkmPerMhsSheetExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $studentItem;
    protected $sheetTitle;

    public function __construct($studentItem, $sheetTitle = null)
    {
        $this->studentItem = $studentItem;

        if ($sheetTitle) {
            $this->sheetTitle = $sheetTitle;
        } else {
            $rawName = $studentItem['student']->nama ?? 'Mahasiswa';
            $cleanName = preg_replace('/[\\\\\\/?*:\\[\\]]/', '', $rawName);
            $cleanName = trim($cleanName);
            if (empty($cleanName)) {
                $cleanName = 'Mhs_' . ($studentItem['student']->nim ?? '1');
            }
            $this->sheetTitle = mb_substr($cleanName, 0, 31);
        }
    }

    public function view(): View
    {
        return view('sadmin.export.rincian_akm_sheet_xls', [
            'mhs' => $this->studentItem['student'],
            'courses' => $this->studentItem['courses'],
            'summary' => $this->studentItem['summary'],
        ]);
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
}
