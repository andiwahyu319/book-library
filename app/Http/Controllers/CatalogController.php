<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
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
        $catalogs = Catalog::with("books")->get();
        return view('hasLogin.catalog.index', compact("catalogs"));
    }

    public function api()
    {
        $catalogs = Catalog::select("catalogs.*", Catalog::raw("COUNT(books.id) AS 'books'"))
                            ->leftjoin("books", "catalogs.id", "=", "books.catalog_id")
                            ->groupBy("catalogs.id")->get();
        $datatables = DataTables()->of($catalogs)
                                ->addColumn("created", function ($catalog) { return my_date_convert($catalog->created_at);})
                                ->addColumn("updated", function ($catalog) { return my_date_convert($catalog->updated_at);})
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
        if (auth()->user()->can("control catalog")) {
            return view('hasLogin.catalog.create');
        } else {
            return abort("403");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->can("control catalog")) {
            $this->validate($request, ["name" => ["required"]]);
            Catalog::create($request->all());
            return redirect("catalog");
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function show(Catalog $catalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function edit(Catalog $catalog)
    {
        if (auth()->user()->can("control catalog")) {
            return view('hasLogin.catalog.edit', compact("catalog"));
        } else {
            return abort("403");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Catalog $catalog)
    {
        if (auth()->user()->can("control catalog")) {
            $this->validate($request, ["name" => ["required"]]);
            $catalog->update($request->all());
            return redirect("catalog");
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Catalog $catalog)
    {
        if (auth()->user()->can("control catalog")) {
            $catalog->delete();
            return redirect("catalog");
        } else {
            return abort("403");
        }
    }
}
