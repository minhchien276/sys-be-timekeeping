document
    .getElementById("createAdviceBtn")
    .addEventListener("click", function () {
        $("#createAdvice").modal("show");
    });

$(document).ready(function () {
    $("#saveAdvice").click(function (e) {
        e.preventDefault();

        // Lấy dữ liệu từ form
        var advice = $("#advice").val();

        // Gửi yêu cầu Ajax
        $.ajax({
            url: $("#createFormAdvice").attr("action"),
            type: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                advice: advice,
            },
            success: function (response) {
                // Xử lý phản hồi thành công
                // Đóng modal và làm sạch form
                $("#createAdvice").modal("hide");
                $("#advice").val("");

                // Cập nhật danh sách hôp test
                alertify.success("Thêm mới dặn dò thành công");

                $("#adviceContainer").load(
                    window.location.href + " #adviceContainer"
                );
            },
            error: function (xhr, status, error) {
                // Xử lý phản hồi lỗi
                console.log(xhr.responseText);
                alertify.error("Thêm mới dặn dò thất bại");
            },
        });
    });
});
