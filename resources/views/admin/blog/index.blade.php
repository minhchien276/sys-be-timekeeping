@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách bài viết </h3>

            <a href="{{ route('blog.create') }}">Thêm bài viết mới</a>
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
                                        <th> Ảnh </th>
                                        <th> Tiêu đề </th>
                                        <th> Nội dung </th>
                                        <th> Thời gian </th>
                                        <th> Cập nhật </th>
                                        <th> Xóa </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($blog as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ $item->image }}" style="width: 100px; height: 100px"
                                                    alt="image">
                                            </td>
                                            <td>{{ $item->title }}</td>

                                            <td>{{ \Illuminate\Support\Str::limit($item->content, 50, '...') }}</td>
                                            <td>{{ $item->dateTimeBlog }}</td>
                                            <td>
                                                <a href="{{ URL::to('/admin/blog/edit/' . $item->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="mdi mdi-table-edit"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <form action="{{ route('blog.delete', $item->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?')">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
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
