<?php

namespace App\ServicesAdmin\Exam;

use App\Models\question;
use App\Models\test;
use Carbon\Carbon;

class createQuestion
{
    public function createQuestion()
    {
        $tests = test::get();

        return view('admin.exam.createQuestion', compact('tests'));
    }

    public function storeQuestion($request)
    {
        try {
            $now = Carbon::now()->timestamp * 1000;

            $question = question::create([
                'testId' => $request->testId,
                'questionText' => $request->questionText,
                'marks' => $request->marks,
                'type' => $request->type,
                'createdAt' => $now,
            ]);

            if (!$question) {
                return redirect()->back()->with('error', 'Tạo mới câu hỏi không thành công!');
            }

            return redirect()->back()->with('success', 'Tạo mới câu hỏi thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tạo mới câu hỏi không thành công!');
        }
    }
}
