<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('hasLogin.author.index');
    }

    public function api()
    {
        $authors = Author::select("authors.*", Author::raw("COUNT(books.id) AS 'books'"))
                            ->leftjoin("books", "authors.id", "=", "books.author_id")
                            ->groupBy("authors.id")->get();
        $datatables = DataTables()->of($authors)
                                ->addColumn("created", function ($author) { return my_date_convert($author->created_at);})
                                ->addColumn("updated", function ($author) { return my_date_convert($author->updated_at);})
                                ->addIndexColumn();
        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->can("control author")) {
            $this->validate($request, [
                "name" => "required",
                "phone_number" => "required|regex:/^([0-9\s\-\+\(\)]*)$/",
                "email" => "required|email|unique:authors,email",
                "address" => "required"
            ]);
            Author::create($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        if (auth()->user()->can("control author")) {
            $this->validate($request, [
                "name" => "required",
                "phone_number" => "regex:/^([0-9\s\-\+\(\)]*)$/",
                "email" => "required|email",
                "address" => "required"
            ]);
            $author->update($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        if (auth()->user()->can("control author")) {
            $author->delete();
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }
}
