<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class KuisionerDsnPaViewTest extends TestCase
{
    public function test_it_uses_radio_button_rating_options_like_the_edom_form(): void
    {
        $view = file_get_contents(__DIR__ . '/../../resources/views/mhs/kuisioner/kuisioner_dsn_pa.blade.php');

        $this->assertStringContainsString('type="radio"', $view);
        $this->assertStringContainsString('kuisioner-rating-option', $view);
    }
}
