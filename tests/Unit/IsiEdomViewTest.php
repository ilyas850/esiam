<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class IsiEdomViewTest extends TestCase
{
    public function test_it_provides_a_link_back_to_the_questionnaire_page(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/mhs/edom/isi_edom.blade.php');

        $this->assertStringContainsString("url('kuisioner')", $view);
        $this->assertStringContainsString('Kembali ke Kuisioner', $view);
        $this->assertStringContainsString('color: #0073b7 !important;', $view);
    }
}
