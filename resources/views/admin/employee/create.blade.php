@extends('layout_master')

@section('css')
@endsection


@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Thêm nhân viên mới </h3>
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
                            @if ($errors->any())
                                <div class="alert alert-danger" id="error-alert">
                                    @if ($errors->has('image'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('image') }}
                                        </div>
                                    @elseif ($errors->has('fullname'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('fullname') }}
                                        </div>
                                    @elseif ($errors->has('birthday'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('birthday') }}
                                        </div>
                                    @elseif ($errors->has('identification'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('identification') }}
                                        </div>
                                    @elseif ($errors->has('salary'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('salary') }}
                                        </div>
                                    @elseif ($errors->has('dayOff'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('dayOff') }}
                                        </div>
                                    @elseif ($errors->has('email'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @elseif ($errors->has('phone'))
                                        <div class="alert alert-danger">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    @endif
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('error-alert').style.display = 'none';
                                    }, 3000);
                                </script>
                            @endif

                            <form method="post" action="{{ route('employee.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Link ảnh: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="image" id="image" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Họ và tên: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="fullname" id="fullname" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Sinh nhật: </label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" name="birthday" id="birthday" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">CCCD/CMND: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="identification"
                                                    id="identification" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Lương: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="salary" id="salary" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Ngày nghỉ phép: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="dayOff" id="dayOff" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Email: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="email" id="email" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Số điện thoại: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="phone" id="phone" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Phòng ban: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="departmentId">
                                                    @foreach ($department as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Leader: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="leaderId">
                                                    <option value="">Hãy chọn leader</option>
                                                    @foreach ($leaders as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->fullname }}
                                                    </option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Chức vụ: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="roleId">
                                                    @foreach ($role as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-buttons-container" style="display: flex;float: right;">
                                    <button type="button" class="btn btn-warning" onclick="goBack()"
                                        style="margin-right: 10px">Quay lại</button>
                                    <button type="submit" class="btn btn-success btn-create-order">Thêm nhân
                                        viên</button>
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
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
