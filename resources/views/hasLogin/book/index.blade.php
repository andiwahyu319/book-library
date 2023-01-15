@extends('layouts.hasLogin')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Book</h1>
        </div>
    </div>
</div>
<div id="app" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Search Books by Title" v-model="search">
                        @can("control book")
                        <div class="input-group-append">
                            <button class="btn btn-primary" @click="addData()">or Add New</button>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="mb-2 mt-2">
    <div class="row justify-content-center">
        <div class="col-md-3 col-sm-6 col-xs-12 mb-2" v-for="book in books">
            <div class="info-box h-100" @click="showData(book)">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">@{{book.title}} (@{{book.qty}})</span>
                    <span class="info-box-number">ISBN: @{{book.isbn}}</span>
                </div>
            </div>
        </div>
    </div>
    @can("control book")
    <div class="modal fade" id="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Book</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form method="POST" v-on:submit.prevent="submitForm()">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" v-if="edit">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" id="title" :value="data.title"
                                placeholder="Enter Title" required>
                        </div>
                        <div class="form-group">
                            <label>ISBN</label>
                            <input type="number" name="isbn" class="form-control" id="isbn"
                                :value="data.isbn" placeholder="Enter ISBN" required>
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" id="year" :value="data.year"
                                placeholder="Enter Year" required>
                        </div>
                        <div class="form-group">
                            <label>Publisher</label>
                            <select name="publisher_id" id="publisher_id" class="form-control">
                                <option v-for="publisher in publishers" :value="publisher.id" :selected="data.publisher_id == publisher.id">@{{publisher.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <select name="author_id" id="author_id" class="form-control">
                                <option v-for="author in authors" :value="author.id" :selected="data.author_id == author.id">@{{author.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>catalog</label>
                            <select name="catalog_id" id="catalog_id" class="form-control">
                                <option v-for="catalog in catalogs" :value="catalog.id" :selected="data.catalog_id == catalog.id">@{{catalog.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Qty</label>
                            <input type="number" name="qty" class="form-control" id="qty" :value="data.qty"
                                placeholder="Enter Qty" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" id="price" :value="data.price"
                                placeholder="Enter Price" required>
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
                    <h4 class="modal-title">Book Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body card">
                    <div class="card-body box-profile">
                        <div class="info-box text-center">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                            <div class="info-box-content">
                                <h3 class="profile-username text-center">@{{data.title}}</h3>
                                <p class="text-muted text-center">ISBN: @{{data.isbn}}</p>
                            </div>
                        </div>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Publisher</b> <a class="float-right">@{{showNameById(publishers, data.publisher_id)}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Author</b> <a id="show-email" class="float-right">@{{showNameById(authors, data.author_id)}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Catalog</b> <a id="show-address" class="float-right">@{{showNameById(catalogs, data.catalog_id)}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Qty</b> <a id="show-address" class="float-right">@{{data.qty}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Price</b> <a id="show-address" class="float-right">Rp. @{{data.price}},-</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @can("control book")
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
<script>
    const {
        createApp
    } = Vue
    
    var controller = createApp({
        data() {
            return {
                datas: [],
                data: {},
                search: "",
                edit: false,
                actionUrl: "{{ url('book') }}",
                apiUrl: "{{ url('/book/api') }}",
                publishers: [],
                authors: [],
                catalogs: []
            }
        },
        mounted: function () {
            this.getBooks();
        },
        methods: {
            getBooks() {
                const _this = this;
                $.get(_this.apiUrl, function (data) {
                    _this.datas = data;
                });
                $.get("{{ url('/publisher/api') }}", function (data) {
                    _this.publishers = data.data;
                });
                $.get("{{ url('/author/api') }}", function (data) {
                    _this.authors = data.data;
                });
                $.get("{{ url('/catalog/api') }}", function (data) {
                    _this.catalogs = data.data;
                });
            },
            number_format(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            showNameById(arrr, idd) {
                name = "";
                for (const key in arrr) {
                    if (Object.hasOwnProperty.call(arrr, key)) {
                        const element = arrr[key];
                        if (element.id == idd) {
                            name = element.name;
                        }
                    }
                };
                return name;
            },
            showData(data) {
                this.data = data;
                $("#modal-show").modal();
            },
            addData() {
                this.data = {};
                this.actionUrl = "{{ url('book') }}";
                this.edit = false;
                $("#modal").modal();
            },
            editData() {
                $("#modal-show").modal("hide");
                this.actionUrl = "{{ url('book') }}" + "/" + this.data.id;
                this.edit = true;
                $('#modal').css('overflow-y', 'auto');
                $("#modal").modal();
            },
            deleteData() {
                this.actionUrl = "{{ url('book') }}" + "/" + this.data.id;
                if (confirm("are you sure ?")) {
                    axios.post(this.actionUrl, {_method : "DELETE"}).then(response => {
                        $("#modal-show").modal("hide");
                        this.getBooks();
                    });
                }
            },
            submitForm() {
                const _this = this;
                axios.post(_this.actionUrl, new FormData(window.event.target)).then(response => {
                    $("#modal").modal("hide");
                    _this.getBooks();
                })
            }
        },
        computed: {
            books() {
                return this.datas.filter(book => {
                    return book.title.toLowerCase().includes(this.search.toLowerCase());
                });
            }
        }
    }).mount('#app')

</script>
@endsection
