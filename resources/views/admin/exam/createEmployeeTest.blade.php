@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Kiểm tra
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
                    {{-- <script>
                        setTimeout(function() {
                            document.getElementById('error-alert').style.display = 'none';
                        }, 3000);
                    </script> --}}
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
                <form method="post" action="{{ route('store-employee-test') }}">
                    @csrf
                    <div class="form-group">
                        <label for="testId">Chọn bài kiểm tra</label>
                        <select class="form-control" name="testId" id="testId">
                            @foreach ($tests as $item)
                                <option value="{{ $item->testId }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="employee">Chọn nhân viên</label>
                        <select class="form-control" name="employee[]" id="employee" multiple style="height: 300px;">
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}">{{ $item->fullname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="expired">Hạn nộp bài</label>
                        <input type="datetime-local" name="expired" id="expired" class="form-control"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
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
