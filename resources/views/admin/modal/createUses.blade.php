<button id="createUsesBtn" class="btn btn-primary">Thêm tác dụng</button>

<div class="modal fade" id="createUses" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Thêm tác dụng mới</h5>
            </div>
            <form id="createFormUses" action="{{ route('orders.create-uses') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="uses">Tên tác dụng:</label>
                        <input type="text" id="uses" class="form-control" name="uses">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveUses" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>