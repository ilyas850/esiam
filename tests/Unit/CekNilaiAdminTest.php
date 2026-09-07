<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CekNilaiAdminTest extends TestCase
{
    public function test_grade_detail_uses_eloquent_relationships_instead_of_the_legacy_stored_procedure(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/SadminController.php');

        $this->assertStringNotContainsString("CALL cek_nilai", $controller);
        $this->assertStringContainsString("Student_record::with(['kurperiode.makul', 'kurperiode.tahun', 'kurperiode.tipe', 'kurperiode.semester'])", $controller);
        $this->assertStringContainsString("->whereHas('kurperiode', function (\$query)", $controller);
    }

    public function test_grade_detail_view_has_summary_selection_and_empty_state(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/sadmin/nilai/ceknilai.blade.php');

        $this->assertStringContainsString('id="example1"', $view);
        $this->assertStringContainsString('id="select-all-pending"', $view);
        $this->assertStringContainsString('Belum ada riwayat nilai', $view);
        $this->assertStringContainsString('data-label="Status KRS"', $view);
    }
}
