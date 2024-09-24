@extends('layout_master')

@section('content')
    <div class="row">
        <div class="col-sm-4 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4>Số lượng nhân sự</h4>
                    <div class="row">
                        <div class="col-8 col-sm-12 col-xl-8 my-auto">
                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                                <h2 class="mb-0">{{ $employee }}</h2>
                            </div>
                        </div>
                        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                            <i class="icon-lg mdi mdi-account text-success ms-auto"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4>Số lượng đơn từ</h4>
                    <div class="row">
                        <div class="col-8 col-sm-12 col-xl-8 my-auto">
                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                                <h2 class="mb-0">{{ $application }}</h2>
                            </div>
                            <h6 class="text-muted font-weight-normal"> {{ $dayoff }} đơn nghỉ phép,
                                {{ $overtime }} đơn tăng ca, {{ $early_lated }} đơn xin đi muộn về sớm </h6>
                        </div>
                        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                            <i class="icon-lg mdi mdi-file-document text-warning ms-auto"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4>Số lượng bài viết</h4>
                    <div class="row">
                        <div class="col-8 col-sm-12 col-xl-8 my-auto">
                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                                <h2 class="mb-0">{{ $blog }}</h2>
                            </div>
                        </div>
                        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                            <i class="icon-lg mdi mdi-blogger text-primary ms-auto"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4>Số lượng nhân viên nghỉ phép hôm nay</h4>
                    <div class="row">
                        <div class="col-8 col-sm-12 col-xl-8 my-auto">
                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                                <h2 class="mb-0">{{ $dayoffToday }}</h2>
                            </div>
                            <h6 class="text-muted font-weight-normal"> {{ $dayoffAllday }} nhân viên nghỉ phép cả ngày,
                                {{ $dayoffMorning }} nhân viên nghỉ phép buổi sáng, {{ $dayoffAfternoon }} nhân viên nghỉ
                                phép buổi chiều </h6>
                        </div>
                        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                            <i class="icon-lg mdi mdi-calendar-today text-primary ms-auto"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
