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
        <div class="col">
            <div class="card">
                <div class="card-header text-bold row">
                    <div class="col">Edit Lend</div>
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <form :action="submitUrl" method="post">
                            <input type="submit" class="btn btn-outline-danger" value="Delete" onclick="return confirm('Are You Sure ?')">
                            @method("delete")
                            @csrf
                        </form>
                    </div>
                </div>
                <form :action="submitUrl" method="POST">
                    @csrf
                    {{ method_field("put") }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <select name="member_id" id="member_id" class="form-control">
                                        <option v-for="member in members_data" :value="member.id" :selected="lend_data.member_id == member.id">@{{member.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date Start</label>
                                            <input type="date" name="date_start" class="form-control" id="date_start"
                                                :value="lend_data.date_start" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date End</label>
                                            <input type="date" name="date_end" class="form-control" id="date_end"
                                                :value="lend_data.date_end" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="book_return" name="book_return" :value="book_return" v-model="book_return">
                                        <label class="custom-control-label" for="book_return"><span v-if="book_return">Already Returned</span><span v-else>Not Returned</span></label>
                                    </div>
                                </div>
                                <input type="hidden" name="book_return" :value="book_return">
                                <input type="hidden" name="books_new" :value="booksNewValue()">
                                <input type="hidden" name="books_stagged" :value="booksStaggedValue()">
                                <input type="hidden" name="books_edited" :value="booksEditedValue()">
                                <input type="hidden" name="books_deleted" :value="booksDeletedValue()">
                                <div class="info-box" @click="showAddNew()">
                                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Add New Book</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Stagged Book</label>
                                        <div class="callout callout-success">
                                            <div class="info-box" v-for="(book, index) in books_stagged">
                                                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-book"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">@{{bookDataById(book.book_id).title}}</span>
                                                    <span class="info-box-number">
                                                        Qty: @{{book.qty}}
                                                        <a class="float-right text-danger" @click="addDeletedBook(index)">Delete</a>
                                                        <a class="float-right text-warning me-2" @click="showEdit(index)">Edit</a> 
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>New Book</label>
                                        <div class="callout callout-info">
                                            <div class="info-box" v-for="(book, index) in books_new">
                                                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">@{{bookDataById(book.book_id).title}}</span>
                                                    <span class="info-box-number">
                                                        Qty: @{{book.qty}}
                                                        <a class="float-right text-danger" @click="removeNewBook(index)">Cancel</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Edited Book</label>
                                        <div class="callout callout-warning">
                                            <div class="info-box" v-for="(book, index) in books_edited">
                                                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-book"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">@{{bookDataById(book.book_id).title}}</span>
                                                    <span class="info-box-text text_muted text-sm">old: @{{bookDataById(book.book_id_old).title}}(@{{book.qty_old}})</span>
                                                    <span class="info-box-number">
                                                        Qty: @{{book.qty}}
                                                        <a class="float-right text-danger" @click="removeEditedBook(index)">Cancel</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Deleted Book</label>
                                        <div class="callout callout-danger">
                                            <div class="info-box" v-for="(book, index) in books_deleted">
                                                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-book"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">@{{bookDataById(book.book_id).title}}</span>
                                                    <span class="info-box-number">
                                                        Qty: @{{book.qty}}
                                                        <a class="float-right text-danger" @click="removeDeletedBook(index)">Cancel</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-warning float-right">Submit</button>
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
                <form v-on:submit.prevent="addNewBook()">
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
    <div class="modal fade" id="modal-edit" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Book</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form v-on:submit.prevent="addEditedBook()">
                    <div class="alert alert-danger" v-if="alert != ''">@{{alert}}</div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Book</label>
                            <select name="book_id" id="book_id_edit" class="form-control" v-model="selected" required>
                                <option v-for="book in books_data" :value="book.id" :selected="book.id == select_edit.book_id">@{{book.title}}</option>
                            </select>
                            <span class="text-muted"><small>old: @{{bookDataById(select_edit.book_id).title}}</small></span>
                        </div>
                        <div class="form-group">
                            <label>Qty</label><span class="text-muted"><small>  (1 - @{{bookDataById(selected).qty}})</small></span>
                            <input type="number" name="qty" id="qty_edit" class="form-control" min="1" :max="bookDataById(selected).qty" :value="select_edit.qty"
                                required>
                            <span class="text-muted"><small>old: @{{select_edit.qty}}</small></span>
                        </div>
                        <input type="hidden" name="id" :value="select_edit.id">
                        <input type="hidden" name="book_id_old" :value="select_edit.book_id">
                        <input type="hidden" name="qty_old" :value="select_edit.qty">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-info btn-sm">Update Book</button>
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
                lend_data: {},
                book_return: false,
                submitUrl: "",
                alert: "",
                selected: 0,
                select_edit: {},
                books_new: [],
                books_stagged: [],
                books_edited: [],
                books_deleted: []
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
                $.getJSON(window.location.href +"/api", function (data) {
                    _this.lend_data = data;
                    _this.books_stagged = data.books;
                    _this.submitUrl = "{{ url('/lend/') }}" + "/" + data.id;
                    if (data.book_return == 1) {
                        _this.book_return = true;
                    }
                });
            },
            showAddNew() {
                this.selected = this.books_data[0].id;
                this.alert = "";
                $("#qty").val(1);
                $("#modal").modal();
            },
            addNewBook() {
                data = Object.fromEntries(new FormData(window.event.target));
                if (data.book_id == 0) {
                    this.alert = "Please Select Book";
                } else if (this.books_new.some(e => e.book_id === data.book_id)) {
                    this.alert = "Book Already Selected, Please Select Another Books";
                } else if (this.bookDataById(data.book_id).qty == 0) {
                    this.alert = "This stock book is empty, Please Select Another Books"
                } else {
                    $("#modal").modal("hide");
                    this.books_new.push(data);
                }
            },
            removeNewBook(index) {
                this.books_new.splice(index, 1);
            },
            showEdit(index) {
                this.select_edit = this.books_stagged[index];
                var book = this.books_stagged[index];
                this.selected = book.book_id;
                this.alert = "";
                $("#modal-edit").modal();
            },
            addEditedBook() {
                data = Object.fromEntries(new FormData(window.event.target));
                if (data.book_id == "0") {
                    this.alert = "Please Select Book";
                } else if (this.lend_data.books.some(e => e.book_id === parseInt(data.book_id))) {
                    this.alert = "Book Already Selected, Please Select Another Books";
                } else if (this.bookDataById(data.book_id).qty == 0) {
                    this.alert = "This stock book is empty, Please Select Another Books";
                } else {
                    $("#modal-edit").modal("hide");
                    var idx = 0;
                    for (let i = 0; i < this.books_stagged.length; i++) {
                        const element = this.books_stagged[i];
                        if (element.id == parseInt(data.id)) {
                            idx = i;
                        }
                    }
                    this.books_stagged.splice(idx, 1);
                    this.books_edited.push(data);
                }
            },
            removeEditedBook(index) {
                this.books_stagged.push({
                    id: this.books_edited[index].id,
                    book_id: this.books_edited[index].book_id_old,
                    qty: this.books_edited[index].qty_old
                });
                this.books_edited.splice(index, 1);
            },
            addDeletedBook(index) {
                this.books_deleted.push(this.books_stagged[index]);
                this.books_stagged.splice(index, 1);
            },
            removeDeletedBook(index) {
                this.books_stagged.push(this.books_deleted[index]);
                this.books_deleted.splice(index, 1);
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
            booksNewValue() {
                return JSON.stringify(this.books_new);
            },
            booksStaggedValue() {
                return JSON.stringify(this.books_stagged);
            },
            booksEditedValue() {
                return JSON.stringify(this.books_edited);
            },
            booksDeletedValue() {
                return JSON.stringify(this.books_deleted);
            }
        }
    }).mount('#app');

</script>
@endsection
