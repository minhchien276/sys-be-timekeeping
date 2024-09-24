@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Thêm checkin </h3>
        </div>
        <div class="card">
            <div class="card-body">
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
                <form method="post" action="{{ route('checkin.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="fullname">Tên nhân viên</label>
                        <select class="form-control" name="fullname" id="fullname">
                            @foreach ($employees as $item)
                                <option value="{{ $item->id }}">{{ $item->fullname }}</option>
                            @endforeach
                        </select>
                        @error('fullname')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="checkin">Checkin</label>
                        <input type="datetime-local" class="form-control" id="checkin" name="checkin">
                        @error('checkin')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="location">Vị trí</label>
                        <input type="text" class="form-control" id="location" name="location"
                            value="3Q2W+9FC, 3Q2W+9FC, Tây Hồ, Hà Nội, Việt Nam">
                        @error('location')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="latitude">Vĩ độ</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="21.0508342219035">
                        @error('latitude')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="longtitude">Kinh độ</label>
                        <input type="text" class="form-control" id="longtitude" name="longtitude"
                            value="105.79613255233873">
                        @error('longtitude')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="meter">Khoảng cách (m)</label>
                        <input type="number" class="form-control" id="meter" name="meter" value="7">
                        @error('meter')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Thêm</button>
                    {{-- <button type="button" class="btn btn-dark" onclick="goBack()">Quay lại</button> --}}
                </form>
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
