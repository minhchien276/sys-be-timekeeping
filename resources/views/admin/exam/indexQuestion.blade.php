@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách câu hỏi </h3>

            <a href="{{ route('question.create') }}">Thêm câu hỏi mới</a>
        </div>
        <div class="row">
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
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> STT </th>
                                        <th> Bài kiểm tra </th>
                                        <th> Câu hỏi </th>
                                        <th> Điểm </th>
                                        <th> Loại </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->questionText }}</td>
                                            <td>{{ $item->marks }}</td>
                                            <td>
                                                @if ($item->type == 1)
                                                    Trăc nghiệm
                                                @else
                                                    Tự luận
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
