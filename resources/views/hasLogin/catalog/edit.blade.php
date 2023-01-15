@extends('layouts.hasLogin')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h1>Catalog</h1>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-bold">Edit Catalog</div>
                    <form action="{{ url('catalog/' . $catalog->id) }}" method="POST">
                        @csrf
                        {{ method_field("put") }}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Catalog Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ $catalog->name }}" placeholder="Enter Name" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
