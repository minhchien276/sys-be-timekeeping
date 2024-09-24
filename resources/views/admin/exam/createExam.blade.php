@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Thêm câu hỏi và câu trả lời
            </h3>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="container" id="app">
                            @if (session('success'))
                                <div class="alert alert-success" id="success-alert">
                                    {{ session('success') }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('success-alert').style.display = 'none';
                                    }, 3000);
                                </script>
                            @endif
                            @if (Session::has('error'))
                                <div class="alert alert-danger" id="error-alert">
                                    {{ Session::get('error') }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('error-alert').style.display = 'none';
                                    }, 3000);
                                </script>
                            @endif

                            <form class="form-sample" method="post" action="{{ route('test.store-all') }}">
                                @csrf
                                <div class="row">
                                    <!-- Trường thông tin tiêu đề bài kiểm tra -->
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tiêu đề: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="title" id="title"
                                                    value="{{ $test->title }}" disabled />
                                                <input type="hidden" class="form-control" name="testId" id="testId"
                                                    value="{{ $test->testId }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- Trường nhập câu hỏi -->
                                    <div class="col-md-12" v-for="(question, qIndex) in questions" :key="qIndex">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Câu hỏi @{{ qIndex + 1 }}: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" v-model="question.questionText"
                                                    :name="'questions[' + qIndex + '][questionText]'" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Điểm: </label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" v-model="question.score"
                                                    :name="'questions[' + qIndex + '][score]'" min="0" required />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Link ảnh (nếu có): </label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" v-model="question.urlImage"
                                                    :name="'questions[' + qIndex + '][urlImage]'" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Loại câu hỏi: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" v-model="question.type"
                                                    :name="'questions[' + qIndex + '][type]'">
                                                    <option value="essay">Tự luận</option>
                                                    <option value="multiple_choice">Trắc nghiệm</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div v-if="question.type === 'essay'" class="form-group row">
                                            <label class="col-sm-3 col-form-label">Đáp án: </label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" v-model="question.answerText" :name="'questions[' + qIndex + '][answerText]'" required></textarea>
                                            </div>
                                        </div>
                                        <div v-if="question.type === 'multiple_choice'" class="form-group row">
                                            <label class="col-sm-3 col-form-label">Số lượng đáp án: </label>
                                            <div class="col-sm-3">
                                                <input type="number" class="form-control" v-model="question.answerCount"
                                                    @change="updateAnswers(qIndex)" min="2" required />
                                            </div>
                                        </div>
                                        <div v-if="question.type === 'multiple_choice'"
                                            v-for="(answer, aIndex) in question.answers" :key="aIndex"
                                            class="form-group row">
                                            <label class="col-sm-3 col-form-label">Đáp án @{{ aIndex + 1 }}: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" v-model="answer.answerText"
                                                    :name="'questions[' + qIndex + '][answers][' + aIndex + '][answerText]'"
                                                    required />
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        :name="'questions[' + qIndex + '][answers][' + aIndex + '][isCorrect]'"
                                                        v-model="answer.isCorrect">
                                                    <label class="form-check-label">Đúng</label>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-danger" @click="removeQuestion(qIndex)">Xóa
                                            câu hỏi</button>
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-buttons-container" style="display: flex; float: right;">
                                    <button type="button" class="btn btn-warning" onclick="goBack()"
                                        style="margin-right: 10px">Quay lại</button>
                                    <button type="button" class="btn btn-primary btn-add-question"
                                        style="margin-right: 10px" @click="addQuestion">Thêm câu hỏi</button>
                                    <button type="submit" class="btn btn-success btn-create-test">Tạo bài kiểm
                                        tra</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                questions: [{
                    questionText: '',
                    score: 10,
                    urlImage: '',
                    type: 'essay',
                    answerText: '',
                    answerCount: 2,
                    answers: [{
                        answerText: '',
                        isCorrect: false
                    }, {
                        answerText: '',
                        isCorrect: false
                    }]
                }],
            },
            methods: {
                addQuestion() {
                    this.questions.push({
                        questionText: '',
                        score: 10,
                        urlImage: '',
                        type: 'essay',
                        answerText: '',
                        answerCount: 2,
                        answers: [{
                            answerText: '',
                            isCorrect: false
                        }, {
                            answerText: '',
                            isCorrect: false
                        }]
                    });
                },
                removeQuestion(index) {
                    this.questions.splice(index, 1);
                },
                updateAnswers(qIndex) {
                    let question = this.questions[qIndex];
                    if (question.answers.length > question.answerCount) {
                        question.answers = question.answers.slice(0, question.answerCount);
                    } else {
                        while (question.answers.length < question.answerCount) {
                            question.answers.push({
                                answerText: '',
                                isCorrect: false
                            });
                        }
                    }
                }
            }
        });
    </script>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
