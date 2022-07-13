<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;

class PersonalAccessTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:data-all|data-create|data-edit|data-show|data-delete', ['only' => ['index']]);
        $this->middleware('permission:data-create|data-all', ['only' => ['create','store']]);
        $this->middleware('permission:data-show|data-all', ['only' => ['show']]);
        $this->middleware('permission:data-edit|data-all', ['only' => ['edit','update']]);
        $this->middleware('permission:data-delete|data-all', ['only' => ['destroy']]);
        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = PersonalAccessToken::orderBy('id','desc')->paginate(100);
		return view('backend.personal-access-token.index',[
			'models'=>$models,
		]);

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return \Illuminate\Http\Response
     */
    public function show(PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return \Illuminate\Http\Response
     */
    public function edit(PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PersonalAccessToken $personalAccessToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PersonalAccessToken  $personalAccessToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonalAccessToken $personalAccessToken)
    {
        //
    }
}
