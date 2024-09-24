@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách chấm công về </h3>

            <form action="{{ route('checkout.details-checkout') }}" method="post">
                @csrf
                <div style="display: flex; justify-content: center;">
                    <input style="margin-right: 10px; width: 250px" type="text" id="email" name="email"
                        placeholder="Nhập e-mail ..." class="form-control" @required(true)
                        @if ($email) value="{{ old('email', $email) }}" @endif />
                    <input style="margin-right: 10px; width: 250px" type="date" id="from_date" name="from_date"
                        class="form-control" @required(true)
                        @if ($from_date) value="{{ old('from_date', $from_date) }}" @endif />
                    <input style="margin-right: 10px; width: 250px" type="date" id="to_date" name="to_date"
                        class="form-control" @required(true)
                        @if ($to_date) value="{{ old('to_date', $to_date) }}" @endif />
                    <button style="width: 200px;" id="checkButton" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>
            @php
                $roleIt = session()->has('user') && session()->get('departmentId') == 3;
                $roleHr = session()->has('user') && session()->get('departmentId') == 6;
            @endphp

            @if ($roleIt || $roleHr)
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
                                    @if ($checkout)
                                        @foreach ($checkout as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <img src="{{ $item->image }}" style="width: 50px; height: 50px"
                                                        alt="image">
                                                </td>
                                                <td>{{ $item->fullname }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>
                                                    @php
                                                        $checkoutTime = strtotime($item->checkout);
                                                        $thresholdTime =
                                                            $item->departmentId == 4 ? '17:00:00' : '17:30:00';
                                                        $checkoutHour = date('H:i:s', $checkoutTime);
                                                    @endphp

                                                    <div
                                                        class="alert alert-{{ $checkoutHour >= $thresholdTime ? 'success' : 'danger' }}">
                                                        {{ $item->checkout }}
                                                    </div>
                                                </td>
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
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
