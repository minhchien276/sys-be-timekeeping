<?php

namespace App\ServicesAdmin\Exam;

use App\Models\answer;
use App\Models\question;
use Carbon\Carbon;

class createAnswer
{
    public function createAnswer()
    {
        $question = question::get();

        return view('admin.exam.createAnswer', compact('question'));
    }

    public function storeAnswer($request)
    {
        try {
            $now = Carbon::now()->timestamp * 1000;

            $answer = answer::create([
                'questionId' => $request->questionId,
                'answerText' => $request->answerText,
                'isCorrect' => $request->isCorrect,
                'createdAt' => $now,
            ]);

            if (!$answer) {
                return redirect()->back()->with('error', 'Tạo mới câu trả lời không thành công!');
            }

            return redirect()->back()->with('success', 'Tạo mới câu trả lời thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tạo mới câu trả lời không thành công!');
        }
    }
}
