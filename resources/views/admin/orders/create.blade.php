@extends('layout_master')

@section('css')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Tạo đơn thuốc mới </h3>

            @include('admin.modal.createMedicine')
            @include('admin.modal.createTypeMedicine')
            @include('admin.modal.createUses')
            @include('admin.modal.createDosage')
            @include('admin.modal.createAdvice')
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="container" id="app">
                            @if (session('success'))
                                <div class="alert alert-success" id="success-alert">
                                    {{ session('success') }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('success-alert').style.display = 'none';
                                    }, 3000);
                                </script>
                            @endif
                            @if (Session::has('error'))
                                <div class="alert alert-danger" id="error-alert">
                                    {{ Session::get('error') }}
                                </div>
                                <script>
                                    setTimeout(function() {
                                        document.getElementById('error-alert').style.display = 'none';
                                    }, 3000);
                                </script>
                            @endif

                            <form class="form-sample" method="post" action="{{ route('orders.store') }}">
                                @csrf
                                <div class="row">
                                    <!-- Các trường thông tin không đổi -->
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Tiêu đề: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="title" id="title"
                                                    value="ĐƠN HÀNG " required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Chuyên gia: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="expert">
                                                    @foreach ($expert as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Lưu ý: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="note">
                                                    @foreach ($note as $item)
                                                        <option value="{{ $item->noteName }}">{{ $item->keyword }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Mã QR: </label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="qrCode">
                                                    <option value="0">Không</option>
                                                    <option value="1">Có</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- Trường nhập thuốc -->
                                    <div class="col-md-6" v-for="(medicine, index) in medicines" :key="index">
                                        <div id="medicineContainer">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Tên sản phẩm: </label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" v-model="medicine.medicineName"
                                                        :name="'medicines[' + index + '][medicineName]'">
                                                        <option value="other">Khác</option>
                                                        @foreach ($medicine as $item)
                                                            <option value="{{ $item->medicineName }}">
                                                                {{ $item->medicineName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input v-if="medicine.medicineName === 'other'" type="text"
                                                        class="form-control" v-model="medicine.otherMedicine"
                                                        placeholder="Nhập tên sản phẩm khác..." name="otherMedicine">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="typeMedicineContainer">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Loại sản phẩm: </label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" v-model="medicine.type"
                                                        :name="'type[' + index + '][type]'">
                                                        <option value="other">Khác</option>
                                                        @foreach ($type_medicine as $item)
                                                            <option value="{{ $item->type }}">{{ $item->type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input v-if="medicine.type === 'other'" type="text"
                                                        class="form-control" v-model="medicine.otherType"
                                                        placeholder="Nhập loại sản phẩm khác..." name="otherType">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="usesContainer">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Tác dụng: </label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" v-model="medicine.uses"
                                                        :name="'uses[' + index + '][uses]'">
                                                        <option value="other">Khác</option>
                                                        @foreach ($uses as $item)
                                                            <option value="{{ $item->usesName }}">{{ $item->usesName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input v-if="medicine.uses === 'other'" type="text"
                                                        class="form-control" v-model="medicine.otherUses"
                                                        placeholder="Nhập tác dụng khác..." name="otherUses">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dosageContainer">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Liều lượng: </label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" v-model="medicine.dosage"
                                                        :name="'dosage[' + index + '][dosage]'">
                                                        <option value="other">Khác</option>
                                                        @foreach ($dosage as $item)
                                                            <option value="{{ $item->dosageName }}">
                                                                {{ $item->dosageName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input v-if="medicine.dosage === 'other'" type="text"
                                                        class="form-control" v-model="medicine.otherDosage"
                                                        placeholder="Nhập liều lượng khác..." name="otherDosage">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="adviceContainer">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Dặn dò: </label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" v-model="medicine.advice"
                                                        :name="'advice[' + index + '][advice]'">
                                                        <option value="other">Khác</option>
                                                        @foreach ($advice as $item)
                                                            <option value="{{ $item->adviceName }}">
                                                                {{ $item->adviceName }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input v-if="medicine.advice === 'other'" type="text"
                                                        class="form-control" v-model="medicine.otherAdvice"
                                                        placeholder="Nhập dặn dò khác..." name="otherAdvice">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-danger" @click="removeMedicine(index)">Xóa
                                            sản phẩm</button>
                                        <hr>
                                    </div>
                                </div>
                                <div class="form-buttons-container" style="display: flex;float: right;">
                                    <button type="button" class="btn btn-warning" onclick="goBack()"
                                        style="margin-right: 10px">Quay lại</button>
                                    <button type="button" class="btn btn-primary btn-add-medicine"
                                        style="margin-right: 10px" @click="addMedicine">Thêm sản phẩm</button>
                                    <button type="submit" class="btn btn-success btn-create-order">Tạo đơn</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <!-- Default theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                medicines: [{
                    medicineName: '',
                    type: '',
                    uses: '',
                    dosage: '',
                    advice: '',
                    note: '',
                }],
            },
            methods: {
                addMedicine() {
                    this.medicines.push({
                        medicineName: '',
                        type: '',
                        uses: '',
                        dosage: '',
                        advice: '',
                        note: '',
                    });
                },
                removeMedicine(index) {
                    this.medicines.splice(index, 1);
                }
            }
        });
    </script>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <script src="{{ asset('ajax/orders/createMedicine.js') }}"></script>
    <script src="{{ asset('ajax/orders/createTypeMedicine.js') }}"></script>
    <script src="{{ asset('ajax/orders/createUses.js') }}"></script>
    <script src="{{ asset('ajax/orders/createDosage.js') }}"></script>
    <script src="{{ asset('ajax/orders/createAdvice.js') }}"></script>
@endsection
