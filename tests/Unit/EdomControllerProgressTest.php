<?php

namespace Tests\Unit;

use App\Http\Controllers\EdomController;
use PHPUnit\Framework\TestCase;

class EdomControllerProgressTest extends TestCase
{
    public function test_courses_display_independent_form_and_comment_statuses(): void
    {
        $controller = new TestableEdomController();
        $courses = collect([
            (object) ['id_kurperiode' => 10, 'id_kurtrans' => 100, 'makul' => 'Algoritma'],
            (object) ['id_kurperiode' => 20, 'id_kurtrans' => 200, 'makul' => 'Basis Data'],
        ]);

        $result = $controller->decorateCourses($courses, ['10:100', '20:200'], ['20:200']);

        $this->assertTrue($result[0]->form_completed);
        $this->assertFalse($result[0]->comment_completed);
        $this->assertTrue($result[0]->is_completed);
        $this->assertTrue($result[1]->is_completed);
    }

    public function test_numeric_edom_answer_does_not_mark_comment_as_completed(): void
    {
        $controller = new TestableEdomController();
        $submissions = $controller->splitSubmissions(collect([
            (object) ['id_edom' => 17, 'nilai_edom' => '4', 'id_kurperiode' => 10, 'id_kurtrans' => 100],
        ]));

        $this->assertSame(['10:100'], $submissions['form']);
        $this->assertSame([], $submissions['comment']);
    }

    public function test_text_comment_marks_only_comment_as_completed(): void
    {
        $controller = new TestableEdomController();
        $submissions = $controller->splitSubmissions(collect([
            (object) ['id_edom' => 17, 'nilai_edom' => 'Dosen menjelaskan materi dengan jelas.', 'id_kurperiode' => 10, 'id_kurtrans' => 100],
        ]));

        $this->assertSame([], $submissions['form']);
        $this->assertSame(['10:100'], $submissions['comment']);
    }
}

class TestableEdomController extends EdomController
{
    public function decorateCourses($courses, array $formSubmissionKeys, array $commentSubmissionKeys)
    {
        return $this->decorateEdomCourses($courses, $formSubmissionKeys, $commentSubmissionKeys);
    }

    public function splitSubmissions($transactions)
    {
        return $this->splitEdomSubmissionKeys($transactions);
    }
}
