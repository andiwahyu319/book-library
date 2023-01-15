<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Catalog;
use App\Models\Lend;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function index_new($tabel)
    {
        switch ($tabel) {
            case "members":
                $members = Member::all();
                return $members;
                break;
            case "members-user":
                $members = Member::with("user")->get();
                return $members;
                break;
            case "books":
                $books = Book::all();
                return $books;
                break;
            case "books-author":
                $books = Book::with("author")->get();
                return $books;
                break;
            case "books-publisher":
                $books = Book::with("publisher")->get();
                return $books;
                break;
            case "books-catalog":
                $books = Book::with("catalog")->get();
                return $books;
                break;
            case "author":
                $author = Author::all();
                return $author;
                break;
            case "author-books":
                $author = Author::with("books")->get();
                return $author;
                break;
            case "publisher":
                $publisher = Publisher::all();
                return $publisher;
                break;
            case "publisher-books":
                $publisher = Publisher::with("books")->get();
                return $publisher;
                break;
            case "catalog":
                $catalog = Catalog::all();
                return $catalog;
                break;
            case "catalog-books":
                $catalog = Catalog::with("books")->get();
                return $catalog;
                break;
            
            default:
                return view('home');
                break;
        }
    }
    public function query($query)
    {
        $data = "[]";
        switch ($query) {
            case "1":
                $data = Member::select("*")
                            ->join("users", "users.member_id", "=", "members.id")
                            ->get();
                break;
            case "2":
                $data = Member::select("*")
                            ->leftJoin("users", "users.member_id", "=", "members.id")
                            ->where("users.id", null)
                            ->get();
                break;
            case "3":
                $data = Member::select("members.id", "members.name")
                            ->join("lends", "lends.member_id", "=", "members.id")
                            ->where("lends.id", null)
                            ->get();
                break;
            case "4":
                $data = Member::select("members.id", "members.name", "members.phone_number")
                            ->join("lends", "lends.member_id", "=", "members.id")
                            ->where("lends.id", "!=", null)
                            ->get();
                break;
            case "5":
                $data = Member::select("members.id", "members.name", "members.phone_number")
                            ->join("lends", "lends.member_id", "=", "members.id")
                            ->where("lends.qty", ">", 1)
                            ->get();
                break;
            case "6":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->get();
                break;
            case "7":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->whereMonth("lends.date_end", 06)
                            ->get();
                break;
            case "8":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->whereMonth("lends.date_start", 07)
                            ->get();
                break;
            case "9":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->whereMonth("lends.date_start", 06)
                            ->whereMonth("lends.date_end", 06)
                            ->get();
                break;
            case "10":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->where("members.address", "LIKE", "%Bandung%")
                            ->get();
                break;
            case "11":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->where("members.address", "LIKE", "%Bandung%")
                            ->where("members.gender", "P")
                            ->get();
                break;
            case "12":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end", "books.isbn" , "lends.qty")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->join("books", "books.id", "=", "lends.book_id")
                            ->where("lends.qty", ">", 1)
                            ->get();
                break;
            case "13":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end",
                            "books.isbn" , "lends.qty", "books.title", "books.price", "lends.qty * books.price as total")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->join("books", "books.id", "=", "lends.book_id")
                            ->get();
                break;
            case "14":
                $data = Lend::select("members.name", "members.phone_number", "members.address", "lends.date_start", "lends.date_end", 
                            "books.isbn" , "lends.qty", "books.title", "books.price", "publishers.name", "authors.name", "catalogs.name")
                            ->join("members", "members.id", "=", "lends.member_id")
                            ->join("books", "books.id", "=", "lends.book_id")
                            ->join("publishers", "publishers.id", "=", "books.publisher_id")
                            ->join("authors", "authors.id", "=", "books.author_id")
                            ->join("catalogs", "catalogs.id", "=", "books.catalog_id")
                            ->get();
                break;
            case "15":
                $data = Book::select("catalogs.*", "books.title")
                            ->join("catalogs", "catalogs.id", "=", "books.catalog_id")
                            ->get();
                break;
            case "16":
                $data = Book::select("*", "publishers.name")
                            ->rightJoin("publishers", "publishers.id", "=", "books.publisher_id")
                            ->get();
                break;
            case "17":
                $data = Book::select("author_id")
                            ->count()
                            ->where("author_id", "PG05")
                            ->get();
                break;
            case "18":
                $data = Book::select("*")
                            ->where("price", ">", 10000)
                            ->get();
                break;
            case "19":
                $data = Book::select("*")
                            ->join("publishers", "publishers.id", "=", "books.publishers_id")
                            ->where("publishers.name", "Penerbit 01")
                            ->get();
                break;
            case "20":
                $data = Member::select("*")
                            ->whereMonth("created_at", "06")
                            ->get();
                break;
            default:
                $data = "[]";
                break;
        }
        return $data;
        
    }
    public function notification()
    {
        $lends = Lend::select("lends.*", "members.name")
                ->join("members", "members.id", "=", "lends.member_id")
                ->get();
        $notif = [];
        foreach ($lends as $lend) {
            if (!$lend->book_return) {
                $this_date = date_create();
                $end_date = date_create($lend->date_end);
                if ($this_date > $end_date) {
                    $diff = date_diff($this_date, $end_date);
                    $message = $lend->name . $diff->format(" exceeded the time limit of %a days");
                    array_push($notif, $message);
                };
            };
        };
        return $notif;
    }
}
