@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách nhân viên </h3>

            <form action="{{ route('employee.search') }}" method="post">
                @csrf
                <div style="display: flex; justify-content: center;">
                    <input style="margin-right: 10px; width: 250px" type="text" id="key_search" name="key_search"
                        class="form-control" @required(true)
                        @if ($key_search) value="{{ old('key_search', $key_search) }}" @endif />
                    <button style="width: 200px;" id="checkButton" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>

            <a href="{{ route('employee.create') }}">Thêm nhân viên</a>
            <a href="{{ route('employee.index-retired') }}">Nhân viên đã nghỉ</a>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            @php
                                $roleIt = session()->has('user') && session()->get('departmentId') == 3;
                                $roleHr = session()->has('user') && session()->get('departmentId') == 6;
                            @endphp
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> STT </th>
                                        @if ($roleIt)
                                            <th> Mã nhân viên </th>
                                        @endif
                                        <th> Ảnh </th>
                                        <th> Họ tên </th>
                                        <th> Email </th>
                                        <th> Sinh nhật </th>
                                        <th> Sđt </th>
                                        <th> Trạng thái </th>
                                        <th> Chi tiết </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @if ($roleIt)
                                                <td>{{ $item->employeeCode }}</td>
                                            @endif
                                            <td>
                                                <img src="{{ $item->image }}" style="width: 50px; height: 50px"
                                                    alt="image">
                                            </td>
                                            <td>{{ $item->fullname }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->birthday }}</td>
                                            <td>{{ $item->phone }}</td>
                                            <td>
                                                @if ($item->status == 1)
                                                    Hoạt động
                                                @else
                                                    Đã nghỉ
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ URL::to('/admin/employee/find/' . $item->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-eye"></i>
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
