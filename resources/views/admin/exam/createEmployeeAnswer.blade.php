@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            {{-- <h3 class="page-title"> Chấm điểm
            </h3> --}}
        </div>
        <div class="card">
            <div class="card-body">
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
                @if ($errors->any())
                    <div class="alert alert-danger" id="error-alert">
                        @if ($errors->has('title'))
                            <div class="alert alert-danger">
                                {{ $errors->first('title') }}
                            </div>
                        @elseif ($errors->has('content'))
                            <div class="alert alert-danger">
                                {{ $errors->first('content') }}
                            </div>
                        @elseif ($errors->has('image'))
                            <div class="alert alert-danger">
                                {{ $errors->first('image') }}
                            </div>
                        @endif
                    </div>
                    <script>
                        setTimeout(function() {
                            document.getElementById('error-alert').style.display = 'none';
                        }, 3000);
                    </script>
                @endif
                <form method="post" action="{{ route('store-employee-answer') }}">
                    @csrf
                    @foreach ($employeeanswers as $item)
                        <input type="hidden" name="employeeAnswerId[]" class="form-control"
                            value="{{ $item->employeeAnswerId }}">
                        <div class="form-group">
                            <label style="font-weight: 700" for="questionText">Câu hỏi: <span>
                                    {{ $item->questionText }}</span></label>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="inputAnswer">Câu trả lời của nhân viên: </label>
                                    <textarea name="inputAnswer" disabled id="inputAnswer" cols="30" rows="20" class="form-control">{{ $item->inputAnswer }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="score">Chấm điểm: </label>
                                    <input type="text" name="score[]" class="form-control" required min="0"
                                        max="{{ $item->marks }}">
                                </div>

                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="answerText">Câu trả lời mẫu: </label>
                                    <textarea name="answerText" disabled id="answerText" cols="30" rows="20" class="form-control">{{ $item->answerText }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="marks">Thang điểm: </label>
                                    <input disabled type="text" name="marks" class="form-control"
                                        value="{{ $item->marks }}">
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                    <button type="submit" class="btn btn-primary me-2">Lưu điểm</button>
                    <button type="button" class="btn btn-secondary" onclick="goBack()">Quay lại</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
