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
                <div class="card-header text-bold row">
                    <div class="col">Data Catalog</div>
                    @can("control catalog")
                    <div class="col d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ url('catalog/create')}}" class="btn btn-primary btn-sm mb-3"> Create New</a>
                    </div>
                    @endcan
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th style="width: 40px">Total Books</th>
                                @can("control catalog")
                                <th>Action</th>
                                @endcan
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($catalogs as $key => $catalog)
                            <tr>
                                <td>{{$key + 1}}.</td>
                                <td>{{$catalog->name}}</td>
                                <td>{{count($catalog->books)}}</td>
                                @can("control catalog")
                                <td>
                                    <div class="btn-group m-0 p-0">
                                        <a href="{{ url('catalog/' . $catalog->id . '/edit')}}" class="btn btn-warning btn-xs">Edit</a>
                                        <form action="{{ url('catalog', ['id' => $catalog->id])}}" method="post" class="btn btn-danger btn-xs">
                                            <input type="submit"class="btn btn-xs" value="Delete" onclick="return confirm('Are You Sure ?')">
                                            @method("delete")
                                            @csrf
                                        </form>
                                    </div>
                                </td>
                                @endcan
                                <td>{{my_date_convert($catalog->created_at)}}</td>
                            </tr>
                            @endforeach()
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
