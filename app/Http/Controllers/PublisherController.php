<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
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
        return view('hasLogin.publisher.index');
    }
    
    public function api()
    {
        $publishers = Publisher::select("publishers.*", Publisher::raw("COUNT(books.id) AS 'books'"))
                            ->leftjoin("books", "publishers.id", "=", "books.publisher_id")
                            ->groupBy("publishers.id")->get();
        $datatables = DataTables()->of($publishers)
                                    ->addColumn("created", function ($publisher) { return my_date_convert($publisher->created_at);})
                                    ->addColumn("updated", function ($publisher) { return my_date_convert($publisher->updated_at);})
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
        if (auth()->user()->can("control publisher")) {
            $this->validate($request, [
                "name" => "required",
                "phone_number" => "required|regex:/^([0-9\s\-\+\(\)]*)$/",
                "email" => "required|email|unique:publishers,email",
                "address" => "required"
            ]);
            Publisher::create($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function show(Publisher $publisher)
    {
        // return view('hasLogin.publisher.show', compact("publisher"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function edit(Publisher $publisher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publisher $publisher)
    {
        if (auth()->user()->can("control publisher")) {
            $this->validate($request, [
                "name" => "required",
                "phone_number" => "regex:/^([0-9\s\-\+\(\)]*)$/",
                "email" => "required|email",
                "address" => "required"
            ]);
            $publisher->update($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publisher  $publisher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publisher $publisher)
    {
        if (auth()->user()->can("control publisher")) {
            $publisher->delete();
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }
}
