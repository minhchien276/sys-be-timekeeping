<button id="createAdviceBtn" class="btn btn-primary">Thêm dặn dò</button>

<div class="modal fade" id="createAdvice" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Thêm dặn dò mới</h5>
            </div>
            <form id="createFormAdvice" action="{{ route('orders.create-advice') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="advice">Tên dặn dò:</label>
                        <input type="text" id="advice" class="form-control" name="advice">
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveAdvice" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>