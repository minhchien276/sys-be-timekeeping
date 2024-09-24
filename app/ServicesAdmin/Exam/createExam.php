<?php

namespace App\ServicesAdmin\Exam;

use App\Models\answer;
use App\Models\question;
use App\Models\test;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class createExam
{
    public function createAllTest($testId)
    {
        $test = test::where('testId', $testId)->first();

        return view('admin.exam.createExam', compact('test'));
    }

    public function storeAllTest($request)
    {
        try {
            DB::beginTransaction();

            $now = Carbon::now()->timestamp * 1000;
            $testId = $request->testId;
            $totalMarks = test::where('testId', $testId)->pluck('totalMarks')->first();

            if (is_null($totalMarks)) {
                $totalMarks = 0;
            }

            foreach ($request->questions as $question) {
                $newQuestion = question::create([
                    'testId' => $testId,
                    'questionText' => $question['questionText'],
                    'marks' => $question['score'],
                    'url' => isset($question['urlImage']) ? $question['urlImage'] : null,
                    'type' => $question['type'] === 'essay' ? 2 : 1,
                    'createdAt' => $now,
                ]);

                // Tính tổng điểm cho bài test
                $totalMarks +=  $question['score'];

                // Xử lý câu trả lời cho loại câu hỏi tự luận
                if ($question['type'] === 'essay') {
                    answer::create([
                        'questionId' => $newQuestion->questionId,
                        'answerText' => $question['answerText'],
                        'isCorrect' => 1,
                        'createdAt' => $now,
                    ]);
                }

                // Xử lý câu trả lời cho loại câu hỏi trắc nghiệm
                if ($question['type'] === 'multiple_choice' && isset($question['answers'])) {
                    foreach ($question['answers'] as $answer) {
                        answer::create([
                            'questionId' => $newQuestion->questionId,
                            'answerText' => $answer['answerText'],
                            'isCorrect' => isset($answer['isCorrect']) && $answer['isCorrect'] === 'on' ? 1 : 0,
                            'createdAt' => $now,
                        ]);
                    }
                }
            }

            test::where('testId', $testId)->update(['totalMarks' => $totalMarks]);

            DB::commit();

            return redirect()->back()->with('success', 'Câu hỏi đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Tạo câu hỏi không thành công.');
        }
    }
}
