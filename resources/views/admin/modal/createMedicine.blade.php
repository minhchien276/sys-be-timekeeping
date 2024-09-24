<button id="createButton" class="btn btn-primary">Thêm thuốc mới</button>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Thêm thuốc mới</h5>
            </div>
            <form id="createForm" action="{{ route('orders.create-medicine') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="medicineName">Tên thuốc:</label>
                        <input type="text" id="medicineName" class="form-control" name="medicineName">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveButton" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>