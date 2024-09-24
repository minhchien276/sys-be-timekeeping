<?php

namespace App\Services\Exam;

use App\Models\answer;
use App\Models\employeetest;
use App\Models\question;
use App\Supports\Responder;
use Exception;

class getTestDetails
{
    public function handle($employeeTestId)
    {
        try {
            $test = employeetest::leftJoin('tests', 'employeetests.testId', '=', 'tests.testId')
                ->where('employeetests.employeeTestId',  $employeeTestId)
                ->first();

            $questions = question::where('testId', $test->testId)->get();
            foreach ($questions as $question) {
                $answers = answer::where('questionId', $question->questionId)->get();
                $question->answers = $answers;
            }
            $test->questions = $questions;
            $test->status = !($test->startTime === null);

            if (!$test) {
                return Responder::fail(null, 'Không tìm thấy chi tiết bài kiểm tra kiến thức chuyên môn', 400);
            }
            return Responder::success($test, 'Chi tiết bài kiểm tra kiến thức chuyên môn');
        } catch (Exception $e) {
            return Responder::fail(null, 'Không tìm thấy chi tiết bài kiểm tra kiến thức chuyên môn');
        }
    }
}
