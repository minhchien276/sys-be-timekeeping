<?php

namespace App\ServicesAdmin\Exam;

use App\Enums\DepartmentEnum;
use App\Models\answer;
use App\Models\employeeanswer;
use App\Models\employeetest;
use App\Models\question;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class indexTests
{
    public function indexTests()
    {
        $tests = DB::table('tests')
            ->leftJoin('questions', 'tests.testId', '=', 'questions.testId')
            ->select('tests.*', DB::raw('COUNT(questions.questionId) as question_count'))
            ->groupBy('tests.testId', 'tests.title', 'tests.description', 'tests.totalMarks', 'tests.timeLimit', 'tests.createdAt', 'tests.updatedAt')
            ->get();

        return view('admin.exam.indexTest', compact('tests'));
    }

    public function indexEmployeeTests($testId)
    {
        $data = employeetest::leftJoin('employee', 'employee.id', '=', 'employeetests.employeeId')
            ->select('employeetests.*', 'employee.fullname', 'employee.departmentId')
            ->where('employeetests.testId', $testId)
            ->where('employee.departmentId', '!=', DepartmentEnum::Director)
            ->get();

        $data->map(function ($item) {
            if ($item->startTime) {
                $startTime = Carbon::createFromTimestamp($item->startTime / 1000);
                $item->startTime = $startTime->format('d-m-Y H:i:s');
            }

            if ($item->endTime) {
                $endTime = Carbon::createFromTimestamp($item->endTime / 1000);
                $item->endTime = $endTime->format('d-m-Y H:i:s');
            }

            return $item;
        });

        return view('admin.exam.indexEmployeeTest', compact('data'));
    }

    public function employeeTestDetails($employeeId, $testId)
    {
        $data = employeeanswer::leftJoin('questions', 'questions.questionId', '=', 'employeeanswers.questionId')
            ->where('employeeanswers.employeeId', $employeeId)
            ->where('employeeanswers.testId', $testId)
            ->get();

        $data->map(function ($item) {
            if ($item->selectedAnswerId != null) {
                $answerText = answer::where('answerId', $item->selectedAnswerId)->pluck('answerText')->first();
                $item->answerText = $answerText;
            }

            return $item;
        });

        return view('admin.exam.detailsEmployeeTest', compact('data'));
    }

    public function indexQuestions()
    {
        $questions = question::join('tests', 'questions.testId', '=', 'tests.testId')
            ->select('questions.*', 'tests.title')
            ->get();

        return view('admin.exam.indexQuestion', compact('questions'));
    }

    public function indexAnswers()
    {
        $answers = answer::join('questions', 'answers.questionId', '=', 'questions.questionId')
            ->select('answers.*', 'questions.questionText')
            ->get();

        return view('admin.exam.indexAnswer', compact('answers'));
    }
}
