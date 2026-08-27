<?php

namespace Tests\Unit;

use App\Http\Controllers\MhsController;
use PHPUnit\Framework\TestCase;

class MhsControllerKuisionerTest extends TestCase
{
    public function test_questionnaire_cards_show_edom_and_category_completion_status(): void
    {
        $controller = new TestableMhsController();
        $categories = collect([
            (object) ['id_kategori_kuisioner' => 1, 'kategori_kuisioner' => 'Dosen Pembimbing Akademik'],
            (object) ['id_kategori_kuisioner' => 8, 'kategori_kuisioner' => 'Perpustakaan'],
        ]);

        $questionnaires = $controller->buildCards($categories, [
            'edom' => ['completed' => 2, 'total' => 4, 'remaining' => 2, 'is_complete' => false],
            'categories' => [1 => true, 8 => false],
        ]);

        $this->assertSame('2 dari 4 mata kuliah sudah diisi', $questionnaires[0]['status_text']);
        $this->assertFalse($questionnaires[0]['is_complete']);
        $this->assertSame('Sudah diisi', $questionnaires[1]['status_text']);
        $this->assertTrue($questionnaires[1]['is_complete']);
        $this->assertSame('Belum diisi', $questionnaires[2]['status_text']);
        $this->assertFalse($questionnaires[2]['is_complete']);
    }

    public function test_edom_completion_ignores_optional_comment_transactions(): void
    {
        $controller = new TestableMhsController();
        $courses = collect([
            (object) ['id_kurperiode' => 10, 'id_kurtrans' => 100],
            (object) ['id_kurperiode' => 20, 'id_kurtrans' => 200],
        ]);
        $transactions = collect([
            (object) ['id_kurperiode' => 10, 'id_kurtrans' => 100, 'nilai_edom' => '4'],
            (object) ['id_kurperiode' => 20, 'id_kurtrans' => 200, 'nilai_edom' => 'Komentar untuk dosen'],
        ]);

        $this->assertSame(1, $controller->calculateCompletedEdomCourses($courses, $transactions));
    }

    public function test_questionnaire_answer_normalization_discards_empty_answers(): void
    {
        $controller = new TestableMhsController();

        $answers = $controller->normalizeQuestionnaireAnswers([
            1 => '1,4',
            2 => '',
            3 => null,
            4 => '4,2',
        ]);

        $this->assertSame(['1,4', '4,2'], $answers);
    }
}

class TestableMhsController extends MhsController
{
    public function buildCards($categories, array $completion)
    {
        return $this->buildKuisionerCards($categories, $completion);
    }

    public function calculateCompletedEdomCourses($courses, $transactions)
    {
        return $this->countEdomCompletedCourses($courses, $transactions);
    }

    public function normalizeQuestionnaireAnswers(array $answers)
    {
        return $this->normalizeKuisionerAnswers($answers);
    }
}
