@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách bài kiểm tra </h3>

            {{-- <a href="{{ route('question.index') }}">Danh sách câu hỏi</a> --}}

            {{-- <a href="{{ route('answer.index') }}">Danh sách câu trả lời</a> --}}

            {{-- <a href="{{ route('mark.index') }}">Chấm điểm tự luận</a> --}}

            <a href="{{ route('create-employee-test') }}">Kiểm tra</a>

            <a href="{{ route('test.create') }}">Thêm bài kiểm tra mới</a>
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
                                        <th> Tiêu đề </th>
                                        <th> Mô tả </th>
                                        <th> Tổng điểm </th>
                                        <th> Số lượng câu hỏi </th>
                                        <th> Tạo bài kiểm tra </th>
                                        <th> Chấm điểm tự luận </th>
                                        <th> Chi tiết </th>
                                    </tr>
                                </thead>
                                <tbody id="tests-table-body">
                                    <!-- Dữ liệu sẽ được thêm vào đây bằng JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center" style="margin-top: 15px;">
                            <nav>
                                <ul class="pagination" id="pagination">
                                    <!-- Liên kết phân trang sẽ được thêm vào đây bằng JavaScript -->
                                </ul>
                            </nav>
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
            const data = @json($tests);
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
                    const title = item.title.length > 50 ? breakText(item.title) : item
                        .title;
                    const description = item.description.length > 50 ? breakText(item.description) : item
                        .description;

                    const createTestUrl = `/admin/test/create-all-test/${item.testId}`;
                    const employeeAnswerUrl = `/admin/test/index-employee-answer/${item.testId}`;
                    const employeeTestUrl = `/admin/test/index-employee-tests/${item.testId}`;

                    tbody.innerHTML += `
                        <tr>
                            <td>${startIndex + index + 1}</td>
                            <td>${title}</td>
                            <td>${description}</td> 
                            <td>${item.totalMarks}</td>
                            <td>${item.question_count}</td>
                            <td>
                                <a href="${createTestUrl}" class="btn btn-sm btn-primary">
                                    <i class="mdi mdi-table-edit"></i>
                                </a>
                            </td>
                            <td>
                                <a href="${employeeAnswerUrl}" class="btn btn-sm btn-primary">
                                    <i class="mdi mdi-table-edit"></i>
                                </a>
                            </td>
                            <td>
                                <a href="${employeeTestUrl}" class="btn btn-sm btn-primary">
                                    <i class="mdi mdi-table-edit"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                });
            }

            function setupPagination() {
                const totalPages = Math.ceil(data.length / itemsPerPage);
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    pagination.innerHTML += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#">${i}</a></li>`;
                }

                document.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        currentPage = Number(this.textContent);
                        displayData(currentPage);
                        setupPagination();
                    });
                });
            }

            displayData(currentPage);
            setupPagination();
        });
    </script>
@endsection
