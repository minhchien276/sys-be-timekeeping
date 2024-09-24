@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Thêm câu hỏi
            </h3>
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
                <form method="post" action="{{ route('question.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="testId">Loại bài kiểm tra</label>
                        <select class="form-control" name="testId" id="testId">
                            @foreach ($tests as $item)
                                <option value="{{ $item->testId }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="questionText">Câu hỏi</label>
                        <textarea name="questionText" id="questionText" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="marks">Điểm</label>
                        <input type="text" name="marks" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="marks">Loại</label>
                        <select class="form-control" name="type" id="type">
                            <option value="1">Trắc nghiệm</option>
                            <option value="2">Tự luận</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Thêm mới</button>
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
