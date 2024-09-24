@extends('layout_master')

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Danh sách đơn xin nghỉ phép </h3>

            <form action="{{ route('dayoff.search-date') }}" method="post">
                @csrf
                <div style="display: flex; justify-content: center;">
                    <input style="margin-right: 10px; width: 250px" type="date" id="search_date" name="search_date"
                        class="form-control" @required(true)
                        @if ($search_date) value="{{ old('search_date', $search_date) }}" @endif />
                    <button style="width: 200px;" id="checkButton" class="btn btn-primary">Tìm kiếm</button>
                </div>
            </form>

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
                                        <th> Lý do </th>
                                        <th> Người gửi </th>
                                        <th> Buổi nghỉ </th>
                                        <th> Thời gian gửi đơn </th>
                                        <th> Thời gian nghỉ </th>
                                        <th> Duyệt </th>
                                    </tr>
                                </thead>
                                <tbody id="dayoff-table-body">
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
            const maxLength = 60;
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
            const data = @json($dayoff);
            const itemsPerPage = 20;
            let currentPage = 1;

            function displayData(page) {
                const startIndex = (page - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const paginatedData = data.slice(startIndex, endIndex);

                const tbody = document.getElementById('dayoff-table-body');
                tbody.innerHTML = '';

                paginatedData.forEach((item, index) => {
                    const sessionText = item.session == 0 ? 'Nghỉ cả ngày' : item.session == 1 ?
                        'Nghỉ buổi sáng' : 'Nghỉ buổi chiều';
                    const statusText = item.status == 1 ? 'Đã duyệt' : item.status === null ? 'Chưa xử lý' :
                        'Đã từ chối';
                    const statusClass = item.status == 1 ? 'alert-success' : item.status === null ?
                        'alert-secondary' : 'alert-danger';

                    // Thêm dấu xuống dòng nếu nội dung quá 50 ký tự
                    const content = item.content.length > 50 ? breakText(item.content) : item.content;

                    tbody.innerHTML += `
                <tr>
                    <td>${startIndex + index + 1}</td>
                    <td>${item.title}</td>
                    <td>${content}</td> <!-- Sử dụng nội dung đã được chia để hiển thị -->
                    <td>${item.fullname}</td>
                    <td>${sessionText}</td>
                    <td>${item.createdAt}</td>
                    <td>${item.dayOffDate}</td>
                    <td><div class="alert ${statusClass}">${statusText}</div></td>
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
