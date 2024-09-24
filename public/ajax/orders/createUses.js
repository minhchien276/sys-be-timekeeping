document.getElementById("createUsesBtn").addEventListener("click", function () {
    $("#createUses").modal("show");
});

$(document).ready(function () {
    $("#saveUses").click(function (e) {
        e.preventDefault();

        // Lấy dữ liệu từ form
        var uses = $("#uses").val();

        // Gửi yêu cầu Ajax
        $.ajax({
            url: $("#createFormUses").attr("action"),
            type: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                uses: uses,
            },
            success: function (response) {
                // Xử lý phản hồi thành công
                // Đóng modal và làm sạch form
                $("#createUses").modal("hide");
                $("#uses").val("");

                // Cập nhật danh sách hôp test
                alertify.success("Thêm mới tác dụng thành công");

                $("#usesContainer").load(
                    window.location.href + " #usesContainer"
                );
            },
            error: function (xhr, status, error) {
                // Xử lý phản hồi lỗi
                console.log(xhr.responseText);
                alertify.error("Thêm mới tác dụng thất bại");
            },
        });
    });
});
