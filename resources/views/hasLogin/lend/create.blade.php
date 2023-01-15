@extends('layouts.hasLogin')

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
                <div class="card-header text-bold">Create New Lend</div>
                <form action="{{ url('lend') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <select name="member_id" id="member_id" class="form-control">
                                        <option v-for="member in members_data" :value="member.id">@{{member.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date Start</label>
                                            <input type="date" name="date_start" class="form-control" id="date_start"
                                                :value="date" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date End</label>
                                            <input type="date" name="date_end" class="form-control" id="date_end"
                                                :min="date" required>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="books" :value="booksValue()">
                                <div class="info-box" @click="showAdd()">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Add New Book</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Book<span v-if="books.length > 1">s</span></label>
                                <div class="callout callout-info">
                                    <div class="info-box" v-for="(book, index) in books">
                                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-book"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">@{{bookDataById(book.book_id).title}}</span>
                                            <span class="info-box-number">
                                                Qty: @{{book.qty}}
                                                <a class="float-right text-danger" @click="removeBook(index)">Cancel</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-info float-right">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Book</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form v-on:submit.prevent="addBook()">
                    <div class="alert alert-danger" v-if="alert != ''">@{{alert}}</div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Book</label>
                            <select name="book_id" id="book_id" class="form-control" v-model="selected" required>
                                <option v-for="book in books_data" :value="book.id">@{{book.title}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Qty</label><span class="text-muted"><small>  (1 - @{{bookDataById(selected).qty}})</small></span>
                            <input type="number" name="qty" id="qty" class="form-control" min="1" :max="bookDataById(selected).qty"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-info btn-sm">Add Book</button>
                    </div>
                </form>
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
                books_data: [],
                members_data: [],
                date: "",
                alert: "",
                selected: 0,
                books: []
            }
        },
        mounted: function () {
            this.loadData();
        },
        methods: {
            loadData() {
                const _this = this;
                $.get("{{ url('/book/api') }}", function (data) {
                    _this.books_data = data;
                });
                $.get("{{ url('/member/api') }}", function (data) {
                    _this.members_data = data.data;
                });
                dtToday = new Date();
                month = dtToday.getMonth() + 1;
                day = dtToday.getDate();
                year = dtToday.getFullYear();
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();
                _this.date = year + '-' + month + '-' + day;
            },
            showAdd() {
                $("#qty").val(1);
                this.alert = "";
                this.selected = this.books_data[0].id;
                $("#modal").modal();
            },
            addBook() {
                data = Object.fromEntries(new FormData(window.event.target));
                if (data.book_id == "0") {
                    this.alert = "Please Select Book";
                } else if (this.books.some(e => e.book_id === data.book_id)) {
                    this.alert = "Book Already Selected, Please Select Another Books";
                } else if (this.bookDataById(data.book_id).qty == 0) {
                    this.alert = "This stock book is empty, Please Select Another Books";
                } else {
                    $("#modal").modal("hide");
                    this.books.push(data);
                }
            },
            removeBook(index) {
                this.books.splice(index, 1);
            },
            bookDataById(id) {
                const _this = this;
                var data = {};
                for (const key in _this.books_data) {
                    if (Object.hasOwnProperty.call(_this.books_data, key)) {
                        const element = _this.books_data[key];
                        if (element.id == parseInt(id)) {
                            data = element;
                        }
                    }
                }
                return data;
            },
            booksValue() {
                return JSON.stringify(this.books);
            }
        }
    }).mount('#app');

</script>
@endsection
