<?php

namespace App\ServicesAdmin\Exam;

use App\Enums\QuestionType;
use App\Models\employeeanswer;

class getAnswersNotMark
{
    public function getAnswersNotMark()
    {
        $employeeanswers = employeeanswer::join('questions', 'employeeanswers.questionId', '=', 'questions.questionId')
            ->leftJoin('answers', 'answers.questionId', '=', 'questions.questionId')
            ->leftJoin('employee', 'employee.id', '=', 'employeeanswers.employeeId')
            ->select('employeeanswers.*', 'questions.questionText', 'questions.marks', 'answers.answerText', 'employee.fullname')
            ->where('questions.type', QuestionType::essay)
            ->whereNull('employeeanswers.score')
            ->get();

        return view('admin.exam.indexMark', compact('employeeanswers'));
    }

    public function indexCaculateScore($employeeAnswerId)
    {
        $data = employeeanswer::join('questions', 'employeeanswers.questionId', '=', 'questions.questionId')
            ->leftJoin('answers', 'answers.questionId', '=', 'questions.questionId')
            ->leftJoin('employee', 'employee.id', '=', 'employeeanswers.employeeId')
            ->select('employeeanswers.*', 'questions.questionText', 'questions.marks', 'answers.answerText', 'employee.fullname')
            ->where('employeeanswers.employeeAnswerId', $employeeAnswerId)
            ->where('questions.type', QuestionType::essay)
            ->whereNull('employeeanswers.score')
            ->first();

        return view('admin.exam.caculateScore', compact('data'));
    }

    public function caculateScore($request)
    {
        try {
            $result = employeeanswer::where('employeeAnswerId', $request->employeeAnswerId)->update(['score' => $request->score]);

            if (!$result) {
                return redirect()->back()->with('error', 'Chấm điểm không thành công.');
            }

            session()->flash('success', 'Chấm điểm thành công.');
            return $this->getAnswersNotMark();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chấm điểm không thành công.');
        }
    }
}
