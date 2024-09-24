document.getElementById("createButton").addEventListener("click", function () {
    $("#createModal").modal("show");
});

$(document).ready(function () {
    $("#saveButton").click(function (e) {
        e.preventDefault();

        // Lấy dữ liệu từ form
        var medicineName = $("#medicineName").val();

        // Gửi yêu cầu Ajax
        $.ajax({
            url: $("#createForm").attr("action"),
            type: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                medicineName: medicineName,
            },
            success: function (response) {
                // Xử lý phản hồi thành công
                // Đóng modal và làm sạch form
                $("#createModal").modal("hide");
                $("#medicineName").val("");

                // Cập nhật danh sách hôp test
                alertify.success("Thêm mới thuốc thành công");

                $("#medicineContainer").load(
                    window.location.href + " #medicineContainer"
                );
            },
            error: function (xhr, status, error) {
                // Xử lý phản hồi lỗi
                console.log(xhr.responseText);
                alertify.error("Thêm mới thuốc thất bại");
            },
        });
    });
});
