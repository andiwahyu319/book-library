<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Lend;
use App\Models\LendDetail;
use Illuminate\Http\Request;

class LendController extends Controller
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
        
        return view('hasLogin.lend.index');
    }

    public function api(Request $request)
    {
        $lends = Lend::select("lends.*", "members.name")
                ->join("members", "members.id", "=", "lends.member_id");
        if ($request->status != "") {
            $lends = $lends->where("book_return", $request->status);
        }
        if ($request->date_end != "") {
            $lends = $lends->where("date_end", $request->date_end);
        }
        $lends = $lends->get();
        $datatables = DataTables()
                        ->of($lends)
                        ->addColumn("book", function ($lend) { 
                                    $detail = LendDetail::select("lend_details.*", "books.title", "books.isbn")
                                        ->join("books", "books.id", "=", "lend_details.book_id")
                                        ->where("lend_details.lend_id", $lend->id)
                                        ->get();
                                    return json_decode($detail);          
                        })
                        ->addIndexColumn()
                        ->make(true);
        return $datatables;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->can("control lend")) {
            return view('hasLogin.lend.create');
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
        if (auth()->user()->can("control lend")) {
            $books = json_decode($request->books, true);
            $request["books"] = $books;
            $this->validate($request, [
                "member_id" => "required|int",
                "date_start" => "required|date",
                "date_end" => "required|date",
                "books.*" => "filled",
                "books.*.book_id" => "required|int",
                "books.*.qty" => "required|int"
            ]);
            $lend = new Lend;
            $lend->member_id = $request->member_id;
            $lend->date_start = $request->date_start;
            $lend->date_end = $request->date_end;
            $lend->book_return = 0;
            $lend->save();
            $lend_id = $lend->id;
            for ($i=0; $i < count($books); $i++) {
                $lend_detail = new LendDetail;
                $lend_detail->lend_id = $lend_id;
                $lend_detail->book_id = $books[$i]["book_id"];
                $lend_detail->qty = $books[$i]["qty"];
                $lend_detail->save();
                Book::where("id", $books[$i]["book_id"])->decrement("qty", $books[$i]["qty"]);
            };
            return redirect("lend");
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function show(Lend $lend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function edit(Lend $lend)
    {
        if (auth()->user()->can("control lend")) {
            return view('hasLogin.lend.edit');
        } else {
            return abort("403");
        }
    }

    public function editApi(Lend $lend)
    {
        if (auth()->user()->can("control lend")) {
            $lend["books"] = LendDetail::select("*")
                                        ->where("lend_id", $lend->id)
                                        ->get();
            return $lend;
        } else {
            return abort("403");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lend $lend)
    {
        if (auth()->user()->can("control lend")) {
            $books_new = json_decode($request->books_new, true);
            $request["books_new"] = $books_new;
            $books_stagged = json_decode($request->books_stagged, true);
            $request["books_stagged"] = $books_stagged;
            $books_edited = json_decode($request->books_edited, true);
            $request["books_edited"] = $books_edited;
            $books_deleted = json_decode($request->books_deleted, true);
            $request["books_deleted"] = $books_deleted;
            $this->validate($request, [
                "member_id" => "required|int",
                "date_start" => "required|date",
                "date_end" => "required|date",
                "book_return" => "required"
            ]);
            $lend->update([
                "member_id" => $request->member_id,
                "date_start" => $request->date_start,
                "date_end" => $request->date_end,
                "book_return" => ($request->book_return == "true")? 1 : 0
            ]);
            for ($i=0; $i < count($books_new); $i++) {
                $lend_detail = new LendDetail;
                $lend_detail->lend_id = $lend->id;
                $lend_detail->book_id = $books_new[$i]["book_id"];
                $lend_detail->qty = $books_new[$i]["qty"];
                $lend_detail->save();
                Book::where("id", $books_new[$i]["book_id"])->decrement("qty", $books_new[$i]["qty"]);
            };
            for ($i=0; $i < count($books_edited); $i++) {
                LendDetail::where("id", $books_edited[$i]["id"])->update([
                    "book_id" => $books_edited[$i]["book_id"],
                    "qty" => $books_edited[$i]["qty"]
                ]);
                Book::where("id", $books_edited[$i]["book_id"])->decrement("qty", $books_edited[$i]["qty"]);
                Book::where("id", $books_edited[$i]["book_id_old"])->increment("qty", $books_edited[$i]["qty_old"]);
            };
            for ($i=0; $i < count($books_deleted); $i++) {
                LendDetail::where("id", $books_deleted[$i]["id"])->delete();
                Book::where("id", $books_deleted[$i]["book_id"])->increment("qty", $books_deleted[$i]["qty"]);
            };
            return redirect("lend");
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lend  $lend
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lend $lend)
    {
        if (auth()->user()->can("control lend")) {
            $lend_detail = LendDetail::where("lend_id", $lend->id)->get();
            for ($i=0; $i < count($lend_detail); $i++) { 
                Book::where("id", $lend_detail[$i]["book_id"])->increment("qty", $lend_detail[$i]["qty"]);
            }
            LendDetail::where("lend_id", $lend->id)->delete();
            $lend->delete();
            return redirect("lend");
        } else {
            return abort("403");
        }
    }
}
