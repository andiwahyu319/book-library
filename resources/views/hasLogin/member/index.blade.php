@extends('layouts.hasLogin')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Member</h1>
        </div>
    </div>
</div>
<div class="container" id="app">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header row">
                    <div class="col">Data Member</div>
                    @can("control member")
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" @click="addData()">
                            Create new
                        </button>
                    </div>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Action</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @can("control member")
    <div class="modal fade" id="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Member</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" v-on:submit.prevent="submitForm()">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" v-if="edit">
                        <div class="form-group">
                            <label>Member Name</label>
                            <input type="text" name="name" class="form-control" id="name" :value="data.name"
                                placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label>Member Gender</label>
                            <select name="gender" class="form-control" id="gender">
                                <option value="L" :selected="data.gender == 'L'">male</option>
                                <option value="P" :selected="data.gender == 'P'">female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Member Phone Number</label>
                            <input type="tel" name="phone_number" class="form-control" id="phone_number"
                                :value="data.phone_number" placeholder="Enter Phone Number" required>
                        </div>
                        <div class="form-group">
                            <label>Member Email</label>
                            <input type="email" name="email" class="form-control" id="email" :value="data.email"
                                placeholder="Enter Email" required>
                        </div>
                        <div class="form-group">
                            <label>Publisher Address</label>
                            <input type="text" name="address" class="form-control" id="address" :value="data.address"
                                placeholder="Enter Address" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @endcan
    <div class="modal fade" id="modal-show" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Member Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body card">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                src="{{ asset('assets/dist/img/user7-128x128.jpg') }}" alt="member profile picture">
                        </div>

                        <h3 id="show-name" class="profile-username text-center"></h3>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Gender</b> <a id="show-gender" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Phone</b> <a id="show-phone" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a id="show-email" class="float-right"></a>
                            </li>
                            <li class="list-group-item">
                                <b>Addreess</b> <a id="show-address" class="float-right"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @can("control member")
                    <div class="d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-warning me-md-2" @click="editData()">Edit</button>
                        <button type="button" class="btn btn-danger" @click="deleteData()">Delete</button>
                    </div>
                    @endcan
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>
@endsection

@section("js")
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script>
    var columns = [
        {data: "DT_RowIndex", orderable: true},
        {data: "name", orderable: true},
        {render: function (index, row, data, meta) {
            return "<button class='btn btn-outline-info btn-xs' onclick='controller.showData(" + meta.row + ")'>Show</button>"
        }, orderable: false},
        {data: "created", orderable: false}
    ];
    
    const {
        createApp
    } = Vue

    var controller = createApp({
        data() {
            return {
                datas: [],
                data: {},
                edit: false,
                actionUrl: "{{ url('member') }}",
                apiUrl: "{{ url('/member/api') }}"
            }
        },
        mounted: function () {
            this.datatable();
        },
        methods: {
            datatable() {
                const _this = this;
                _this.table = $("#datatable").DataTable({
                    ajax: {
                        url: this.apiUrl
                    },
                    columns: columns
                }).on("xhr", function () {
                    _this.datas = _this.table.ajax.json().data;
                });
            },
            showData(row) {
                this.data = this.datas[row];
                data = this.data;;
                $("#show-name").text(data.name);
                if (data.gender == "L") {
                    $("#show-gender").text("Male");
                } else {
                    $("#show-gender").text("Female");
                }
                $("#show-phone").text(data.phone_number);
                $("#show-email").text(data.email);
                $("#show-address").text(data.address);
                $("#modal-show").modal();
            },
            addData() {
                this.data = {};
                this.actionUrl = "{{ url('member') }}";
                this.edit = false;
                $("#modal").modal();
            },
            editData() {
                $("#modal-show").modal("hide");
                this.actionUrl = "{{ url('member') }}" + "/" + this.data.id;
                this.edit = true;
                $("#modal").modal();
            },
            deleteData() {
                this.actionUrl = "{{ url('member') }}" + "/" + this.data.id;
                if (confirm("are you sure ?")) {
                    axios.post(this.actionUrl, {_method : "DELETE"}).then(response => {
                        $("#modal-show").modal("hide");
                        this.table.ajax.reload();
                    });
                }
            },
            submitForm() {
                const _this = this;
                axios.post(_this.actionUrl, new FormData(window.event.target)).then(response => {
                    $("#modal").modal("hide");
                    _this.table.ajax.reload();
                })
            }
        }
    }).mount('#app')

</script>
@endsection
