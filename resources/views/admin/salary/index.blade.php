@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Nhập xuất lương
            </h3>
        </div>
        <div class="row">
            @if (session('success'))
                <div class="alert alert-success" id="success-alert">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('success-alert').style.display = 'none';
                    }, 5000);
                </script>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger" id="error-alert">
                    {{ Session::get('error') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('error-alert').style.display = 'none';
                    }, 5000);
                </script>
            @endif
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('export.daywork') }}">
                            @csrf
                            <div class="form-group">
                                <label for="startOfDay">Từ ngày: </label>
                                <input type="date" name="startOfDay" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="endOfDay">Đến ngày: </label>
                                <input type="date" name="endOfDay" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary me-2">Xuất file</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('import.daywork') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">File excel</label>
                                <input type="file" name="file" accept=".xlsx, .xls, .csv" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="title" name="title" class="form-control" value="THÔNG BÁO KỲ LƯƠNG THÁNG {{ $lastMonth }}/{{ $currentYear }}">
                            </div>
                            <div class="form-group">
                                <label for="content">Nội dung</label>
                                <input type="content" name="content" class="form-control" value="TMSC VIETNAM Thông báo chi trả kỳ lương Tháng {{ $lastMonth }} năm {{ $currentYear }}">
                            </div>

                            <button type="submit" class="btn btn-primary me-2">Nhập file</button>
                        </form>
                    </div>
                </div>
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
