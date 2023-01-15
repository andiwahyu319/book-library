<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
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
        return view('hasLogin.member.index');
    }

    public function api()
    {
        $members = Member::all();
        $datatables = DataTables()->of($members)
                                ->addColumn("created", function ($member) { return my_date_convert($member->created_at);})
                                ->addColumn("updated", function ($member) { return my_date_convert($member->updated_at);})
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
        if (auth()->user()->can("control member")) {
            $this->validate($request, [
                "name" => "required",
                "gender" => "required",
                "phone_number" => "required|regex:/^([0-9\s\-\+\(\)]*)$/",
                "email" => "required|email|unique:members,email",
                "address" => "required"
            ]);
            Member::create($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Member $member)
    {
        if (auth()->user()->can("control member")) {
            $this->validate($request, [
                "name" => "required",
                "gender" => "required",
                "phone_number" => "required|regex:/^([0-9\s\-\+\(\)]*)$/",
                "email" => "required|email",
                "address" => "required"
            ]);
            $member->update($request->all());
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        if (auth()->user()->can("control member")) {
            $member->delete();
            return '{"status": "ok"}';
        } else {
            return abort("403");
        }
    }
}
