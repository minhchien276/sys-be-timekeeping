<button id="createTypeMedicineBtn" class="btn btn-primary">Thêm loại thuốc</button>

<div class="modal fade" id="createTypeMedicine" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Thêm loại thuốc mới</h5>
            </div>
            <form id="createFormMedicineType" action="{{ route('orders.create-type-medicine') }}"
                method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="medicineType">Tên loại thuốc:</label>
                        <input type="text" id="medicineType" class="form-control" name="medicineType">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveTypeMedicine" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>