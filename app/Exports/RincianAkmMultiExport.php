<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class RincianAkmMultiExport implements WithMultipleSheets
{
    use Exportable;

    protected $studentsData;

    public function __construct(array $studentsData)
    {
        $this->studentsData = $studentsData;
    }

    public function sheets(): array
    {
        $sheets = [];
        $usedTitles = [];

        foreach ($this->studentsData as $studentItem) {
            $rawName = $studentItem['student']->nama ?? 'Mahasiswa';
            $nim = $studentItem['student']->nim ?? '';

            // Bersihkan karakter terlarang di nama sheet Excel: \ / ? * : [ ]
            $cleanName = preg_replace('/[\\\\\\/?*:\\[\\]]/', '', $rawName);
            $cleanName = trim($cleanName);
            if (empty($cleanName)) {
                $cleanName = 'Mhs_' . $nim;
            }

            $title = mb_substr($cleanName, 0, 31);

            // Cegah duplikasi nama sheet di Excel
            if (isset($usedTitles[strtoupper($title)])) {
                $suffix = '_' . substr($nim, -4);
                $title = mb_substr($cleanName, 0, 31 - mb_strlen($suffix)) . $suffix;
                if (isset($usedTitles[strtoupper($title)])) {
                    $counter = 2;
                    while (isset($usedTitles[strtoupper($title)])) {
                        $suffix = '_' . $counter;
                        $title = mb_substr($cleanName, 0, 31 - mb_strlen($suffix)) . $suffix;
                        $counter++;
                    }
                }
            }
            $usedTitles[strtoupper($title)] = true;

            $sheets[] = new RincianAkmPerMhsSheetExport($studentItem, $title);
        }

        return $sheets;
    }
}
