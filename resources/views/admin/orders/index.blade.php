@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách đơn thuốc </h3>

            <form action="{{ route('orders.search-date') }}" method="post">
                @csrf
                <div style="display: flex; justify-content: center;">
                    <input style="margin-right: 10px; width: 250px" type="date" id="search_date"
                        name="search_date" class="form-control" @required(true)
                        @if ($search_date) value="{{ old('search_date', $search_date) }}" @endif />
                    <button style="width: 200px;" id="checkButton" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>

            <a href="{{ route('orders.create') }}">Tạo đơn thuốc</a>
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
                                        <th> Tiêu đề </th>
                                        <th> Chuyên gia </th>
                                        <th> Thời gian tạo </th>
                                        <th> Xuất pdf </th>
                                        <th> Xóa </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->createdAt }}</td>
                                            <td>
                                                <a href="{{ URL::to('/admin/orders/generate-pdf/' . $item['id']) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-export"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ URL::to('/admin/orders/delete/' . $item['id']) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-delete"></i>
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
