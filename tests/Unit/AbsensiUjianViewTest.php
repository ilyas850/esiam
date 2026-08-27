<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AbsensiUjianViewTest extends TestCase
{
    public function test_it_provides_responsive_exam_status_structure(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/mhs/ujian/absensi_ujian.blade.php');

        $this->assertStringContainsString('exam-attendance-table', $view);
        $this->assertStringContainsString('data-label="Status UAS"', $view);
        $this->assertStringContainsString('$hariIndonesia', $view);
    }
}
