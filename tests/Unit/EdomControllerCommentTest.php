<?php

namespace Tests\Unit;

use App\Http\Controllers\EdomController;
use PHPUnit\Framework\TestCase;

class EdomControllerCommentTest extends TestCase
{
    public function test_comment_is_trimmed_before_saving(): void
    {
        $controller = new TestableEdomCommentController();

        $this->assertSame('Dosen menjelaskan materi dengan jelas.', $controller->normalizeComment('  Dosen menjelaskan materi dengan jelas.  '));
    }
}

class TestableEdomCommentController extends EdomController
{
    public function normalizeComment($comment)
    {
        return $this->normalizeEdomComment($comment);
    }
}
