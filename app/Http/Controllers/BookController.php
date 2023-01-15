<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
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
        return view('hasLogin.book.index');
    }

    public function api()
    {
        $books = Book::all();
        return $books;
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
        if (auth()->user()->can("control book")) {
            $this->validate($request, [
                "title" => "required",
                "isbn" => "required|integer",
                "year" => "required|integer",
                "publisher_id" => "required|integer",
                "author_id" => "required|integer",
                "catalog_id" => "required|integer",
                "qty" => "required|integer",
                "price" => "required|integer",
            ]);
            Book::create($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        if (auth()->user()->can("control book")) {
            $this->validate($request, [
                "title" => "required",
                "isbn" => "required|integer",
                "year" => "required|integer",
                "publisher_id" => "required|integer",
                "author_id" => "required|integer",
                "catalog_id" => "required|integer",
                "qty" => "required|integer",
                "price" => "required|integer",
            ]);
            $book->update($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        if (auth()->user()->can("control book")) {
            $book->delete();
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }
}
