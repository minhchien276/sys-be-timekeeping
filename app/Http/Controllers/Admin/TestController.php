<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ServicesAdmin\Exam\createAnswer;
use App\ServicesAdmin\Exam\createExam;
use App\ServicesAdmin\Exam\createQuestion;
use App\ServicesAdmin\Exam\createTest;
use App\ServicesAdmin\Exam\employeeAnswer;
use App\ServicesAdmin\Exam\EmployeeTest;
use App\ServicesAdmin\Exam\getAnswersNotMark;
use App\ServicesAdmin\Exam\indexTests;
use Illuminate\Http\Request;

class TestController extends Controller
{
    private $indexTests;
    private $createTest;
    private $createQuestion;
    private $createAnswer;
    private $createExam;
    private $getAnswersNotMark;
    private $EmployeeTest;
    private $employeeAnswer;

    public function __construct(
        indexTests $indexTests,
        createTest $createTest,
        createQuestion $createQuestion,
        createAnswer $createAnswer,
        createExam $createExam,
        getAnswersNotMark $getAnswersNotMark,
        EmployeeTest $EmployeeTest,
        employeeAnswer $employeeAnswer,
    ) {
        $this->indexTests = $indexTests;
        $this->createTest = $createTest;
        $this->createQuestion = $createQuestion;
        $this->createAnswer = $createAnswer;
        $this->createExam = $createExam;
        $this->getAnswersNotMark = $getAnswersNotMark;
        $this->EmployeeTest = $EmployeeTest;
        $this->employeeAnswer = $employeeAnswer;
    }

    public function indexTests()
    {
        return $this->indexTests->indexTests();
    }

    public function createTest()
    {
        return $this->createTest->createTest();
    }

    public function storeTest(Request $request)
    {
        return $this->createTest->storeTest($request);
    }

    public function indexQuestion()
    {
        return $this->indexTests->indexQuestions();
    }

    public function createQuestion()
    {
        return $this->createQuestion->createQuestion();
    }

    public function storeQuestion(Request $request)
    {
        return $this->createQuestion->storeQuestion($request);
    }

    public function indexAnswer()
    {
        return $this->indexTests->indexAnswers();
    }

    public function createAnswer()
    {
        return $this->createAnswer->createAnswer();
    }

    public function storeAnswer(Request $request)
    {
        return $this->createAnswer->storeAnswer($request);
    }

    public function createAllTest($testId)
    {
        return $this->createExam->createAllTest($testId);
    }

    public function storeAllTest(Request $request)
    {
        return $this->createExam->storeAllTest($request);
    }

    public function indexMark()
    {
        return $this->getAnswersNotMark->getAnswersNotMark();
    }

    public function indexCaculateScore($employeeAnswerId)
    {
        return $this->getAnswersNotMark->indexCaculateScore($employeeAnswerId);
    }

    public function caculateScore(Request $request)
    {
        return $this->getAnswersNotMark->caculateScore($request);
    }

    public function createEmployeeTest()
    {
        return $this->EmployeeTest->createEmployeeTest();
    }

    public function storeEmployeeTest(Request $request)
    {
        return $this->EmployeeTest->storeEmployeeTest($request);
    }

    public function indexEmployeeAnswer($testId)
    {
        return $this->employeeAnswer->indexEmployeeAnswer($testId);
    }

    public function createEmployeeAnswer($testId, $employeeId)
    {
        return $this->employeeAnswer->createEmployeeAnswer($testId, $employeeId);
    }

    public function storeEmployeeAnswer(Request $request)
    {
        return $this->employeeAnswer->storeEmployeeAnswer($request);
    }

    public function indexEmployeeTests($testId)
    {
        return $this->indexTests->indexEmployeeTests($testId);
    }

    public function employeeTestDetails($employeeId, $testId)
    {
        return $this->indexTests->employeeTestDetails($employeeId, $testId);
    }
}
