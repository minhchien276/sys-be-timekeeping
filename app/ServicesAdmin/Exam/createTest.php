<?php

namespace App\ServicesAdmin\Exam;

use App\Models\test;
use Carbon\Carbon;

class createTest
{
    public function createTest()
    {
        return view('admin.exam.createTest');
    }

    public function storeTest($request)
    {
        try {
            $now = Carbon::now()->timestamp * 1000;

            $test = test::create([
                'title' => $request->title,
                'description' => $request->description,
                'totalMarks' => $request->totalMarks,
                'timeLimit' => $request->timeLimit,
                'createdAt' => $now,
            ]);

            if (!$test) {
                return redirect()->back()->with('error', 'Tạo mới bài kiểm tra không thành công!');
            }

            return redirect()->back()->with('success', 'Tạo mới bài kiểm tra thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Tạo mới bài kiểm tra không thành công!');
        }
    }
}
