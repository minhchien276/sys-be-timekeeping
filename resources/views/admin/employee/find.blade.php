@extends('layout_master')

@section('css')
@endsection


@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Chi tiết nhân viên </h3>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="container" id="app">
                            @if (session()->has('success') || session()->has('error'))
                                @php
                                    $alertType = '';
                                    $message = '';
                                    if (session()->has('success')) {
                                        $alertType = 'success';
                                        $message = session()->get('success');
                                    } elseif (session()->has('error')) {
                                        $alertType = 'danger';
                                        $message = session()->get('error');
                                    }
                                @endphp
                                <div class="alert alert-{{ $alertType }}" id="alert-message">
                                    {{ $message }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('alert-message').style.display = 'none';
                                    }, 3000);
                                </script>
                            @endif
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-alert">
                                        {{ $error }}
                                    </div>
                                @endforeach
                                <script>
                                    setTimeout(function() {
                                        var errorAlerts = document.querySelectorAll('.error-alert');
                                        for (var i = 0; i < errorAlerts.length; i++) {
                                            errorAlerts[i].style.display = 'none';
                                        }
                                    }, 3000);
                                </script>
                            @endif

                            <form method="post" action="{{ route('employee.update', $employee->id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Link ảnh: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="image" id="image"
                                                    value="{{ old('image', $employee->image) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Họ và tên: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="fullname" id="fullname"
                                                    value="{{ old('fullname', $employee->fullname) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Sinh nhật: </label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" name="birthday" id="birthday"
                                                    value="{{ old('birthday', $employee->birthday) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">CCCD/CMND: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="identification"
                                                    id="identification"
                                                    value="{{ old('identification', $employee->identification) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Lương: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="salary" id="salary"
                                                    value="{{ old('salary', $employee->salary) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Ngày nghỉ phép: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="dayOff" id="dayOff"
                                                    value="{{ old('dayOff', $employee->dayOff) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Email: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="email" id="email"
                                                    value="{{ old('email', $employee->email) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Số điện thoại: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="phone" id="phone"
                                                    value="{{ old('phone', $employee->phone) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Phòng ban: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="departmentId">
                                                    @foreach ($department as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $employee->departmentId ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
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
                                                        <option value="{{ $item->id }}"
                                                            {{ $item->id == $employee->roleId ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Trạng thái hoạt động: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="status">
                                                    <option value="1"
                                                        {{ old('status', $employee->status) == '1' ? 'selected' : '' }}>
                                                        Đang hoạt động</option>
                                                    <option value="0"
                                                        {{ old('status', $employee->status) == '0' ? 'selected' : '' }}>
                                                        Đã nghỉ</option>
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
                                                    <option value="{{ $item->id }}"
                                                        {{ $employee->leaderId == $item->id ? 'selected' : '' }}>
                                                        {{ $item->fullname }}
                                                    </option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Logged: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="logged">
                                                    <option value="1"
                                                        {{ old('logged', $employee->logged) == '1' ? 'selected' : '' }}>
                                                        Đã đăng nhập</option>
                                                    <option value="0"
                                                        {{ old('logged', $employee->logged) == '0' ? 'selected' : '' }}>
                                                        Chưa đăng nhập</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-buttons-container" style="display: flex;float: right;">
                                    <a type="button" class="btn btn-warning" href="{{ route('employee.index') }}"
                                        style="margin-right: 10px">Quay lại</a>
                                    <button type="submit" class="btn btn-success btn-create-order">Cập nhật</button>
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
