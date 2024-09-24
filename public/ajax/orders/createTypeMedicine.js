document
    .getElementById("createTypeMedicineBtn")
    .addEventListener("click", function () {
        $("#createTypeMedicine").modal("show");
    });

$(document).ready(function () {
    $("#saveTypeMedicine").click(function (e) {
        e.preventDefault();

        // Lấy dữ liệu từ form
        var medicineType = $("#medicineType").val();

        // Gửi yêu cầu Ajax
        $.ajax({
            url: $("#createFormMedicineType").attr("action"),
            type: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                medicineType: medicineType,
            },
            success: function (response) {
                // Xử lý phản hồi thành công
                // Đóng modal và làm sạch form
                $("#createTypeMedicine").modal("hide");
                $("#medicineType").val("");

                // Cập nhật danh sách hôp test
                alertify.success("Thêm mới loại thuốc thành công");

                $("#typeMedicineContainer").load(
                    window.location.href + " #typeMedicineContainer"
                );
            },
            error: function (xhr, status, error) {
                // Xử lý phản hồi lỗi
                console.log(xhr.responseText);
                alertify.error("Thêm mới loại thuốc thất bại");
            },
        });
    });
});
