<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class RekapPerkuliahanTest extends TestCase
{
    public function test_sadmin_controller_contains_rekap_perkuliahan_optimizations_and_export(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/SadminController.php');

        // Verify method signatures and imports
        $this->assertStringContainsString("use App\Exports\DataRekapPerkuliahanExport;", $controller);
        $this->assertStringContainsString("public function export_rekap_perkuliahan", $controller);
        $this->assertStringContainsString("public function rekap_perkuliahan(?Request \$request = null)", $controller);
        $this->assertStringContainsString("function rekapPerkuliahan(\$idPeriodeTahun, \$idPeriodeTipe, \$idProdi = null)", $controller);

        // Verify stats calculation and prodi filter logic
        $this->assertStringContainsString("'total_kelas'", $controller);
        $this->assertStringContainsString("'tercapai'", $controller);
        $this->assertStringContainsString("'belum_tercapai'", $controller);
        $this->assertStringContainsString("'total_sesi'", $controller);
        $this->assertStringContainsString("'total_online'", $controller);
        $this->assertStringContainsString("'total_offline'", $controller);
        $this->assertStringContainsString("id_prodi = ?", $controller);
    }

    public function test_prodi_controller_contains_rekap_perkuliahan_optimizations_and_export(): void
    {
        $controller = file_get_contents(__DIR__ . '/../../app/Http/Controllers/ProdiController.php');

        // Verify method signatures and imports
        $this->assertStringContainsString("use App\Exports\DataRekapPerkuliahanExport;", $controller);
        $this->assertStringContainsString("public function export_rekap_perkuliahan_prodi", $controller);
        $this->assertStringContainsString("public function rekap_perkuliahan(?Request \$request = null)", $controller);
        $this->assertStringContainsString("function get_rekap_perkuliahan_data(\$tahun, \$tipe, \$kodeprodi)", $controller);

        // Verify stats calculation and scoped prodi logic
        $this->assertStringContainsString("'total_kelas'", $controller);
        $this->assertStringContainsString("'tercapai'", $controller);
        $this->assertStringContainsString("'belum_tercapai'", $controller);
        $this->assertStringContainsString("'total_sesi'", $controller);
        $this->assertStringContainsString("'total_online'", $controller);
        $this->assertStringContainsString("'total_offline'", $controller);
        $this->assertStringContainsString("prd.kodeprodi = ?", $controller);
    }

    public function test_sadmin_view_contains_modern_ui_elements_and_filters(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/sadmin/perkuliahan/rekap_perkuliahan.blade.php');

        // Breadcrumb and styling
        $this->assertStringContainsString('Rekapitulasi Perkuliahan & BAP', $view);
        $this->assertStringContainsString('info-box', $view);
        $this->assertStringContainsString('Total Kelas Kuliah', $view);
        $this->assertStringContainsString('Target Tercapai', $view);
        $this->assertStringContainsString('Belum Tercapai', $view);
        $this->assertStringContainsString('Total Sesi Terlaksana', $view);

        // Filters and actions
        $this->assertStringContainsString('id_prodi', $view);
        $this->assertStringContainsString('-- Semua Program Studi --', $view);
        $this->assertStringContainsString('export_rekap_perkuliahan', $view);
        $this->assertStringContainsString('window.print()', $view);
        $this->assertStringContainsString('cek_rekapan', $view);
        $this->assertStringContainsString('download_bap_dosen', $view);
        $this->assertStringContainsString('download_absensi_mhs', $view);
        $this->assertStringContainsString('example8', $view);
    }

    public function test_rekap_filter_forms_post_to_their_registered_endpoints(): void
    {
        $sadminView = file_get_contents(__DIR__ . '/../../resources/views/sadmin/perkuliahan/rekap_perkuliahan.blade.php');
        $prodiView = file_get_contents(__DIR__ . '/../../resources/views/adminprodi/perkuliahan/rekap_perkuliahan.blade.php');
        $kaprodiView = file_get_contents(__DIR__ . '/../../resources/views/kaprodi/perkuliahan/rekap_perkuliahan.blade.php');
        $routes = file_get_contents(__DIR__ . '/../../routes/web.php');

        $this->assertStringContainsString('<form action="{{ url(\'filter_rekap_perkuliahan\') }}" method="POST">', $sadminView);
        $this->assertStringContainsString('@csrf', $sadminView);
        $this->assertStringContainsString('<form action="{{ url(\'filter_rekap_perkuliahan_prodi\') }}" method="POST">', $prodiView);
        $this->assertStringContainsString('@csrf', $prodiView);
        $this->assertStringContainsString("url('filter_rekap_perkuliahan_kprd')", $kaprodiView);
        $this->assertStringContainsString('method="POST"', $kaprodiView);
        $this->assertStringContainsString("Route::post('filter_rekap_perkuliahan', 'SadminController@filter_rekap_perkuliahan');", $routes);
        $this->assertStringContainsString("Route::post('filter_rekap_perkuliahan_prodi', 'ProdiController@filter_rekap_perkuliahan');", $routes);
        $this->assertStringContainsString("Route::post('filter_rekap_perkuliahan_kprd', 'KaprodiController@filter_rekap_perkuliahan');", $routes);
    }

    public function test_prodi_view_contains_modern_ui_elements_and_scoped_filters(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/adminprodi/perkuliahan/rekap_perkuliahan.blade.php');

        // Breadcrumb and styling
        $this->assertStringContainsString('Rekapitulasi Perkuliahan & BAP', $view);
        $this->assertStringContainsString('info-box', $view);
        $this->assertStringContainsString('Total Kelas Kuliah', $view);
        $this->assertStringContainsString('Target Tercapai', $view);
        $this->assertStringContainsString('Belum Tercapai', $view);
        $this->assertStringContainsString('Total Sesi Terlaksana', $view);

        // Actions and table
        $this->assertStringContainsString('export_rekap_perkuliahan_prodi', $view);
        $this->assertStringContainsString('window.print()', $view);
        $this->assertStringContainsString('cek_rekapan_prodi', $view);
        $this->assertStringContainsString('example8', $view);
    }

    public function test_export_class_and_blade_exist(): void
    {
        $exportClass = file_get_contents(__DIR__ . '/../../app/Exports/DataRekapPerkuliahanExport.php');
        $this->assertStringContainsString('FromView', $exportClass);
        $this->assertStringContainsString('ShouldAutoSize', $exportClass);
        $this->assertStringContainsString('export_excel/data_rekap_perkuliahan', $exportClass);

        $exportBlade = file_get_contents(__DIR__ . '/../../resources/views/export_excel/data_rekap_perkuliahan.blade.php');
        $this->assertStringContainsString('REKAPITULASI PERKULIAHAN & BAP', $exportBlade);
        $this->assertStringContainsString('Mata Kuliah', $exportBlade);
        $this->assertStringContainsString('Jumlah Pertemuan', $exportBlade);
        $this->assertStringContainsString('Persentase', $exportBlade);
    }

    public function test_routes_exist(): void
    {
        $routes = file_get_contents(__DIR__ . '/../../routes/web.php');
        $this->assertStringContainsString("export_rekap_perkuliahan", $routes);
        $this->assertStringContainsString("SadminController@export_rekap_perkuliahan", $routes);
        $this->assertStringContainsString("export_rekap_perkuliahan_prodi", $routes);
        $this->assertStringContainsString("ProdiController@export_rekap_perkuliahan_prodi", $routes);
    }
}
