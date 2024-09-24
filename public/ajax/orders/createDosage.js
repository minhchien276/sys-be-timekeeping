document
    .getElementById("createDosageBtn")
    .addEventListener("click", function () {
        $("#createDosage").modal("show");
    });

$(document).ready(function () {
    $("#saveDosage").click(function (e) {
        e.preventDefault();

        // Lấy dữ liệu từ form
        var dosage = $("#dosage").val();

        // Gửi yêu cầu Ajax
        $.ajax({
            url: $("#createFormDosage").attr("action"),
            type: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                dosage: dosage,
            },
            success: function (response) {
                // Xử lý phản hồi thành công
                // Đóng modal và làm sạch form
                $("#createDosage").modal("hide");
                $("#dosage").val("");

                // Cập nhật danh sách hôp test
                alertify.success("Thêm mới liều lượng thành công");

                $("#dosageContainer").load(
                    window.location.href + " #dosageContainer"
                );
            },
            error: function (xhr, status, error) {
                // Xử lý phản hồi lỗi
                console.log(xhr.responseText);
                alertify.error("Thêm mới liều lượng thất bại");
            },
        });
    });
});
