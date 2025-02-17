@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách câu tự luân chưa chấm điểm </h3>

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
                                        <th> Nhân viên </th>
                                        <th> Câu hỏi </th>
                                        <th> Chấm điểm </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employeeanswers as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->fullname }}</td>
                                            <td>{{ $item->questionText }}</td>
                                            <td>
                                                <a href="{{ URL::to('/admin/test/caculate-score/' . $item->employeeAnswerId) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-table-edit"></i>
                                                </a>
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
