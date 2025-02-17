@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Cập nhật thời gian checkin </h3>
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
                <form method="post" action="{{ route('checkin.update', $checkin->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="fullname">Tên nhân viên</label>
                        <select class="form-control" name="fullname" id="fullname" disabled>
                            <option value="{{ $checkin->employeeId }}">{{ $checkin->fullname }}</option>
                        </select>
                        @error('fullname')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="checkin">Checkin</label>
                        <input type="datetime-local" class="form-control" id="checkin" name="checkin"
                            value="{{ $checkin->checkin }}">
                        @error('checkin')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="location">Vị trí</label>
                        <input type="text" class="form-control" id="location" name="location"
                            value="{{ $checkin->location }}" disabled>
                        @error('location')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="latitude">Vĩ độ</label>
                        <input type="text" class="form-control" id="latitude" name="latitude"
                            value="{{ $checkin->latitude }}" disabled>
                        @error('latitude')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="longtitude">Kinh độ</label>
                        <input type="text" class="form-control" id="longtitude" name="longtitude"
                            value="{{ $checkin->longtitude }}" disabled>
                        @error('longtitude')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="meter">Khoảng cách (m)</label>
                        <input type="number" class="form-control" id="meter" name="meter"
                            value="{{ $checkin->meter }}" disabled>
                        @error('meter')
                            <span style="color: red; width: 100%; font-size: smaller;">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Cập nhật</button>
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
