<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTypeController extends Controller
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
        $models = UserType::orderBy('id','desc')->paginate(100);
        // echo '<pre>';
        // dump($models);die;
		return view('backend.user-type.index',[
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
        return view('backend.user-type.create')->with([
			// 'models'=>$models,
		]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
			'name' => 'required|unique:user_type,name|max:255',

		]); 

        if ($validator->fails()) {
			return redirect()->route('user-type.create')->withErrors($validator)->withInput();
		}

        $model = UserType::create([
            'name' => $request->input('name'),

        ]);

        if ($model) {
            // Session::flash('success','ajoyib');
            return redirect()->route('user-type.index')->with('success','user-type ' . __('msg.successfully created'));

        }

        return redirect()->route('user-typ.create')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function show(UserType $userType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = UserType::findorFail($id);
        return view('backend.user-type.edit')->with([
			'model'=>$model,
		]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = UserType::findorFail($id);
        
        $validator = Validator::make($request->all(),[
			'name' => 'required|string|max:255',

		]); 

        if ($validator->fails()) {
			return redirect()->route('user-type.edit',$id)->withErrors($validator)->withInput();
		}

        $model->name = $request->input('name');
        if ($model->update()) {
            return redirect()->route('user-type.index')->with('success','user-type ' . __('msg.successfully updated'));
        }
        
       
        return redirect()->route('user-typ.edit',$id)->withInput();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserType  $userType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = UserType::findOrFail($id);
        
        if (!empty($model)) {
            $model->delete();
            return redirect()->route('user-type.index')->with('success','user-type ' . __('successfully deleted'));
        }
    }
}
