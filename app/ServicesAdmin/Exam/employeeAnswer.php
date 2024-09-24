<?php

namespace App\ServicesAdmin\Exam;

use App\Enums\QuestionType;
use App\Models\employeeanswer as ModelsEmployeeanswer;
use App\Models\employeetest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class employeeAnswer
{
    public function indexEmployeeAnswer($testId)
    {
        $employee_test = employeetest::join('employee', 'employeetests.employeeId', '=', 'employee.id')
            ->select('employeetests.*', 'employee.fullname')
            ->where('employeetests.testId', $testId)
            ->get();

        $employee_test->map(function ($item) {
            if ($item->expired) {
                $expired = Carbon::createFromTimestamp($item->expired / 1000);
                $item->expired = $expired->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.exam.indexEmployeeAnswer', compact('employee_test'));
    }

    public function createEmployeeAnswer($testId, $employeeId)
    {
        $employeeanswers = ModelsEmployeeanswer::join('questions', 'employeeanswers.questionId', '=', 'questions.questionId')
            ->leftJoin('answers', 'answers.questionId', '=', 'questions.questionId')
            ->leftJoin('employee', 'employee.id', '=', 'employeeanswers.employeeId')
            ->select('employeeanswers.*', 'questions.questionText', 'questions.marks', 'answers.answerText', 'employee.fullname')
            ->where('questions.type', QuestionType::essay)
            ->where('employeeanswers.testId', $testId)
            ->where('employeeanswers.employeeId', $employeeId)
            ->whereNull('employeeanswers.score')
            ->get();

        return view('admin.exam.createEmployeeAnswer', compact('employeeanswers'));
    }

    public function storeEmployeeAnswer($request)
    {
        try {
            DB::beginTransaction();
            $scoreEssay = 0;
            foreach ($request->employeeAnswerId as $index => $employeeAnswerId) {
                $score = $request->score[$index];

                $scoreEssay += $score;

                $result = ModelsEmployeeanswer::where('employeeAnswerId', $employeeAnswerId)
                    ->update(['score' => $score]);
            }

            if ($result === 0) {
                return redirect()->back()->with('error', 'Chấm điểm không thành công.');
            }

            session()->flash('success', 'Chấm điểm thành công.');

            $testId = ModelsEmployeeanswer::where('employeeAnswerId', $request->employeeAnswerId[0])->pluck('testId')->first();
            $employeeId = ModelsEmployeeanswer::where('employeeAnswerId', $request->employeeAnswerId[0])->pluck('employeeId')->first();

            employeetest::where('testId', $testId)->where('employeeId', $employeeId)->update(['scoreEssay' => $scoreEssay]);

            DB::commit();

            return $this->indexEmployeeAnswer($testId);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Chấm điểm không thành công.');
        }
    }
}
