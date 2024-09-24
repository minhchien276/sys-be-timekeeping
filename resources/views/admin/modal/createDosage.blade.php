<button id="createDosageBtn" class="btn btn-primary">Thêm liểu lượng</button>

<div class="modal fade" id="createDosage" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Thêm liểu lượng mới</h5>
            </div>
            <form id="createFormDosage" action="{{ route('orders.create-dosage') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="dosage">Tên liểu lượng:</label>
                        <input type="text" id="dosage" class="form-control" name="dosage">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveDosage" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>