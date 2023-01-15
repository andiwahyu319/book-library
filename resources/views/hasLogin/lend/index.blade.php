@extends('layouts.hasLogin')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<style>
    .widget-user-header {
        background: url("{{ asset('assets/dist/img/photo1.png') }}") center center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Lend</h1>
        </div>
    </div>
</div>
<div class="container" id="app">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header row">
                    <div class="col">Lend</div>
                    @can("control lend")
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ url('lend/create')}}" class="btn btn-primary">Create new</a>
                    </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6"></div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label>Returned</label>
                                <input type="date" class="form-control" name="date_end" id="date_end" v-model="filter.date_end" @change="filterData()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" id="status" v-model="filter.status" @change="filterData()">
                                    <option value="">All</option>
                                    <option value="1">Returned</option>
                                    <option value="0">Not Returned</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th style="width: 40px">Total Book</th>
                                <th>Date Return</th>
                                <th>Action</th>
                                <th style="width: 40px">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-show" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lend Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body card">
                    <div class="card-body">
                        <div class="card card-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header text-white">
                                <h3 class="widget-user-username text-right">@{{data.name}}</h3>
                                <h5 class="widget-user-desc text-right">Members</h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{ asset('assets/dist/img/user4-128x128.jpg') }}" alt="User Avatar">
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header">Returned</h5>
                                            <span class="description-text">
                                                <i class="fas fa-check" v-if="data.book_return"></i>
                                                <i class="fas fa-times" v-else></i>
                                            </span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header">Date Start</h5>
                                            <span class="description-text">@{{data.date_start}}</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <h5 class="description-header">Date End</h5>
                                            <span class="description-text">@{{data.date_end}}</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                        <div class="info-box" v-for="book in data.book">
                            <span class="info-box-icon bg-info elevation-1">
                                <i class="fas fa-book"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">
                                    @{{book.title}} 
                                    <span class="badge badge-danger text-bold">Qty: @{{book.qty}}</span>
                                </span>
                                <span class="info-box-text text-muted">ISBN: @{{book.isbn}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @can("control lend")
                    <div class="d-md-flex justify-content-md-end">
                        <a :href="link" class="btn btn-outline-warning">Edit / Delete</a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
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
        {data: "book", render: function (data, type) {
                cont = 0;
                for (const key in data) {
                    if (Object.hasOwnProperty.call(data, key)) {
                        const element = data[key];
                        cont = cont + element.qty;
                    }
                }
                return cont;
            }, orderable: true},
        {data: "date_end", orderable: true},
        {render: function (index, row, data, meta) {
                return "<button class='btn btn-outline-info btn-xs' onclick='controller.showData(" + meta.row + ")'>Show</button>"
            }, orderable: false},
        {data: "book_return", render: function (data, type) {
                if (data) {
                    return "<span class='text-success'><i class='fas fa-check'></i></span>"
                } else {
                    return "<span class='text-danger'><i class='fas fa-times'></i></span>"
                }
            }, orderable: false}
    ];

    const {
        createApp
    } = Vue

    var controller = createApp({
        data() {
            return {
                datas: [],
                data: {},
                link: "",
                filter:{
                    date_end: "",
                    status: ""
                }
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
                        url: "{{ url('/lend/api') }}"
                    },
                    columns: columns
                }).on("xhr", function () {
                    _this.datas = _this.table.ajax.json().data;
                });
            },
            showData(row) {
                this.data = this.datas[row];
                data = this.data;
                this.link = "{{ url('lend')}}" + "/" + data.id + "/edit";
                $("#modal-show").modal();
            },
            filterData() {
                date_end = this.filter.date_end;
                status = this.filter.status;
                this.table.ajax.url("{{ url('/lend/api') }}" + "?status=" + status + "&date_end=" + date_end).load()
            }
        }
    }).mount('#app')

</script>
@endsection
