<?php

namespace Tests\Unit;

use App\Http\Controllers\EdomController;
use PHPUnit\Framework\TestCase;

class EdomControllerAnswerTest extends TestCase
{
    public function test_answer_payload_normalization_discards_empty_answers_and_keeps_selected_scores(): void
    {
        $controller = new TestableEdomAnswerController();

        $answers = $controller->normalizeAnswers([
            1 => '1,4',
            2 => '',
            3 => '3,2',
        ]);

        $this->assertSame(['1,4', '3,2'], $answers);
    }
}

class TestableEdomAnswerController extends EdomController
{
    public function normalizeAnswers(array $answers)
    {
        return $this->normalizeEdomAnswers($answers);
    }
}
