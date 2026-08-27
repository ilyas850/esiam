<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class KuisionerBaakViewTest extends TestCase
{
    public function test_it_uses_radio_button_rating_options_and_shows_progress(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/mhs/kuisioner/kuisioner_baak.blade.php');

        $this->assertStringContainsString('type="radio"', $view);
        $this->assertStringContainsString('kuisioner-progress-bar', $view);
    }
}
