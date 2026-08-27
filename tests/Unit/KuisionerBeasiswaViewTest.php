<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class KuisionerBeasiswaViewTest extends TestCase
{
    public function test_it_uses_radio_button_satisfaction_options_and_shows_progress(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/mhs/kuisioner/kuisioner_beasiswa.blade.php');

        $this->assertStringContainsString('type="radio"', $view);
        $this->assertStringContainsString('kuisioner-progress-bar', $view);
    }
}
