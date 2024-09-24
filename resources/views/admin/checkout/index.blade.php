@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách chấm công về </h3>

            <form action="{{ route('checkout.search-date') }}" method="post">
                @csrf
                <div style="display: flex; justify-content: center;">
                    <input style="margin-right: 10px; width: 250px" type="date" id="search_date" name="search_date"
                        class="form-control" @required(true)
                        @if ($search_date) value="{{ old('search_date', $search_date) }}" @endif />
                    <button style="width: 200px;" id="checkButton" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
            @php
                $roleIt = session()->has('user') && session()->get('departmentId') == 3;
                $roleHr = session()->has('user') && session()->get('departmentId') == 6;
            @endphp
            @if ($roleIt || $roleHr)
                <a href="{{ route('checkout.index-details') }}">Chi tiết checkout</a>
                <a href="{{ route('checkout.create') }}">Thêm checkout</a>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> STT </th>
                                        <th> Ảnh </th>
                                        <th> Họ tên </th>
                                        <th> Email </th>
                                        <th> Giờ checkout </th>
                                        <th> Khoảng cách (m) </th>
                                        <th> Vị trí </th>
                                        @if ($roleIt)
                                            <th> Cập nhật </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checkout as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ $item->image }}" style="width: 50px; height: 50px"
                                                    alt="image">
                                            </td>
                                            <td>{{ $item->fullname }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->checkout }}</td>
                                            <td>{{ $item->meter }}</td>
                                            <td>{{ $item->location }}</td>
                                            @if ($roleIt)
                                                <td>
                                                    <a href="{{ URL::to('/admin/checkout/edit/' . $item->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="mdi mdi-table-edit"></i>
                                                    </a>
                                                </td>
                                            @endif
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
