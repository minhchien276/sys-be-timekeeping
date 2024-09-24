<!DOCTYPE html>
<html>
<style>
    * {
        font-family: DejaVu Sans !important;
    }

    .background-image {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100vh;
        filter: blur(10px);
        opacity: 0.05;
        z-index: -1;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Đơn Hàng</title>
</head>

<body>
    <div class="container">
        <img class="background-image"
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/Asset2.svg'))) }}">
        {{-- <div class="header">
            <div>
                <div style="float: left; text-align: center;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/Asset1.svg'))) }}"
                        style="width: 150px" alt="logo">
                </div>
                <div style="float: right;">
                    <b style="font-size: 20px">{{ $title }}</b><br>
                    {{ $expert->address }}<br>
                    ĐT: {{ $expert->phone }}<br>
                    Website: {{ $expert->website }}<br>
                </div>
            </div>
        </div>
        <br><br><br><br><br> --}}
        <h3 style="text-align: center; font-size: 20px">{{ $order_name->title }}</h3>
        <div class="body">
            @foreach ($orders as $item)
                <b>{{ $loop->iteration }}. TÊN SẢN PHẨM: {{ $item->medicine }}</b> <br>
                <b style="font-size: 15px">- Tác dụng:</b> {{ $item->uses }}<br>
                <b style="font-size: 15px">- Dặn dò:</b> {{ $item->dosage }}, {{ $item->advice }}<br>
            @endforeach
            {{-- <br> --}}
            <b>CHÚ Ý</b><br>
            <span style="font-size: 15px">{!! $expert->note !!}</span>
            {{-- <br> --}}

            @if ($expert->qrCode == 1)
                Quét mã QR sau đây để xem clip hướng dẫn massage da đầu <br>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/Asset3.svg'))) }}"
                    style="width: 150px" alt="maqr">
            @endif
        </div>
        <br>
        {{-- <div class="footer" style="float: right; text-align: center;">
            <b>Chuyên gia điều trị</b><br>
            <span>{{ $expert->name }} - {{ $expert->phone }}</span>
        </div> --}}
        <img class="background-image"
            src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/images/Asset2.svg'))) }}">
    </div>
</body>

</html>
