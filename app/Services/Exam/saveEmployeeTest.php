<?php

namespace App\Services\Exam;

use App\Enums\QuestionType;
use App\Models\employeeanswer;
use App\Models\employeetest;
use App\Services\ParseToken\ParseToken;
use App\Supports\Responder;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class saveEmployeeTest
{
    private $parseToken;

    public function __construct(
        ParseToken $parseToken
    ) {
        $this->parseToken = $parseToken;
    }

    public function handle($request)
    {
        try {
            DB::beginTransaction();
            $now = Carbon::now()->timestamp * 1000;
            $data = $request->data;
            $testId = $request->testId;
            $employeeTestId = $request->employeeTestId;
            $employeeId = $this->parseToken->handle()->id;
            $score_choice = 0;

            foreach ($data as $answer) {
                if ($answer['type'] == QuestionType::choice) {
                    employeeanswer::create([
                        'employeeId' =>  $employeeId,
                        'testId' =>  $testId,
                        'selectedAnswerId' => join(",", $answer['selectedAnswerId']),
                        'questionId' =>  $answer['questionId'],
                        'employeeId' =>  $employeeId,
                        'score' =>  $answer['score'],
                        'createdAt' => $now,
                    ]);
                    $score_choice += $answer['score'];
                } else if ($answer['type'] == QuestionType::essay) {
                    employeeanswer::create([
                        'employeeId' =>  $employeeId,
                        'testId' =>  $testId,
                        'inputAnswer' => $answer['inputAnswer'],
                        'questionId' =>  $answer['questionId'],
                        'employeeId' =>  $employeeId,
                        'createdAt' => $now,
                    ]);
                }
            }
            employeetest::where('employeeTestId', $employeeTestId)->update([
                'scoreChoice' => $score_choice,
                'pause' => $request->pause,
                'endTime' => $request->endTime,
                'updatedAt' => $now,
            ]);
            DB::commit();
            return Responder::success(null, 'Nộp bài thành công');
        } catch (Exception $e) {
            DB::rollback();
            return Responder::fail($e, 'Nộp bài không thành công');
        }
    }
}
