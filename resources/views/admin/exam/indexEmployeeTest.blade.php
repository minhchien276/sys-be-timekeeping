@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách nhân viên đã kiểm tra</h3>

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
                                        <th> Điểm trắc nghiệm </th>
                                        <th> Điểm tự luận </th>
                                        <th> Thời gian bắt đầu </th>
                                        <th> Thời gian kết thúc </th>
                                        <th> Chi tiết </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->fullname }}</td>
                                            <td>{{ $item->scoreChoice }}</td>
                                            <td>{{ $item->scoreEssay }}</td>
                                            <td>{{ $item->startTime }}</td>
                                            <td>{{ $item->endTime }}</td>
                                            <td>
                                                <a href="{{ URL::to('/admin/test/details-employee-tests/' . $item->employeeId . '/' . $item->testId) }}"
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
