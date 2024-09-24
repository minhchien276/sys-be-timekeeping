@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Chi tiết bài kiểm tra </h3>

            <button type="button" class="btn btn-primary" onclick="goBack()">Quay lại</button>
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
                                        <th> Loại câu hỏi </th>
                                        <th> Câu hỏi </th>
                                        <th> Câu trả lời của nhân viên </th>
                                        <th> Điểm trên điểm tổng</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->type == 1)
                                                    Trắc nghiệm
                                                @else
                                                    Tự luận
                                                @endif
                                            </td>
                                            <td>{{ $item->questionText }}</td>
                                            <td>
                                                @if ($item->inputAnswer != null)
                                                    {{ $item->inputAnswer }}
                                                @else
                                                    {{ $item->selectedAnswerId }}
                                                @endif
                                            </td>
                                            <td>{{ $item->score != null ? $item->score : 0 }} / {{ $item->marks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody> --}}
                                <tbody id="tests-table-body">
                                    <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function breakText(text) {
            const maxLength = 65;
            let brokenText = '';
            let currentLength = 0;
            let currentLine = '';

            // Duyệt qua từng từ trong nội dung
            text.split(' ').forEach(word => {
                if (currentLength + word.length <= maxLength) {
                    // Nếu độ dài hiện tại không vượt quá maxLength, thêm từ vào dòng hiện tại
                    currentLine += (currentLine ? ' ' : '') + word;
                    currentLength += word.length + 1; // Độ dài từ cộng thêm 1 cho dấu cách
                } else {
                    // Nếu độ dài vượt quá maxLength, chuyển sang dòng mới
                    brokenText += (brokenText ? '<br><br>' : '') + currentLine;
                    currentLine = word;
                    currentLength = word.length;
                }
            });

            // Thêm dòng cuối cùng
            brokenText += (brokenText ? '<br><br>' : '') + currentLine;

            return brokenText;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const data = @json($data);
            const itemsPerPage = 20;
            let currentPage = 1;

            function displayData(page) {
                const startIndex = (page - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const paginatedData = data.slice(startIndex, endIndex);

                const tbody = document.getElementById('tests-table-body');
                tbody.innerHTML = '';

                paginatedData.forEach((item, index) => {
                    // Thêm dấu xuống dòng nếu nội dung quá 50 ký tự
                    const questionText = item.questionText.length > 50 ? breakText(item.questionText) : item
                        .questionText;
                    const inputAnswer = item.inputAnswer && item.inputAnswer.length > 50 ? breakText(item
                        .inputAnswer) : item.inputAnswer;
                    const answerText = item.answerText && item.answerText.length > 50 ? breakText(item
                        .answerText) : item.answerText;

                    // Xác định loại câu hỏi
                    const questionType = item.type == 1 ? 'Trắc nghiệm' : 'Tự luận';

                    // Xác định câu trả lời
                    const answer = item.inputAnswer != null ? inputAnswer : answerText;

                    // Tính toán điểm số
                    const score = item.score != null ? item.score : 0;

                    tbody.innerHTML += `
            <tr>
                <td>${startIndex + index + 1}</td>
                <td>${questionType}</td>
                <td>${questionText}</td>
                <td>${answer}</td>
                <td>${score} / ${item.marks}</td>
            </tr>
        `;
                });
            }

            displayData(currentPage);
        });
    </script>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
