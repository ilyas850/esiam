<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class JadwalPerkuliahanEloquentTest extends TestCase
{
    public function test_sadmin_controller_uses_eloquent_instead_of_stored_procedure_and_groups_duplicates(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/SadminController.php');

        $this->assertStringNotContainsString("CALL jadwal_perkuliahan", $controller);
        $this->assertStringContainsString("Kurikulum_periode::join('matakuliah'", $controller);
        $this->assertStringContainsString("groupBy(", $controller);
        $this->assertStringContainsString("export_jadwal_perkuliahan", $controller);
        $this->assertStringContainsString("DataJadwalExport", $controller);
    }

    public function test_prodi_controller_uses_eloquent_and_scoped_to_prodi(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/ProdiController.php');

        $this->assertStringNotContainsString("CALL jadwal_perkuliahan", $controller);
        $this->assertStringContainsString("Kurikulum_periode::join('matakuliah'", $controller);
        $this->assertStringContainsString("groupBy(", $controller);
        $this->assertStringContainsString("export_jadwal_perkuliahan_prodi", $controller);
    }

    public function test_sadmin_view_contains_modern_ui_elements_and_day_mapping(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/sadmin/perkuliahan/jadwal_perkuliahan.blade.php');

        $this->assertStringContainsString('info-box', $view);
        $this->assertStringContainsString('Total Jadwal Kelas', $view);
        $this->assertStringContainsString('Total SKS Terjadwal', $view);
        $this->assertStringContainsString('Dosen Mengajar', $view);
        $this->assertStringContainsString('Ruangan Digunakan', $view);
        $this->assertStringContainsString('hariMap', $view);
        $this->assertStringContainsString('daftar_konsentrasi', $view);
        $this->assertStringContainsString('export_jadwal_perkuliahan', $view);
        $this->assertStringContainsString('window.print()', $view);
        $this->assertStringContainsString('example8', $view);
    }

    public function test_prodi_view_contains_modern_ui_elements_and_day_mapping(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/adminprodi/perkuliahan/jadwal_perkuliahan.blade.php');

        $this->assertStringContainsString('info-box', $view);
        $this->assertStringContainsString('Total Jadwal Kelas', $view);
        $this->assertStringContainsString('Total SKS Terjadwal', $view);
        $this->assertStringContainsString('hariMap', $view);
        $this->assertStringContainsString('daftar_konsentrasi', $view);
        $this->assertStringContainsString('export_jadwal_perkuliahan_prodi', $view);
        $this->assertStringContainsString('window.print()', $view);
        $this->assertStringContainsString('example8', $view);
    }

    public function test_export_class_and_blade_exist(): void
    {
        $exportClass = file_get_contents(__DIR__ . '/../../app/Exports/DataJadwalExport.php');
        $this->assertStringContainsString('FromView', $exportClass);
        $this->assertStringContainsString('ShouldAutoSize', $exportClass);
        $this->assertStringContainsString('export_excel/data_jadwal_perkuliahan', $exportClass);

        $this->assertFileExists(__DIR__ . '/../../resources/views/export_excel/data_jadwal_perkuliahan.blade.php');
    }
}
