<?php

namespace App\Services\Exam;

use App\Models\answer;
use App\Models\employeetest;
use App\Models\question;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Exception;

class getAllTests
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken,
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle()
    {
        try {
            $employee = $this->parseToken->handle();

            $tests = employeetest::leftJoin('tests', 'employeetests.testId', '=', 'tests.testId')
                ->where('employeetests.employeeId', $employee->id)
                ->get();

            foreach ($tests as $test) {
                $questions = question::where('testId', $test->testId)->get();
                foreach ($questions as $question) {
                    $answers = answer::where('questionId', $question->questionId)->get();
                    $question->answers = $answers;
                }
                $test->questions = $questions;
                $test->status = true;
            }

            if (!$tests) {
                return Responder::success([], 'Chưa có bài kiểm tra', 200);
            }

            return Responder::success($tests, 'Danh sách bài kiểm tra kiến thức chuyên môn');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy bài kiểm tra kiến thức chuyên môn');
        }
    }
}
